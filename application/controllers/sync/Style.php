<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Sync_Controller.php';
class style extends my_sync_controller{
    public function __construct(){
		parent::__construct('Tbl_M_StyleMaster', 'StyleMasterID');
	}
	public function sub_func($value){
		$exists = $this->db_operations->get_record('style_master', ['style_id' => $value['StyleMasterID']]);
		$data 	= [];
		$data['style_name']         = trim($value['StyleName']);
		$data['style_group']        = trim($value['StyleCategory']);
		$data['style_status']       = $value['IsDeleted'] == '' ? 1 : 0;
		if(empty($exists)){
			$data['style_id'] 		 = $value['StyleMasterID'];
			$data['style_created_by']= $_SESSION['user_id'];
			$data['style_updated_by']= $_SESSION['user_id'];
			$data['style_created_at']= date('Y-m-d H:i:s');
			$data['style_updated_at']= date('Y-m-d H:i:s');
			if($this->db_operations->data_insert('style_master', $data) < 1){
				$this->add_fail = $this->add_fail + 1;
			}else{
				$this->add = $this->add + 1;
			}
		}else{
			$data['style_updated_by']= $_SESSION['user_id'];
			$data['style_updated_at']= date('Y-m-d H:i:s');
			if($this->db_operations->data_update('style_master', $data, 'style_id', $exists[0]['style_id']) < 1){
				$this->edit_fail = $this->edit_fail + 1;
			}else{
				$this->edit = $this->edit + 1;
			}
		}
	}
}
?>
