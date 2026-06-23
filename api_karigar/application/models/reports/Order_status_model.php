<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    require_once APPPATH . 'core/MY_Model.php';
    class order_status_model extends my_model{
        public function __construct(){ parent::__construct(); }
        public function isExist($id){
            return false;
        }
        public function read($search, $args){     
            $where  = '';
            if(isset($search['customer_id']) && !empty($search['customer_id']))
                $where .= " AND customer.customer_id = '".$search['customer_id']."'";
            
            if(isset($search['customer_name']) && !empty($search['customer_name']))
                $where .= " AND customer.customer_name LIKE '%".$search['customer_name']."%'";

            if(isset($search['date_from']) && !empty($search['date_from'])){ 
                $date_from = date('Y-m-d', strtotime($search['date_from']));
                $where .=" AND om.om_em_entry_date >= '".$date_from."'";
            }else{
                // $where .=" AND om.om_em_entry_date >= '".date('Y-m-d')."'"; 
            }

            if(isset($search['date_to']) && !empty($search['date_to'])){
                $date_to = date('Y-m-d', strtotime($search['date_to']));
                $where .=" AND om.om_em_entry_date <= '".$date_to."'";
            }else{
                // $where .=" AND om.om_em_entry_date <= '".date('Y-m-d')."'"; 
            }

            if(isset($search['barcode']) && !empty($search['barcode']))
                $where .= " AND obt.obt_item_code = '".$search['barcode']."'";
            
           $query="SELECT 
                obt.obt_id,
                om.om_em_entry_no as entry_no,
                DATE_FORMAT(om.om_em_entry_date, '%d-%m-%Y') as entry_date,
                UPPER(customer.customer_name) as customer_name,
                customer.customer_mobile,
                UPPER(apparel.apparel_name) as apparel_name,
                obt.obt_item_code as barcode
                FROM order_master om
                INNER JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)
                INNER JOIN order_trans ot ON(ot.ot_om_id = om.om_id)
                INNER JOIN order_barcode_trans obt ON(obt.obt_ot_id = ot.ot_id)
                INNER JOIN apparel_master apparel ON(apparel.apparel_id = obt.obt_apparel_id)
                WHERE om.om_delete_status = 0
                AND ot.ot_delete_status = 0
                AND obt.obt_delete_status = 0
                $where
                GROUP BY obt.obt_id 
                ORDER BY om.om_em_entry_date DESC";
            // print_r($query);die;    

            if (isset($args['wantCount']) && $args['wantCount'] == true) 
                return $this->db->query($query)->num_rows();
            if (isset($args['limit']) && !empty($args['limit']))
                $query .= " LIMIT ".(int) $args['limit'];

            if (isset($args['offset']) && !empty($args['offset']))
                $query .= " OFFSET ".(int) $args['offset'];
           $data = $this->db->query($query)->result_array();
           if(!empty($data)){
                foreach ($data as $key => $value) {
                     $status_data = $this->get_status_data($value['obt_id']);
                     $data[$key]['karigar_name'] =  $status_data['karigar_name'];
                     $data[$key]['proces_name'] =  $status_data['proces_name'];
                     $data[$key]['status']      =  $status_data['status'];
                     unset($data[$key]['obt_id']);
                }
           }

           return $data;
        }

        public function get_order_status($om_id) { 
            $query="SELECT obt.obt_id,
                    UPPER(apparel.apparel_name)  as apparel_name
                    FROM order_barcode_trans obt
                    LEFT JOIN apparel_master apparel ON(apparel.apparel_id = obt.obt_apparel_id)
                    WHERE obt.obt_delete_status = 0
                    AND obt.obt_om_id = $om_id
                    ORDER BY apparel.apparel_name ASC";
            $data = $this->db->query($query)->result_array();
            // print_r($query);die;
            // if(empty($data)) return ['message' => 'Order Not Found'];
            $record =[];
            if(!empty($data)) {
                foreach ($data as $key => $value) {
                    $status_data = $this->get_status_data($value['obt_id']);
                    $arr['apparel_name'] = $value['apparel_name'];
                    $arr['karigar_name'] = $status_data['karigar_name'];
                    $arr['proces_name'] = $status_data['proces_name'];
                    $arr['status']      = $status_data['status'];
                    array_push($record, $arr);
                }
            }

            return $record;
           // return $record;
        }


        public function get_status_data($obt_id) {
            $query="SELECT 
                    UPPER(karigar.karigar_name) as karigar_name,
                    UPPER(proces.proces_name) as proces_name,
                    'ISSUED' as status
                    FROM job_issue_trans jit
                    INNER JOIN job_issue_master jim ON(jim.jim_id = jit.jit_jim_id)
                    INNER JOIN karigar_master karigar ON(karigar.karigar_id = jim.jim_karigar_id)
                    INNER JOIN proces_master proces ON(proces.proces_id = jim.jim_proces_id)
                    LEFT JOIN job_receive_trans jrt ON(jrt.jrt_jit_id = jit.jit_id)
                    WHERE jim.jim_delete_status = 0
                    AND jit.jit_obt_id = $obt_id
                    AND IFNULL(jrt.jrt_jit_id, 0)=0
                    ORDER BY jit.jit_id DESC
                    LIMIT 1";
            $data = $this->db->query($query)->result_array();
            // echo "<pre>"; print_r($query); exit();
            // echo "<pre>"; print_r($data); exit();
            if(!empty($data)){
                return ['karigar_name' => $data[0]['karigar_name'], 'proces_name' => $data[0]['proces_name'], 'status' =>  $data[0]['status']];
            }

            $query="SELECT 
                    UPPER(karigar.karigar_name) as karigar_name,
                    UPPER(proces.proces_name) as proces_name,
                    'RECEIVED' as status
                    FROM job_receive_trans jrt
                    INNER JOIN job_issue_master jim ON(jim.jim_id = jrt.jrt_jim_id)
                    INNER JOIN job_receive_master jrm ON(jrm.jrm_id = jrt.jrt_jrm_id)
                    INNER JOIN karigar_master karigar ON(karigar.karigar_id = jim.jim_karigar_id)
                    INNER JOIN proces_master proces ON(proces.proces_id = jim.jim_proces_id)
                    WHERE jrm.jrm_delete_status = 0
                    AND jrt.jrt_obt_id = $obt_id
                    AND jrt.jrt_jim_id != 0
                    ORDER BY jrt.jrt_id DESC
                    LIMIT 1";
            $data = $this->db->query($query)->result_array();
            // echo "<pre>"; print_r($query); exit();
            // echo "<pre>"; print_r($data); exit();

            if(!empty($data)){
                return ['karigar_name' => $data[0]['karigar_name'], 'proces_name' => $data[0]['proces_name'], 'status' =>  $data[0]['status']];
            }

            

            return ['karigar_name' => 'NOT ASSIGN', 'proces_name' => 'NOT DEFINE', 'status' => 'PENDING'];
        }

             
    }
?>