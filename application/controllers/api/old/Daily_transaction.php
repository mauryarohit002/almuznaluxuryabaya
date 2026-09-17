<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    use Restserver\Libraries\REST_Controller;
    require_once APPPATH . 'core/MY_Api_Controller.php';
    class daily_transaction extends my_api_controller{
        public function __construct(){
            parent::__construct([
                'model' => 'api/daily_transaction_model',
                'table' => 'daily_transaction_master',
                'label' => 'daily_transaction',
            ]);
        }
        
        public function get_data(){
            $this->allow_method(['POST']);  
            $search             = isset($this->post_data['search']) ? $this->post_data['search'] : null;
            $data['record']     = $this->model->get_data($search);
            if (empty($data['record'])) return $this->response(['message' => 'Record not found.']);
            return $this->response(['status' => true, 'data' => $data]);
        }

}?>