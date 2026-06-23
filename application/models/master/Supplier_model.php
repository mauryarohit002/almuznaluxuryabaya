<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class supplier_model extends my_model{
	public function __construct(){ parent::__construct('master', 'supplier'); }
	public function isExist($id){
		$constansts = get_constant_supplier($this->config->item('supplier_constant'));
		if(in_array($id, $constansts)) return true;
		
		$data = $this->db->query("SELECT prmm_id FROM purchase_readymade_master WHERE prmm_supplier_id = $id LIMIT 1")->result_array();
		if(!empty($data)) return true;
		
		$data = $this->db->query("SELECT sku_id FROM sku_master WHERE sku_supplier_id = $id LIMIT 1")->result_array();
		if(!empty($data)) return true;

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
		
		if(isset($_GET['_name']) && !empty($_GET['_name'])){
			$subsql .=" AND supplier_name = '".$_GET['_name']."'";
			$record['filter']['_name']['value'] = $_GET['_name'];
			$record['filter']['_name']['text'] = $_GET['_name'];
		}
		if(isset($_GET['_code']) && !empty($_GET['_code'])){
			$subsql .=" AND supplier_code = '".$_GET['_code']."'";
			$record['filter']['_code']['value'] = $_GET['_code'];
			$record['filter']['_code']['text'] = $_GET['_code'];
		}
		if(isset($_GET['_mobile']) && !empty($_GET['_mobile'])){
			$subsql .=" AND supplier_mobile = '".$_GET['_mobile']."'";
			$record['filter']['_mobile']['value'] = $_GET['_mobile'];
			$record['filter']['_mobile']['text'] = $_GET['_mobile'];
		}
		if(isset($_GET['_status'])){
			$status = $_GET['_status'] == 2 ? 0 : $_GET['_status'];
			$subsql .=" AND supplier_status = ".$status;
			$record['filter']['_status'] = $this->Commonmdl->get_status($_GET['_status']);
		}
		$query="SELECT supplier.*,
				created.user_fullname as created_by,
				updated.user_fullname as updated_by
				FROM supplier_master supplier
				INNER JOIN user_master created ON(created.user_id = supplier.supplier_created_by)
				INNER JOIN user_master updated ON(updated.user_id = supplier.supplier_updated_by)
				WHERE 1
				$subsql
				ORDER BY supplier_id DESC
				$limit
				$ofset";
		// echo "<pre>"; print_r($query); exit;
		if($wantCount){
			return $this->db->query($query)->num_rows();
		}
		$record['data'] = $this->db->query($query)->result_array();
		if(!empty($record['data'])){
			foreach ($record['data'] as $key => $value) {
				$record['data'][$key]['isExist'] = $this->isExist($value['supplier_id']);
			}
		}
		return $record;
	}
	public function get_data($id){
		$query="SELECT supplier.*,
				IFNULL(UPPER(city.city_name), '') as city_name,
				IFNULL(UPPER(state.state_name), '') as state_name,
				IFNULL(UPPER(country.country_name), '') as country_name
				FROM supplier_master supplier
				LEFT JOIN city_master city ON(city.city_id = supplier.supplier_city_id)
				LEFT JOIN state_master state ON(state.state_id = supplier.supplier_state_id)
				LEFT JOIN country_master country ON(country.country_id = supplier.supplier_country_id)
				WHERE supplier.supplier_id = $id";
		return $this->db->query($query)->result_array();
	}
	public function get_company_name($company_id){
		$query="SELECT UPPER(company.company_name) as company_name
				FROM company_master company
				WHERE company.company_id = $company_id";
		$data = $this->db->query($query)->result_array();			
		return empty($data) ? '' : $data[0]['company_name'];
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
			$subsql .= " AND (supplier_name LIKE '%".$name."%') ";
		}
		if(isset($_GET['param']) && !empty($_GET['param'])){
			$param 	= $_GET['param'];
			$subsql .= " AND (supplier_status = $param) ";
		}
		$query="SELECT supplier_id as id, UPPER(supplier_name) as name
				FROM supplier_master
				WHERE 1
				$subsql
				ORDER BY supplier_name ASC
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
			$subsql .= " AND (supplier_name LIKE '%".$name."%') ";
		}
		if(isset($_GET['param']) && !empty($_GET['param'])){
			$param 	= $_GET['param'];
			$subsql .= " AND (supplier_status = $param) ";
		}
		$query="SELECT supplier_name as id, UPPER(supplier_name) as name
				FROM supplier_master
				WHERE 1
				$subsql
				GROUP BY supplier_name ASC
				LIMIT $limit
				OFFSET $offset";
		// echo $query; exit();
		return $this->db->query($query)->result_array();
	}
	public function _code(){
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
			$subsql .= " AND (supplier_code LIKE '%".$name."%') ";
		}
		if(isset($_GET['param']) && !empty($_GET['param'])){
			$param 	= $_GET['param'];
			$subsql .= " AND (supplier_status = $param) ";
		}
		$query="SELECT supplier_code as id, UPPER(supplier_code) as name
				FROM supplier_master
				WHERE 1
				$subsql
				GROUP BY supplier_code ASC
				LIMIT $limit
				OFFSET $offset";
		// echo $query; exit();
		return $this->db->query($query)->result_array();
	}
	public function _mobile(){
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
			$subsql .= " AND (supplier_mobile LIKE '%".$name."%') ";
		}
		if(isset($_GET['param']) && !empty($_GET['param'])){
			$param 	= $_GET['param'];
			$subsql .= " AND (supplier_status = $param) ";
		}
		$query="SELECT supplier_mobile as id, UPPER(supplier_mobile) as name
				FROM supplier_master
				WHERE 1
				$subsql
				GROUP BY supplier_mobile ASC
				LIMIT $limit
				OFFSET $offset";
		// echo $query; exit();
		return $this->db->query($query)->result_array();
	}

	 public function _bm_id(){
            $subsql = "";
            $name   = "";
            if(isset($_GET['name']) && !empty($_GET['name'])){
                $name   = $_GET['name'];
                $subsql .= " AND (bm.bm_item_code = '".$name."')";
            }
            $query ="
                        SELECT bm.bm_id as id, ".$name." as name
                        FROM barcode_master bm
                        WHERE 1
                        $subsql
                        ORDER BY bm.bm_id DESC
                        LIMIT 1
                    ";
            // echo $query; exit;
            return $this->db->query($query)->result_array();
        }

        
}
?>