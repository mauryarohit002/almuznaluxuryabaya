<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Sync_Controller.php';
class apparel extends my_sync_controller{
	public function __construct(){
		parent::__construct('Tbl_M_MesarmentType', 'MesarmentTypeID');
	}
	public function sub_func($value){
		$exists = $this->db_operations->get_record('apparel_master', ['apparel_id' => $value['MesarmentTypeID']]);
		$data 	= [];
		$data['apparel_name']           = trim($value['MesarmentType']);
		$data['apparel_code']           = trim($value['MesarmentTypeCode']);
		$data['apparel_group']          = $value['MesarmentGroup'] == 'P' ? 'PANT' : ($value['MesarmentGroup'] == 'S' ? 'SHIRT' : 'OTHER');
		$data['apparel_sgst_per']       = 2.5;
		$data['apparel_cgst_per']       = 2.5;
		$data['apparel_igst_per']       = 5.0;
		$data['apparel_status']         = $value['IsDeleted'] == '' ? 1 : 0;
		if(empty($exists)){
			$data['apparel_id'] 		= $value['MesarmentTypeID'];
			$data['apparel_created_by']= $_SESSION['user_id'];
			$data['apparel_updated_by']= $_SESSION['user_id'];
			$data['apparel_created_at']= date('Y-m-d H:i:s');
			$data['apparel_updated_at']= date('Y-m-d H:i:s');
			if($this->db_operations->data_insert('apparel_master', $data) < 1){
				$this->add_fail = $this->add_fail + 1;
			}else{
				$this->add = $this->add + 1;
			}
		}else{
			$data['apparel_updated_by']= $_SESSION['user_id'];
			$data['apparel_updated_at']= date('Y-m-d H:i:s');
			if($this->db_operations->data_update('apparel_master', $data, 'apparel_id', $exists[0]['apparel_id']) < 1){
				$this->edit_fail = $this->edit_fail + 1;
			}else{
				$this->edit = $this->edit + 1;
			}
		}
	}
}
?>
