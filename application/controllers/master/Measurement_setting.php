<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Controller.php';
class measurement_setting extends my_controller{
	public function __construct(){ parent::__construct('master', 'measurement_setting'); }
	public function add_update(){
		$post_data  = $this->input->post();
		$id         = $post_data['id'];
		$result     = isMenuAssigned($this->menu, $this->sub_menu, ($id == 0 ? 'add' : 'edit'));
		if(!$result['session'] || !$result['status'] || !$result['active']) return $result;
		// echo "<pre>"; print_r($post_data); exit;
		unset($post_data['func']);
		unset($post_data['id']);

		$post_data[$this->sub_menu.'_apparel_id'] 		= isset($post_data[$this->sub_menu.'_apparel_id']) ? $post_data[$this->sub_menu.'_apparel_id'] : 0;
		$post_data[$this->sub_menu.'_measurement_id'] 	= isset($post_data[$this->sub_menu.'_measurement_id']) ? $post_data[$this->sub_menu.'_measurement_id'] : 0;
		$post_data[$this->sub_menu.'_status'] 			= isset($post_data[$this->sub_menu.'_status']);
		$post_data[$this->sub_menu.'_updated_by'] 		= $_SESSION['user_id'];

		$temp = $this->db_operations->get_record($this->sub_menu.'_master', [$this->sub_menu.'_id !=' => $id, $this->sub_menu.'_apparel_id' => $post_data[$this->sub_menu.'_apparel_id'], $this->sub_menu.'_measurement_id' => $post_data[$this->sub_menu.'_measurement_id']]);
		if(!empty($temp)) return ['msg' => str_replace('_', ' ', ucfirst($this->sub_menu)).' already exist.'];	

		$temp = $this->db_operations->get_record($this->sub_menu.'_master', [$this->sub_menu.'_id !=' => $id, $this->sub_menu.'_apparel_id' => $post_data[$this->sub_menu.'_apparel_id'], $this->sub_menu.'_priority' => $post_data[$this->sub_menu.'_priority']]);
		if(!empty($temp)) return ['msg' => 'Priority already Exist For Same Apparel.'];	

		$this->db->trans_begin();
		if($id == 0){
			$post_data[$this->sub_menu.'_created_by'] 	= $_SESSION['user_id'];
			$post_data[$this->sub_menu.'_created_at'] 	= date('Y-m-d H:i:s');

			$id 	= $this->db_operations->data_insert($this->sub_menu.'_master', $post_data);
			$msg 	= str_replace('_', ' ', ucfirst($this->sub_menu)).' added successfully.';
			if($id < 1){
				$this->db->trans_rollback();
				return ['msg' => str_replace('_', ' ', ucfirst($this->sub_menu)).' not added.'];
			}
		}else{
			$prev_data = $this->db_operations->get_record($this->sub_menu.'_master', [$this->sub_menu.'_id' => $id]);
			if(empty($prev_data)){
				$this->db->trans_rollback();
				return['status' => REFRESH, 'msg' => str_replace('_', ' ', ucfirst($this->sub_menu)).' not found.'];
			}
			$msg = str_replace('_', ' ', ucfirst($this->sub_menu)).' updated successfully.';
			if($this->db_operations->data_update($this->sub_menu.'_master', $post_data, $this->sub_menu.'_id', $id) < 1){
				$this->db->trans_rollback();
				return ['msg' => str_replace('_', ' ', ucfirst($this->sub_menu)).' not updated.'];
			}
		}

		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return ['msg' => '1. Transaction Rollback.'];
		}
		$this->db->trans_commit();

		$data['id'] 	= $id;
		return['session' => TRUE, 'status' => TRUE, 'data' => $data,  'msg' => $msg];
	}
}
?>
