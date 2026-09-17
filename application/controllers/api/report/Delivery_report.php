<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    use Restserver\Libraries\REST_Controller;
    require_once APPPATH . 'core/MY_Api_Controller.php';
    class Delivery_report extends my_api_controller{
        public function __construct(){
            parent::__construct([
                'model' => 'api/report/Delivery_report_model',
                'table' => 'delivery_master',
                'label' => 'delivery',
            ]);
        }
        
    }
?>