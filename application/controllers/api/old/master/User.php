<?php defined('BASEPATH') or exit('No direct script access allowed');
    use Restserver\Libraries\REST_Controller;
    require_once APPPATH . 'core/MY_Controller.php';
    class user extends my_controller{ 
        protected $name;
        public function __construct(){
            $this->name = 'user'; 
            parent::__construct([
                'model' => 'master/user_model',
                'table' => 'user_master',
                'label' => 'user',
            ]);
        }

        public function store_master($id){ 
            $result = $this->validate_master($id);
            if(!isset($result['status']) || (isset($result['status']) && $result['status'] === FALSE)) return $result;
            // print_r($result);die;
            $form_data = $result['data'];
            if($id==0){
                $id = $this->db_operations->data_insert($this->name.'_master', $form_data);
                if($id < 1){ 
                    return ['message' => 'user not added.'];
                }
                $message = ucfirst($this->name).' added successfully';  
            }else{
                $prev_data = $this->db_operations->get_record($this->name.'_master', [$this->name.'_id' => $id]);
                if(empty($prev_data)){
                    return['status' => FALSE, 'message' => ucfirst($this->name).' not found.'];
                }
                if($this->db_operations->data_update($this->name.'_master', $form_data, $this->name.'_id', $id) < 1){
                    $this->db->trans_rollback();
                    return ['message' => ucfirst($this->name).' not updated.'];
                }
                $message = ucfirst($this->name).' updated successfully';   
            }

            $data['user_id']        = $id;
            $data['user_name']      = $this->post_data['user_name'];
            return ['status' => TRUE,'data'=>$data,'message'=>$message];
        }

        protected function validate_master($id){
            $data = [];
            if(!isset($this->post_data['user_full_name']) || (empty($this->post_data['user_full_name']))) return ['message' => 'User Full Name Required']; 

            if(!isset($this->post_data['user_name']) || (empty($this->post_data['user_name']))) return ['message' => 'user Name Required']; 


            if(!empty($this->post_data['user_mobile'])){
                if(!isset($this->post_data['user_mobile']) || (empty($this->post_data['user_mobile']))) return ['message' => 'Mobile no Required'];

                if (strlen($this->post_data['user_mobile']) < 10)  return ['message' => 'Invalid Mobile Number Required'];

                $temp = $this->db_operations->get_record($this->name.'_master', ['user_id !=' => $id, 'user_mobile' => $this->post_data['user_mobile']]);
                if(!empty($temp)) return ['message' => 'Mobile no. already exist.'];    
            }

            if($id==0){

                $data['user_create_date'] = date('Y-m-d H:i:s');
                 $data['user_update_date'] = date('Y-m-d H:i:s');
                if(!isset($this->post_data[$this->name.'_password']) || (empty($this->post_data[$this->name.'_password']))) return ['message' => 'Password Required']; 

                $data[$this->name.'_password'] = md5(trim($this->post_data[$this->name.'_password']));
                $data[$this->name.'_visible_password'] = trim($this->post_data[$this->name.'_password']);

            }else{
                if(!empty($this->post_data[$this->name.'_password'])){
                   $data[$this->name.'_password']     =  md5(trim($this->post_data[$this->name.'_password']));
                   $data[$this->name.'_visible_password'] = trim($this->post_data[$this->name.'_password']);
                }
                $data['user_update_date'] = date('Y-m-d H:i:s');
            }    
            $data['user_status'] = $this->post_data['user_status'];
            $data['user_full_name'] = $this->post_data['user_full_name'];
            $data['user_name'] = $this->post_data['user_name'];
            $data['user_mobile'] = $this->post_data['user_mobile'];
            $data['user_email'] = $this->post_data['user_email'];
            $data['user_address'] = $this->post_data['user_address'];

            $check = $this->model->check_duplicate($id,$data['user_name']);
            if(!empty($check)) return ['message' => 'user already exist.']; 
            return ['status' => TRUE, 'data' => $data];
        }

        public function remove_master($id){ 
            $prev_data = $this->db_operations->get_record($this->name.'_master', [$this->name.'_id' => $id]);
            if(empty($prev_data)){
                return['status' => FALSE, 'message' => ucfirst($this->name).' not found.'];
            }
            if($this->model->isExist($id)) return ['message' => 'Not allowed to delete.'];  
            
            if($this->db_operations->delete_record($this->name.'_master', [$this->name.'_id' => $id]) < 1)return ['msg' => ucfirst($this->name).' not deleted.'];

            return ['status' => TRUE,'data'=>[],'message'=>ucfirst($this->name).' deleted successfully'];
        }

}?>