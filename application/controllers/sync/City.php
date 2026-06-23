<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Sync_Controller.php';
class city extends my_sync_controller{
	public function __construct(){
		parent::__construct('Tbl_M_City', 'City_ID');
	}
	public function sub_func($value){
		$exists = $this->db_operations->get_record('city_master', ['city_id' => $value['City_ID']]);
		$data 	= [];
		$data['city_state_id']      = $value['State_ID'];
		$data['city_name']          = trim($value['Cityname']);
		$data['city_code']          = trim($value['STDCode']);
		$data['city_status']        = $value['IsDeleted'] == '' ? 1 : 0;
		if(empty($exists)){
			$data['city_id'] 		= $value['City_ID'];
			$data['city_created_by']= $_SESSION['user_id'];
			$data['city_updated_by']= $_SESSION['user_id'];
			$data['city_created_at']= date('Y-m-d H:i:s');
			$data['city_updated_at']= date('Y-m-d H:i:s');
			if($this->db_operations->data_insert('city_master', $data) < 1){
				$this->add_fail = $this->add_fail + 1;
			}else{
				$this->add = $this->add + 1;
			}
		}else{
			$data['city_updated_by']= $_SESSION['user_id'];
			$data['city_updated_at']= date('Y-m-d H:i:s');
			if($this->db_operations->data_update('city_master', $data, 'city_id', $exists[0]['city_id']) < 1){
				$this->edit_fail = $this->edit_fail + 1;
			}else{
				$this->edit = $this->edit + 1;
			}
		}
	}
}
?>
