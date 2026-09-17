<?php 
    class my_api_model extends CI_model{
        protected $user = null;
        public function __construct() {parent::__construct();}
        public function set_user($user) {
            $this->user = $user;
        }
        public function get_user($user_id){
            $query="SELECT
                        customer.customer_id as id,
                        customer.customer_status as is_active,
                        customer.customer_app_type_id as app_type_id,
                        IFNULL(payment.is_subscribed, 0) as is_subscribed
                    FROM customer_master customer
                    LEFT JOIN (
                        SELECT
                            payment.customer_id,
                            1 as is_subscribed
                        FROM payment_gateway payment
                        WHERE payment.order_status = 'SUCCESS'
                        AND payment.module = 'SUBSCRIPTION'
                        GROUP BY payment.customer_id
                    ) AS payment ON(payment.customer_id = customer.customer_id)
                    WHERE customer.customer_id = $user_id";
            return $this->db->query($query)->result_array();
        }
        protected function get_category_ids(){
    	    $query="SELECT cict.cict_item_category_id as id
                    FROM customer_item_category_trans cict
                    WHERE cict.cict_customer_id = ".$this->user['id']."
                    GROUP BY cict.cict_item_category_id";
            $data = $this->db->query($query)->result_array();
            if(!empty($data)) return $this->get_ids($data);

            $query="SELECT item_category.item_category_id as id
                    FROM item_master item
                    INNER JOIN item_app_type_trans iatt ON(iatt.iatt_item_id = item.item_id)
                    INNER JOIN item_category_master item_category ON(item_category.item_category_id = item.item_category_id)
                    WHERE 1
                    AND item.item_status = 1
                    AND iatt.iatt_delete_status = 0
                    AND iatt.iatt_app_type_id = ".$this->user['type_id']."
                    GROUP BY item_category.item_category_id";
            $data = $this->db->query($query)->result_array();
            if(!empty($data)) return $this->get_ids($data);
            return '-1';
    	}
        protected function get_ids($arr){
            $ids = '';
            foreach ($arr as $key => $value) $ids = empty($ids) ? $value['id'] : $ids.','.$value['id'];
            return $ids;
        }
        public function get_order_id($user_id){
            $data = $this->db->query("SELECT UUID() as uuid FROM payment_gateway")->result_array();
            return empty($data) ? time().''.$user_id : $data[0]['uuid'];
        }
        public function is_color_belongs_to_app_type($color_id, $app_type_id){
            $query="SELECT
                    iatt.iatt_app_type_id as id
                    FROM item_master item
                    INNER JOIN item_color_trans ict ON(ict.ict_item_id = item.item_id)
                    INNER JOIN item_app_type_trans iatt ON(iatt.iatt_item_id = item.item_id)
                    WHERE 1
                    AND iatt.iatt_delete_status = 0
                    AND ict.ict_id = $color_id
                    AND iatt.iatt_app_type_id = $app_type_id";
            $data = $this->db->query($query)->num_rows();
            return !empty($data);
        }
        public function get_category_id($color_id){
            $query="SELECT
                    item.item_category_id as id
                    FROM item_master item
                    INNER JOIN item_color_trans ict ON(ict.ict_item_id = item.item_id)
                    WHERE 1
                    AND ict.ict_id = $color_id";
            $data = $this->db->query($query)->result_array();
            return empty($data) ? 0 : $data[0]['id'];
        }
        public function get_category($category_id){
            $query="SELECT
                    category.item_category_status as category_status
                    FROM item_category_master category
                    WHERE category.item_category_id = $category_id";
            return $this->db->query($query)->result_array();
        }
        public function isCategoryExists($category_id){
            $query="SELECT
                    cict_item_category_id as category_id
                    FROM customer_item_category_trans
                    WHERE cict_customer_id = ".$this->user['id'];
            $data = $this->db->query($query)->result_array();
            if(empty($data)) return true;
            foreach ($data as $key => $value) {
                if($value['category_id'] == $category_id) return true;
            }
            return false;
        }
        public function update_checkout($cart, $status){
            $date = date('Y-m-d H:i:s');
            if(!empty($cart['payment_id'])) {
                $update = ['order_status' => $status, 'updated_at' => $date];
                $this->db_operations->data_update('payment_gateway', $update, 'order_id', $cart['payment_id']);
            }
            if(!empty($cart['checkout_id'])) {
                $update = ['status' => $status, 'updated_at' => $date];
                $this->db_operations->data_update('checkouts', $update, 'id', $cart['checkout_id']);
            }
        }
        public function get_images($color_id){
            $query="SELECT 
                    icf_path as url
                    FROM item_color_fabric
                    WHERE icf_ict_id = $color_id";
            $data = $this->db->query($query)->result_array();
            if(!empty($data)) return $data;
            return [0 => ['url' => assets('images/no-image.jpg')]];
        }
        public function get_cart_total($delivery_charges){
            $params = [
                $delivery_charges,
                $delivery_charges,
                $delivery_charges,
                $delivery_charges,
                $this->user['id']
            ];
            
            $query="SELECT 
                        cart.count,
                        cart.mtr AS ordered_mtr,
                        cart.weight,
                        cart.amt AS ordered_amt,
                        cart.packing_charge,
                        cart.total_amt,
                        cart.discount_amt,
                        cart.amt_after_discount,
                        ? AS delivery_charges,
                        ROUND((? + cart.amt_after_discount), 2) AS net_amt,
                        5 AS gst_per,
                        ROUND((? + cart.amt_after_discount) * 0.05, 2) AS gst_amt,
                        ROUND((? + cart.amt_after_discount) * 1.05, 2) AS payable_amt,
                        cart.checkout_status
                    FROM view_carts cart
                    WHERE cart.customer_id = ?";
            return $this->db->query($query, $params)->result_array();
        }
        public function get_courier_rate_chart($type, $city_id){
            $data = $this->db->from('courier_rate_chart')
                    ->where('courier_status', 1)
                    ->order_by('id', 'DESC')
                    ->limit(1)
                    ->get()
                    ->result_array();
            if(empty($data)) return [];

            $data[0]['type']    = $type;
            $data[0]['city_id'] = $city_id;
            $data[0]['state_id']= $this->get_state_id($city_id);
            $data[0]['weight']  = $this->get_weight();

            return $data[0];
        }
        protected function get_state_id($city_id){
            $query="SELECT city_state_id as id
                    FROM city_master 
                    WHERE city_id = $city_id";
            $data = $this->db->query($query)->result_array();
            return empty($data) ? 0 :$data[0]['id'];
        }
        protected function get_weight(){
            $query="SELECT cart.weight as weight
                    FROM view_carts cart
                    WHERE cart.customer_id = ".$this->user['id'];
            $data = $this->db->query($query)->result_array();
            return empty($data) ? 0 :$data[0]['weight'];
        }
        protected function get_by_road_standard_delivery_charges($temp){
            $flag     = true;
            $disabled = 0;
            $selected = $temp['type'] == 'by_road_standard' ? 1 : 0;
            
            $min_delivery_days = 0;
            $max_delivery_days = 0;

            $weight = ceil($temp['weight']);
            
            $within_city_ids = json_decode($temp['within_city_ids']);
            if(in_array($temp['city_id'], $within_city_ids)){
                $flag       = false;
                $charges    = round($temp['within_city_by_surface_standard_per_kg_rate'] * $weight, 2);
                $delivery_by= date('d-m-Y', strtotime("+".$temp['within_city_by_surface_max_delivery_days']." days"));  
                $min_delivery_days = $temp['within_city_by_surface_min_delivery_days'];
                $max_delivery_days = $temp['within_city_by_surface_max_delivery_days'];
            }
            
            $within_state_ids = json_decode($temp['within_state_ids']);
            if(in_array($temp['state_id'], $within_state_ids)){
                $flag       = false;
                $charges    = round($temp['within_zone_by_surface_standard_per_kg_rate'] * $weight, 2);
                $delivery_by= date('d-m-Y', strtotime("+".$temp['within_zone_by_surface_max_delivery_days']." days"));  
                $min_delivery_days = $temp['within_zone_by_surface_min_delivery_days'];
                $max_delivery_days = $temp['within_zone_by_surface_max_delivery_days'];
            }

            $metro_city_ids = json_decode($temp['metro_city_ids']);
            if(in_array($temp['city_id'], $metro_city_ids)){
                $flag       = false;
                $charges    = round($temp['metros_by_surface_standard_per_kg_rate'] * $weight, 2);
                $delivery_by= date('d-m-Y', strtotime("+".$temp['metros_by_surface_max_delivery_days']." days"));  
                $min_delivery_days = $temp['metros_by_surface_min_delivery_days'];
                $max_delivery_days = $temp['metros_by_surface_max_delivery_days'];
            }

            $special_state_ids = json_decode($temp['special_state_ids']);
            if(in_array($temp['state_id'], $special_state_ids)){
                $flag       = false;
                $charges    = round($temp['special_location_by_surface_standard_per_kg_rate'] * $weight, 2);
                $delivery_by= date('d-m-Y', strtotime("+".$temp['special_location_by_surface_max_delivery_days']." days"));
                $min_delivery_days = $temp['special_location_by_surface_min_delivery_days'];
                $max_delivery_days = $temp['special_location_by_surface_max_delivery_days'];
            }
            if($flag){
                $charges    = round($temp['rest_of_india_by_surface_standard_per_kg_rate'] * $weight, 2);
                $delivery_by= date('d-m-Y', strtotime("+".$temp['rest_of_india_by_surface_max_delivery_days']." days"));
                $min_delivery_days = $temp['rest_of_india_by_surface_min_delivery_days'];
                $max_delivery_days = $temp['rest_of_india_by_surface_max_delivery_days'];
            }
            return [
                'courier_id'    => $temp['id'],
                'id'            => 'by_road_standard',
                'label'         => 'BY ROAD',
                'selected'      => $selected, 
                'disabled'      => $disabled, 
                'charges'       => $charges, 
                'delivery_by'   => $delivery_by,
                'min_delivery_days' => $min_delivery_days,
                'max_delivery_days' => $max_delivery_days
            ];  
        }
        protected function get_by_air_standard_delivery_charges($temp){
            $flag     = true;
            $disabled = 0;
            $selected = $temp['type'] == 'by_air_standard' ? 1 : 0;

            $min_delivery_days = 0;
            $max_delivery_days = 0;
            
            $weight = floor($temp['weight']);
            $no_of_500_gms = 0;
            if($weight < 1) {
                $weight = 1;
            }else{
                $weight = 1;
                $extra_weight = $temp['weight'] - $weight;
                $no_of_500_gms = ceil($extra_weight / 0.5);
            }
            
            $within_city_ids = json_decode($temp['within_city_ids']);
            if(in_array($temp['city_id'], $within_city_ids)){
                $flag       = false;
                $disabled   = 1;
                $charges    = 0;
                $delivery_by= 'NA';
            }
            
            $within_state_ids = json_decode($temp['within_state_ids']);
            if(in_array($temp['state_id'], $within_state_ids)){
                $flag       = false;
                $charges    = round($temp['within_zone_by_air_standard_per_kg_rate'] * $weight, 2);
                $additional_charges = $no_of_500_gms * $temp['within_zone_by_air_standard_additional_500_gms_rate'];
                $charges += $additional_charges;
                $delivery_by= date('d-m-Y', strtotime("+".$temp['within_zone_by_air_max_delivery_days']." days"));     
                $min_delivery_days = $temp['within_zone_by_air_min_delivery_days'];
                $max_delivery_days = $temp['within_zone_by_air_max_delivery_days']; 
            }

            $metro_city_ids = json_decode($temp['metro_city_ids']);
            if(in_array($temp['city_id'], $metro_city_ids)){
                $flag       = false;
                $charges    = round($temp['metros_by_air_standard_per_kg_rate'] * $weight, 2);
                $additional_charges = $no_of_500_gms * $temp['metros_by_air_standard_additional_500_gms_rate'];
                $charges += $additional_charges;
                $delivery_by= date('d-m-Y', strtotime("+".$temp['metros_by_air_max_delivery_days']." days"));
                $min_delivery_days = $temp['metros_by_air_min_delivery_days'];
                $max_delivery_days = $temp['metros_by_air_max_delivery_days'];
            }

            $special_state_ids = json_decode($temp['special_state_ids']);
            if(in_array($temp['state_id'], $special_state_ids)){
                $flag       = false;
                $charges    = round($temp['special_location_by_air_standard_per_kg_rate'] * $weight, 2);
                $additional_charges = $no_of_500_gms * $temp['special_location_by_air_standard_additional_500_gms_rate'];
                $charges += $additional_charges;
                $delivery_by= date('d-m-Y', strtotime("+".$temp['special_location_by_air_max_delivery_days']." days"));
                $min_delivery_days = $temp['special_location_by_air_min_delivery_days'];
                $max_delivery_days = $temp['special_location_by_air_max_delivery_days'];
            }

            if($flag){
                $charges    = round($temp['rest_of_india_by_air_standard_per_kg_rate'] * $weight, 2);
                $additional_charges = $no_of_500_gms * $temp['rest_of_india_by_air_standard_additional_500_gms_rate'];
                $charges += $additional_charges;
                $delivery_by= date('d-m-Y', strtotime("+".$temp['rest_of_india_by_air_max_delivery_days']." days"));
                $min_delivery_days = $temp['rest_of_india_by_air_min_delivery_days'];
                $max_delivery_days = $temp['rest_of_india_by_air_max_delivery_days'];
            }
            return [
                'courier_id'    => $temp['id'],
                'id'            => 'by_air_standard',
                'label'         => 'BY AIR',
                'selected'      => $selected, 
                'disabled'      => $disabled, 
                'charges'       => $charges, 
                'delivery_by'   => $delivery_by,
                'min_delivery_days' => $min_delivery_days,
                'max_delivery_days' => $max_delivery_days
            ];
        }
        protected function get_by_self_delivery_charges($type){
            $selected = $type == 'by_self' ? 1 : 0;
            return [
                'courier_id'    => 0,
                'id'            => 'by_self',
                'label'         => 'BY SELF',
                'selected'      => $selected, 
                'disabled'      => 0, 
                'charges'       => 0, 
                'delivery_by'   => 'NA',
                'min_delivery_days' => 0,
                'max_delivery_days' => 0
            ];
        }
	}
?>