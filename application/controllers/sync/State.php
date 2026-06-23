<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Sync_Controller.php';
class state extends my_sync_controller{
    public function __construct(){
		parent::__construct('Tbl_M_State', 'State_ID');
	}
	public function sub_func($value){
		$exists = $this->db_operations->get_record('state_master', ['state_id' => $value['State_ID']]);
		$data 	= [];
		$data['state_country_id']   = $value['CountryID'];
		$data['state_name']         = trim($value['StateName']);
		$data['state_code']         = trim($value['StateCode']);
		$data['state_status']       = $value['IsDeleted'] == '' ? 1 : 0;
		if(empty($exists)){
			$data['state_id'] 		 = $value['State_ID'];
			$data['state_created_by']= $_SESSION['user_id'];
			$data['state_updated_by']= $_SESSION['user_id'];
			$data['state_created_at']= date('Y-m-d H:i:s');
			$data['state_updated_at']= date('Y-m-d H:i:s');
			if($this->db_operations->data_insert('state_master', $data) < 1){
				$this->add_fail = $this->add_fail + 1;
			}else{
				$this->add = $this->add + 1;
			}
		}else{
			$data['state_updated_by']= $_SESSION['user_id'];
			$data['state_updated_at']= date('Y-m-d H:i:s');
			if($this->db_operations->data_update('state_master', $data, 'state_id', $exists[0]['state_id']) < 1){
				$this->edit_fail = $this->edit_fail + 1;
			}else{
				$this->edit = $this->edit + 1;
			}
		}
	}
}
?>
