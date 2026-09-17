<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Controller.php';
class apparel extends my_controller{
	public function __construct(){ parent::__construct('master', 'apparel'); }
	public function add_update(){
		$post_data  = $this->input->post();
		$id         = $post_data['id'];
		$result     = isMenuAssigned($this->menu, $this->sub_menu, ($id == 0 ? 'add' : 'edit'));
		if(!$result['session'] || !$result['status'] || !$result['active']) return $result;
		// echo "<pre>"; print_r($post_data); exit;
		 $form_data = $post_data;
		unset($post_data['func']);
		unset($post_data['id']);

		$post_data[$this->sub_menu.'_category_id'] 	= isset($post_data[$this->sub_menu.'_category_id']) ? $post_data[$this->sub_menu.'_category_id'] : 0;
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

			// $measurement =$this->db_operations->get_record('measurement_master',['measurement_status'=>1]);
			// if (!empty($measurement))
			// {
			// 	$m_setting=[];
			// 	foreach ($measurement as $key => $value) 
			// 	{
			// 		$m_setting['measurement_setting_apparel_id']=$id;
			// 		$m_setting['measurement_setting_measurement_id']=$value['measurement_id'];
			// 		$m_setting['measurement_setting_status']=1;
			// 		$this->db_operations->data_insert('measurement_setting_master',$m_setting);
			// 	}
			// }

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

    public function temp_funtion(){
		$data = $this->db_operations->get_record('barcode_readymade_master',['brmm_delete_status'=>0]);
		$resp=[];
		foreach ($data as $key => $value) {
			$cnt = $this->db_operations->get_cnt('purchase_readymade_trans',['prmt_id'=>$value['brmm_prmt_id'],'prmt_delete_status'=>0]);
			if($cnt==0){
				array_push($resp, $value['brmm_id']);
			}	
		}
		echo "<pre>"; print_r($resp);die;	
	}
	
}
?>
