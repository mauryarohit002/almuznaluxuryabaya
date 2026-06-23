<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Controller.php';
class branch extends my_controller{
	public function __construct(){ 
        $this->menu     = 'master';
        $this->sub_menu = 'branch';
        parent::__construct($this->menu, $this->sub_menu); 
    }
    public function add_update(){
        $post_data  = $this->input->post();
        $id         = $post_data['id'];
        $result     = isMenuAssigned($this->menu, $this->sub_menu, ($id == 0 ? 'add' : 'edit'));
        if(!$result['session'] || !$result['status'] || !$result['active']) return $result;

        unset($post_data['func']);
        unset($post_data['id']);

        $post_data[$this->sub_menu.'_status'] 		= isset($post_data[$this->sub_menu.'_status']);
        $post_data[$this->sub_menu.'_updated_by'] 	= $_SESSION['user_id'];

        $temp = $this->db_operations->get_record($this->sub_menu.'_master', [$this->sub_menu.'_id !=' => $id, $this->sub_menu.'_name' => $post_data[$this->sub_menu.'_name']]);
        if(!empty($temp)) return ['msg' => ucfirst($this->sub_menu).' already exist.'];	

        $this->db->trans_begin();
        if($id == 0){
            $post_data[$this->sub_menu.'_created_by'] 	= $_SESSION['user_id'];
            $post_data[$this->sub_menu.'_created_at'] 	= date('Y-m-d H:i:s');

            $id 	= $this->db_operations->data_insert($this->sub_menu.'_master', $post_data);
            $msg 	= ucfirst($this->sub_menu).' added successfully.';
            if($id < 1){
                $this->db->trans_rollback();
                return ['msg' => ucfirst($this->sub_menu).' not added.'];
            }
            $result = $this->add_users($id);
			if(!isset($result['status'])){
				$this->db->trans_rollback();
				return $result;
			}
        }else{
            $prev_data = $this->db_operations->get_record($this->sub_menu.'_master', [$this->sub_menu.'_id' => $id]);
            if(empty($prev_data)){
                $this->db->trans_rollback();
                return['status' => REFRESH, 'msg' => ucfirst($this->sub_menu).' not found.'];
            }
            if($prev_data[0][$this->sub_menu.'_default'] == 1 &&($post_data[$this->sub_menu.'_status'] != 1)){
				$this->db->trans_rollback();
				return['msg' => 'Main branch cannot set as inactive.'];
			}
            $msg = ucfirst($this->sub_menu).' updated successfully.';
            if($this->db_operations->data_update($this->sub_menu.'_master', $post_data, $this->sub_menu.'_id', $id) < 1){
                $this->db->trans_rollback();
                return ['msg' => ucfirst($this->sub_menu).' not updated.'];
            }
        }

        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return ['msg' => '1. Transaction Rollback.'];
        }
        $this->db->trans_commit();

        $data['id'] 	= $id;
        $data['name'] 	= strtoupper($post_data[$this->sub_menu.'_name']);
        return['session' => TRUE, 'status' => TRUE, 'data' => $data,  'msg' => $msg];
    }
    public function add_users($branch_id){
		$sadmin['user_role'] 		= SUPER_ADMIN;
		$sadmin['user_role_id'] 	= 1;
		$sadmin['user_branch_id'] 	= $branch_id;
		$sadmin['user_name'] 		= 'sadmin';
		$sadmin['user_password'] 	= md5('sadmin');
		$sadmin['user_fullname'] 	= 'Super Admin';
		$sadmin['user_type'] 		= 1;
		$sadmin['user_created_by'] 	= $_SESSION['user_id'];
		$sadmin['user_updated_by'] 	= $_SESSION['user_id'];
		$sadmin['user_created_at'] 	= date('Y-m-d H:i:s');
		if($this->db_operations->data_insert('user_master', $sadmin) < 1) return ['msg' => '1. Super admin not created.'];

		$admin['user_role'] 		= ADMIN;
		$admin['user_role_id'] 		= 2;
		$admin['user_branch_id'] 	= $branch_id;
		$admin['user_name'] 		= 'admin';
		$admin['user_password'] 	= md5('admin');
		$admin['user_fullname'] 	= 'Admin';
		$admin['user_type'] 		= 2;
		$admin['user_created_by'] 	= $_SESSION['user_id'];
		$admin['user_updated_by'] 	= $_SESSION['user_id'];
		$admin['user_created_at'] 	= date('Y-m-d H:i:s');
		if($this->db_operations->data_insert('user_master', $admin) < 1) return ['msg' => '1. Admin not created.'];

		return ['status' => TRUE];
	}
}
?>
