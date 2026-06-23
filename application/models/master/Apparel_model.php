<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class apparel_model extends my_model{
	public function __construct(){ parent::__construct('master', 'apparel'); }
	public function isExist($id){
		$data = $this->db->query("SELECT sku_id FROM sku_master WHERE sku_apparel_id = $id LIMIT 1")->result_array();
		if(!empty($data)) return true;
        
//         $data = $this->db->query("SELECT pt_id FROM purchase_trans WHERE pt_apparel_id = $id LIMIT 1")->result_array();
// 		if(!empty($data)) return true;

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
        
        if(isset($_GET['_apparel_name']) && !empty($_GET['_apparel_name'])){
            $subsql .=" AND apparel.apparel_name LIKE '%".$_GET['_apparel_name']."%'";
            $record['filter']['_apparel_name']['value'] = $_GET['_apparel_name'];
            $record['filter']['_apparel_name']['text'] = $_GET['_apparel_name'];
        }
        if(isset($_GET['_category_name']) && !empty($_GET['_category_name'])){
            $subsql .=" AND category.category_name LIKE '%".$_GET['_category_name']."%'";
            $record['filter']['_category_name']['value'] = $_GET['_category_name'];
            $record['filter']['_category_name']['text'] = $_GET['_category_name'];
        }
        if(isset($_GET['_status'])){
            $status = $_GET['_status'] == 2 ? 0 : $_GET['_status'];
            $subsql .=" AND apparel_status = ".$status;
            $record['filter']['_status'] = $this->Commonmdl->get_status($_GET['_status']);
        }
        $query="SELECT apparel.apparel_id, 
                UPPER(apparel.apparel_name) as apparel_name, 
                apparel.apparel_charges,
                apparel.apparel_status,
                apparel.apparel_default as isExist
                FROM apparel_master apparel
                WHERE 1
                $subsql
                ORDER BY apparel.apparel_id DESC
                $limit
                $ofset";
        // echo "<pre>"; print_r($query); exit;
        if($wantCount){
            return $this->db->query($query)->num_rows();
        }
        $record['data'] = $this->db->query($query)->result_array();
        if(!empty($record['data'])){
            foreach ($record['data'] as $key => $value) {
                $record['data'][$key]['isExist']    = $value['isExist'] ?  $value['isExist'] : $this->isExist($value['apparel_id']);
            }
        }
        return $record;
    }
	public function get_data($id){
        $query="SELECT apparel.*
                FROM apparel_master apparel
                WHERE apparel.apparel_id = $id";
        $data['master_data']= $this->db->query($query)->result_array();     
       return $data;          
       
    }
    public function _copy(){
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
            $subsql .= " AND (apparel.apparel_name LIKE '".$name."%') ";
        }
        if(isset($_GET['param']) && !empty($_GET['param'])){
            $param 	= $_GET['param'];
            $subsql .= " AND (apparel.apparel_status = $param) ";
        }
        if(isset($_GET['param1']) && !empty($_GET['param1'])){
            $param1  = $_GET['param1'];
            $subsql .= " AND (apparel.apparel_id != $param1) ";
        }
        $query="SELECT apparel.apparel_id as id, 
                UPPER(apparel.apparel_name) as name
                FROM apparel_master apparel
                WHERE 1
                $subsql
                GROUP BY apparel.apparel_id 
                ORDER BY apparel.apparel_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }

      public function _apparel_name(){
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
            $name   = $_GET['name'];
            $subsql .= " AND (apparel.apparel_name LIKE '".$name."%') ";
        }
        if(isset($_GET['param']) && !empty($_GET['param'])){
            $param  = $_GET['param'];
            $subsql .= " AND (apparel.apparel_status = $param) ";
        }
        $query="SELECT 
                apparel.apparel_name as id, 
                UPPER(apparel.apparel_name) as name
                FROM apparel_master apparel
                WHERE 1
                $subsql
                GROUP BY apparel.apparel_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
}
?>
