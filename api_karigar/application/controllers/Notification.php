<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    use Restserver\Libraries\REST_Controller;
    require APPPATH . '/libraries/REST_Controller.php';
    class notification extends \Restserver\Libraries\REST_Controller{
        protected $fcm_path;
        protected $fcm_key;
        protected $version;
        public function __construct(){
            parent::__construct();

            $this->version  = 1;
            $this->fcm_path = 'https://fcm.googleapis.com/fcm/send';
            $this->fcm_key  = 'AAAAKkcd6uM:APA91bFsACkNc3YCeH4nQXwe6wm22nRtaFaXtpTpcWxNv7cxguWWRwZdMIf_OI3VoK1hPFXW29tVhPE4SIqrfMu1Xw5gNIHclHvKkWiTECAoZuLuWZTfUwUvWjPANFerzlYIoYolLj8G';

            $this->load->library('validation');
            $this->load->model('notification_model', 'model');	    
        }
        public function handler_post(){
            $isValid = $this->validation->middleware(['validateAccessKey', 'validateToken', 'validateUser']);
            if($isValid['status'] !== TRUE) return $this->response($isValid, REST_Controller::HTTP_NOT_FOUND);
            $user_id = $isValid['data']['customer_id'];
            
            $post_data  = json_decode(file_get_contents('php://input'), true);
            if(empty($post_data)){
                return $this->response(['status' => false, 'message' => 'Form data is empty.'], REST_Controller::HTTP_BAD_REQUEST);
            }
            // return $this->response($funcs, REST_Controller::HTTP_BAD_REQUEST);
            $funcs  = array_keys($post_data);
            $reqFunc= '';
            $flag   = false;
            foreach ($funcs as $key => $func) {
                if(!isset($post_data[$func]['call'])){
                    return $this->response(['status' => false, 'message' => 'Active field is not define.'], REST_Controller::HTTP_BAD_REQUEST);
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

            $_POST              = $this->security->xss_clean($post_data[$reqFunc]); 
            $_POST['user_id']   = $user_id;
        
            $version    = isset($_POST['version']) ? $_POST['version'] : $this->version;
            $func       = $reqFunc.''.$version;
            $resp       = $this->$func($user_id);
            if(!$resp['status']){
                return $this->response(['status' => $resp['status'], 'message' => $resp['message']], $resp['code']);
            }
            return $this->response(['status' => $resp['status'], 'data' => $resp['data'], 'message' => $resp['message']], $resp['code']);
        }
        
    // ************************** Version 1 *******************************************
        public function get_list1(){
            $next_offset= $this->model->get_list(NEXT_OFFSET);
            if($next_offset > 0) $data['next_offset'] = $next_offset;

            $data['record']   = $this->model->get_list();
            if(empty($data['record'])) return ['status' => false, 'data' => [], 'message' => 'Record not found.', 'code' => REST_Controller::HTTP_BAD_REQUEST];
            return ['status' => true,'data' => $data, 'message' => 'Record fetched successfully.', 'code' => REST_Controller::HTTP_OK];
        }
        public function get_detail1(){
            $this->form_validation->set_rules('id', 'Notification id', 'trim|required');
            if ($this->form_validation->run() === FALSE){
                return ['status' => false, 'error' => $this->form_validation->error_array(), 'message' => validation_errors(), 'code' => REST_Controller::HTTP_BAD_REQUEST];
            }
            
            $data['detail'] = $this->model->get_detail();
            if(empty($data['detail'])) return ['status' => false, 'message' => 'Record not found.', 'code' => REST_Controller::HTTP_BAD_REQUEST];

            return ['status' => true,'data' => $data, 'message' => "Record fetched successfully.", 'code' => REST_Controller::HTTP_OK];
        }
        public function get_type1(){
            $next_offset = $this->model->get_type(NEXT_OFFSET);
            if($next_offset > 0) $data['next_offset'] = $next_offset;    
            $data['record']   = $this->model->get_type();
            if(empty($data['record'])) return ['status' => false, 'message' => 'Record not found.', 'code' => REST_Controller::HTTP_BAD_REQUEST];
            return ['status' => true,'data' => $data, 'message' => "Record fetched successfully.", 'code' => REST_Controller::HTTP_OK];
        }
        public function get_count1(){
            $data = $this->model->get_count();
            return ['status' => true,'data' => $data, 'message' => "Record fetched successfully.", 'code' => REST_Controller::HTTP_OK];
        }
        public function send1(){	
            $this->form_validation->set_rules('token', 'Token', 'trim|required');
            $this->form_validation->set_rules('title', 'Notification title', 'trim|required');
            $this->form_validation->set_rules('body', 'Notification body', 'trim|required');
            
            if ($this->form_validation->run() === FALSE){
                return ['status' => false, 'error' => $this->form_validation->error_array(), 'message' => validation_errors(), 'code' => REST_Controller::HTTP_BAD_REQUEST];
            }
            $server_key = !isset($_POST['server_key']) || (isset($_POST['server_key']) && empty($_POST['server_key'])) ? $this->fcm_key : $_POST['server_key'];
            $headers = ['Authorization:key=' .$server_key, 'Content-Type:application/json'];
    
            $notification['title']  = $_POST['title'];
            $notification['body']   = $_POST['body'];
    
            $data['screen']         = $_POST['screen'];
            $data['id']             = $_POST['id'];
    
            $fields['to']           = $_POST['token'];
            $fields['notification'] = $notification;
            $fields['data']         = $data;
            // echo "<pre>"; print_r($fields); exit;
            $curl = curl_init();
            curl_setopt( $curl,CURLOPT_URL, $this->fcm_path);
            curl_setopt( $curl,CURLOPT_POST, true );
            curl_setopt( $curl,CURLOPT_HTTPHEADER, $headers);
            curl_setopt( $curl,CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $curl,CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $curl,CURLOPT_POSTFIELDS, json_encode( $fields ) );
            $result = curl_exec($curl );
            $result = json_decode($result, true);
            curl_close( $curl );
            $message            = isset($result['results'][0]['error']) ? $result['results'][0]['error'] : 'Notification send successfully.';
            $resp['success']    = isset($result['success']) ? $result['success'] : 0;
            $resp['failure']    = isset($result['failure']) ? $result['failure'] : 0;
            
            if(empty($resp['success']) && empty($resp['failure'])){
                $message = isset($result['results'][0]['error']) ? $result['results'][0]['error'] : 'Notification not send.';
            }else if(!empty($resp['success']) && empty($resp['failure'])){
                $message = isset($result['results'][0]['error']) ? $result['results'][0]['error'] : 'Notification send successfully.';
            }else if(empty($resp['success']) && !empty($resp['failure'])){
                $message = isset($result['results'][0]['error']) ? $result['results'][0]['error'] : 'Notification not send.';
            }else{
                $message = isset($result['results'][0]['error']) ? $result['results'][0]['error'] : 'Not possible.';
            }
    
            return ['status' => true,'data' => $resp, 'message' => $message, 'code' => REST_Controller::HTTP_OK];
        }
    // ************************** Version 1 *******************************************


}
?>