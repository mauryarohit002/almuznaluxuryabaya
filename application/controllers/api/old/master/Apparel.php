<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    use Restserver\Libraries\REST_Controller;
    require_once APPPATH . 'core/MY_Api_Controller.php';
    class apparel extends my_api_controller{ 
        protected $name;
        public function __construct(){
            $this->name = 'apparel'; 
            parent::__construct([
                'model' => 'api/master/apparel_model',
                'table' => 'apparel_master',
                'label' => 'apparel',
            ]);
        }

    }
?>