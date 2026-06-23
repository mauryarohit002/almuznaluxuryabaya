<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Sync_Controller.php';
class measurement_setting extends my_sync_controller{
	public function __construct(){
		parent::__construct('Tbl_M_ProductAttributes', 'ProductAttributeID');
	}
	public function sub_func($value){
        if($value['MesarmentUnitID'] > 0){
            $exists = $this->db_operations->get_record('measurement_setting_master', ['measurement_setting_id' => $value['ProductAttributeID']]);
            $data 	= [];
            $data['measurement_setting_apparel_id']     = trim($value['MesarmentTypeID']);
            $data['measurement_setting_measurement_id'] = trim($value['MesarmentUnitID']);
            $data['measurement_setting_status']         = $value['IsDeleted'] == '' ? 1 : 0;
            if(empty($exists)){
                $data['measurement_setting_id'] 		= $value['ProductAttributeID'];
                $data['measurement_setting_created_by'] = $_SESSION['user_id'];
                $data['measurement_setting_updated_by'] = $_SESSION['user_id'];
                $data['measurement_setting_created_at'] = date('Y-m-d H:i:s');
                $data['measurement_setting_updated_at'] = date('Y-m-d H:i:s');
                if($this->db_operations->data_insert('measurement_setting_master', $data) < 1){
                    $this->add_fail = $this->add_fail + 1;
                }else{
                    $this->add = $this->add + 1;
                }
            }else{
                $data['measurement_setting_updated_by'] = $_SESSION['user_id'];
                $data['measurement_setting_updated_at'] = date('Y-m-d H:i:s');
                if($this->db_operations->data_update('measurement_setting_master', $data, 'measurement_setting_id', $exists[0]['measurement_setting_id']) < 1){
                    $this->edit_fail = $this->edit_fail + 1;
                }else{
                    $this->edit = $this->edit + 1;
                }
            }
        }
	}
}
?>
