<?php defined('BASEPATH') OR exit('No direct script access allowed');
	class Commonmdl extends CI_model{
		public function __construct(){
			parent::__construct();
		}
		public function get_role($role){
			$data 	= $this->config->item('role');
			if(empty($data)) return ['value' => '', 'text' => ''];
			foreach ($data as $key => $value) {
				if($role == $key){
					return ['value' => $key, 'text' => $value];
				}
			}
		}
		public function get_mode($mode){
			$data 	= $this->config->item('payment_mode');
			if(empty($data)) return ['value' => '', 'text' => ''];
			foreach ($data as $key => $value) {
				if($mode == $key){
					return ['value' => $key, 'text' => $value];
				}
			}
		}
		public function get_status($status){
			$data 	= $this->config->item('status');
			if(empty($data)) return ['value' => '', 'text' => ''];
			foreach ($data as $key => $value) {
				if($status == $key){
					return ['value' => $key, 'text' => $value];
				}
			}
		}
		public function get_drcr($status){
			$data 	= $this->config->item('drcr');
			if(empty($data)) return ['value' => '', 'text' => ''];
			foreach ($data as $key => $value) {
				if($status == $key){
					return ['value' => $key, 'text' => $value];
				}
			}
		}
		public function _grp_id(){
			$subsql = "";
			$limit  = PER_PAGE;
            $offset = OFFSET;
            $page   = 1;
            if(isset($_GET['limit']) && !empty($_GET['limit'])){
                $limit = $_GET['limit'];
            }
            if(isset($_GET['page']) && !empty($_GET['page'])){
                $page   = $_GET['page'];
                $offset = $limit * ($page - 1);
            }
			if(isset($_GET['name']) && !empty($_GET['name'])){
				$name 	= $_GET['name'];
				$subsql .= " AND (grp_name LIKE '%".$name."%') ";
			}
			if(isset($_GET['param']) && !empty($_GET['param'])){
				$param 	= $_GET['param'];
				$subsql .= " AND (grp_status = $param) ";
			}
			$query ="
						SELECT grp_id as id, UPPER(grp_name) as name
						FROM group_master
						WHERE 1
						$subsql
						ORDER BY grp_name ASC
						LIMIT $limit
                        OFFSET $offset
					";
			// echo $query; exit();
			return $this->db->query($query)->result_array();
		}
		
	}
?>