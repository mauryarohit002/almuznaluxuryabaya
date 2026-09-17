<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    use Restserver\Libraries\REST_Controller;
    require_once APPPATH . 'core/MY_Api_Controller.php';
    class dashboard extends my_api_controller{
        public function __construct(){
            parent::__construct([
                'model' => 'api/dashboard_model',
                'table' => 'order_master',
                'label' => 'Order',
            ]);
        }
        
        public function dashboard_read(){ 
            $this->allow_method(['POST']);  
            $search             = isset($this->post_data['search']) ? $this->post_data['search'] : null;
            $data['record']     = $this->model->dashboard_read($search);
            if (empty($data['record'])) return $this->response(['message' => 'Record not found.']);
            return $this->response(['status' => true, 'data' => $data['record']]);
        }


}?>