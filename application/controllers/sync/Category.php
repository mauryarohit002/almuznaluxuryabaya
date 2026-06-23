<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Sync_Controller.php';
class category extends my_sync_controller{
	public function __construct(){
		parent::__construct('Tbl_M_ItemCategory', 'CategoryID');
	}
	public function sub_func($value){
		$exists = $this->db_operations->get_record('category_master', ['category_id' => $value['CategoryID']]);
		$data 	= [];
		$data['category_name']          = trim($value['CategoryName']);
		$data['category_code']          = trim($value['CategoryCode']);
		$data['category_saleable']      = trim($value['IsSaleable']);
		$data['category_status']        = $value['IsDeleted'] == '' ? 1 : 0;
		if(empty($exists)){
			$data['category_id'] 		= $value['CategoryID'];
			$data['category_created_by']= $_SESSION['user_id'];
			$data['category_updated_by']= $_SESSION['user_id'];
			$data['category_created_at']= date('Y-m-d H:i:s');
			$data['category_updated_at']= date('Y-m-d H:i:s');
			if($this->db_operations->data_insert('category_master', $data) < 1){
				$this->add_fail = $this->add_fail + 1;
			}else{
				$this->add = $this->add + 1;
			}
		}else{
			$data['category_updated_by']= $_SESSION['user_id'];
			$data['category_updated_at']= date('Y-m-d H:i:s');
			if($this->db_operations->data_update('category_master', $data, 'category_id', $exists[0]['category_id']) < 1){
				$this->edit_fail = $this->edit_fail + 1;
			}else{
				$this->edit = $this->edit + 1;
			}
		}
	}
}
?>
