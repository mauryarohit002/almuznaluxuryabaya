<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Sync_Controller.php';
class supplier extends my_sync_controller{
	public function __construct(){
		parent::__construct('Tbl_M_Supplier', 'Supplier_ID');
	}
	public function sub_func($value){
		$exists = $this->db_operations->get_record('supplier_master', ['supplier_id' => $value['Supplier_ID']]);
		$data 	= [];
		$data['supplier_name']          = trim($value['Supplier_Name']);
		$data['supplier_person']        = trim($value['ContactPerson']);
		$data['supplier_mobile']        = empty($value['ContactNo']) ? '' : substr(trim($value['ContactNo']), 0, 10);
		$data['supplier_phone1']        = trim($value['ContactNo']);
		$data['supplier_email']         = trim($value['Email']);
		$data['supplier_address']       = trim($value['FullAddress']);
		$data['supplier_state_id']      = trim($value['State_ID']);
		$data['supplier_pincode']       = trim($value['ZipNo']);
		$data['supplier_pan_no']        = trim($value['PANNO']);
		$data['supplier_gst_no']        = trim($value['TINNO']);
		$data['supplier_city_id']       = $this->get_city_id($value['CityName']);
		$data['supplier_status']        = $value['IsDeleted'] == '' ? 1 : 0;
		if(empty($exists)){
			$data['supplier_id'] 		= $value['Supplier_ID'];
			$data['supplier_created_by']= $_SESSION['user_id'];
			$data['supplier_updated_by']= $_SESSION['user_id'];
			$data['supplier_created_at']= date('Y-m-d H:i:s');
			$data['supplier_updated_at']= date('Y-m-d H:i:s');
			if($this->db_operations->data_insert('supplier_master', $data) < 1){
				$this->add_fail = $this->add_fail + 1;
			}else{
				$this->add = $this->add + 1;
			}
		}else{
			$data['supplier_updated_by']= $_SESSION['user_id'];
			$data['supplier_updated_at']= date('Y-m-d H:i:s');
			if($this->db_operations->data_update('supplier_master', $data, 'supplier_id', $exists[0]['supplier_id']) < 1){
				$this->edit_fail = $this->edit_fail + 1;
			}else{
				$this->edit = $this->edit + 1;
			}
		}
	}
}
?>
