<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    use Restserver\Libraries\REST_Controller;
    require_once APPPATH . 'core/MY_Api_Controller.php';
    class order extends my_api_controller{
        public function __construct(){
            parent::__construct([
                'model' => 'api/Order_model',
                'table' => 'order_master',
                'label' => 'Order',
            ]);
        }

        public function get_details($om_id) {
            $this->allow_method(['GET']);
            if(!isset($om_id) || (isset($om_id) && $om_id <= 0)) return $this->response(['message' => 'Invalid Id']);
            $query="SELECT obt.obt_id,
                    UPPER(apparel.apparel_name) as apparel_name
                    FROM order_barcode_trans obt
                    INNER JOIN apparel_master apparel ON(apparel.apparel_id = obt.obt_apparel_id)
                    WHERE obt.obt_delete_status = 0
                    AND obt.obt_om_id = $om_id
                    ORDER BY apparel.apparel_name ASC";
            $data = $this->db->query($query)->result_array();
            if(empty($data)) return $this->response(['message' => 'Order Not found']);
            $record =[];
            if(!empty($data)) {
                foreach ($data as $key => $value) {
                    $status_data = $this->get_job_status($value['obt_id']);
                    $arr['apparel_name'] = $value['apparel_name'];
                    $arr['karigar_name'] = $status_data['karigar_name'];
                    $arr['proces_name'] = $status_data['proces_name'];
                    $arr['status']      = $status_data['status'];
                    array_push($record, $arr);
                }
            }
            
            return $this->response(['status' => true, 'data' => $record]);
           // return $record;
        }

        public function get_barcode_status($obt_item_code) {
            $this->allow_method(['GET']);
            if(!isset($obt_item_code) || (isset($obt_item_code) && $obt_item_code <= 0)) return $this->response(['message' => 'Invalid barcode']);
            $obt_item_code = trim($obt_item_code);
            $query="SELECT obt.obt_id,
                    UPPER(apparel.apparel_name) as apparel_name
                    FROM order_barcode_trans obt
                    INNER JOIN apparel_master apparel ON(apparel.apparel_id = obt.obt_apparel_id)
                    WHERE obt.obt_delete_status = 0
                    AND obt.obt_item_code = $obt_item_code
                    ORDER BY apparel.apparel_name ASC";
            $data = $this->db->query($query)->result_array();
            if(empty($data)) return $this->response(['message' => 'Barcode Not found']);
            $record =[];
            if(!empty($data)) {
                foreach ($data as $key => $value) {
                    $status_data = $this->get_job_status($value['obt_id']);
                    $arr['apparel_name'] = $value['apparel_name'];
                    $arr['karigar_name'] = $status_data['karigar_name'];
                    $arr['proces_name'] = $status_data['proces_name'];
                    $arr['status']      = $status_data['status'];
                    array_push($record, $arr);
                }
            }
            
            return $this->response(['status' => true, 'data' => $record]);
           // return $record;
        }
    
        public function get_job_status($obt_id) {
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

}?>