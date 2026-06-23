<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class role_model extends my_model{
	public function __construct(){ parent::__construct('master', 'role'); }
	public function isExist($id){
		$data = $this->db->query("SELECT user_id FROM user_master WHERE user_role_id = $id LIMIT 1")->result_array();
		if(!empty($data)) return true;

		return false;
	}
}
?>
