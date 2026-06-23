<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class sku_model extends my_model{
	public function __construct(){ parent::__construct('master', 'sku'); }
	public function isMasterExist($id){
		$data = $this->db->query("SELECT prmt_id FROM purchase_readymade_trans WHERE prmt_sku_id = $id LIMIT 1")->result_array();
		if(!empty($data)) return true;
		
		$data = $this->db->query("SELECT brmm_id FROM barcode_readymade_master WHERE brmm_sku_id = $id LIMIT 1")->result_array();
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
        
        if(isset($_GET['_sku_name']) && !empty($_GET['_sku_name'])){
            $subsql .=" AND sku.sku_name LIKE '%".$_GET['_sku_name']."%'";
            $record['filter']['_sku_name']['value'] = $_GET['_sku_name'];
            $record['filter']['_sku_name']['text'] = $_GET['_sku_name'];
        }
        if(isset($_GET['_apparel_name']) && !empty($_GET['_apparel_name'])){
            $subsql .=" AND apparel.apparel_name LIKE '%".$_GET['_apparel_name']."%'";
            $record['filter']['_apparel_name']['value'] = $_GET['_apparel_name'];
            $record['filter']['_apparel_name']['text'] = $_GET['_apparel_name'];
        }
        if(isset($_GET['_status'])){
            $status = $_GET['_status'] == 2 ? 0 : $_GET['_status'];
            $subsql .=" AND sku.sku_status = ".$status;
            $record['filter']['_status'] = $this->Commonmdl->get_status($_GET['_status']);
        }
        $query="SELECT sku.*,
                UPPER(apparel.apparel_name) as apparel_name,
                UPPER(supplier.supplier_name) as supplier_name
                FROM sku_master sku
                INNER JOIN apparel_master apparel ON(apparel.apparel_id = sku.sku_apparel_id)
                INNER JOIN supplier_master supplier ON(supplier.supplier_id = sku.sku_supplier_id)
                WHERE sku.sku_delete_status = 0
                $subsql
                ORDER BY sku.sku_id DESC
                $limit
                $ofset";
        // echo "<pre>"; print_r($query); exit;
        if($wantCount){
            return $this->db->query($query)->num_rows();
        }
        $record['data'] = $this->db->query($query)->result_array();
        if(!empty($record['data'])){
            foreach ($record['data'] as $key => $value) {
                $record['data'][$key]['isExist'] = $this->isMasterExist($value['sku_id']);
            }
        }
        return $record;
    }
    public function get_data_for_add(){
        $record['sku_uuid'] = $_SESSION['user_id'].''.time();
        return $record;
    }
    public function get_data_for_edit($id){
        $query="SELECT sku.*,
                UPPER(apparel.apparel_name) as apparel_name,
                UPPER(supplier.supplier_name) as supplier_name
                FROM sku_master sku
                INNER JOIN apparel_master apparel ON(apparel.apparel_id = sku.sku_apparel_id)
                INNER JOIN supplier_master supplier ON(supplier.supplier_id = sku.sku_supplier_id)
                WHERE sku.sku_id = $id
                AND sku.sku_delete_status = 0";
        $record['master_data'] = $this->db->query($query)->result_array();
        if(!empty($record['master_data'])){
            $record['master_data'][0]['isExist'] = $this->isMasterExist($id);
        }
        // echo "<pre>"; print_r($record); exit;
        return $record;
    }
    public function get_name($term, $id){
        $query="SELECT UPPER(".$term."_name) as name FROM ".$term."_master WHERE ".$term."_id = $id";
        $data = $this->db->query($query)->result_array();
        return empty($data) ? '' : $data[0]['name'];
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
            $subsql .= " AND (sku.sku_name LIKE '".$name."%' OR apparel.apparel_name LIKE '".$name."%') ";
        }
        if(isset($_GET['param']) && !empty($_GET['param'])){
            $param 	= $_GET['param'];
            $subsql .= " AND (sku.sku_status = $param) ";
        }
        $query="SELECT 
                sku.sku_id as id, 
                CONCAT(UPPER(sku.sku_name), ' - ', UPPER(apparel.apparel_name)) as name
                FROM sku_master sku
                INNER JOIN apparel_master apparel ON(apparel.apparel_id = sku.sku_apparel_id)
                WHERE sku.sku_delete_status = 0
                $subsql
                GROUP BY sku.sku_id 
                ORDER BY sku.sku_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _sku_name(){
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
            $subsql .= " AND (sku.sku_name LIKE '".$name."%') ";
        }
        if(isset($_GET['param']) && !empty($_GET['param'])){
            $param 	= $_GET['param'];
            $subsql .= " AND (sku.sku_status = $param) ";
        }
        $query="SELECT 
                sku.sku_name as id, 
                UPPER(sku.sku_name) as name
                FROM sku_master sku
                WHERE sku.sku_delete_status = 0
                $subsql
                GROUP BY sku.sku_name ASC
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
            $subsql .= " AND (apparel.apparel_name LIKE '".$name."%') ";
        }
        if(isset($_GET['param']) && !empty($_GET['param'])){
            $param 	= $_GET['param'];
            $subsql .= " AND (apparel.apparel_status = $param) ";
        }
        $query="SELECT 
                apparel.apparel_name as id, 
                UPPER(apparel.apparel_name) as name
                FROM sku_master sku
                INNER JOIN apparel_master apparel ON(apparel.apparel_id = sku.sku_apparel_id)
                WHERE sku.sku_delete_status = 0
                $subsql
                GROUP BY apparel.apparel_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
}
?>
