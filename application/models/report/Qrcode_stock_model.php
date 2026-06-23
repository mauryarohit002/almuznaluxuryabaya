<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class qrcode_stock_model extends my_model{
    public function __construct(){ parent::__construct('report', 'qrcode_stock'); }
    public function get_record(){
        $record     = [];
        $subsql 	= '';
        $having 	= '';
        if(isset($_REQUEST['_entry_no']) && !empty($_REQUEST['_entry_no'])){
            $subsql .=" AND pm.pm_entry_no = '".$_REQUEST['_entry_no']."'";
            $record['filter']['_entry_no']['value'] = $_REQUEST['_entry_no'];
            $record['filter']['_entry_no']['text']  = $_REQUEST['_entry_no'];
        }
        if(isset($_REQUEST['_item_code']) && !empty($_REQUEST['_item_code'])){
            $subsql .=" AND bm.bm_item_code = '".$_REQUEST['_item_code']."'";
            $record['filter']['_item_code']['value'] = $_REQUEST['_item_code'];
            $record['filter']['_item_code']['text']  = $_REQUEST['_item_code'];
        }
        if(isset($_REQUEST['_supplier_name']) && !empty($_REQUEST['_supplier_name'])){
            $subsql .=" AND supplier.supplier_name = '".$_REQUEST['_supplier_name']."'";
            $record['filter']['_supplier_name']['value'] = $_REQUEST['_supplier_name'];
            $record['filter']['_supplier_name']['text']  = $_REQUEST['_supplier_name'];
        }
        if(isset($_REQUEST['_fabric_name']) && !empty($_REQUEST['_fabric_name'])){
            $subsql .=" AND fabric.fabric_name = '".$_REQUEST['_fabric_name']."'";
            $record['filter']['_fabric_name']['value'] = $_REQUEST['_fabric_name'];
            $record['filter']['_fabric_name']['text']  = $_REQUEST['_fabric_name'];
        }
        if(isset($_REQUEST['_design_name']) && !empty($_REQUEST['_design_name'])){
            $subsql .=" AND design.design_name = '".$_REQUEST['_design_name']."'";
            $record['filter']['_design_name']['value'] = $_REQUEST['_design_name'];
            $record['filter']['_design_name']['text']  = $_REQUEST['_design_name'];
        }
        if(isset($_REQUEST['_color_name']) && !empty($_REQUEST['_color_name'])){
            $subsql .=" AND color.color_name = '".$_REQUEST['_color_name']."'";
            $record['filter']['_color_name']['value'] = $_REQUEST['_color_name'];
            $record['filter']['_color_name']['text']  = $_REQUEST['_color_name'];
        }
        if(isset($_REQUEST['_category_name']) && !empty($_REQUEST['_category_name'])){
            $subsql .=" AND category.category_name = '".$_REQUEST['_category_name']."'";
            $record['filter']['_category_name']['value'] = $_REQUEST['_category_name'];
            $record['filter']['_category_name']['text']  = $_REQUEST['_category_name'];
        }
        if(isset($_REQUEST['_width_name']) && !empty($_REQUEST['_width_name'])){
            $subsql .=" AND width.width_name = '".$_REQUEST['_width_name']."'";
            $record['filter']['_width_name']['value'] = $_REQUEST['_width_name'];
            $record['filter']['_width_name']['text']  = $_REQUEST['_width_name'];
        }
        if(isset($_REQUEST['_description']) && !empty($_REQUEST['_description'])){
            $subsql .=" AND bm.bm_description = '".$_REQUEST['_description']."'";
            $record['filter']['_description']['value'] = $_REQUEST['_description'];
            $record['filter']['_description']['text']  = $_REQUEST['_description'];
        }
        if(isset($_REQUEST['_entry_date_from'])){
            if($_REQUEST['_entry_date_from'] != ''){
                $subsql .=" AND pm.pm_entry_date >= '".$_REQUEST['_entry_date_from']."'";
            }
        }
        if(isset($_REQUEST['_entry_date_to'])){
            if($_REQUEST['_entry_date_to'] != ''){
                $subsql .=" AND pm.pm_entry_date <= '".$_REQUEST['_entry_date_to']."'";
            }
        }
        
        $query="SELECT 
                pm.pm_entry_no as entry_no,
                DATE_FORMAT(pm.pm_entry_date, '%d-%m-%Y') as entry_date,
                DATEDIFF(CURRENT_DATE(), pm.pm_entry_date) as nod,
                bm.bm_item_code as item_code,
                UPPER(supplier.supplier_name) as supplier_name,
                UPPER(fabric.fabric_name) as fabric_name,
                UPPER(design.design_name) as design_name,
                UPPER(category.category_name) as category_name,
                UPPER(color.color_name) as color_name,
                UPPER(width.width_name) as width_name,
                UPPER(bm.bm_description) as description,
                bm.bm_pt_rate as rate,
                bm.bm_mrp as mrp,
                (bm.bm_pt_mtr) as pt_mtr, 
                (bm.bm_ot_mtr) as ot_mtr,
                ((bm.bm_pt_mtr-bm.bm_prt_mtr) - bm.bm_ot_mtr) as bal_mtr,
                (((bm.bm_pt_mtr-bm.bm_prt_mtr) - bm.bm_ot_mtr) * bm.bm_pt_rate) as bal_amt
                FROM barcode_master bm
                INNER JOIN purchase_master pm ON(pm.pm_id = bm.bm_pm_id)
                INNER JOIN supplier_master supplier ON(supplier.supplier_id = bm.bm_supplier_id)
                INNER JOIN fabric_master fabric ON(fabric.fabric_id = bm.bm_fabric_id)
                LEFT JOIN design_master design ON(design.design_id = bm.bm_design_id)
                LEFT JOIN category_master category ON(category.category_id = bm.bm_category_id)
                LEFT JOIN color_master color ON(color.color_id = bm.bm_color_id)
                LEFT JOIN width_master width ON(width.width_id = bm.bm_width_id)
                WHERE bm.bm_delete_status = 0
                $subsql
                GROUP BY bm.bm_id
                HAVING 1
                $having
                ORDER BY bal_mtr DESC, bal_amt DESC";
        $data = $this->db->query($query)->result_array();
        // echo "<pre>"; print_r($query); exit();
        // echo "<pre>"; print_r($data); exit();
        $record['totals']['rows']   = count($data);
        $record['totals']['pt_mtr'] = 0;
        $record['totals']['ot_mtr'] = 0;
        $record['totals']['bal_mtr']= 0;
        $record['totals']['bal_amt']= 0;
        $record['data']             = [];
        if(!empty($data)){
            foreach ($data as $key => $value) {
                array_push($record['data'], [
                                                'entry_no'      => $value['entry_no'],
                                                'entry_date'    => $value['entry_date'],
                                                'nod'           => $value['nod'],
                                                'item_code'     => $value['item_code'],
                                                'supplier_name' => $value['supplier_name'],
                                                'fabric_name'   => $value['fabric_name'],
                                                'design_name'   => $value['design_name'],
                                                'category_name' 	=> $value['category_name'],
                                                'color_name'    => $value['color_name'],
                                                'width_name' 	=> $value['width_name'],
                                                'description' 	=> $value['description'],
                                                'pt_mtr' 		=> (float)$value['pt_mtr'],
                                                'ot_mtr' 		=> (float)$value['ot_mtr'],
                                                'rate' 		    => (float)$value['rate'],
                                                'mrp' 		    => (float)$value['mrp'],
                                                'bal_mtr' 		=> (float)$value['bal_mtr'],
                                                'bal_amt' 		=> (float)$value['bal_amt'],
                                            ]);

                $record['totals']['pt_mtr'] 	= $record['totals']['pt_mtr'] 		+ $value['pt_mtr'];
                $record['totals']['ot_mtr'] 	= $record['totals']['ot_mtr'] 		+ $value['ot_mtr'];
                $record['totals']['bal_mtr'] 	= $record['totals']['bal_mtr'] 		+ $value['bal_mtr'];
                $record['totals']['bal_amt'] 	= $record['totals']['bal_amt'] 		+ $value['bal_amt'];
            }
        }
        // echo "<pre>"; print_r($record); exit();
        return $record;
    }
    public function _item_code(){
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
            $subsql .= " AND (bm.bm_item_code LIKE '%".$name."%') ";
        }
        $query="SELECT bm.bm_item_code as id, UPPER(bm.bm_item_code) as name
                FROM barcode_master bm
                WHERE bm.bm_delete_status = 0
                $subsql
                GROUP BY bm.bm_item_code ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _entry_no(){
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
            $subsql .= " AND (pm.pm_entry_no LIKE '%".$name."%') ";
        }
        $query="SELECT pm.pm_entry_no as id, UPPER(pm.pm_entry_no) as name
                FROM barcode_master bm
                INNER JOIN purchase_master pm ON(pm.pm_id = bm.bm_pm_id)
                WHERE bm.bm_delete_status = 0
                AND pm.pm_delete_status = 0
                $subsql
                GROUP BY pm.pm_entry_no ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _supplier_name(){
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
            $subsql .= " AND (supplier.supplier_name LIKE '%".$name."%') ";
        }
        $query="SELECT supplier.supplier_name as id, UPPER(supplier.supplier_name) as name
                FROM barcode_master bm
                INNER JOIN supplier_master supplier ON(supplier.supplier_id = bm.bm_supplier_id)
                WHERE bm.bm_delete_status = 0
                $subsql
                GROUP BY supplier.supplier_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _fabric_name(){
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
            $subsql .= " AND (fabric.fabric_name LIKE '%".$name."%') ";
        }
        $query="SELECT fabric.fabric_name as id, UPPER(fabric.fabric_name) as name
                FROM barcode_master bm
                INNER JOIN fabric_master fabric ON(fabric.fabric_id = bm.bm_fabric_id)
                WHERE bm.bm_delete_status = 0
                $subsql
                GROUP BY fabric.fabric_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _design_name(){
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
            $subsql .= " AND (design.design_name LIKE '%".$name."%') ";
        }
        $query="SELECT design.design_name as id, UPPER(design.design_name) as name
                FROM barcode_master bm
                LEFT JOIN design_master design ON(design.design_id = bm.bm_design_id)
                WHERE bm.bm_delete_status = 0
                $subsql
                GROUP BY design.design_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _category_name(){
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
            $subsql .= " AND (category.category_name LIKE '%".$name."%') ";
        }
        $query="SELECT category.category_name as id, UPPER(category.category_name) as name
                FROM barcode_master bm
                INNER JOIN category_master category ON(category.category_id = bm.bm_category_id)
                WHERE bm.bm_delete_status = 0
                $subsql
                GROUP BY category.category_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _color_name(){
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
            $subsql .= " AND (color.color_name LIKE '%".$name."%') ";
        }
        $query="SELECT color.color_name as id, UPPER(color.color_name) as name
                FROM barcode_master bm
                INNER JOIN color_master color ON(color.color_id = bm.bm_color_id)
                WHERE bm.bm_delete_status = 0
                $subsql
                GROUP BY color.color_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _width_name(){
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
            $subsql .= " AND (width.width_name LIKE '%".$name."%') ";
        }
        $query="SELECT width.width_name as id, UPPER(width.width_name) as name
                FROM barcode_master bm
                INNER JOIN width_master width ON(width.width_id = bm.bm_width_id)
                WHERE bm.bm_delete_status = 0
                $subsql
                GROUP BY width.width_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _description(){
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
            $subsql .= " AND (bm.bm_description LIKE '%".$name."%') ";
        }
        $query="SELECT bm.bm_description as id, UPPER(bm.bm_description) as name
                FROM barcode_master bm
                WHERE bm.bm_delete_status = 0
                $subsql
                GROUP BY bm.bm_description ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
}
?>