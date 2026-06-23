<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    use Restserver\Libraries\REST_Controller;
    require APPPATH . '/libraries/REST_Controller.php';
    class version extends \Restserver\Libraries\REST_Controller{
        protected $version;
        public function __construct(){
            parent::__construct();

            $this->version  = 1;

            $this->load->library('validation');
            $this->load->model('version_model', 'model');	    
        }
        public function handler_post(){
            $isValid = $this->validation->middleware(['validateAccessKey']);
            if($isValid['status'] !== TRUE) return $this->response($isValid, REST_Controller::HTTP_NOT_FOUND);
            
            $post_data  = json_decode(file_get_contents('php://input'), true);
            // return $this->response($post_data, REST_Controller::HTTP_BAD_REQUEST);
            if(empty($post_data)){
                return $this->response(['status' => false, 'message' => 'Form data is empty.'], REST_Controller::HTTP_BAD_REQUEST);
            }
            $funcs  = array_keys($post_data);
            $reqFunc= '';
            $flag   = false;
            foreach ($funcs as $key => $func) {
                if(!isset($post_data[$func]['active'])){
                    return $this->response(['status' => false, 'message' => 'Active field is not define.'], REST_Controller::HTTP_BAD_REQUEST);
                }
                if($post_data[$func]['active'] == 1){
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
            public function get_list1(){
                $next_offset= $this->model->get_list(NEXT_OFFSET);
                if($next_offset > 0) $data['next_offset'] = $next_offset;

                $data['record']   = $this->model->get_list();
                if(empty($data['record'])) return ['status' => false, 'data' => [], 'message' => 'Record not found.', 'code' => REST_Controller::HTTP_BAD_REQUEST];
                return ['status' => true,'data' => $data, 'message' => 'Record fetched successfully.', 'code' => REST_Controller::HTTP_OK];
            }
            public function get_latest1(){
                return ['status' => true,'data' => $this->model->get_latest(), 'message' => 'Record fetched successfully.', 'code' => REST_Controller::HTTP_OK];
            }
            public function add_update_post(){
                $isValid = $this->validation->middleware(['validateAccessKey']);
                if($isValid['status'] !== TRUE) return $this->response($isValid, REST_Controller::HTTP_NOT_FOUND);
                
                $post_data = $this->input->post();
                if(!isset($post_data) || (isset($post_data) && empty($post_data))){
                    return $this->response(['status' => false, 'message' => 'Form data is empty.'], REST_Controller::HTTP_BAD_REQUEST);
                } 
                // echo "<pre>"; print_r($_POST);
                // echo "<pre>"; print_r($_FILES); exit;
                $_POST = $this->security->xss_clean($post_data); # XSS Filtering (https://www.codeigniter.com/user_guide/libraries/security.html)
                $this->form_validation->set_rules('version_id', 'Version id', 'trim|required');
                $this->form_validation->set_rules('version_no', 'Version no', 'trim|required');
                $this->form_validation->set_rules('version_min_no', 'Version min build', 'trim|required');
                if ($this->form_validation->run() === FALSE){
                    return $this->response(['status' => false, 'error' => $this->form_validation->error_array(), 'message' => validation_errors()], REST_Controller::HTTP_BAD_REQUEST);
                }
                $msg                                = '';
                $master_data                        = [];
                $master_data['version_no'] 	        = isset($_POST['version_no']) ? trim($_POST['version_no']) : '';
                $master_data['version_min_no'] 	    = isset($_POST['version_min_no']) ? trim($_POST['version_min_no']) : '';
                $master_data['version_description'] = isset($_POST['version_description']) ? trim($_POST['version_description']) : '';
                $master_data['version_updated_at'] 	= date('Y-m-d H:i:s');

                $temp = $this->db_operations->get_record('customer_version_master', ['version_id !=' => $_POST['version_id'], 'version_no' => $_POST['version_no']]);
                if(!empty($temp)){
                    return $this->response(['status' => false, 'message' => 'Version no already exists.'], REST_Controller::HTTP_BAD_REQUEST);
                }

                $this->db->trans_begin();
                if(!isset($_POST['version_id']) || (isset($_POST['version_id']) && empty($_POST['version_id']))){
                    $result = $this->add_attachment();
                    if(!$result['status']){
                        $this->db->trans_rollback();
                        return $this->response(['status' => false, 'message' => $result['msg']], REST_Controller::HTTP_BAD_REQUEST);
                    }
                    $master_data['version_path'] 		= $result['data'];
                    $master_data['version_created_at'] 	= date('Y-m-d H:i:s');
                    
                    // echo "<pre>"; print_r($master_data); exit;
                    $master_data['version_id'] = $this->db_operations->data_insert('customer_version_master', $master_data);
                    if($master_data['version_id'] < 1){
                        $this->db->trans_rollback();
                        return $this->response(['status' => false, 'message' => 'Version not added.'], REST_Controller::HTTP_BAD_REQUEST);
                    }
                    $msg = 'Version added successfully.';
                }else{
                    $prev_data = $this->db_operations->get_record('customer_version_master', ['version_id' => $_POST['version_id']]);
                    if(empty($prev_data)){
                        $this->db->trans_rollback();
                        return $this->response(['status' => false, 'message' => 'Version not found.'], REST_Controller::HTTP_BAD_REQUEST);
                    }
                    $result = $this->update_attachment($prev_data[0]);
                    if(!$result['status']){
                        $this->db->trans_rollback();
                        return $this->response(['status' => false, 'message' => $result['msg']], REST_Controller::HTTP_BAD_REQUEST);
                    }
                    $master_data['version_path'] = $result['data'];
                    if($this->db_operations->data_update('customer_version_master', $master_data, 'version_id', $_POST['version_id']) < 1){
                        $this->db->trans_rollback();
                        return $this->response(['status' => false, 'message' => 'Version not updated.'], REST_Controller::HTTP_BAD_REQUEST);
                    }
                    $master_data['version_id'] = $_POST['version_id'];
                    $msg = 'Version updated successfully.';
                }
                $this->db->trans_commit();

                $this->response(['status' => true,'data' => $master_data, 'message' => $msg], REST_Controller::HTTP_OK);
            }
            public function add_attachment(){
                $files 		= $_FILES;
                // echo "<pre>"; print_r($files); exit;
                if(isset($files['version_attachment']) && !empty($files['version_attachment'])){
                    if($files['version_attachment']['error'] == 0){
                        $_FILES['version_attachment']['name']		= $files['version_attachment']['name'];
                        $_FILES['version_attachment']['type']		= $files['version_attachment']['type'];
                        $_FILES['version_attachment']['tmp_name']	= $files['version_attachment']['tmp_name'];
                        $_FILES['version_attachment']['error']	    = $files['version_attachment']['error'];
                        $_FILES['version_attachment']['size']		= $files['version_attachment']['size'];

                        unset($config);
                        $config 					= array();
                        $config['upload_path'] 		= 'public/uploads/version';
                        $config['allowed_types'] 	= '*';
                        $file_name 					= $files['version_attachment']['name'];
                        if(!file_exists($config['upload_path'])){
                            mkdir($config['upload_path'], 0777);
                        }
                        $ext 						= strtolower(substr($file_name, strrpos($file_name, '.') + 1));
                        $filename 					= 'version_'.time().'.'.$ext;
                        $config['file_name'] 		= $filename;
                        
                        if($ext != 'apk') return ['status' => FALSE, 'data' => [],  'msg' => 'The filetype you are attempting to upload is not allowed.'];					

                        $this->upload->initialize($config);
                        if(!$this->upload->do_upload('version_attachment')){
                            return ['status' => FALSE, 'data' => [],  'msg' => $this->upload->display_errors()];					
                        }
                        $imageinfo = $this->upload->data();
                        $full_path = $imageinfo['full_path'];

                        return ['status' => TRUE, 'data' => uploads('version/'.$filename), 'msg' => ''];
                        
                    }
                }
                return ['status' => FALSE, 'data' => [], 'msg' => 'Attachment not found.'];
            }
            public function update_attachment($prev_data){
                $result = $this->add_attachment();
                if($result['status']) return $result;
                return ['status' => TRUE, 'data' => $prev_data['version_path'], 'msg' => ''];
            }
            public function delete_attachment($value){
                if($this->db_operations->delete_record('version_attachment_trans', ['tat_id' => $value['tat_id']]) < 1){
                    return ['status' => FALSE, 'data' => [], 'msg' => 'Attachment not deleted.'];
                }
                $explode   = explode('/', $value['tat_photo']);
                $file_name = 'public/uploads/version/'.end($explode);
                // echo "<pre>";print_r($file_name); exit();
                if(file_exists($file_name)){
                    unlink($file_name);
                }
                return ['status' => TRUE, 'data' => [], 'msg' => ''];
            }
        // ************************** Version 1 *******************************************


}
?>