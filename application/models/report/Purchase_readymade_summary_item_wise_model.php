<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class purchase_readymade_summary_item_wise_model extends my_model{
    public function __construct(){ parent::__construct('report', 'purchase_readymade_summary_item_wise'); }
    public function get_record($flag = false){
        $record     = [];
        $subsql 	= '';
        $having 	= '';
        $order_by 	= " ORDER BY prmm.prmm_bill_no ASC";
        $page       = isset($_REQUEST['page']) ? $_REQUEST['page'] : 0;
        if(isset($_REQUEST['order_by']) && isset($_REQUEST['sort_by'])){
            if(!empty($_REQUEST['order_by']) && !empty($_REQUEST['sort_by'])){
                $order_by = " ORDER BY ".$_REQUEST['order_by']." ".$_REQUEST['sort_by'];
            }
        }
        if(isset($_REQUEST['_bill_no']) && !empty($_REQUEST['_bill_no'])){
            $subsql .=" AND prmm.prmm_bill_no = '".$_REQUEST['_bill_no']."'";
            $record['filter']['_bill_no']['value'] = $_REQUEST['_bill_no'];
            $record['filter']['_bill_no']['text']  = $_REQUEST['_bill_no'];
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
        if(isset($_REQUEST['_hsn_name']) && !empty($_REQUEST['_hsn_name'])){
            $subsql .=" AND hsn.hsn_name = '".$_REQUEST['_hsn_name']."'";
            $record['filter']['_hsn_name']['value'] = $_REQUEST['_hsn_name'];
            $record['filter']['_hsn_name']['text']  = $_REQUEST['_hsn_name'];
        }
        if(isset($_REQUEST['_bill_date_from'])){
            if($_REQUEST['_bill_date_from'] != ''){
                $subsql .=" AND prmm.prmm_bill_date >= '".$_REQUEST['_bill_date_from']."'";
                $record['filter']['_bill_date_from'] = $_REQUEST['_bill_date_from'];
            }
        }
        if(isset($_REQUEST['_bill_date_to'])){
            if($_REQUEST['_bill_date_to'] != ''){
                $subsql .=" AND prmm.prmm_bill_date <= '".$_REQUEST['_bill_date_to']."'";
                $record['filter']['_bill_date_to'] = $_REQUEST['_bill_date_to'];
            }
        }
        if(isset($_REQUEST['_qty_from'])){
            if($_REQUEST['_qty_from'] != ''){
                $subsql .=" AND prmt.prmt_qty >= ".$_REQUEST['_qty_from'];
                $record['filter']['_qty_from'] = $_REQUEST['_qty_from'];
            }
        }
        if(isset($_REQUEST['_qty_to'])){
            if($_REQUEST['_qty_to'] != ''){
                $subsql .=" AND prmt.prmt_qty <= ".$_REQUEST['_qty_to'];
                $record['filter']['_qty_to'] = $_REQUEST['_qty_to'];
            }
        }
        if(isset($_REQUEST['_qty_from'])){
            if($_REQUEST['_qty_from'] != ''){
                $subsql .=" AND prmt.prmt_qty >= ".$_REQUEST['_qty_from'];
                $record['filter']['_qty_from'] = $_REQUEST['_qty_from'];
            }
        }
        if(isset($_REQUEST['_qty_to'])){
            if($_REQUEST['_qty_to'] != ''){
                $subsql .=" AND prmt.prmt_qty <= ".$_REQUEST['_qty_to'];
                $record['filter']['_qty_to'] = $_REQUEST['_qty_to'];
            }
        }
        if(isset($_REQUEST['_rate_from'])){
            if($_REQUEST['_rate_from'] != ''){
                $subsql .=" AND prmt.prmt_rate >= ".$_REQUEST['_rate_from'];
                $record['filter']['_rate_from'] = $_REQUEST['_rate_from'];
            }
        }
        if(isset($_REQUEST['_rate_to'])){
            if($_REQUEST['_rate_to'] != ''){
                $subsql .=" AND prmt.prmt_rate <= ".$_REQUEST['_rate_to'];
                $record['filter']['_rate_to'] = $_REQUEST['_rate_to'];
            }
        }
        if(isset($_REQUEST['_sub_amt_from'])){
            if($_REQUEST['_sub_amt_from'] != ''){
                $subsql .=" AND prmt.prmt_amt >= ".$_REQUEST['_sub_amt_from'];
                $record['filter']['_sub_amt_from'] = $_REQUEST['_sub_amt_from'];
            }
        }
        if(isset($_REQUEST['_sub_amt_to'])){
            if($_REQUEST['_sub_amt_to'] != ''){
                $subsql .=" AND prmt.prmt_amt <= ".$_REQUEST['_sub_amt_to'];
                $record['filter']['_sub_amt_to'] = $_REQUEST['_sub_amt_to'];
            }
        }
        if(isset($_REQUEST['_disc_amt_from'])){
            if($_REQUEST['_disc_amt_from'] != ''){
                $subsql .=" AND prmt.prmt_disc_amt >= ".$_REQUEST['_disc_amt_from'];
                $record['filter']['_disc_amt_from'] = $_REQUEST['_disc_amt_from'];
            }
        }
        if(isset($_REQUEST['_disc_amt_to'])){
            if($_REQUEST['_disc_amt_to'] != ''){
                $subsql .=" AND prmt.prmt_disc_amt <= ".$_REQUEST['_disc_amt_to'];
                $record['filter']['_disc_amt_to'] = $_REQUEST['_disc_amt_to'];
            }
        }
        if(isset($_REQUEST['_taxable_amt_from'])){
            if($_REQUEST['_taxable_amt_from'] != ''){
                $subsql .=" AND prmt.prmt_taxable_amt >= ".$_REQUEST['_taxable_amt_from'];
                $record['filter']['_taxable_amt_from'] = $_REQUEST['_taxable_amt_from'];
            }
        }
        if(isset($_REQUEST['_taxable_amt_to'])){
            if($_REQUEST['_taxable_amt_to'] != ''){
                $subsql .=" AND prmt.prmt_taxable_amt <= ".$_REQUEST['_taxable_amt_to'];
                $record['filter']['_taxable_amt_to'] = $_REQUEST['_taxable_amt_to'];
            }
        }
        if(isset($_REQUEST['_sgst_amt_from'])){
            if($_REQUEST['_sgst_amt_from'] != ''){
                $subsql .=" AND prmt.prmt_sgst_amt >= ".$_REQUEST['_sgst_amt_from'];
                $record['filter']['_sgst_amt_from'] = $_REQUEST['_sgst_amt_from'];
            }
        }
        if(isset($_REQUEST['_sgst_amt_to'])){
            if($_REQUEST['_sgst_amt_to'] != ''){
                $subsql .=" AND prmt.prmt_sgst_amt <= ".$_REQUEST['_sgst_amt_to'];
                $record['filter']['_sgst_amt_to'] = $_REQUEST['_sgst_amt_to'];
            }
        }
        if(isset($_REQUEST['_cgst_amt_from'])){
            if($_REQUEST['_cgst_amt_from'] != ''){
                $subsql .=" AND prmt.prmt_cgst_amt >= ".$_REQUEST['_cgst_amt_from'];
                $record['filter']['_cgst_amt_from'] = $_REQUEST['_cgst_amt_from'];
            }
        }
        if(isset($_REQUEST['_cgst_amt_to'])){
            if($_REQUEST['_cgst_amt_to'] != ''){
                $subsql .=" AND prmt.prmt_cgst_amt <= ".$_REQUEST['_cgst_amt_to'];
                $record['filter']['_cgst_amt_to'] = $_REQUEST['_cgst_amt_to'];
            }
        }
        if(isset($_REQUEST['_igst_amt_from'])){
            if($_REQUEST['_igst_amt_from'] != ''){
                $subsql .=" AND prmt.prmt_igst_amt >= ".$_REQUEST['_igst_amt_from'];
                $record['filter']['_igst_amt_from'] = $_REQUEST['_igst_amt_from'];
            }
        }
        if(isset($_REQUEST['_igst_amt_to'])){
            if($_REQUEST['_igst_amt_to'] != ''){
                $subsql .=" AND prmt.prmt_igst_amt <= ".$_REQUEST['_igst_amt_to'];
                $record['filter']['_igst_amt_to'] = $_REQUEST['_igst_amt_to'];
            }
        }
        if(isset($_REQUEST['_total_amt_from'])){
            if($_REQUEST['_total_amt_from'] != ''){
                $subsql .=" AND prmt.prmt_total_amt >= ".$_REQUEST['_total_amt_from'];
                $record['filter']['_total_amt_from'] = $_REQUEST['_total_amt_from'];
            }
        }
        if(isset($_REQUEST['_total_amt_to'])){
            if($_REQUEST['_total_amt_to'] != ''){
                $subsql .=" AND prmt.prmt_total_amt <= ".$_REQUEST['_total_amt_to'];
                $record['filter']['_total_amt_to'] = $_REQUEST['_total_amt_to'];
            }
        }
        $query="SELECT 'PURCHAE' as module_name,
                prmm.prmm_bill_no as bill_no,
                prmm.prmm_bill_date as bill_date,
                UPPER(supplier.supplier_name) as supplier_name,
                supplier.supplier_mobile as supplier_mobile,
                prmt.prmt_qty as qty,
                prmt.prmt_rate as rate,
                prmt.prmt_mrp as mrp,
                prmt.prmt_amt as sub_amt,
                prmt.prmt_disc_per as disc_per,
                prmt.prmt_disc_amt as disc_amt,
                prmt.prmt_taxable_amt as taxable_amt,
                prmt.prmt_sgst_per as sgst_per,
                prmt.prmt_sgst_amt as sgst_amt,
                prmt.prmt_cgst_per as cgst_per,
                prmt.prmt_cgst_amt as cgst_amt,
                prmt.prmt_igst_per as igst_per,
                prmt.prmt_igst_amt as igst_amt,
                prmt.prmt_total_amt as total_amt,
                UPPER(product.product_name) as product_name,
                IFNULL(UPPER(design.design_name), '') as design_name,
                IFNULL(UPPER(color.color_name), '') as color_name,
                IFNULL(UPPER(size.size_name), '') as size_name,
                IFNULL(UPPER(gender.gender_name), '') as gender_name,
                design.design_image,
                prmm.prmm_created_at as created_at
                FROM purchase_readymade_master prmm
                INNER JOIN supplier_master supplier ON(supplier.supplier_id = prmm.prmm_supplier_id)
                INNER JOIN purchase_readymade_trans prmt ON(prmt.prmt_prmm_id = prmm.prmm_id)
                INNER JOIN product_master product ON(product.product_id = prmt.prmt_product_id)
                LEFT JOIN design_master design ON(design.design_id = prmt.prmt_design_id)
                LEFT JOIN color_master color ON(color.color_id = prmt.prmt_color_id)
                LEFT JOIN size_master size ON(size.size_id = prmt.prmt_size_id)
                LEFT JOIN gender_master gender ON(gender.gender_id = prmt.prmt_gender_id)
                WHERE prmm.prmm_delete_status = 0
                AND prmt.prmt_delete_status = 0
                $subsql
                $order_by";
        $data = $this->db->query($query)->result_array();
        // echo "<pre>"; print_r($query); exit();
        // echo "<pre>"; print_r($data); exit();
        
        $record['totals']['rows']           = count($data);
        $record['totals']['qty']            = 0;
        $record['totals']['total_qty']      = 0;
        $record['totals']['sub_amt']        = 0;
        $record['totals']['disc_amt']       = 0;
        $record['totals']['taxable_amt']    = 0;
        $record['totals']['sgst_amt']       = 0;
        $record['totals']['cgst_amt']       = 0;
        $record['totals']['igst_amt']       = 0;
        $record['totals']['total_amt']      = 0;
        $record['data']                     = [];
        if(!empty($data)){
            $cnt    = 0;
            $start  = $page * 20;
            $end    = (($page+1) * 20) - 1;
            foreach ($data as $key => $value) {
                if($flag || ($cnt >= $start && $cnt <= $end)){
                    $value['cnt']           = $cnt;
                    $value['page']          = $page;
                    $value['bill_date']    = empty($value['bill_date']) ? '' : date('d-m-Y', strtotime($value['bill_date']));
                    array_push($record['data'], $value);
                }
                $record['totals']['total_qty'] 	    = $record['totals']['total_qty'] 		+ $value['qty'];
                $record['totals']['sub_amt'] 	    = $record['totals']['sub_amt'] 		    + $value['sub_amt'];
                $record['totals']['disc_amt'] 	    = $record['totals']['disc_amt'] 		+ $value['disc_amt'];
                $record['totals']['taxable_amt'] 	= $record['totals']['taxable_amt'] 		+ $value['taxable_amt'];
                $record['totals']['sgst_amt'] 	    = $record['totals']['sgst_amt'] 		+ $value['sgst_amt'];
                $record['totals']['cgst_amt'] 	    = $record['totals']['cgst_amt'] 		+ $value['cgst_amt'];
                $record['totals']['igst_amt'] 	    = $record['totals']['igst_amt'] 		+ $value['igst_amt'];
                $record['totals']['total_amt'] 	    = $record['totals']['total_amt'] 		+ $value['total_amt'];
                $cnt++;
            }
        }
        
        // echo "<pre>"; print_r($record); exit();
        return $record;
    }
    public function _bill_no(){
        $subsql = '';
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
            $subsql .= " AND (prmm.prmm_bill_no LIKE '".$name."%') ";
        }
        $query="SELECT prmm.prmm_bill_no as id , 
                UPPER(prmm.prmm_bill_no) as name 
                FROM purchase_readymade_master prmm 
                WHERE prmm.prmm_delete_status = 0
                $subsql
                GROUP BY prmm.prmm_bill_no ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
   
    public function _supplier_name(){
        $subsql = '';
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
            $subsql .= " AND (supplier.supplier_name LIKE '".$name."%') ";
        }
        $query="SELECT supplier.supplier_name as id , 
                UPPER(supplier.supplier_name) as name 
                FROM purchase_readymade_master prmm 
                INNER JOIN supplier_master supplier ON(supplier.supplier_id = prmm.prmm_supplier_id)
                WHERE prmm.prmm_delete_status = 0
                $subsql
                GROUP BY supplier.supplier_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _product_name(){
        $subsql = '';
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
            $subsql .= " AND (readymade._product_name LIKE '".$name."%') ";
        }
        $query="SELECT product.product_name as id, 
                UPPER(product.product_name) as name 
                FROM purchase_readymade_master prmm 
                INNER JOIN purchase_readymade_trans prmt ON(prmt.prmt_prmm_id = prmm.prmm_id)
                INNER JOIN product_master product ON(product.product_id = prmt.prmt_product_id)
                WHERE prmm.prmm_delete_status = 0
                AND prmt.prmt_delete_status = 0
                $subsql
                GROUP BY product.product_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _design_name(){
        $subsql = '';
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
            $subsql .= " AND (design.design_name LIKE '".$name."%') ";
        }
        $query="SELECT design.design_name as id, 
                UPPER(design.design_name) as name 
                FROM purchase_readymade_master prmm 
                INNER JOIN purchase_readymade_trans prmt ON(prmt.prmt_prmm_id = prmm.prmm_id)
                INNER JOIN design_master design ON(design.design_id = prmt.prmt_design_id)
                WHERE prmm.prmm_delete_status = 0
                AND prmt.prmt_delete_status = 0
                $subsql
                GROUP BY design.design_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _color_name(){
        $subsql = '';
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
            $subsql .= " AND (color.color_name LIKE '".$name."%') ";
        }
        $query="SELECT color.color_name as id, 
                UPPER(color.color_name) as name 
                FROM purchase_readymade_master prmm 
                INNER JOIN purchase_readymade_trans prmt ON(prmt.prmt_prmm_id = prmm.prmm_id)
                INNER JOIN color_master color ON(color.color_id = prmt.prmt_color_id)
                WHERE prmm.prmm_delete_status = 0
                AND prmt.prmt_delete_status = 0
                $subsql
                GROUP BY color.color_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _size_name(){
        $subsql = '';
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
            $subsql .= " AND (size.size_name LIKE '".$name."%') ";
        }
        $query="SELECT size.size_name as id, 
                UPPER(size.size_name) as name 
                FROM purchase_readymade_master prmm 
                INNER JOIN purchase_readymade_trans prmt ON(prmt.prmt_prmm_id = prmm.prmm_id)
                INNER JOIN size_master size ON(size.size_id = prmt.prmt_size_id)
                WHERE prmm.prmm_delete_status = 0
                AND prmt.prmt_delete_status = 0
                $subsql
                GROUP BY size.size_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
   
}
?>