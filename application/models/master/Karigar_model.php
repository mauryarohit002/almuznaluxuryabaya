<?php defined('BASEPATH') OR exit('No direct script access allowed');
	require_once APPPATH . 'core/MY_Model.php';
	class karigar_model extends my_model{
		public function __construct(){ parent::__construct('master', 'karigar'); }
        public function isExist($id){
            // $data = $this->db->query("SELECT bm_id FROM barcode_master WHERE bm_pm_id = $id AND (bm_sot_mtr + bm_it_mtr + bm_prt_mtr) > 0 LIMIT 1")->result_array();
            // if(!empty($data)) return true;
    
            return false;
        }
        public function isTransExist($id){
            // $data = $this->db->query("SELECT bm_id FROM barcode_master WHERE bm_pt_id = $id AND (bm_sot_mtr + bm_it_mtr + bm_prt_mtr) > 0 LIMIT 1")->result_array();
            // if(!empty($data)) return true;
    
            return false;
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
			
			if(isset($_GET['_karigar_name']) && !empty($_GET['_karigar_name'])){
				$subsql .=' AND karigar.karigar_name = "'.$_GET['_karigar_name'].'"';
				$record['filter']['_karigar_name']['value'] = $_GET['_karigar_name'];
				$record['filter']['_karigar_name']['text'] = $_GET['_karigar_name'];
			}
            if(isset($_GET['_status'])){
				$status = $_GET['_status'] == 2 ? 0 : $_GET['_status'];
				$subsql .=" AND menu_status = ".$status;
				$record['filter']['_status'] = $this->Commonmdl->get_status($_GET['_status']);
			}
			$query="SELECT karigar.*
					FROM karigar_master karigar
					WHERE 1
					$subsql
					ORDER BY karigar.karigar_name ASC
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
			$query="SELECT karigar.*,
                    IFNULL(UPPER(city.city_name), '') as city_name,
                    IFNULL(UPPER(state.state_name), '') as state_name,
                    IFNULL(UPPER(country.country_name), '') as country_name
					FROM karigar_master karigar
                    LEFT JOIN city_master city ON(city.city_id = karigar.karigar_city_id)
                    LEFT JOIN state_master state ON(state.state_id = karigar.karigar_state_id)
                    LEFT JOIN country_master country ON(country.country_id = karigar.karigar_country_id)
					WHERE karigar.karigar_id = $id";
			$record['master_data'] = $this->db->query($query)->result_array();

			$record['family_trans'] = $this->db_operations->get_record('karigar_family_trans', ['kft_karigar_id' => $id]);

            return $record;
		}
        public function get_transaction($id){
			$query="SELECT kat.*
				FROM karigar_attachment_trans kat
				WHERE kat.kat_karigar_id = $id";
		    $record['attachment_data'] = $this->db->query($query)->result_array();

            $query="SELECT kpt.*,
                UPPER(proces.proces_name) as proces_name
				FROM karigar_proces_trans kpt
                INNER JOIN proces_master proces ON(proces.proces_id = kpt.kpt_proces_id)
				WHERE kpt.kpt_karigar_id = $id";
		    $record['proces_data'] = $this->db->query($query)->result_array();

            $query="SELECT kapt.*,
                UPPER(apparel.apparel_name) as apparel_name
				FROM karigar_apparel_trans kapt
                INNER JOIN apparel_master apparel ON(apparel.apparel_id = kapt.kapt_apparel_id)
				WHERE kapt.kapt_karigar_id = $id";
		    $record['apparel_data'] = $this->db->query($query)->result_array();

			return $record;
		}
		public function get_proces($id){
			$query="SELECT 0 as kpt_id,
				kpt.kpt_proces_id,
				UPPER(proces.proces_name) as proces_name,
				0 as isExist
				FROM karigar_proces_trans kpt
                INNER JOIN proces_master proces ON(proces.proces_id = kpt.kpt_proces_id)
				WHERE kpt.kpt_karigar_id = $id";
		    $data = $this->db->query($query)->result_array();
			if(!empty($data)){
				foreach ($data as $key => $value) {
					$data[$key]['isExist'] = false;
				}
			}
			return $data;
		}
		public function get_apparel($id){
			$query="SELECT 0 as kapt_id,
				kapt.kapt_apparel_id,
				kapt.kapt_qty,
				kapt.kapt_rate,
				UPPER(apparel.apparel_name) as apparel_name,
				0 as isExist
				FROM karigar_apparel_trans kapt
                INNER JOIN apparel_master apparel ON(apparel.apparel_id = kapt.kapt_apparel_id)
				WHERE kapt.kapt_karigar_id = $id";
		    $data = $this->db->query($query)->result_array();
			if(!empty($data)){
				foreach ($data as $key => $value) {
					$data[$key]['isExist'] = false;
				}
			}
			return $data;
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
				$subsql .= " AND (karigar_name LIKE '%".$name."%') ";
			}
			if(isset($_GET['param']) && !empty($_GET['param'])){
				$param 	= $_GET['param'];
				$subsql .= " AND (karigar_status = $param) ";
			}
			$query="SELECT karigar_id as id, UPPER(karigar_name) as name
					FROM karigar_master
					WHERE 1
					$subsql
					ORDER BY karigar_name ASC
					LIMIT $limit
					OFFSET $offset";
			// echo $query; exit();
			return $this->db->query($query)->result_array();
		}
		public function _apparel_name(){
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
				$subsql .= " AND (apparel_name LIKE '%".$name."%') ";
			}
			if(isset($_GET['param']) && !empty($_GET['param'])){
				$param 	= $_GET['param'];
				$subsql .= " AND (apparel_status = $param) ";
			}
			$query="SELECT apparel_name as id, UPPER(apparel_name) as name
					FROM apparel_master
					WHERE 1
					$subsql
					GROUP BY apparel_name ASC
					LIMIT $limit
					OFFSET $offset";
			// echo $query; exit();
			return $this->db->query($query)->result_array();
		}
		public function _proces_id(){
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
				$subsql .= " AND (karigar.karigar_name LIKE '".$name."%') ";
			}
			if(isset($_GET['param']) && !empty($_GET['param'])){
				$param 	= $_GET['param'];
				$subsql .= " AND (karigar.karigar_status = $param) ";
			}
			if(isset($_GET['param1']) && !empty($_GET['param1'])){
				$param1  = $_GET['param1'];
				$subsql .= " AND (karigar.karigar_id != $param1) ";
			}
			$query="SELECT karigar.karigar_id as id, 
					UPPER(karigar.karigar_name) as name
					FROM karigar_master karigar
					INNER JOIN karigar_proces_trans kpt ON(kpt.kpt_karigar_id = karigar.karigar_id)
					WHERE 1
					$subsql
					GROUP BY karigar.karigar_id 
					ORDER BY karigar.karigar_name ASC
					LIMIT $limit
					OFFSET $offset";
			// echo $query; exit();
			return $this->db->query($query)->result_array();
		}
		public function _apparel_id(){
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
				$subsql .= " AND (karigar.karigar_name LIKE '".$name."%') ";
			}
			if(isset($_GET['param']) && !empty($_GET['param'])){
				$param 	= $_GET['param'];
				$subsql .= " AND (karigar.karigar_status = $param) ";
			}
			if(isset($_GET['param1']) && !empty($_GET['param1'])){
				$param1  = $_GET['param1'];
				$subsql .= " AND (karigar.karigar_id != $param1) ";
			}
			$query="SELECT karigar.karigar_id as id, 
					UPPER(karigar.karigar_name) as name
					FROM karigar_master karigar
					INNER JOIN karigar_apparel_trans kapt ON(kapt.kapt_karigar_id = karigar.karigar_id)
					WHERE 1
					$subsql
					GROUP BY karigar.karigar_id 
					ORDER BY karigar.karigar_name ASC
					LIMIT $limit
					OFFSET $offset";
			// echo $query; exit();
			return $this->db->query($query)->result_array();
		}
	}
?>