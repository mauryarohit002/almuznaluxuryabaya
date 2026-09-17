<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    use Restserver\Libraries\REST_Controller;
    require_once APPPATH . 'core/MY_Api_Controller.php';
    class karigar extends my_api_controller{ 
        protected $name;
        public function __construct(){
            $this->name = 'karigar'; 
            parent::__construct([
                'model' => 'api/master/karigar_model',
                'table' => 'karigar_master',
                'label' => 'karigar',
            ]);
        }

    }
?>