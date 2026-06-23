<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Sync_Controller.php';
class measurement extends my_sync_controller{
	public function __construct(){
		parent::__construct('Tbl_M_MesarmentUnit', 'MesarmentUnitID');
	}
	public function sub_func($value){
		$exists = $this->db_operations->get_record('measurement_master', ['measurement_id' => $value['MesarmentUnitID']]);
		$data 	= [];
		$data['measurement_name']          = trim($value['MesarmentUnit']);
		$data['measurement_group']         = trim($value['MesarmentGroup']);
		$data['measurement_status']        = $value['IsDeleted'] == '' ? 1 : 0;
		if(empty($exists)){
			$data['measurement_id'] 	   = $value['MesarmentUnitID'];
			$data['measurement_created_by']= $_SESSION['user_id'];
			$data['measurement_updated_by']= $_SESSION['user_id'];
			$data['measurement_created_at']= date('Y-m-d H:i:s');
			$data['measurement_updated_at']= date('Y-m-d H:i:s');
			if($this->db_operations->data_insert('measurement_master', $data) < 1){
				$this->add_fail = $this->add_fail + 1;
			}else{
				$this->add = $this->add + 1;
			}
		}else{
			$data['measurement_updated_by']= $_SESSION['user_id'];
			$data['measurement_updated_at']= date('Y-m-d H:i:s');
			if($this->db_operations->data_update('measurement_master', $data, 'measurement_id', $exists[0]['measurement_id']) < 1){
				$this->edit_fail = $this->edit_fail + 1;
			}else{
				$this->edit = $this->edit + 1;
			}
		}
	}
}
?>
