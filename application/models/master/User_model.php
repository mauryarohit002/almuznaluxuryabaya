<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class user_model extends my_model{
	public function __construct(){
		parent::__construct('master', 'user');

		$this->load->model('master/Commonmdl');
	}
	public function isExist($id){
		$constansts = get_constant_user($this->config->item('user_constant'));
		if(in_array($id, $constansts)) return true;

		$tables = ['customer', 'supplier', 'branch', 'city', 'state', 'country', 'user'];
		foreach ($tables as $key => $value) {
			$select = $value.'_id';
			$table 	= $value.'_master';
			$subsql = " AND ".$value."_created_by = $id";
			$data = $this->db->query("SELECT $select FROM $table WHERE 1 $subsql LIMIT 1")->result_array();
			if(!empty($data)) return true;
		}

		return false;
	}
	public function get_list($wantCount, $per_page = 20, $offset = 0){
		$record 	= [];
		$limit  	= '';
		$ofset  	= '';
		$type 		= $_SESSION['user_type'];
		$subsql 	= $type == 1 ? '' : " AND user.user_type = 2";
		
		if(!$wantCount){
			$limit .= " LIMIT $per_page";
			$ofset .= " OFFSET $offset";
		}	
		if(isset($_GET['_fullname']) && !empty($_GET['_fullname'])){
			$subsql .=" AND user.user_fullname = '".$_GET['_fullname']."'";
			$record['filter']['_fullname']['value'] = $_GET['_fullname'];
			$record['filter']['_fullname']['text'] = $_GET['_fullname'];
		}
		if(isset($_GET['_role_name']) && !empty($_GET['_role_name'])){
			$subsql .=" AND role.role_name = '".$_GET['_role_name']."'";
			$record['filter']['_role_name']['value'] = $_GET['_role_name'];
			$record['filter']['_role_name']['text'] = $_GET['_role_name'];
		}
		if(isset($_GET['_branch_name']) && !empty($_GET['_branch_name'])){
			$subsql .=" AND branch.branch_name = '".$_GET['_branch_name']."'";
			$record['filter']['_branch_name']['value'] 	= $_GET['_branch_name'];
			$record['filter']['_branch_name']['text'] 	= $_GET['_branch_name'];
		}
		if(isset($_GET['_status'])){
			$status = $_GET['_status'] == 2 ? 0 : $_GET['_status'];
			$subsql .=" AND user_status = ".$status;
			$record['filter']['_status'] = $this->Commonmdl->get_status($_GET['_status']);
		}
		$query="SELECT user.*, 
				UPPER(role.role_name) as role_name,
				UPPER(branch.branch_name) as branch_name
				FROM user_master user
				INNER JOIN role_master role ON(role.role_id = user.user_role_id)
				INNER JOIN branch_master branch ON(branch.branch_id = user.user_branch_id)
				WHERE 1
				$subsql
				ORDER BY role.role_name ASC
				$limit
				$ofset";
		// echo "<pre>"; print_r($query); exit;
		if($wantCount){
			return $this->db->query($query)->num_rows();
		}
		$record['data'] = $this->db->query($query)->result_array();
		if(!empty($record['data'])){
			foreach ($record['data'] as $key => $value) {
				$record['data'][$key]['isExist'] = $this->isExist($value['user_id']);
			}
		}
		// echo "<pre>"; print_r($record); exit;

		return $record;
	}
	public function get_data($id){
		$type 	= $_SESSION['user_type'];
		$subsql = $type == 1 ? '' : " AND user.user_type = 2";
		$query="SELECT user.*, 
				UPPER(role.role_name) as role_name,
				UPPER(branch.branch_name) as branch_name,
				IFNULL(UPPER(city.city_name), '') as city_name,
				IFNULL(UPPER(state.state_name), '') as state_name,
				IFNULL(UPPER(country.country_name), '') as country_name
				FROM user_master user
				INNER JOIN role_master role ON(role.role_id = user.user_role_id)
				INNER JOIN branch_master branch ON(branch.branch_id = user.user_branch_id)
				LEFT JOIN city_master city ON(city.city_id = user.user_city_id)
				LEFT JOIN state_master state ON(state.state_id = user.user_state_id)
				LEFT JOIN country_master country ON(country.country_id = user.user_country_id)
				WHERE user.user_id = $id
				$subsql";
		$record = $this->db->query($query)->result_array();
		if(!empty($record)){
			$record[0]['isExist'] = $this->isExist($record[0]['user_id']);
		}
		return $record;
	}
	public function _fullname(){
		$limit  = PER_PAGE;
		$offset = OFFSET;
		$page 	= 1;
		$type 	= $_SESSION['user_type'];
		$subsql = $type == 1 ? '' : " AND user_type = 2";
		if(isset($_GET['limit']) && !empty($_GET['limit'])){
			$limit = $_GET['limit'];
		}
		if(isset($_GET['page']) && !empty($_GET['page'])){
			$page 	= $_GET['page'];
			$offset = $limit * ($page - 1);
		}
		if(isset($_GET['name']) && !empty($_GET['name'])){
			$name 	= $_GET['name'];
			$subsql .= " AND (user_fullname LIKE '%".$name."%') ";
		}
		if(isset($_GET['param']) && !empty($_GET['param'])){
			$param 	= $_GET['param'];
			$subsql .= " AND (user_status = $param) ";
		}
		$query="SELECT user_fullname as id, UPPER(user_fullname) as name
				FROM user_master
				WHERE 1
				$subsql
				GROUP BY user_fullname ASC
				LIMIT $limit
				OFFSET $offset";
		// echo $query; exit();
		return $this->db->query($query)->result_array();
	}
	public function _role_name(){
		$limit  = PER_PAGE;
		$offset = OFFSET;
		$page 	= 1;
		$type 		= $_SESSION['user_type'];
		$subsql 	= $type == 1 ? '' : " AND role.role_type = 2";
		if(isset($_GET['limit']) && !empty($_GET['limit'])){
			$limit = $_GET['limit'];
		}
		if(isset($_GET['page']) && !empty($_GET['page'])){
			$page 	= $_GET['page'];
			$offset = $limit * ($page - 1);
		}
		if(isset($_GET['name']) && !empty($_GET['name'])){
			$name 	= $_GET['name'];
			$subsql .= " AND (role.role_name LIKE '%".$name."%') ";
		}
		if(isset($_GET['param']) && !empty($_GET['param'])){
			$param 	= $_GET['param'];
			$subsql .= " AND (role.role_status = $param) ";
		}
		$query="SELECT role.role_name as id, UPPER(role.role_name) as name
				FROM role_master role
				WHERE 1
				$subsql
				GROUP BY role.role_name ASC
				LIMIT $limit
				OFFSET $offset";
		// echo $query; exit();
		return $this->db->query($query)->result_array();
	}
	public function _branch_name(){
		$limit  = PER_PAGE;
		$offset = OFFSET;
		$page 	= 1;
		$type 	= $_SESSION['user_type'];
		$subsql = $type == 1 ? '' : " AND user.user_type = 2";
		if(isset($_GET['limit']) && !empty($_GET['limit'])){
			$limit = $_GET['limit'];
		}
		if(isset($_GET['page']) && !empty($_GET['page'])){
			$page 	= $_GET['page'];
			$offset = $limit * ($page - 1);
		}
		if(isset($_GET['name']) && !empty($_GET['name'])){
			$name 	= $_GET['name'];
			$subsql .= " AND (branch.branch_name LIKE '%".$name."%') ";
		}
		if(isset($_GET['param']) && !empty($_GET['param'])){
			$param 	= $_GET['param'];
			$subsql .= " AND (user.user_status = $param) ";
		}
		$query="SELECT branch.branch_name as id, UPPER(branch.branch_name) as name
				FROM user_master user
				INNER JOIN branch_master branch ON(branch.branch_id = user.user_branch_id)
				WHERE 1
				$subsql
				GROUP BY branch.branch_name ASC
				LIMIT $limit
				OFFSET $offset";
		// echo $query; exit();
		return $this->db->query($query)->result_array();
	}		
}
?>