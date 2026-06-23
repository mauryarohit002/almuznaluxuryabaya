<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class company_model extends my_model{
	public function __construct(){ parent::__construct('master', 'company'); }
	public function isExist($id){
		// $data = $this->db->query("SELECT cpt_id FROM city_pincode_trans WHERE cpt_city_id = $id LIMIT 1")->result_array();
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
        
        if(isset($_GET['_name']) && !empty($_GET['_name'])){
            $subsql .=" AND company_name = '".$_GET['_name']."'";
            $record['filter']['_name']['value'] = $_GET['_name'];
            $record['filter']['_name']['text'] = $_GET['_name'];
        }
        if(isset($_GET['_person']) && !empty($_GET['_person'])){
            $subsql .=" AND company_person = '".$_GET['_person']."'";
            $record['filter']['_person']['value'] = $_GET['_person'];
            $record['filter']['_person']['text'] = $_GET['_person'];
        }
        if(isset($_GET['_mobile']) && !empty($_GET['_mobile'])){
            $subsql .=" AND company_mobile = '".$_GET['_mobile']."'";
            $record['filter']['_mobile']['value'] = $_GET['_mobile'];
            $record['filter']['_mobile']['text'] = $_GET['_mobile'];
        }
        if(isset($_GET['_status'])){
            $status = $_GET['_status'] == 2 ? 0 : $_GET['_status'];
            $subsql .=" AND company_status = ".$status;
            $record['filter']['_status'] = $this->Commonmdl->get_status($_GET['_status']);
        }
        $query="SELECT company.*,
                created.user_fullname as created_by,
                updated.user_fullname as updated_by
                FROM company_master company
                INNER JOIN user_master created ON(created.user_id = company.company_created_by)
                INNER JOIN user_master updated ON(updated.user_id = company.company_updated_by)
                WHERE 1
                $subsql
                ORDER BY company_id DESC
                $limit
                $ofset";
        // echo "<pre>"; print_r($query); exit;
        if($wantCount){
            return $this->db->query($query)->num_rows();
        }
        $record['data'] = $this->db->query($query)->result_array();
        if(!empty($record['data'])){
            foreach ($record['data'] as $key => $value) {
                $record['data'][$key]['isExist'] = empty($value['company_constant']) ? $this->isExist($value['company_id']) : 1;
            }
        }
        return $record;
    }
    public function get_data($id){
        $query="SELECT company.*,
                IFNULL(UPPER(city_name), '') as city_name,
                IFNULL(UPPER(state_name), '') as state_name,
                IFNULL(UPPER(country_name), '') as country_name
                FROM company_master company
                LEFT JOIN city_master city ON(city.city_id = company.company_city_id)
                LEFT JOIN state_master state ON(state.state_id = company.company_state_id)
                LEFT JOIN country_master country ON(country.country_id = company.company_country_id)
                WHERE company.company_id = $id";
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
            $subsql .= " AND (company_name LIKE '%".$name."%') ";
        }
        if(isset($_GET['param']) && !empty($_GET['param'])){
            $param 	= $_GET['param'];
            $subsql .= " AND (company_status = $param) ";
        }
        $query="SELECT company_id as id, UPPER(company_name) as name
                FROM company_master
                WHERE 1
                $subsql
                ORDER BY company_name ASC
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
            $subsql .= " AND (company_name LIKE '%".$name."%') ";
        }
        if(isset($_GET['param']) && !empty($_GET['param'])){
            $param 	= $_GET['param'];
            $subsql .= " AND (company_status = $param) ";
        }
        $query="SELECT company_name as id, UPPER(company_name) as name
                FROM company_master
                WHERE 1
                $subsql
                GROUP BY company_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _person(){
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
            $subsql .= " AND (company_person LIKE '%".$name."%') ";
        }
        if(isset($_GET['param']) && !empty($_GET['param'])){
            $param 	= $_GET['param'];
            $subsql .= " AND (company_status = $param) ";
        }
        $query="SELECT company_person as id, UPPER(company_person) as name
                FROM company_master
                WHERE 1
                $subsql
                GROUP BY company_person ASC
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
            $subsql .= " AND (company_mobile LIKE '%".$name."%') ";
        }
        if(isset($_GET['param']) && !empty($_GET['param'])){
            $param 	= $_GET['param'];
            $subsql .= " AND (company_status = $param) ";
        }
        $query="SELECT company_mobile as id, UPPER(company_mobile) as name
                FROM company_master
                WHERE 1
                $subsql
                GROUP BY company_mobile ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
}
?>
