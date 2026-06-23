<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    use Restserver\Libraries\REST_Controller;
    require APPPATH . '/libraries/REST_Controller.php';
    class ticket extends \Restserver\Libraries\REST_Controller{
        protected $version;
        public function __construct(){
            parent::__construct();

            $this->version  = 1;

            $this->load->library('validation');
            $this->load->model('ticket_model', 'model');	    
        }
        public function handler_post(){
            $isValid = $this->validation->middleware(['validateAccessKey', 'validateToken', 'validateUser']);
            if($isValid['status'] !== TRUE) return $this->response($isValid, REST_Controller::HTTP_NOT_FOUND);
            $user_id = $isValid['data']['executive_id'];

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
            $_POST['user_id']   = $user_id;
        
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
            public function get_detail1(){
                $this->form_validation->set_rules('id', 'Ticket Id', 'trim|required');
                if ($this->form_validation->run() === FALSE) return ['error' => $this->form_validation->error_array(), 'message' => validation_errors()];

                $data = $this->model->get_detail();
                if(empty($data)) return ['status' => false, 'data' => [], 'message' => 'Record not found.', 'code' => REST_Controller::HTTP_BAD_REQUEST];
                return ['status' => true,'data' => $data, 'message' => 'Record fetched successfully.', 'code' => REST_Controller::HTTP_OK];
            }
            public function add_update_post(){
                $isValid = $this->validation->middleware(['validateAccessKey', 'validateToken', 'validateUser', 'getUser']);
                if($isValid['status'] !== TRUE) return $this->response($isValid, REST_Controller::HTTP_NOT_FOUND);
                $_POST['user_id'] = $isValid['data']['user_id'];

                $post_data = $this->input->post();
                if(!isset($post_data) || (isset($post_data) && empty($post_data))){
                    return $this->response(['status' => false, 'message' => 'Form data is empty.'], REST_Controller::HTTP_BAD_REQUEST);
                } 
                // echo "<pre>"; print_r($_POST);
                // echo "<pre>"; print_r($_FILES); exit;
                $_POST = $this->security->xss_clean($post_data); # XSS Filtering (https://www.codeigniter.com/user_guide/libraries/security.html)
                $this->form_validation->set_rules('ticket_id', 'Ticket id', 'trim|required');
                $this->form_validation->set_rules('ticket_name', 'Ticket name', 'trim|required');
                $this->form_validation->set_rules('ticket_desc', 'Ticket Description', 'trim|required');
                if ($this->form_validation->run() === FALSE){
                    return $this->response(['status' => false, 'error' => $this->form_validation->error_array(), 'message' => validation_errors()], REST_Controller::HTTP_BAD_REQUEST);
                }
                $msg                                = '';
                $master_data                        = [];
                $master_data['ticket_name'] 		= isset($_POST['ticket_name']) ? trim($_POST['ticket_name']) : '';
                $master_data['ticket_desc'] 		= isset($_POST['ticket_desc']) ? trim($_POST['ticket_desc']) : '';
                $master_data['ticket_updated_by'] 	= $_POST['user_id'];
                $master_data['ticket_updated_at'] 	= date('Y-m-d H:i:s');

                $this->db->trans_begin();
                if(!isset($_POST['ticket_id']) || (isset($_POST['ticket_id']) && empty($_POST['ticket_id']))){
                    $master_data['ticket_entry_no']     = $this->db_operations->get_max_id_custom('ticket_master', 'ticket_entry_no');
                    $master_data['ticket_entry_date'] 	= date('Y-m-d');
                    $master_data['ticket_created_by'] 	= $_POST['user_id'];
                    $master_data['ticket_created_at'] 	= date('Y-m-d H:i:s');
                    $master_data['ticket_status'] 		= 2;

                    $_POST['ticket_id'] = $this->db_operations->data_insert('ticket_master', $master_data);
                    if($_POST['ticket_id'] < 1){
                        $this->db->trans_rollback();
                        return $this->response(['status' => false, 'message' => 'Ticket not added.'], REST_Controller::HTTP_BAD_REQUEST);
                    }
                    $msg = 'Ticket added successfully.';
                }else{
                    $prev_data = $this->db_operations->get_record('ticket_master', ['ticket_id' => $_POST['ticket_id'], 'ticket_delete_status' => false]);
                    if(empty($prev_data)){
                        $this->db->trans_rollback();
                        return $this->response(['status' => false, 'message' => 'Ticket not found.'], REST_Controller::HTTP_BAD_REQUEST);
                    }

                    if($prev_data[0]['ticket_status'] != 2){
                        return $this->response(['status' => false, 'message' => 'Not allowed to update ticket.'], REST_Controller::HTTP_BAD_REQUEST);
                    }

                    if($this->db_operations->data_update('ticket_master', $master_data, 'ticket_id', $_POST['ticket_id']) < 1){
                        $this->db->trans_rollback();
                        return $this->response(['status' => false, 'message' => 'Ticket not updated.'], REST_Controller::HTTP_BAD_REQUEST);
                    }

                    if(isset($_POST['id']) && !empty($_POST['id'])){
                        $result = $this->update_attachment();
                        if(!$result['status']) return ['status' => FALSE, 'data' => $result['data'], 'msg' => $result['msg']];
                    }else{
                        $prev_data = $this->db_operations->get_record('ticket_attachment_trans', ['tat_ticket_id' => $_POST['ticket_id']]);
                        if(!empty($prev_data)){
                            foreach ($prev_data as $key => $value) {
                                $result = $this->delete_attachment($value);
                                if(!$result['status']) return ['status' => FALSE, 'data' => $result['data'], 'msg' => $result['msg']];
                            }
                        }
                    }
                    
                    $msg = 'Ticket updated successfully.';
                }

                $result = $this->add_attachment();
                if(!$result['status']) return ['status' => FALSE, 'data' => $result['data'], 'msg' => $result['msg']];

                if ($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                    return $this->response(['status' => false, 'message' => '1. Transaction Rollback.'], REST_Controller::HTTP_BAD_REQUEST);
                }
                $this->db->trans_commit();

                $data['id'] = $_POST['ticket_id'];
                $this->response(['status' => true,'data' => $data, 'message' => $msg], REST_Controller::HTTP_OK);
            }
            public function add_attachment(){
                $files 		= $_FILES;
                // echo "<pre>"; print_r($_FILES); exit;
                if(isset($files['ticket_attachment']) && !empty($files['ticket_attachment'])){
                    $cnt = isset($files['ticket_attachment']['name']) ? count($files['ticket_attachment']['name']) : 0;
                    for($i = 0; $i < $cnt; $i++){
                        if($files['ticket_attachment']['error'][$i] == 0){
                            $_FILES['ticket_attachment']['name']		= $files['ticket_attachment']['name'][$i];
                            $_FILES['ticket_attachment']['type']		= $files['ticket_attachment']['type'][$i];
                            $_FILES['ticket_attachment']['tmp_name']	= $files['ticket_attachment']['tmp_name'][$i];
                            $_FILES['ticket_attachment']['error']	    = $files['ticket_attachment']['error'][$i];
                            $_FILES['ticket_attachment']['size']		= $files['ticket_attachment']['size'][$i];

                            $this->load->library('image_lib');
                            $configer['image_library'] 	= 'gd2';
                            $configer['source_image']	= $_FILES['ticket_attachment']['tmp_name'];
                            $configer['maintain_ratio'] = TRUE;
                            $configer['width'] 			= '400';
                            $configer['height'] 		= '500';
                            $configer['master_dim'] 	= 'width';
                            $configer['quality'] 		= '20%';  
                            $this->image_lib->clear(); 
                            $this->image_lib->initialize($configer); 
                            $this->image_lib->resize();

                            unset($config);
                            $config 					= array();
                            $config['upload_path'] 		= 'public/uploads/ticket';
                            $config['allowed_types'] 	= 'gif|jpg|png|jpeg';
                            $file_name 					= $files['ticket_attachment']['name'][$i];
                            if(!file_exists($config['upload_path'])){
                                mkdir($config['upload_path'], 0777);
                            }
                            $ext 						= strtolower(substr($file_name, strrpos($file_name, '.') + 1));
                            $filename 					= 'ticket_'.$i.''.time().'.'.$ext;
                            $config['file_name'] 		= $filename;

                            $this->upload->initialize($config);
                            if(!$this->upload->do_upload('ticket_attachment')){

                                return ['status' => FALSE, 'data' => [],  'msg' => $this->upload->display_errors()];					
                            }
                            $imageinfo = $this->upload->data();
                            $full_path = $imageinfo['full_path'];

                            // check EXIF and autorotate if needed
                            // $this->db_operations->image_autorotate_resize(array('filepath' => $full_path), TRUE);		
                            $trans_data 				    = [];
                            $trans_data['tat_ticket_id']    = $_POST['ticket_id'];
                            $trans_data['tat_photo'] 	    = uploads('ticket/'.$filename);
                            // echo "<pre>"; print_r($trans_data); exit;
                            $trans_data['tat_id']           = $this->db_operations->data_insert('ticket_attachment_trans', $trans_data);
                            if($trans_data['tat_id'] < 1){
                                return ['status' => FALSE, 'data' => [],  'msg' => 'Image not inserted in database.'];						
                            }
                        }
                    }
                }
                return ['status' => TRUE, 'data' => [], 'msg' => ''];
            }
            public function update_attachment(){
                $prev_data = $this->db_operations->get_record('ticket_attachment_trans', ['tat_ticket_id' => $_POST['ticket_id']]);
                if(!empty($prev_data)){
                    foreach ($prev_data as $key => $value) {
                        if(!in_array($value['tat_id'], $_POST['id'])){
                            $result = $this->delete_attachment($value);
                            if(!$result['status']) return ['status' => FALSE, 'data' => $result['data'], 'msg' => $result['msg']];
                        }
                    }
                }
                return ['status' => TRUE, 'data' => [], 'msg' => ''];
            }
            public function delete_attachment($value){
                if($this->db_operations->delete_record('ticket_attachment_trans', ['tat_id' => $value['tat_id']]) < 1){
                    return ['status' => FALSE, 'data' => [], 'msg' => 'Attachment not deleted.'];
                }
                $explode   = explode('/', $value['tat_photo']);
                $file_name = 'public/uploads/ticket/'.end($explode);
                // echo "<pre>";print_r($file_name); exit();
                if(file_exists($file_name)){
                    unlink($file_name);
                }
                return ['status' => TRUE, 'data' => [], 'msg' => ''];
            }
        // ************************** Version 1 *******************************************


}
?>