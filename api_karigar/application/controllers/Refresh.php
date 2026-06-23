<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    use Restserver\Libraries\REST_Controller;
    require APPPATH . '/libraries/REST_Controller.php';
    class refresh extends \Restserver\Libraries\REST_Controller{
        protected $version;
        public function __construct(){
            parent::__construct();
            $this->version  = 1;
            $this->load->library('validation');
        }
        public function handler_post(){   
            $isValid = $this->validation->middleware(['validateAccessKey', 'validateToken', 'validateUser']);
            echo "<pre>"; print_r($isValid);die;
            if($isValid['status'] !== TRUE) return $this->response($isValid, REST_Controller::HTTP_NOT_FOUND);
           
            $post_data  = json_decode(file_get_contents('php://input'), true);
            if(empty($post_data)){
                return $this->response(['status' => false, 'message' => 'Form data is empty.'], REST_Controller::HTTP_BAD_REQUEST);
            }
            $funcs  = array_keys($post_data);
            $reqFunc= '';
            $flag   = false; 
            foreach ($funcs as $key => $func) {
                if(!isset($post_data[$func]['call'])){
                    return $this->response(['status' => false, 'message' => 'Call field is not define.'], REST_Controller::HTTP_BAD_REQUEST);
                }
                if($post_data[$func]['call'] == 1){
                    $reqFunc = $func;
                    $flag    = true;
                }
            }

            if($flag == false){
                return $this->response(['status' => false, 'message' => 'No requested function.'], REST_Controller::HTTP_BAD_REQUEST);
            }

            if(!isset($post_data[$reqFunc]) || (isset($post_data[$reqFunc]) && empty($post_data[$reqFunc]))){
                return $this->response(['status' => false, 'message' => 'Form data is empty.'], REST_Controller::HTTP_BAD_REQUEST);
            }

            $_POST              = $this->security->xss_clean($post_data[$reqFunc]); # XSS Filtering (https://www.codeigniter.com/user_guide/libraries/security.html)
            $_POST['user_id']   = $user_id;
            $_POST['group_id']  = $group_id;
        
            $version    = isset($_POST['version']) ? $_POST['version'] : $this->version;
            $func       = $reqFunc.''.$version;
            $resp       = $this->$func();

            $result['status']   = isset($resp['status'])    ? $resp['status']   : false;
            $result['message']  = isset($resp['message'])   ? $resp['message']  : '';
            $result['data']     = isset($resp['data'])      ? $resp['data']     : [];
            $result['error']    = isset($resp['error'])     ? $resp['error']    : [];
            $code               = isset($resp['code'])      ? $resp['code']     : REST_Controller::HTTP_BAD_REQUEST;

            return $this->response($result, $code);
        }
        
        // ************************** Version 1 *******************************************
            public function scan_barcode1(){

                if(!isset($_POST['barcode'])) return ['message' => 'Barcode not define.'];
                if(empty($_POST['barcode'])) return ['message' => 'Barcode not empty.'];

                $data = $this->model->get_barcode_data();
                if(empty($data))                    return ['message' => 'Barcode not found.'];
                if(empty($data[0]['apparel_id']))   return ['message' => 'Apparel not define.'];
                if($data[0]['obt_delete_status'] == 1)              return ['message' => 'Barcode is deleted.'];
                $process_data   = []; 
                $latest_data    = $this->model->get_latest_issue_data($data[0]['obt_id']);
                $job_data       = $this->model->get_receive_barcode_data($data[0]['obt_id']);
                
                $temp = $this->model->get_process_data($_POST['user_id']);
                if(empty($temp)) return ['message' => 'Process not define in karigar master.'];

                if(empty($latest_data)) { 
                    foreach ($temp as $key => $value) { 
                        $process_data[$key]['id']      = $value['process_id'];
                        $process_data[$key]['name']    = $value['process_name'];
                        $process_data[$key]['selected']= false;
                    }
                }else if(!empty($latest_data) && empty($latest_data[0]['jrt_id'])) {   
                    $process_data[0]['id']      = -1;
                    $process_data[0]['name']    = 'FINISH';
                    $process_data[0]['selected']= false;
                }else if(!empty($job_data) && !empty($job_data[0]['jrt_jit_id'])) {  
                    foreach ($temp as $key => $value) {
                        $process_data[$key]['id']      = $value['process_id'];
                        $process_data[$key]['name']    = $value['process_name'];
                        $process_data[$key]['selected']= false;
                    }
                } else{  
                    // echo "<pre>"; print_r($job_data);die; 
                    if($_POST['user_id'] == $job_data[0]['jim_karigar_id']){ 
                       if($job_data[0]['jrt_jit_id'] < 1) {  
                            $temp = $this->model->get_process_data($_POST['user_id']);
                            if(empty($temp)) return ['message' => 'Process not define in karigar master.'];
                            foreach ($temp as $key => $value) {
                                $process_data[$key]['id']      = $value['process_id'];
                                $process_data[$key]['name']    = $value['process_name'];
                                $process_data[$key]['selected']= false;
                            }
                            $process_data[count($temp)]['id']      = -1;
                            $process_data[count($temp)]['name']    = 'FINISH';
                            $process_data[count($temp)]['selected']= false;
                        } else {
                            return ['message' => '1. Job has been compeleted.'];
                        }
                    } else{ 
                        if($job_data[0]['jrt_jit_id'] < 1) {
                            $process_data[0]['id']      = $_POST['process_id'];
                            $process_data[0]['name']    = 'ACCEPT';
                            $process_data[0]['selected']= true;
                        } else {
                            return ['message' => '1. Barcode accepted by other karigar.'];
                        }
                    }
                }
                
                $resp = [];
                $resp['barcode_data'] = $data;
                $resp['process_data'] = $process_data;
                return ['status' => true,'data' => $resp, 'message' => 'Record fetched successfully.', 'code' => REST_Controller::HTTP_OK];
            }
           

}
?>