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

	// public function temp_funtion(){
	// 	$data = $this->db_operations->get_recordlist('barcode_master');
	// 	foreach ($data as $key => $value) {
	// 	$cost_char='';	
	// 		$str = $value['bm_pt_rate'];
	// 		$length = strlen($str);
			
	// 		// echo "<pre>";print_r(str_split($str)[0]);die;
	// 		for ($i = 0; $i < $length; $i++) {
	// 			if(str_split($str)[$i]=='0'){
	// 				$char='D';
	// 			}else if(str_split($str)[$i]==1){
	// 				$char='P';
	// 			}else if(str_split($str)[$i]==2){
	// 				$char='R';
	// 			}else if(str_split($str)[$i]==3){
	// 				$char='A';
	// 			}else if(str_split($str)[$i]==4){
	// 				$char='N';
	// 			}else if(str_split($str)[$i]==5){
	// 				$char='S';
	// 			}else if(str_split($str)[$i]==6){
	// 				$char='H';
	// 			}else if(str_split($str)[$i]==7){
	// 				$char='V';
	// 			}else if(str_split($str)[$i]==8){
	// 				$char='I';
	// 			}else if(str_split($str)[$i]==9){
	// 				$char='G';
	// 			}else if(str_split($str)[$i]=="."){
	// 				$char=".";
	// 			}
	// 		$cost_char.=$char;	
	// 		}
	// 	$this->db_operations->data_update('barcode_master',['bm_cost_char'=>$cost_char],'bm_id',$value['bm_id']);	
			
	// 	}	
	// }
	
}
?>
