<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class grp_model extends my_model{
	public function __construct(){ parent::__construct('master', 'grp'); }
	public function isExist($id){
		// $data = $this->db->query("SELECT pt_id FROM purchase_trans WHERE pt_category_id = $id LIMIT 1")->result_array();
		// if(!empty($data)) return true;

		return false;
	}
}
?>
