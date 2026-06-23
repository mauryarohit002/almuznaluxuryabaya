<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Controller.php';
class general extends my_controller{
	public function __construct(){ parent::__construct('master', 'general'); }
	public function add_update(){
		$post_data  = $this->input->post();
		$id         = $post_data['id'];
		$result     = isMenuAssigned($this->menu, $this->sub_menu, ($id == 0 ? 'add' : 'edit'));
		if(!$result['session'] || !$result['status'] || !$result['active']) return $result;
		// echo "<pre>"; print_r($post_data); exit;
		unset($post_data['func']);
		unset($post_data['id']);

		$post_data[$this->sub_menu.'_grp_id'] 		= isset($post_data[$this->sub_menu.'_grp_id']) ? $post_data[$this->sub_menu.'_grp_id'] : 0;
		$post_data[$this->sub_menu.'_status'] 		= isset($post_data[$this->sub_menu.'_status']);
		$post_data[$this->sub_menu.'_updated_by'] 	= $_SESSION['user_id'];

		$temp = $this->db_operations->get_record($this->sub_menu.'_master', [$this->sub_menu.'_id !=' => $id, $this->sub_menu.'_name' => $post_data[$this->sub_menu.'_name']]);
		if(!empty($temp)) return ['msg' => ucfirst($this->sub_menu).' already exist.'];	

		$this->db->trans_begin();
		if($id == 0){
			$post_data[$this->sub_menu.'_created_by'] 	= $_SESSION['user_id'];
			// $post_data[$this->sub_menu.'_branch_id'] 	= $_SESSION['user_branch_id'];
			$post_data[$this->sub_menu.'_created_at'] 	= date('Y-m-d H:i:s');

			$id 	= $this->db_operations->data_insert($this->sub_menu.'_master', $post_data);
			$msg 	= ucfirst($this->sub_menu).' added successfully.';
			if($id < 1){
				$this->db->trans_rollback();
				return ['msg' => ucfirst($this->sub_menu).' not added. '.$id];
			}
		}else{
			$prev_data = $this->db_operations->get_record($this->sub_menu.'_master', [$this->sub_menu.'_id' => $id]);
			if(empty($prev_data)){
				$this->db->trans_rollback();
				return['status' => REFRESH, 'msg' => ucfirst($this->sub_menu).' not found.'];
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
	public function get_process_data(){
		$post_data  = $this->input->post();
		$id         = $post_data['id'];
		
		$data = $this->model->get_process_data($id);
		return['status' => TRUE, 'data' => $data, 'msg' => 'Record fetched successfully.'];
	}
}
?>
