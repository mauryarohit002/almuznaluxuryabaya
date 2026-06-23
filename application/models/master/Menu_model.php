<?php defined('BASEPATH') OR exit('No direct script access allowed');
	require_once APPPATH . 'core/MY_Model.php';
	class menu_model extends my_model{
		public function __construct(){ parent::__construct('master', 'menu'); }
		public function get_list($wantCount, $per_page = 20, $offset = 0){
			$record 	= [];
			$subsql 	= '';
			$limit  	= '';
			$ofset  	= '';
			
			if(!$wantCount){
				$limit .= " LIMIT $per_page";
				$ofset .= " OFFSET $offset";
			}
			
			if(isset($_GET['_name']) && !empty($_GET['_name'])){
				$subsql .=' AND menu_name = "'.$_GET['_name'].'"';
				$record['filter']['_name']['value'] = $_GET['_name'];
				$record['filter']['_name']['text'] = $_GET['_name'];
			}
			if(isset($_GET['_js']) && !empty($_GET['_js'])){
				$subsql .=" AND menu_js = '".$_GET['_js']."'";
				$record['filter']['_js']['value'] = $_GET['_js'];
				$record['filter']['_js']['text'] = $_GET['_js'];
			}
            if(isset($_GET['_status'])){
				$status = $_GET['_status'] == 2 ? 0 : $_GET['_status'];
				$subsql .=" AND menu_status = ".$status;
				$record['filter']['_status'] = $this->Commonmdl->get_status($_GET['_status']);
			}
			$query="SELECT menu.*
					FROM menu_master menu
					WHERE 1
					$subsql
					ORDER BY menu_id DESC
					$limit
					$ofset";
			// echo "<pre>"; print_r($query); exit;
			if($wantCount){
				return $this->db->query($query)->num_rows();
			}
			$record['data'] = $this->db->query($query)->result_array();
			// echo "<pre>"; print_r($record); exit;
			return $record;
		}
		public function get_data_for_add(){
		}
		public function get_data_for_edit($id){
			$query="SELECT menu.*
					FROM menu_master menu
					WHERE menu.menu_id = $id";
			$record['master_data'] = $this->db->query($query)->result_array();
			return $record;
		}
        public function get_transaction($id){
			$query="SELECT mt.*
					FROM menu_trans mt
					WHERE mt.mt_menu_id = $id
					ORDER BY mt.mt_name DESC";
			$data = $this->db->query($query)->result_array();
            if(!empty($data)){
                foreach ($data as $key => $value) {
                    $data[$key]['action_data'] = $this->get_action_data($value);
                }
            }
            return $data;
		}
        public function get_action_data($val){
			$query="SELECT mat.*
					FROM menu_action_trans mat
					WHERE mat.mat_mt_id = ".$val['mt_id']."";
			return $this->db->query($query)->result_array();
		}
		public function _id(){
			$subsql = "";
			$limit  = PER_PAGE;
			$offset = OFFSET;
			$page 	= 1;
			if(isset($_GET['limit']) && !empty($_GET['limit'])){
				$limit = $_GET['limit'];
			}
			if(isset($_GET['page']) && !empty($_GET['page'])){
				$page 	= $_GET['page'];
				$offset = $limit * ($page - 1);
			}
			if(isset($_GET['name']) && !empty($_GET['name'])){
				$name 	= $_GET['name'];
				$subsql .= " AND (menu_name LIKE '%".$name."%') ";
			}
			if(isset($_GET['param']) && !empty($_GET['param'])){
				$param 	= $_GET['param'];
				$subsql .= " AND (menu_status = $param) ";
			}
			$query="SELECT menu_id as id, UPPER(menu_name) as name
					FROM menu_master
					WHERE 1
					$subsql
					ORDER BY menu_name ASC
					LIMIT $limit
					OFFSET $offset";
			// echo $query; exit();
			return $this->db->query($query)->result_array();
		}
		public function _name(){
			$subsql = "";
			$limit  = PER_PAGE;
			$offset = OFFSET;
			$page 	= 1;
			if(isset($_GET['limit']) && !empty($_GET['limit'])){
				$limit = $_GET['limit'];
			}
			if(isset($_GET['page']) && !empty($_GET['page'])){
				$page 	= $_GET['page'];
				$offset = $limit * ($page - 1);
			}
			if(isset($_GET['name']) && !empty($_GET['name'])){
				$name 	= $_GET['name'];
				$subsql .= " AND (menu_name LIKE '%".$name."%') ";
			}
			if(isset($_GET['param']) && !empty($_GET['param'])){
				$param 	= $_GET['param'];
				$subsql .= " AND (menu_status = $param) ";
			}
			$query="SELECT menu_name as id, UPPER(menu_name) as name
					FROM menu_master
					WHERE 1
					$subsql
					GROUP BY menu_name ASC
					LIMIT $limit
					OFFSET $offset";
			// echo $query; exit();
			return $this->db->query($query)->result_array();
		}
		public function _js(){
			$subsql = "";
			$limit  = PER_PAGE;
			$offset = OFFSET;
			$page 	= 1;
			if(isset($_GET['limit']) && !empty($_GET['limit'])){
				$limit = $_GET['limit'];
			}
			if(isset($_GET['page']) && !empty($_GET['page'])){
				$page 	= $_GET['page'];
				$offset = $limit * ($page - 1);
			}
			if(isset($_GET['name']) && !empty($_GET['name'])){
				$name 	= $_GET['name'];
				$subsql .= " AND (menu_js LIKE '%".$name."%') ";
			}
			if(isset($_GET['param']) && !empty($_GET['param'])){
				$param 	= $_GET['param'];
				$subsql .= " AND (menu_status = $param) ";
			}
			$query="SELECT menu_js as id, UPPER(menu_js) as name
					FROM menu_master
					WHERE 1
					$subsql
					GROUP BY menu_js ASC
					LIMIT $limit
					OFFSET $offset";
			// echo $query; exit();
			return $this->db->query($query)->result_array();
		}
		public function _mt_name(){
			$subsql = " AND (maut.maut_user_id =".$_SESSION['user_id']." OR mart.mart_role_id =".$_SESSION['user_role_id'].")";
			$limit  = PER_PAGE;
			$offset = OFFSET;
			$page 	= 1;
			if(isset($_GET['limit']) && !empty($_GET['limit'])){
				$limit = $_GET['limit'];
			}
			if(isset($_GET['page']) && !empty($_GET['page'])){
				$page 	= $_GET['page'];
				$offset = $limit * ($page - 1);
			}
			if(isset($_GET['name']) && !empty($_GET['name'])){
				$name 	= $_GET['name'];
				$subsql .= " AND (mt.mt_name LIKE '%".$name."%') ";
			}
			$query="SELECT CONCAT(menu.menu_js,'/',mt.mt_url) as id, UPPER(mt.mt_name) as name
					FROM menu_master menu
					INNER JOIN menu_action_trans mat ON(mat.mat_menu_id = menu.menu_id) 
					INNER JOIN menu_trans mt ON(mt.mt_id = mat.mat_mt_id)
					LEFT JOIN menu_action_user_trans maut ON(maut.maut_mat_id = mat.mat_id)
					LEFT JOIN menu_action_role_trans mart ON(mart.mart_mat_id = mat.mat_id)
					WHERE mat.mat_status = 1
					AND mt.mt_status = 1
					$subsql
					GROUP BY mt.mt_js
					ORDER BY mt.mt_name ASC
					LIMIT $limit
					OFFSET $offset";
			// echo $query; exit();
			return $this->db->query($query)->result_array();
		}
	}
?>