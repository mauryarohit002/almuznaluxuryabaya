<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    use Restserver\Libraries\REST_Controller;
    require_once APPPATH . 'core/MY_Controller.php';
    class order_status extends my_controller{ 
        protected $name;
        public function __construct(){ 
            $this->name = 'order_status'; 
            parent::__construct([
                'model' => 'reports/order_status_model',
                'table' => '',
                'label' => 'order_status',
            ]);
        }


         public function get_status($id=0){     
            $this->allow_method(['GET']);
            if(!isset($id)) return $this->response(['message' => 'Order Id not defined.']);
            if(empty($id)) return $this->response(['message' => 'Order Id is empty.']);
            $data=$this->db_operations->get_record('order_master',['om_id'=>$id,'om_delete_status'=>0]); 
            if(empty($data)) return $this->response(['message' => 'Order Not Found ']);
            $data = $this->model->get_order_status($id);
            if(empty($data)) return $this->response(['message' => '1. Order Not Found ']);
            return $this->response(['status' => TRUE,'data' => $data[0], 'message' => 'Data fetched successfully..', 'code' => REST_Controller::HTTP_OK]);
        }

}?>