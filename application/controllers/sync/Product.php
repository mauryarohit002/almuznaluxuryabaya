<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Sync_Controller.php';
class product extends my_sync_controller{
	public function __construct(){
		parent::__construct('Tbl_M_ProductMaster', 'Product_ID');
	}
	public function sub_func($value){
		$exists = $this->db_operations->get_record('product_master', ['product_id' => $value['Product_ID']]);
		$data 	= [];
		$data['product_name']               = trim($value['Product_Name']);
		$data['product_specification']      = trim($value['ItemSpecification']);
		$data['product_category_id']        = trim($value['CategoryID']);
		$data['product_type_id']            = trim($value['ItemPackingTypeID']);
		$data['product_unit']               = trim($value['InKg']);
		$data['product_pan_no']             = trim($value['Pano']);
		$data['product_hsn_code']           = trim($value['HSNCode']);
		$data['product_image']              = trim($value['ProductImage']);
		$data['product_input_tax']          = trim($value['InputTaxID']);
		$data['product_output_tax']         = trim($value['OutPutTaxID']);
		$data['product_group_id']           = trim($value['DeleveryGroupID']);
		$data['product_max_sale_allowed']   = trim($value['MaxSaleAllowed']);
		$data['product_status']             = $value['IsDeleted'] == '' ? 1 : 0;
		if(empty($exists)){
			$data['product_id'] 		= $value['Product_ID'];
			$data['product_created_by']= $_SESSION['user_id'];
			$data['product_updated_by']= $_SESSION['user_id'];
			$data['product_created_at']= date('Y-m-d H:i:s');
			$data['product_updated_at']= date('Y-m-d H:i:s');
			if($this->db_operations->data_insert('product_master', $data) < 1){
				$this->add_fail = $this->add_fail + 1;
			}else{
				$this->add = $this->add + 1;
			}
		}else{
			$data['product_updated_by']= $_SESSION['user_id'];
            $data['product_updated_at']= date('Y-m-d H:i:s');
			if($this->db_operations->data_update('product_master', $data, 'product_id', $exists[0]['product_id']) < 1){
				$this->edit_fail = $this->edit_fail + 1;
			}else{
				$this->edit = $this->edit + 1;
			}
		}
	}
}
?>
