<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    use Restserver\Libraries\REST_Controller;
    require_once APPPATH . 'core/MY_Api_Controller.php';
    class payment_mode extends my_api_controller{ 
        protected $name;
        public function __construct(){
            $this->name = 'payment_mode'; 
            parent::__construct([
                'model' => 'api/master/payment_mode_model',
                'table' => 'payment_mode_master',
                'label' => 'payment_mode',
            ]);
        }

    }
?>