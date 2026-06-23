<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Sync_Controller.php';
class country extends my_sync_controller{
	public function __construct(){
		parent::__construct('tbl_M_Country', 'CountryID');
	}
	public function sub_func($value){
		$exists = $this->db_operations->get_record('country_master', ['country_id' => $value['CountryID']]);
        $data 	= [];
        $data['country_name']          = trim($value['CountryName']);
        $data['country_status']        = $value['IsDeleted'] == '' ? 1 : 0;
        if(empty($exists)){
            $data['country_id'] 		= $value['CountryID'];
            $data['country_created_by']= $_SESSION['user_id'];
            $data['country_updated_by']= $_SESSION['user_id'];
            $data['country_created_at']= date('Y-m-d H:i:s');
            $data['country_updated_at']= date('Y-m-d H:i:s');
            if($this->db_operations->data_insert('country_master', $data) < 1){
                $this->add_fail = $this->add_fail + 1;
			}else{
				$this->add = $this->add + 1;
			}
        }else{
            $data['country_updated_by']= $_SESSION['user_id'];
            $data['country_updated_at']= date('Y-m-d H:i:s');
            if($this->db_operations->data_update('country_master', $data, 'country_id', $exists[0]['country_id']) < 1){
                $this->edit_fail = $this->edit_fail + 1;
			}else{
				$this->edit = $this->edit + 1;
			}
        }
	}
}
?>
