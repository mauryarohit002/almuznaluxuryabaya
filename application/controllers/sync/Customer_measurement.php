<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Sync_Controller.php';
class customer_measurement extends my_sync_controller{
	public function __construct(){
		parent::__construct('Tbl_M_TraderMaster_Mesurament', 'CustomerMessmentID');
	}
    public function sub_func($value){
		// echo "<pre>";print_r($value); exit;
        $exists = $this->db_operations->get_record('customer_measurement_trans', ['cmt_id' => $value['CustomerMessmentID']]);
        $data 	= [];
        $data['cmt_measurement_id'] = trim($value['MesarmentUnitID']);
        $data['cmt_customer_id']    = trim($value['TraderMaster_ID']);
        $data['cmt_apparel_id']     = trim($value['MesarmentTypeID']);
        $data['cmt_value1']         = trim($value['Measurements']);
        $data['cmt_value2']         = trim($value['Measurements2']);
        $data['cmt_remark']         = trim($value['Remark']);
        $data['cmt_is_exist']       = trim($value['ISExist']);
        $data['cmt_bill_no']        = trim($value['BillNo']);
        $data['cmt_bill_date']      = trim($value['BillDate']);
        $data['cmt_delete_status']  = $value['IsDeleted'] == '' ? 1 : 0;
        if(empty($exists)){
            $data['cmt_id'] 	    = $value['CustomerMessmentID'];
            $data['cmt_created_by'] = $_SESSION['user_id'];
            $data['cmt_updated_by'] = $_SESSION['user_id'];
            $data['cmt_created_at'] = date('Y-m-d H:i:s');
            $data['cmt_updated_at'] = date('Y-m-d H:i:s');
            if($this->db_operations->data_insert('customer_measurement_trans', $data) < 1){
                $this->add_fail = $this->add_fail + 1;
			}else{
				$this->add = $this->add + 1;
			}
        }else{
            $data['cmt_updated_by']= $_SESSION['user_id'];
            $data['cmt_updated_at']= date('Y-m-d H:i:s');
            if($this->db_operations->data_update('customer_measurement_trans', $data, 'cmt_id', $exists[0]['cmt_id']) < 1){
                $this->edit_fail = $this->edit_fail + 1;
			}else{
				$this->edit = $this->edit + 1;
			}
        }
	}
}
?>
