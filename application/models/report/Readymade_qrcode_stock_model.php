<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class Readymade_qrcode_stock_model extends my_model{
    public function __construct(){ parent::__construct('report', 'qrcode_stock'); }
    public function get_record(){
        $record     = [];
        $subsql 	= '';
        $having 	= '';
        if(isset($_REQUEST['_entry_no']) && !empty($_REQUEST['_entry_no'])){
            $subsql .=" AND prmm.prmm_entry_no = '".$_REQUEST['_entry_no']."'";
            $record['filter']['_entry_no']['value'] = $_REQUEST['_entry_no'];
            $record['filter']['_entry_no']['text']  = $_REQUEST['_entry_no'];
        }
        if(isset($_REQUEST['_item_code']) && !empty($_REQUEST['_item_code'])){
            $subsql .=" AND brmm.brmm_item_code = '".$_REQUEST['_item_code']."'";
            $record['filter']['_item_code']['value'] = $_REQUEST['_item_code'];
            $record['filter']['_item_code']['text']  = $_REQUEST['_item_code'];
        }
        if(isset($_REQUEST['_supplier_name']) && !empty($_REQUEST['_supplier_name'])){
            $subsql .=" AND supplier.supplier_name = '".$_REQUEST['_supplier_name']."'";
            $record['filter']['_supplier_name']['value'] = $_REQUEST['_supplier_name'];
            $record['filter']['_supplier_name']['text']  = $_REQUEST['_supplier_name'];
        }
        if(isset($_REQUEST['_product_name']) && !empty($_REQUEST['_product_name'])){
            $subsql .=" AND product.product_name = '".$_REQUEST['_product_name']."'";
            $record['filter']['_product_name']['value'] = $_REQUEST['_product_name'];
            $record['filter']['_product_name']['text']  = $_REQUEST['_product_name'];
        }
        if(isset($_REQUEST['_design_name']) && !empty($_REQUEST['_design_name'])){
            $subsql .=" AND design.design_name = '".$_REQUEST['_design_name']."'";
            $record['filter']['_design_name']['value'] = $_REQUEST['_design_name'];
            $record['filter']['_design_name']['text']  = $_REQUEST['_design_name'];
        }
        if(isset($_REQUEST['_category_name']) && !empty($_REQUEST['_category_name'])){
            $subsql .=" AND category.readymade_category_name = '".$_REQUEST['_category_name']."'";
            $record['filter']['_category_name']['value'] = $_REQUEST['_category_name'];
            $record['filter']['_category_name']['text']  = $_REQUEST['_category_name'];
        }
        if(isset($_REQUEST['_color_name']) && !empty($_REQUEST['_color_name'])){
            $subsql .=" AND color.color_name = '".$_REQUEST['_color_name']."'";
            $record['filter']['_color_name']['value'] = $_REQUEST['_color_name'];
            $record['filter']['_color_name']['text']  = $_REQUEST['_color_name'];
        }
        if(isset($_REQUEST['_size_name']) && !empty($_REQUEST['_size_name'])){
            $subsql .=" AND size.size_name = '".$_REQUEST['_size_name']."'";
            $record['filter']['_size_name']['value'] = $_REQUEST['_size_name'];
            $record['filter']['_size_name']['text']  = $_REQUEST['_size_name'];
        }
        if(isset($_REQUEST['_gender_name']) && !empty($_REQUEST['_gender_name'])){
            $subsql .=" AND gender.gender_name = '".$_REQUEST['_gender_name']."'";
            $record['filter']['_gender_name']['value'] = $_REQUEST['_gender_name'];
            $record['filter']['_gender_name']['text']  = $_REQUEST['_gender_name'];
        }
        if(isset($_REQUEST['_description']) && !empty($_REQUEST['_description'])){
            $subsql .=" AND brmm.brmm_description = '".$_REQUEST['_description']."'";
            $record['filter']['_description']['value'] = $_REQUEST['_description'];
            $record['filter']['_description']['text']  = $_REQUEST['_description'];
        }
        if(isset($_REQUEST['_entry_date_from'])){
            if($_REQUEST['_entry_date_from'] != ''){
                $subsql .=" AND prmm.prmm_entry_date >= '".$_REQUEST['_entry_date_from']."'";
            }
        }
        if(isset($_REQUEST['_entry_date_to'])){
            if($_REQUEST['_entry_date_to'] != ''){
                $subsql .=" AND prmm.prmm_entry_date <= '".$_REQUEST['_entry_date_to']."'";
            }
        }
        if(isset($_REQUEST['_rate_from'])){
            if($_REQUEST['_rate_from'] != ''){
                $subsql .=" AND brmm.brmm_prmt_rate >= ".$_REQUEST['_rate_from'];
            }
        }
        if(isset($_REQUEST['_rate_to'])){
            if($_REQUEST['_rate_to'] != ''){
                $subsql .=" AND brmm.brmm_prmt_rate <= ".$_REQUEST['_rate_to'];
            }
        }
        if(isset($_REQUEST['_mrp_from'])){
            if($_REQUEST['_mrp_from'] != ''){
                $subsql .=" AND brmm.brmm_mrp >= ".$_REQUEST['_mrp_from'];
            }
        }
        if(isset($_REQUEST['_mrp_to'])){
            if($_REQUEST['_mrp_to'] != ''){
                $subsql .=" AND brmm.brmm_mrp <= ".$_REQUEST['_mrp_to'];
            }
        }
        if(isset($_REQUEST['_nod_from'])){
            if($_REQUEST['_nod_from'] != ''){
                $having .=" AND nod >= ".$_REQUEST['_nod_from'];
            }
        }
        if(isset($_REQUEST['_nod_to'])){
            if($_REQUEST['_nod_to'] != ''){
                $having .=" AND nod <= ".$_REQUEST['_nod_to'];
            }
        }
        if(isset($_REQUEST['_prmt_qty_from'])){
            if($_REQUEST['_prmt_qty_from'] != ''){
                $having .=" AND prmt_qty >= ".$_REQUEST['_prmt_qty_from'];
            }
        }
        if(isset($_REQUEST['_prmt_qty_to'])){
            if($_REQUEST['_prmt_qty_to'] != ''){
                $having .=" AND prmt_qty <= ".$_REQUEST['_prmt_qty_to'];
            }
        }
        if(isset($_REQUEST['_ot_qty_from'])){
            if($_REQUEST['_ot_qty_from'] != ''){
                $having .=" AND ot_qty >= ".$_REQUEST['_ot_qty_from'];
            }
        }
        if(isset($_REQUEST['_ot_qty_to'])){
            if($_REQUEST['_ot_qty_to'] != ''){
                $having .=" AND ot_qty <= ".$_REQUEST['_ot_qty_to'];
            }
        }
        if(isset($_REQUEST['_bal_qty_from'])){
            if($_REQUEST['_bal_qty_from'] != ''){
                $having .=" AND bal_qty >= ".$_REQUEST['_bal_qty_from'];
            }
        }
        if(isset($_REQUEST['_bal_qty_to'])){
            if($_REQUEST['_bal_qty_to'] != ''){
                $having .=" AND bal_qty <= ".$_REQUEST['_bal_qty_to'];
            }
        }
        if(isset($_REQUEST['_bal_amt_from'])){
            if($_REQUEST['_bal_amt_from'] != ''){
                $having .=" AND bal_amt >= ".$_REQUEST['_bal_amt_from'];
            }
        }
        if(isset($_REQUEST['_bal_amt_to'])){
            if($_REQUEST['_bal_amt_to'] != ''){
                $having .=" AND bal_amt <= ".$_REQUEST['_bal_amt_to'];
            }
        }
        if(isset($_REQUEST['_prmt_rate_from'])){
            if($_REQUEST['_prmt_rate_from'] != ''){
                $having .=" AND prmt_rate >= ".$_REQUEST['_prmt_rate_from'];
            }
        }
        if(isset($_REQUEST['_prmt_rate_to'])){
            if($_REQUEST['_prmt_rate_to'] != ''){
                $having .=" AND prmt_rate <= ".$_REQUEST['_prmt_rate_to'];
            }
        }
        
        $query="SELECT 
                prmm.prmm_entry_no as entry_no,
                DATE_FORMAT(prmm.prmm_entry_date, '%d-%m-%Y') as entry_date,
                DATEDIFF(CURRENT_DATE(), prmm.prmm_entry_date) as nod,
                brmm.brmm_item_code as item_code,
                IFNULL(UPPER(supplier.supplier_name),'') as supplier_name,
                IFNULL(UPPER(product.product_name),'') as product_name,
                IFNULL(UPPER(design.design_name),'') as design_name,
                IFNULL(UPPER(category.readymade_category_name),'') as category_name,
                IFNULL(UPPER(color.color_name),'') as color_name,
                IFNULL(UPPER(size.size_name),'') as size_name,
                IFNULL(UPPER(gender.gender_name),'') as gender_name,
                IFNULL(UPPER(brmm.brmm_description),'') as description,
                brmm.brmm_prmt_rate as rate,
                brmm.brmm_mrp as mrp,
                (brmm.brmm_prmt_qty) as prmt_qty,
                (brmm.brmm_ot_qty) as ot_qty,
                 ((brmm.brmm_prmt_qty - brmm.brmm_prrt_qty) - (brmm.brmm_ot_qty + brmm.brmm_et_qty)) as bal_qty,
                (((brmm.brmm_prmt_qty - brmm.brmm_prrt_qty) - (brmm.brmm_ot_qty + brmm.brmm_et_qty)) * brmm.brmm_prmt_rate) as bal_amt
                FROM barcode_readymade_master brmm
                INNER JOIN purchase_readymade_master prmm ON(prmm.prmm_id = brmm.brmm_prmm_id)
                INNER JOIN supplier_master supplier ON(supplier.supplier_id = brmm.brmm_supplier_id)
                LEFT JOIN design_master design ON(design.design_id = brmm.brmm_design_id)
                LEFT JOIN product_master product ON(product.product_id = brmm.brmm_product_id)
                INNER JOIN readymade_category_master category ON(category.readymade_category_id = brmm.brmm_readymade_category_id)
                LEFT JOIN color_master color ON(color.color_id = brmm.brmm_color_id)
                LEFT JOIN size_master size ON(size.size_id = brmm.brmm_size_id)
                LEFT JOIN gender_master gender ON(gender.gender_id = brmm.brmm_gender_id)
                WHERE brmm.brmm_delete_status = 0
                $subsql
                GROUP BY brmm.brmm_id
                HAVING 1
                $having
                ORDER BY bal_qty DESC, bal_amt DESC";
        $data = $this->db->query($query)->result_array();
        // echo "<pre>"; print_r($query); exit();
        // echo "<pre>"; print_r($data); exit();
        $record['totals']['rows']   = count($data);
        $record['totals']['prmt_qty'] = 0;
        $record['totals']['ot_qty'] = 0;
        $record['totals']['bal_qty']= 0;
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
                                                'category_name' => $value['category_name'],
                                                'product_name'   => $value['product_name'],
                                                'design_name'   => $value['design_name'],
                                                'color_name' 	=> $value['color_name'],
                                                'size_name'     => $value['size_name'],
                                                'gender_name' 	=> $value['gender_name'],
                                                'description' 	=> $value['description'],
                                                'prmt_qty' 		=> (int)$value['prmt_qty'],
                                                'ot_qty' 		=> (int)$value['ot_qty'],
                                                'rate' 		    => (float)$value['rate'],
                                                'mrp' 		    => (float)$value['mrp'],
                                                'bal_qty' 		=> (int)$value['bal_qty'],
                                                'bal_amt' 		=> (float)$value['bal_amt'],
                                            ]);

                $record['totals']['prmt_qty'] 	= $record['totals']['prmt_qty'] 	+ $value['prmt_qty'];
                $record['totals']['ot_qty'] 	= $record['totals']['ot_qty'] 		+ $value['ot_qty'];
                $record['totals']['bal_qty'] 	= $record['totals']['bal_qty'] 		+ $value['bal_qty'];
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
            $subsql .= " AND (brmm.brmm_item_code LIKE '%".$name."%') ";
        }
        $query="SELECT brmm.brmm_item_code as id, UPPER(brmm.brmm_item_code) as name
                FROM barcode_readymade_master brmm
                WHERE brmm.brmm_delete_status = 0
                $subsql
                GROUP BY brmm.brmm_item_code ASC
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
            $subsql .= " AND (prmm.prmm_entry_no LIKE '%".$name."%') ";
        }
        $query="SELECT prmm.prmm_entry_no as id, UPPER(prmm.prmm_entry_no) as name
                FROM barcode_readymade_master brmm
                INNER JOIN purchase_readymade_master prmm ON(prmm.prmm_id = brmm.brmm_prmm_id)
                WHERE brmm.brmm_delete_status = 0
                AND prmm.prmm_delete_status = 0
                $subsql
                GROUP BY prmm.prmm_entry_no ASC
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
                FROM barcode_readymade_master brmm
                INNER JOIN supplier_master supplier ON(supplier.supplier_id = brmm.brmm_supplier_id)
                WHERE brmm.brmm_delete_status = 0
                $subsql
                GROUP BY supplier.supplier_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
     public function _product_name(){
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
            $subsql .= " AND (product.product_name LIKE '%".$name."%') ";
        }
        $query="SELECT product.product_name as id, UPPER(product.product_name) as name
                FROM barcode_readymade_master brmm
                INNER JOIN product_master product ON(product.product_id = brmm.brmm_product_id)
                WHERE brmm.brmm_delete_status = 0
                $subsql
                GROUP BY product.product_name ASC
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
            $subsql .= " AND (category.readymade_category_name LIKE '%".$name."%') ";
        }
        $query="SELECT category.readymade_category_name as id, UPPER(category.readymade_category_name) as name
                FROM barcode_readymade_master brmm
                INNER JOIN readymade_category_master category ON(category.readymade_category_id = brmm.brmm_readymade_category_id)
                WHERE brmm.brmm_delete_status = 0
                $subsql
                GROUP BY category.readymade_category_name ASC
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
                FROM barcode_readymade_master brmm
                LEFT JOIN design_master design ON(design.design_id = brmm.brmm_design_id)
                WHERE brmm.brmm_delete_status = 0
                $subsql
                GROUP BY design.design_name ASC
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
                FROM barcode_readymade_master brmm
                INNER JOIN color_master color ON(color.color_id = brmm.brmm_color_id)
                WHERE brmm.brmm_delete_status = 0
                $subsql
                GROUP BY color.color_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _size_name(){
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
            $subsql .= " AND (size.size_name LIKE '%".$name."%') ";
        }
        $query="SELECT size.size_name as id, UPPER(size.size_name) as name
                FROM barcode_readymade_master brmm
                INNER JOIN size_master size ON(size.size_id = brmm.brmm_size_id)
                WHERE brmm.brmm_delete_status = 0
                $subsql
                GROUP BY size.size_name ASC
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
            $subsql .= " AND (brmm.brmm_description LIKE '%".$name."%') ";
        }
        $query="SELECT brmm.brmm_description as id, UPPER(brmm.brmm_description) as name
                FROM barcode_readymade_master brmm
                WHERE brmm.brmm_delete_status = 0
                $subsql
                GROUP BY brmm.brmm_description ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
}
?>