<?php defined('BASEPATH') OR exit('No direct script access allowed');
	class thememdl extends CI_model{
		public function __construct(){
			parent::__construct();
            $this->load->model('master/Commonmdl');
		}
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
				$subsql .=' AND theme_name = "'.$_GET['_name'].'"';
				$record['filter']['_name']['value'] = $_GET['_name'];
				$record['filter']['_name']['text'] = $_GET['_name'];
			}
			if(isset($_GET['_js']) && !empty($_GET['_js'])){
				$subsql .=" AND theme_js = '".$_GET['_js']."'";
				$record['filter']['_js']['value'] = $_GET['_js'];
				$record['filter']['_js']['text'] = $_GET['_js'];
			}
            if(isset($_GET['_status'])){
				$status = $_GET['_status'] == 2 ? 0 : $_GET['_status'];
				$subsql .=" AND theme_status = ".$status;
				$record['filter']['_status'] = $this->Commonmdl->get_status($_GET['_status']);
			}
			$query="SELECT theme.*
					FROM theme_master theme
					WHERE 1
					$subsql
					ORDER BY theme_id DESC
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
		public function get_data_for_edit($id){
			$query="SELECT theme.*
					FROM theme_master theme
					WHERE theme.theme_id = $id";
			$record['master_data'] = $this->db->query($query)->result_array();
			return $record;
		}
        public function get_transaction($id){
			$query="SELECT tt.*
					FROM theme_trans tt
					WHERE tt.tt_theme_id = $id
					ORDER BY tt.tt_variable ASC";
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
				$subsql .= " AND (theme_name LIKE '%".$name."%') ";
			}
			if(isset($_GET['param']) && !empty($_GET['param'])){
				$param 	= $_GET['param'];
				$subsql .= " AND (theme_status = $param) ";
			}
			$query="SELECT theme_id as id, UPPER(theme_name) as name
					FROM theme_master
					WHERE 1
					$subsql
					ORDER BY theme_name ASC
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
				$subsql .= " AND (theme_name LIKE '%".$name."%') ";
			}
			if(isset($_GET['param']) && !empty($_GET['param'])){
				$param 	= $_GET['param'];
				$subsql .= " AND (theme_status = $param) ";
			}
			$query="SELECT theme_name as id, UPPER(theme_name) as name
					FROM theme_master
					WHERE 1
					$subsql
					GROUP BY theme_name ASC
					LIMIT $limit
					OFFSET $offset";
			// echo $query; exit();
			return $this->db->query($query)->result_array();
		}
	}
?>