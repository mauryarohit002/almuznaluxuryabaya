<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    use Restserver\Libraries\REST_Controller;
    require_once APPPATH . 'core/MY_Controller.php';
    class user extends my_controller{
        public function __construct(){
            parent::__construct([
                'model' => 'User_model',
                'table' => 'user_master',
                'label' => 'User',
            ]);
        }

         public function login(){ 
            $this->allow_method(['POST']);
            $input = json_decode(file_get_contents("php://input"), true);

            if(!isset($this->post_data['mobile_no'])) return $this->response(['message' => 'Mobile no not defined.']);
            if(empty($this->post_data['mobile_no'])) return $this->response(['message' => 'Mobile no is empty.']);
           
            if(!isset($this->post_data['password'])) return $this->response(['message' => 'password not defined.']);
            if(empty($this->post_data['password'])) return $this->response(['message' => 'password is empty.']);

            // echo "<pre>"; print_r($this->post_data['password']); exit;

            
            $user = $this->model->get_user($this->post_data['mobile_no']);
            if(empty($user))return $this->response(['message' => 'Karigar not found.']);
            if($user[0]['karigar_status'] == 0) return $this->response(['message' => 'Karigar account has been deactivated.', 'code' => REST_Controller::HTTP_UNAUTHORIZED]);

            if($user[0]['karigar_password'] != md5($this->post_data['password'])){
                return $this->response(['message' => 'Invalid Credentials', 'code' => REST_Controller::HTTP_UNAUTHORIZED]); 
            }
            
            $type_id = (!isset($input['karigar_type_id']) || (isset($input['karigar_type_id'])  && empty($input['karigar_type_id']))) ? 0 : $user[0]['karigar_type_id'];

            if($type_id != $user[0]['karigar_type_id']) return $this->response(['message' => 'Your Karigar Type is different.', 'code' => REST_Controller::HTTP_UNAUTHORIZED]); 

            if(empty($input['karigar_type_id']) && $user[0]['karigar_type_id']==1){
                return $this->response(['message' => '1. Your Karigar Type is different.', 'code' => REST_Controller::HTTP_UNAUTHORIZED]); 
            } 

            $data = $this->model->get_session_by_user($user[0]['karigar_id']); 
            if(!empty($data)){
                foreach ($data as $key => $value) { 
                    $this->db_operations->delete_record('karigar_session_master', ['ksm_id' => $value['ksm_id']]);
                }
            } 
            if(isset($this->post_data['token']) && !empty($this->post_data['token']))
                $this->store_token($user[0]['user_id'], $this->post_data['token']);

            $token = $this->validation->generateToken([
                'user_id'           => $user[0]['karigar_id'],
                'financial_year'    => $this->get_financial_year(),
                'created_at'        => strtotime('now')
            ]);
            return $this->response(['status' => TRUE, 'data' => ['token' => $token], 'message' => "Login successful."]);
        } 
        public function refresh_token(){  
            $this->allow_method(['GET']);
            $result = $this->validation->jwt_token();
            if(!isset($result['data']) || (isset($result['data']) && empty($result['data']))) return $this->response($result);
            $token = $this->validation->generateToken([
                'user_id'           => $result['data']['user_id'],
                'financial_year'    => $this->get_financial_year(),
                'created_at'        => strtotime('now')
            ]);
            return $this->response(['status' => TRUE, 'data' => ['token' => $token], 'message' => "Token refreshed."]);
        }

        protected function store_token($user_id, $token){
            $prev_data = $this->db_operations->get_record('user_token_trans', ['utt_user_id' => $user_id, 'utt_token' => $token]);
            $trans_data= [];
            $trans_data['utt_updated_at']   = date("Y-m-d H:i:s");
            $trans_data['utt_status']       = true;
            if(empty($prev_data)){
                $trans_data['utt_user_id']  = $user_id;
                $trans_data['utt_token']        = $token;
                $trans_data['utt_created_at']   = date("Y-m-d H:i:s");
                $id = $this->db_operations->data_insert('user_token_trans', $trans_data);
                if($id < 1) return $this->response(['message' => 'Notification token not added.', 'code' => HTTP_INTERNAL_SERVER_ERROR]);
            }else{
                if($this->db_operations->data_update('user_token_trans', $trans_data, 'utt_id', $prev_data[0]['utt_id']) < 1){
                    return $this->response(['message' => 'Notification token not updated.', 'code' => HTTP_INTERNAL_SERVER_ERROR]);
                } 
            }

            $prev_data = $this->db_operations->get_record('user_token_trans', ['utt_user_id != ' => $user_id, 'utt_token' => $token]);
            if(!empty($prev_data)){
                foreach ($prev_data as $key => $value) {
                    $temp_data                  = [];
                    $temp_data['utt_status']    = false;
                    $temp_data['utt_updated_at']= date("Y-m-d H:i:s");

                    if($this->db_operations->data_update('user_token_trans', $temp_data, 'utt_id', $value['utt_id']) < 1){
                        return $this->response(['message' => 'Notification status not inactive.', 'code' => HTTP_INTERNAL_SERVER_ERROR]);
                    }
                }
            }

            return ['status' => TRUE];
        }

        protected function get_financial_year(){
            $start_date = date('Y-04-01');
            $today_date = date('Y-m-d'); 
            
            if(strtotime($today_date) >= strtotime($start_date)) return date('Y').'-'.(date('Y')+1);
            return (date('Y')-1).'-'.date('Y');
        }
        
    }
?>