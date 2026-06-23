<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Sync_Controller.php';
class customer extends my_sync_controller{
	public function __construct(){
		parent::__construct('Tbl_M_TraderMaster', 'TraderMaster_ID');
	}
    public function sub_func($value){
		// echo "<pre>";print_r($value); exit;
        $exists = $this->db_operations->get_record('customer_master', ['customer_id' => $value['TraderMaster_ID']]);
        $data 	= [];
        $data['customer_no']                = trim($value['MAST_CODE']);
        $data['customer_name']              = trim($value['TraderMaster_Name']);
        $data['customer_contact_person']    = trim($value['ContactPerson']);
        $data['customer_mobile']            = empty($value['ContactNo']) ? '' : substr(trim($value['ContactNo']), 0, 10);
        $data['customer_phone1']            = trim($value['ContactNo']);
        $data['customer_email']             = trim($value['Email']);
        $data['customer_address']           = trim($value['FullAddress']);
        $data['customer_city_id']           = $this->get_city_id($value['CityName']);
        $data['customer_state_id']          = $value['State_ID'];
        $data['customer_pincode']           = trim($value['ZipNo']);
        $data['customer_status']            = $value['IsDeleted'] == '' ? 1 : 0;
        $data['customer_proof_no']          = trim($value['IDProofNo']);
        $data['customer_type_id']           = $value['CustomerTypeID'];
        $data['customer_birth_date']        = trim($value['DateOfBrith']);
        $data['customer_anniversary_date']  = trim($value['DateOfAnv']);
        $data['customer_office_phone']      = trim($value['OfficeContactNo']);
        $data['customer_office_address']    = trim($value['OfficeAddress']);
        $data['customer_spouse_name']       = trim($value['SpouseName']);
        if(empty($exists)){
            $data['customer_id'] 	    = $value['TraderMaster_ID'];
            $data['customer_uuid'] 	    = time().''.$value['TraderMaster_ID'];
            $data['customer_created_by']= $_SESSION['user_id'];
            $data['customer_updated_by']= $_SESSION['user_id'];
            $data['customer_created_at']= date('Y-m-d H:i:s');
            $data['customer_updated_at']= date('Y-m-d H:i:s');
            if($this->db_operations->data_insert('customer_master', $data) < 1){
                $this->add_fail = $this->add_fail + 1;
			}else{
				$this->add = $this->add + 1;
			}
        }else{
            $data['customer_updated_by']= $_SESSION['user_id'];
            $data['customer_updated_at']= date('Y-m-d H:i:s');
            if($this->db_operations->data_update('customer_master', $data, 'customer_id', $exists[0]['customer_id']) < 1){
                $this->edit_fail = $this->edit_fail + 1;
			}else{
				$this->edit = $this->edit + 1;
			}
        }
	}
}
?>
