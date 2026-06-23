<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Controller.php';
class user_rights extends my_controller{
    protected $menu;
    protected $sub_menu;
	public function __construct(){
        $this->menu     = 'master'; 
        $this->sub_menu = 'user_rights'; 
        parent::__construct($this->menu, $this->sub_menu); 
    }
    public function index(){	
		$result = isLoggedIn();
		// echo "<pre>"; print_r($result);exit;
		if(!$result['session'] || !$result['status'] || !$result['active']){
			redirect('login/logout?msg='.$result['msg']);
			return;
		}
		$result = isMenuAssigned($this->menu, $this->sub_menu);
        if(!$result['session'] || !$result['status'] || !$result['active']){
			$this->load->view('errors/unauthorized'); return;
		}
        $record = $this->model->get_menu();
		// echo "<pre>"; print_r($record); exit;
		$this->load->view('pages/'.$this->menu.'/'.$this->sub_menu.'/_form', $record);
	}
    public function get_assign_rights(){
		$post_data = $this->input->post();
        if(!isset($post_data['mt_id'])) return ['msg' => '1. Id not define.'];
		if(empty($post_data['mt_id'])) return ['msg' => '2. Id not define.'];
		
		$mt_id = $post_data['mt_id'];

		$data['assign_data'] = $this->model->get_assign_rights($mt_id);
		$data['action_data'] = get_action_data($this->menu, $this->sub_menu);
		return ['status' => TRUE, 'data' => $data, 'msg' => 'Assigned user fetched successfully.'];
	}
	
    // menu_action_user_trans
		public function add_action_user(){
			$post_data  = $this->input->post();
			$_id        = $post_data['_id'];
			$mat_id     = $post_data['mat_id'];
			foreach ($post_data['mat_id'] as $key => $mat_id) {
				$prev_data = $this->db_operations->get_record('menu_action_user_trans', ['maut_user_id' => $_id, 'maut_mat_id' => $mat_id]);
				if(empty($prev_data)){
					if($this->db_operations->data_insert('menu_action_user_trans', ['maut_user_id' => $_id, 'maut_mat_id' => $mat_id]) < 1){
						return ['msg' => '1. Assigned user not added.'];
					}
				}
			}
			
			$action_data = $this->db_operations->get_record('menu_action_trans', ['mat_id' => $mat_id]);
			if(empty($action_data)) return ['msg' => '1. Action data not found.'];
			$data['assign_data'] = $this->model->get_assign_rights($action_data[0]['mat_mt_id']);
			$data['action_data'] = get_action_data($this->menu, $this->sub_menu);
			return ['status' => TRUE, 'data' => $data, 'msg' => 'Assigned user added successfully.'];
		}
		public function remove_action_user(){
			$post_data  = $this->input->post();
			$maut_id    = $post_data['maut_id'];
			$mat_id     = $post_data['mat_id'];
			
			if($this->db_operations->delete_record('menu_action_user_trans', ['maut_id' => $maut_id]) < 1) return ['msg' => '1. Assigned user not delete.'];
			$data['assign_data'] = $this->model->get_menu_action_user($mat_id);
			$data['action_data'] = get_action_data($this->menu, $this->sub_menu);
			return ['status' => TRUE, 'data' => $data, 'msg' => 'Assigned user deleted successfully.'];
		}
	// menu_action_user_trans

	// menu_action_role_trans
		public function add_action_role(){
			$post_data = $this->input->post();
			$_id       = $post_data['_id'];
			$mat_id    = $post_data['mat_id'];
			foreach ($post_data['mat_id'] as $key => $mat_id) {
				$prev_data = $this->db_operations->get_record('menu_action_role_trans', ['mart_role_id' => $_id, 'mart_mat_id' => $mat_id]);
				if(empty($prev_data)){
					if($this->db_operations->data_insert('menu_action_role_trans', ['mart_role_id' => $_id, 'mart_mat_id' => $mat_id]) < 1){
						return ['msg' => '1. Assigned role not added.'];
					}
				}       
			}
			
			$action_data = $this->db_operations->get_record('menu_action_trans', ['mat_id' => $mat_id]);
			if(empty($action_data)) return ['msg' => '2. Action data not found.'];
			$data['assign_data'] = $this->model->get_assign_rights($action_data[0]['mat_mt_id']);
			$data['action_data'] = get_action_data($this->menu, $this->sub_menu);
			return ['status' => TRUE, 'data' => $data, 'msg' => 'Assigned role added successfully.'];
		}
		public function remove_action_role(){
			$post_data  = $this->input->post();
			$mart_id    = $post_data['mart_id'];
			$mat_id     = $post_data['mat_id'];
			
			if($this->db_operations->delete_record('menu_action_role_trans', ['mart_id' => $mart_id]) < 1) return ['msg' => '1. Assigned role not delete.'];
			$data['assign_data'] = $this->model->get_menu_action_role($mat_id);
			$data['action_data'] = get_action_data($this->menu, $this->sub_menu);
			return ['session' => TRUE, 'status' => TRUE, 'data' => $data, 'msg' => 'Assigned role deleted successfully.'];
		}
	// menu_action_role_trans
}
?>