<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class state_model extends my_model{
	public function __construct(){ parent::__construct('master', 'state'); }
	public function isExist($id){
		// $data = $this->db->query("SELECT city_id FROM city_master WHERE city_state_id = $id LIMIT 1")->result_array();
		// if(!empty($data)) return true;

		return false;
	}
}
?>
