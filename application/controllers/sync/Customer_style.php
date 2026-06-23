<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Sync_Controller.php';
class customer_style extends my_sync_controller{
	public function __construct(){
		parent::__construct('Tbl_T_Sale_Style', 'SaleStyleID');
	}
    public function sub_func($value){
		// echo "<pre>";print_r($value); exit;
        $exists = $this->db_operations->get_record('customer_style_trans', ['cst_id' => $value['SaleStyleID']]);
        $data 	= [];
        $sales  = $this->get_sales_data($value['SaleBillID']);
        $data['cst_style_id']       = trim($value['StyleMasterID']);
        $data['cst_customer_id']    = trim($value['TraderMaster_ID']);
        $data['cst_apparel_id']     = trim($value['MesarmentTypeID']);
        $data['cst_bill_no']        = $sales['no'];
        $data['cst_bill_date']      = $sales['date'];
        $data['cst_value']          = 1;
        $data['cst_delete_status']  = $value['IsDeleted'] == '' ? 1 : 0;
        if(empty($exists)){
            $data['cst_id'] 	    = $value['SaleStyleID'];
            $data['cst_created_by'] = $_SESSION['user_id'];
            $data['cst_updated_by'] = $_SESSION['user_id'];
            $data['cst_created_at'] = date('Y-m-d H:i:s');
            $data['cst_updated_at'] = date('Y-m-d H:i:s');
            if($this->db_operations->data_insert('customer_style_trans', $data) < 1){
                $this->add_fail = $this->add_fail + 1;
			}else{
				$this->add = $this->add + 1;
			}
        }else{
            $data['cst_updated_by']= $_SESSION['user_id'];
            $data['cst_updated_at']= date('Y-m-d H:i:s');
            if($this->db_operations->data_update('customer_style_trans', $data, 'cst_id', $exists[0]['cst_id']) < 1){
                $this->edit_fail = $this->edit_fail + 1;
			}else{
				$this->edit = $this->edit + 1;
			}
        }
	}

    public function get_sales_data($id) {
        $query = "SELECT BillNo as no, BillDate as date FROM Tbl_T_Sale WHERE SaleBillID = $id";
        $data= $this->db2->query($query)->result_array();
        return empty($data) ? ['no' => '', 'date' => ''] : ['no' => $data[0]['no'], 'date' => $data[0]['date']];
    }
}
?>
