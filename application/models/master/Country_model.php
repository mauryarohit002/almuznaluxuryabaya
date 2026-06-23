<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class country_model extends my_model{
	public function __construct(){ parent::__construct('master', 'country'); }
	public function isExist($id){
		// $data = $this->db->query("SELECT city_id FROM city_master WHERE city_country_id = $id LIMIT 1")->result_array();
		// if(!empty($data)) return true;

		return false;
	}
}
?>
