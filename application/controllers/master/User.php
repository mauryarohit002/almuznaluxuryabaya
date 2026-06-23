<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Controller.php';
class user extends my_controller{
	protected $menu;
	protected $sub_menu;
	public function __construct(){
		$this->menu     = 'master'; 
        $this->sub_menu = 'user'; 
		parent::__construct($this->menu, $this->sub_menu);

		$this->load->library('pagination');
		$this->config->load('extra');
	}
	public function add_update(){
		$post_data  = $this->input->post();
		$id         = $post_data['id'];
		$result     = isMenuAssigned($this->menu, $this->sub_menu, ($id == 0 ? 'add' : 'edit'));
		if(!$result['session'] || !$result['status'] || !$result['active']) return $result;
		
		unset($post_data['func']);
		unset($post_data['id']);

		// master_data
			$post_data[$this->sub_menu.'_status'] 		= isset($post_data[$this->sub_menu.'_status']);
			$post_data[$this->sub_menu.'_updated_by'] 	= $_SESSION['user_id'];
		// master_data
		
		$temp = $this->db_operations->get_record($this->sub_menu.'_master', [$this->sub_menu.'_id !=' => $id, $this->sub_menu.'_name' => $post_data[$this->sub_menu.'_name'], $this->sub_menu.'_branch_id' => $post_data[$this->sub_menu.'_branch_id']]);
		if(!empty($temp)) return ['msg' => '1. Username already exist.'];	
		if($id == 0){
			$post_data[$this->sub_menu.'_password'] 	= md5(trim($post_data[$this->sub_menu.'_password']));
			$post_data[$this->sub_menu.'_created_by'] 	= $_SESSION['user_id'];
			$post_data[$this->sub_menu.'_created_at'] 	= date('Y-m-d H:i:s');

			$id 	= $this->db_operations->data_insert($this->sub_menu.'_master', $post_data);
			$msg 	= ucfirst($this->sub_menu).' added successfully.';
			if($id < 1) return ['msg' => ucfirst($this->sub_menu).' not added.'];
		}else{
			$prev_data = $this->db_operations->get_record($this->sub_menu.'_master', [$this->sub_menu.'_id' => $id]);
			if(empty($prev_data)) return ['status' => REFRESH, 'msg' => ucfirst($this->sub_menu).' not found.'];

			if($this->model->isExist($id)){
				if($prev_data[0][$this->sub_menu.'_role_id'] != $post_data[$this->sub_menu.'_role_id']){
					$this->db->trans_rollback();
					return['msg' => '1. Not allowed to change role.'];
				}
				if($prev_data[0][$this->sub_menu.'_branch_id'] != $post_data[$this->sub_menu.'_branch_id']){
					$this->db->trans_rollback();
					return['msg' => '1. Not allowed to change branch.'];
				}
			}

			if(!empty($post_data[$this->sub_menu.'_password'])){
				$post_data[$this->sub_menu.'_password'] 	=  md5(trim($post_data[$this->sub_menu.'_password']));
			}else{
				unset($post_data[$this->sub_menu.'_password']);
			}
			$msg = 'Updated successfully';
			if($this->db_operations->data_update($this->sub_menu.'_master', $post_data, $this->sub_menu.'_id', $id) < 1) return ['msg' => ucfirst($this->sub_menu).' not updated.'];
		}
		$data['id'] 	= $id;
		$data['name'] 	= strtoupper($post_data[$this->sub_menu.'_fullname']);
		if($_SESSION['user_id'] == $id){
			$this->session->sess_destroy();
			return ['session' => FALSE, 'msg' => 'Session expired.'];
		}
		return ['status' => TRUE, 'data' => $data,  'msg' => $msg];
	}
	public function set_user_bg_color(){
		$post_data 	= $this->input->post();
		$user_id 	= isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
		if(empty($user_id)) return ['msg' => ucfirst($this->sub_menu).' not found.'];
		$this->db_operations->data_update('user_master', ['user_bgcolor' => $post_data['color']], 'user_id', $user_id);
		return ['status' => TRUE, 'msg' => 'Background color updated successfully.'];
	}
}
?>
