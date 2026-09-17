<?php defined('BASEPATH') or exit('No direct script access allowed');
    use Restserver\Libraries\REST_Controller;
    require_once APPPATH . 'core/MY_Api_Controller.php';
    class customer extends my_api_controller{ 
        protected $name;
        public function __construct(){
            $this->name = 'customer'; 
            parent::__construct([
                'model' => 'api/master/customer_model',
                'table' => 'customer_master',
                'label' => 'customer',
            ]);
        }


}?>