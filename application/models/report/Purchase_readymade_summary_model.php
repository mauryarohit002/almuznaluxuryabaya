<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class purchase_readymade_summary_model extends my_model{
    public function __construct(){ parent::__construct('report', 'purchase_readymade_summary'); }
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
        if(isset($_REQUEST['_supplier_mobile']) && !empty($_REQUEST['_supplier_mobile'])){
            $subsql .=" AND supplier.supplier_mobile = '".$_REQUEST['_supplier_mobile']."'";
            $record['filter']['_supplier_mobile']['value'] = $_REQUEST['_supplier_mobile'];
            $record['filter']['_supplier_mobile']['text']  = $_REQUEST['_supplier_mobile'];
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
        if(isset($_REQUEST['_total_qty_from'])){
            if($_REQUEST['_total_qty_from'] != ''){
                $subsql .=" AND prmm.prmm_total_qty >= ".$_REQUEST['_total_qty_from'];
                $record['filter']['_total_qty_from'] = $_REQUEST['_total_qty_from'];
            }
        }
        if(isset($_REQUEST['_total_qty_to'])){
            if($_REQUEST['_total_qty_to'] != ''){
                $subsql .=" AND prmm.prmm_total_qty <= ".$_REQUEST['_total_qty_to'];
                $record['filter']['_total_qty_to'] = $_REQUEST['_total_qty_to'];
            }
        }
        if(isset($_REQUEST['_sub_amt_from'])){
            if($_REQUEST['_sub_amt_from'] != ''){
                $subsql .=" AND prmm.prmm_sub_amt >= ".$_REQUEST['_sub_amt_from'];
                $record['filter']['_sub_amt_from'] = $_REQUEST['_sub_amt_from'];
            }
        }
        if(isset($_REQUEST['_sub_amt_to'])){
            if($_REQUEST['_sub_amt_to'] != ''){
                $subsql .=" AND prmm.prmm_sub_amt <= ".$_REQUEST['_sub_amt_to'];
                $record['filter']['_sub_amt_to'] = $_REQUEST['_sub_amt_to'];
            }
        }
        if(isset($_REQUEST['_disc_amt_from'])){
            if($_REQUEST['_disc_amt_from'] != ''){
                $subsql .=" AND prmm.prmm_disc_amt >= ".$_REQUEST['_disc_amt_from'];
                $record['filter']['_disc_amt_from'] = $_REQUEST['_disc_amt_from'];
            }
        }
        if(isset($_REQUEST['_disc_amt_to'])){
            if($_REQUEST['_disc_amt_to'] != ''){
                $subsql .=" AND prmm.prmm_disc_amt <= ".$_REQUEST['_disc_amt_to'];
                $record['filter']['_disc_amt_to'] = $_REQUEST['_disc_amt_to'];
            }
        }
        if(isset($_REQUEST['_taxable_amt_from'])){
            if($_REQUEST['_taxable_amt_from'] != ''){
                $subsql .=" AND prmm.prmm_taxable_amt >= ".$_REQUEST['_taxable_amt_from'];
                $record['filter']['_taxable_amt_from'] = $_REQUEST['_taxable_amt_from'];
            }
        }
        if(isset($_REQUEST['_taxable_amt_to'])){
            if($_REQUEST['_taxable_amt_to'] != ''){
                $subsql .=" AND prmm.prmm_taxable_amt <= ".$_REQUEST['_taxable_amt_to'];
                $record['filter']['_taxable_amt_to'] = $_REQUEST['_taxable_amt_to'];
            }
        }
        if(isset($_REQUEST['_sgst_amt_from'])){
            if($_REQUEST['_sgst_amt_from'] != ''){
                $subsql .=" AND prmm.prmm_sgst_amt >= ".$_REQUEST['_sgst_amt_from'];
                $record['filter']['_sgst_amt_from'] = $_REQUEST['_sgst_amt_from'];
            }
        }
        if(isset($_REQUEST['_sgst_amt_to'])){
            if($_REQUEST['_sgst_amt_to'] != ''){
                $subsql .=" AND prmm.prmm_sgst_amt <= ".$_REQUEST['_sgst_amt_to'];
                $record['filter']['_sgst_amt_to'] = $_REQUEST['_sgst_amt_to'];
            }
        }
        if(isset($_REQUEST['_cgst_amt_from'])){
            if($_REQUEST['_cgst_amt_from'] != ''){
                $subsql .=" AND prmm.prmm_cgst_amt >= ".$_REQUEST['_cgst_amt_from'];
                $record['filter']['_cgst_amt_from'] = $_REQUEST['_cgst_amt_from'];
            }
        }
        if(isset($_REQUEST['_cgst_amt_to'])){
            if($_REQUEST['_cgst_amt_to'] != ''){
                $subsql .=" AND prmm.prmm_cgst_amt <= ".$_REQUEST['_cgst_amt_to'];
                $record['filter']['_cgst_amt_to'] = $_REQUEST['_cgst_amt_to'];
            }
        }
        if(isset($_REQUEST['_igst_amt_from'])){
            if($_REQUEST['_igst_amt_from'] != ''){
                $subsql .=" AND prmm.prmm_igst_amt >= ".$_REQUEST['_igst_amt_from'];
                $record['filter']['_igst_amt_from'] = $_REQUEST['_igst_amt_from'];
            }
        }
        if(isset($_REQUEST['_igst_amt_to'])){
            if($_REQUEST['_igst_amt_to'] != ''){
                $subsql .=" AND prmm.prmm_igst_amt <= ".$_REQUEST['_igst_amt_to'];
                $record['filter']['_igst_amt_to'] = $_REQUEST['_igst_amt_to'];
            }
        }
        if(isset($_REQUEST['_bill_disc_from'])){
            if($_REQUEST['_bill_disc_from'] != ''){
                $subsql .=" AND prmm.prmm_bill_disc_amt >= ".$_REQUEST['_bill_disc_from'];
                $record['filter']['_bill_disc_from'] = $_REQUEST['_bill_disc_from'];
            }
        }
        if(isset($_REQUEST['_bill_disc_to'])){
            if($_REQUEST['_bill_disc_to'] != ''){
                $subsql .=" AND prmm.prmm_bill_disc_amt <= ".$_REQUEST['_bill_disc_to'];
                $record['filter']['_bill_disc_to'] = $_REQUEST['_bill_disc_to'];
            }
        }
        if(isset($_REQUEST['_total_amt_from'])){
            if($_REQUEST['_total_amt_from'] != ''){
                $subsql .=" AND prmm.prmm_total_amt >= ".$_REQUEST['_total_amt_from'];
                $record['filter']['_total_amt_from'] = $_REQUEST['_total_amt_from'];
            }
        }
        if(isset($_REQUEST['_total_amt_to'])){
            if($_REQUEST['_total_amt_to'] != ''){
                $subsql .=" AND prmm.prmm_total_amt <= ".$_REQUEST['_total_amt_to'];
                $record['filter']['_total_amt_to'] = $_REQUEST['_total_amt_to'];
            }
        }
        $query="SELECT 'PURCHASE' as module_name,
                prmm.prmm_bill_no as bill_no,
                prmm.prmm_bill_date as bill_date,
                UPPER(supplier.supplier_name) as supplier_name,
                supplier.supplier_mobile as supplier_mobile,
                prmm.prmm_total_qty as total_qty,
                prmm.prmm_sub_amt as sub_amt,
                prmm.prmm_disc_amt as disc_amt,
                prmm.prmm_taxable_amt as taxable_amt,
                prmm.prmm_sgst_amt as sgst_amt,
                prmm.prmm_cgst_amt as cgst_amt,
                prmm.prmm_igst_amt as igst_amt,
                prmm.prmm_bill_disc_per as bill_disc_per,
                prmm.prmm_bill_disc_amt as bill_disc_amt,
                prmm.prmm_round_off as round_off,
                prmm.prmm_total_amt as total_amt,
                prmm.prmm_created_at as created_at
                FROM purchase_readymade_master prmm
                INNER JOIN supplier_master supplier ON(supplier.supplier_id = prmm.prmm_supplier_id)
                WHERE prmm.prmm_delete_status = 0
                $subsql
                $order_by";
        $data = $this->db->query($query)->result_array();
        // echo "<pre>"; print_r($query); exit();
        // echo "<pre>"; print_r($data); exit();
        
        $record['totals']['rows']           = count($data);
        $record['totals']['total_qty']      = 0;
        $record['totals']['sub_amt']        = 0;
        $record['totals']['disc_amt']       = 0;
        $record['totals']['taxable_amt']    = 0;
        $record['totals']['sgst_amt']       = 0;
        $record['totals']['cgst_amt']       = 0;
        $record['totals']['igst_amt']       = 0;
        $record['totals']['bill_disc_amt']  = 0;
        $record['totals']['round_off']      = 0;
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
                $record['totals']['total_qty'] 	    = $record['totals']['total_qty'] 		+ $value['total_qty'];
                $record['totals']['sub_amt'] 	    = $record['totals']['sub_amt'] 		    + $value['sub_amt'];
                $record['totals']['disc_amt'] 	    = $record['totals']['disc_amt'] 		+ $value['disc_amt'];
                $record['totals']['taxable_amt'] 	= $record['totals']['taxable_amt'] 		+ $value['taxable_amt'];
                $record['totals']['sgst_amt'] 	    = $record['totals']['sgst_amt'] 		+ $value['sgst_amt'];
                $record['totals']['cgst_amt'] 	    = $record['totals']['cgst_amt'] 		+ $value['cgst_amt'];
                $record['totals']['igst_amt'] 	    = $record['totals']['igst_amt'] 		+ $value['igst_amt'];
                $record['totals']['bill_disc_amt'] 	= $record['totals']['bill_disc_amt'] 	+ $value['bill_disc_amt'];
                $record['totals']['round_off'] 	    = $record['totals']['round_off'] 		+ $value['round_off'];
                $record['totals']['total_amt'] 	    = $record['totals']['total_amt'] 		+ $value['total_amt'];
                $cnt++;
            }
        }
        
        // echo "<pre>"; print_r($record); exit();
        return $record;
    }
    public function get_xml_data(){
        $record     = [];
        $subsql 	= '';
        $having 	= '';
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
        if(isset($_REQUEST['_supplier_mobile']) && !empty($_REQUEST['_supplier_mobile'])){
            $subsql .=" AND supplier.supplier_mobile = '".$_REQUEST['_supplier_mobile']."'";
            $record['filter']['_supplier_mobile']['value'] = $_REQUEST['_supplier_mobile'];
            $record['filter']['_supplier_mobile']['text']  = $_REQUEST['_supplier_mobile'];
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
        if(isset($_REQUEST['_total_qty_from'])){
            if($_REQUEST['_total_qty_from'] != ''){
                $subsql .=" AND prmm.prmm_total_qty >= ".$_REQUEST['_total_qty_from'];
                $record['filter']['_total_qty_from'] = $_REQUEST['_total_qty_from'];
            }
        }
        if(isset($_REQUEST['_total_qty_to'])){
            if($_REQUEST['_total_qty_to'] != ''){
                $subsql .=" AND prmm.prmm_total_qty <= ".$_REQUEST['_total_qty_to'];
                $record['filter']['_total_qty_to'] = $_REQUEST['_total_qty_to'];
            }
        }
        if(isset($_REQUEST['_sub_amt_from'])){
            if($_REQUEST['_sub_amt_from'] != ''){
                $subsql .=" AND prmm.prmm_sub_amt >= ".$_REQUEST['_sub_amt_from'];
                $record['filter']['_sub_amt_from'] = $_REQUEST['_sub_amt_from'];
            }
        }
        if(isset($_REQUEST['_sub_amt_to'])){
            if($_REQUEST['_sub_amt_to'] != ''){
                $subsql .=" AND prmm.prmm_sub_amt <= ".$_REQUEST['_sub_amt_to'];
                $record['filter']['_sub_amt_to'] = $_REQUEST['_sub_amt_to'];
            }
        }
        if(isset($_REQUEST['_disc_amt_from'])){
            if($_REQUEST['_disc_amt_from'] != ''){
                $subsql .=" AND prmm.prmm_disc_amt >= ".$_REQUEST['_disc_amt_from'];
                $record['filter']['_disc_amt_from'] = $_REQUEST['_disc_amt_from'];
            }
        }
        if(isset($_REQUEST['_disc_amt_to'])){
            if($_REQUEST['_disc_amt_to'] != ''){
                $subsql .=" AND prmm.prmm_disc_amt <= ".$_REQUEST['_disc_amt_to'];
                $record['filter']['_disc_amt_to'] = $_REQUEST['_disc_amt_to'];
            }
        }
        if(isset($_REQUEST['_taxable_amt_from'])){
            if($_REQUEST['_taxable_amt_from'] != ''){
                $subsql .=" AND prmm.prmm_taxable_amt >= ".$_REQUEST['_taxable_amt_from'];
                $record['filter']['_taxable_amt_from'] = $_REQUEST['_taxable_amt_from'];
            }
        }
        if(isset($_REQUEST['_taxable_amt_to'])){
            if($_REQUEST['_taxable_amt_to'] != ''){
                $subsql .=" AND prmm.prmm_taxable_amt <= ".$_REQUEST['_taxable_amt_to'];
                $record['filter']['_taxable_amt_to'] = $_REQUEST['_taxable_amt_to'];
            }
        }
        if(isset($_REQUEST['_sgst_amt_from'])){
            if($_REQUEST['_sgst_amt_from'] != ''){
                $subsql .=" AND prmm.prmm_sgst_amt >= ".$_REQUEST['_sgst_amt_from'];
                $record['filter']['_sgst_amt_from'] = $_REQUEST['_sgst_amt_from'];
            }
        }
        if(isset($_REQUEST['_sgst_amt_to'])){
            if($_REQUEST['_sgst_amt_to'] != ''){
                $subsql .=" AND prmm.prmm_sgst_amt <= ".$_REQUEST['_sgst_amt_to'];
                $record['filter']['_sgst_amt_to'] = $_REQUEST['_sgst_amt_to'];
            }
        }
        if(isset($_REQUEST['_cgst_amt_from'])){
            if($_REQUEST['_cgst_amt_from'] != ''){
                $subsql .=" AND prmm.prmm_cgst_amt >= ".$_REQUEST['_cgst_amt_from'];
                $record['filter']['_cgst_amt_from'] = $_REQUEST['_cgst_amt_from'];
            }
        }
        if(isset($_REQUEST['_cgst_amt_to'])){
            if($_REQUEST['_cgst_amt_to'] != ''){
                $subsql .=" AND prmm.prmm_cgst_amt <= ".$_REQUEST['_cgst_amt_to'];
                $record['filter']['_cgst_amt_to'] = $_REQUEST['_cgst_amt_to'];
            }
        }
        if(isset($_REQUEST['_igst_amt_from'])){
            if($_REQUEST['_igst_amt_from'] != ''){
                $subsql .=" AND prmm.prmm_igst_amt >= ".$_REQUEST['_igst_amt_from'];
                $record['filter']['_igst_amt_from'] = $_REQUEST['_igst_amt_from'];
            }
        }
        if(isset($_REQUEST['_igst_amt_to'])){
            if($_REQUEST['_igst_amt_to'] != ''){
                $subsql .=" AND prmm.prmm_igst_amt <= ".$_REQUEST['_igst_amt_to'];
                $record['filter']['_igst_amt_to'] = $_REQUEST['_igst_amt_to'];
            }
        }
        if(isset($_REQUEST['_bill_disc_from'])){
            if($_REQUEST['_bill_disc_from'] != ''){
                $subsql .=" AND prmm.prmm_bill_disc_amt >= ".$_REQUEST['_bill_disc_from'];
                $record['filter']['_bill_disc_from'] = $_REQUEST['_bill_disc_from'];
            }
        }
        if(isset($_REQUEST['_bill_disc_to'])){
            if($_REQUEST['_bill_disc_to'] != ''){
                $subsql .=" AND prmm.prmm_bill_disc_amt <= ".$_REQUEST['_bill_disc_to'];
                $record['filter']['_bill_disc_to'] = $_REQUEST['_bill_disc_to'];
            }
        }
        if(isset($_REQUEST['_total_amt_from'])){
            if($_REQUEST['_total_amt_from'] != ''){
                $subsql .=" AND prmm.prmm_total_amt >= ".$_REQUEST['_total_amt_from'];
                $record['filter']['_total_amt_from'] = $_REQUEST['_total_amt_from'];
            }
        }
        if(isset($_REQUEST['_total_amt_to'])){
            if($_REQUEST['_total_amt_to'] != ''){
                $subsql .=" AND prmm.prmm_total_amt <= ".$_REQUEST['_total_amt_to'];
                $record['filter']['_total_amt_to'] = $_REQUEST['_total_amt_to'];
            }
        }
        $query="SELECT prmm.prmm_id as id,
                prmm.prmm_bill_no as bill_no,
                DATE_FORMAT(prmm.prmm_bill_date, '%Y%m%d') as bill_date,
                UPPER(supplier.supplier_name) as supplier_name,
                supplier.supplier_mobile as supplier_mobile,
                supplier.supplier_gst_no as gst_no,
                supplier.supplier_pincode as pincode,
                prmm.prmm_total_qty as total_qty,
                prmm.prmm_sub_amt as sub_amt,
                prmm.prmm_disc_amt as disc_amt,
                prmm.prmm_taxable_amt as taxable_amt,
                prmm.prmm_sgst_amt as sgst_amt,
                prmm.prmm_cgst_amt as cgst_amt,
                prmm.prmm_igst_amt as igst_amt,
                prmm.prmm_bill_disc_per as bill_disc_per,
                prmm.prmm_bill_disc_amt as bill_disc_amt,
                prmm.prmm_round_off as round_off,
                prmm.prmm_total_amt as total_amt,
                prmm.prmm_created_at as created_at
                FROM purchase_readymade_master prmm
                INNER JOIN supplier_master supplier ON(supplier.supplier_id = prmm.prmm_supplier_id)
                WHERE prmm.prmm_delete_status = 0
                $subsql
                ORDER BY created_at DESC";
        $record['data'] = $this->db->query($query)->result_array();
        // echo "<pre>"; print_r($query); exit();
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
    public function _supplier_mobile(){
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
            $subsql .= " AND (supplier.supplier_mobile LIKE '".$name."%') ";
        }
        $query="SELECT supplier.supplier_mobile as id, 
                UPPER(supplier.supplier_mobile) as name 
                FROM purchase_readymade_master prmm 
                INNER JOIN supplier_master supplier ON(supplier.supplier_id = prmm.prmm_supplier_id)
                WHERE prmm.prmm_delete_status = 0
                $subsql
                GROUP BY supplier.supplier_mobile ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
}
?>