<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    use Restserver\Libraries\REST_Controller;
    require_once APPPATH . 'core/MY_Api_Controller.php';
    class proces extends my_api_controller{ 
        protected $name;
        public function __construct(){
            $this->name = 'proces'; 
            parent::__construct([
                'model' => 'api/master/proces_model',
                'table' => 'proces_master',
                'label' => 'proces',
            ]);
        }

    }
?>