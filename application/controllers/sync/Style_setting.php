<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Sync_Controller.php';
class style_setting extends my_sync_controller{
	public function __construct(){
		parent::__construct('Tbl_M_ProductAttributes', 'ProductAttributeID');
	}
	public function sub_func($value){
        if($value['StyleMasterID'] > 0){
            $exists = $this->db_operations->get_record('style_setting_master', ['style_setting_id' => $value['ProductAttributeID']]);
            $data 	= [];
            $data['style_setting_apparel_id']     = trim($value['MesarmentTypeID']);
            $data['style_setting_style_id']       = trim($value['StyleMasterID']);
            $data['style_setting_status']         = $value['IsDeleted'] == '' ? 1 : 0;
            if(empty($exists)){
                $data['style_setting_id'] 		  = $value['ProductAttributeID'];
                $data['style_setting_created_by'] = $_SESSION['user_id'];
                $data['style_setting_updated_by'] = $_SESSION['user_id'];
                $data['style_setting_created_at'] = date('Y-m-d H:i:s');
                $data['style_setting_updated_at'] = date('Y-m-d H:i:s');
                if($this->db_operations->data_insert('style_setting_master', $data) < 1){
                    $this->add_fail = $this->add_fail + 1;
                }else{
                    $this->add = $this->add + 1;
                }
            }else{
                $data['style_setting_updated_by'] = $_SESSION['user_id'];
                $data['style_setting_updated_at'] = date('Y-m-d H:i:s');
                if($this->db_operations->data_update('style_setting_master', $data, 'style_setting_id', $exists[0]['style_setting_id']) < 1){
                    $this->edit_fail = $this->edit_fail + 1;
                }else{
                    $this->edit = $this->edit + 1;
                }
            }
        }
	}
}
?>
