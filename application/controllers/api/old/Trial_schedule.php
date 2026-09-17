<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    use Restserver\Libraries\REST_Controller;
    require_once APPPATH . 'core/MY_Api_Controller.php';
    class trial_schedule extends my_api_controller{
        public function __construct(){
            parent::__construct([
                'model' => 'api/trial_schedule_model',
                'table' => 'order_master',
                'label' => 'trial_schedule',
            ]);
        } 

}?>