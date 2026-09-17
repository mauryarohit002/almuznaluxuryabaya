<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    use Restserver\Libraries\REST_Controller;
    require_once APPPATH . 'core/MY_Api_Controller.php';
    class Branch extends my_api_controller{ 
        protected $name;
        public function __construct(){
            $this->name = 'branch'; 
            parent::__construct([
                'model' => 'api/master/Branch_model',
                'table' => 'branch_master',
                'label' => 'branch',
            ]);
        }

    }
?>