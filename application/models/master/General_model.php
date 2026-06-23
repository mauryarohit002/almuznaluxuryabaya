<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class general_model extends my_model{
	public function __construct(){ parent::__construct('master', 'general'); }
	public function isExist($id){
		// $data = $this->db->query("SELECT pt_id FROM purchase_trans WHERE pt_general_id = $id LIMIT 1")->result_array();
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
        
        if(isset($_GET['_general_name']) && !empty($_GET['_general_name'])){
            $subsql .=" AND general.general_name LIKE '%".$_GET['_general_name']."%'";
            $record['filter']['_general_name']['value'] = $_GET['_general_name'];
            $record['filter']['_general_name']['text'] = $_GET['_general_name'];
        }
        if(isset($_GET['_category_name']) && !empty($_GET['_category_name'])){
            $subsql .=" AND category.category_name LIKE '%".$_GET['_category_name']."%'";
            $record['filter']['_category_name']['value'] = $_GET['_category_name'];
            $record['filter']['_category_name']['text'] = $_GET['_category_name'];
        }
        if(isset($_GET['_status'])){
            $status = $_GET['_status'] == 2 ? 0 : $_GET['_status'];
            $subsql .=" AND general_status = ".$status;
            $record['filter']['_status'] = $this->Commonmdl->get_status($_GET['_status']);
        }
        $query="SELECT general.general_id, 
                UPPER(general.general_name) as general_name, 
                general.general_status
                FROM general_master general
                WHERE 1
                $subsql
                ORDER BY general.general_id DESC
                $limit
                $ofset";
        // echo "<pre>"; print_r($query); exit;
        if($wantCount){
            return $this->db->query($query)->num_rows();
        }
        $record['data'] = $this->db->query($query)->result_array();
        if(!empty($record['data'])){
            foreach ($record['data'] as $key => $value) {
                $record['data'][$key]['isExist']    = $this->isExist($value['general_id']);
            }
        }
        return $record;
    }
	public function get_data($id){
        $query="SELECT general.*
                FROM general_master general
                WHERE general.general_id = $id";
        return $this->db->query($query)->result_array();
    }
    
    public function _general_name(){
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
            $subsql .= " AND (".$this->sub_menu."_name LIKE '".$name."%') ";
        }
        if(isset($_GET['param']) && !empty($_GET['param'])){
            $param 	= $_GET['param'];
            $subsql .= " AND (".$this->sub_menu."_status = $param) ";
        }
        $query="SELECT 
                ".$this->sub_menu."_name as id, 
                UPPER(".$this->sub_menu."_name) as name
                FROM ".$this->sub_menu."_master
                WHERE 1
                AND ".$this->sub_menu."_branch_id='".$_SESSION['user_branch_id']."'
                $subsql
                GROUP BY ".$this->sub_menu."_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
}
?>
