<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class purchase_summary_model extends my_model{
    public function __construct(){ parent::__construct('report', 'purchase_summary'); }
    public function get_record(){
        $record     = [];
        $subsql 	= '';
        $having 	= '';
        if(isset($_REQUEST['_entry_no']) && !empty($_REQUEST['_entry_no'])){
            $subsql .=" AND pm.pm_entry_no = '".$_REQUEST['_entry_no']."'";
            $record['filter']['_entry_no']['value'] = $_REQUEST['_entry_no'];
            $record['filter']['_entry_no']['text']  = $_REQUEST['_entry_no'];
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
        if(isset($_REQUEST['_entry_date_from'])){
            if($_REQUEST['_entry_date_from'] != ''){
                $subsql .=" AND pm.pm_entry_date >= '".$_REQUEST['_entry_date_from']."'";
                $record['filter']['_entry_date_from'] = $_REQUEST['_entry_date_from'];
            }
        }
        if(isset($_REQUEST['_entry_date_to'])){
            if($_REQUEST['_entry_date_to'] != ''){
                $subsql .=" AND pm.pm_entry_date <= '".$_REQUEST['_entry_date_to']."'";
                $record['filter']['_entry_date_to'] = $_REQUEST['_entry_date_to'];
            }
        }
        if(isset($_REQUEST['_total_mtr_from'])){
            if($_REQUEST['_total_mtr_from'] != ''){
                $subsql .=" AND pm.pm_total_mtr >= ".$_REQUEST['_total_mtr_from'];
                $record['filter']['_total_mtr_from'] = $_REQUEST['_total_mtr_from'];
            }
        }
        if(isset($_REQUEST['_total_mtr_to'])){
            if($_REQUEST['_total_mtr_to'] != ''){
                $subsql .=" AND pm.pm_total_mtr <= ".$_REQUEST['_total_mtr_to'];
                $record['filter']['_total_mtr_to'] = $_REQUEST['_total_mtr_to'];
            }
        }
        if(isset($_REQUEST['_sub_amt_from'])){
            if($_REQUEST['_sub_amt_from'] != ''){
                $subsql .=" AND pm.pm_sub_amt >= ".$_REQUEST['_sub_amt_from'];
                $record['filter']['_sub_amt_from'] = $_REQUEST['_sub_amt_from'];
            }
        }
        if(isset($_REQUEST['_sub_amt_to'])){
            if($_REQUEST['_sub_amt_to'] != ''){
                $subsql .=" AND pm.pm_sub_amt <= ".$_REQUEST['_sub_amt_to'];
                $record['filter']['_sub_amt_to'] = $_REQUEST['_sub_amt_to'];
            }
        }
        if(isset($_REQUEST['_disc_amt_from'])){
            if($_REQUEST['_disc_amt_from'] != ''){
                $subsql .=" AND pm.pm_disc_amt >= ".$_REQUEST['_disc_amt_from'];
                $record['filter']['_disc_amt_from'] = $_REQUEST['_disc_amt_from'];
            }
        }
        if(isset($_REQUEST['_disc_amt_to'])){
            if($_REQUEST['_disc_amt_to'] != ''){
                $subsql .=" AND pm.pm_disc_amt <= ".$_REQUEST['_disc_amt_to'];
                $record['filter']['_disc_amt_to'] = $_REQUEST['_disc_amt_to'];
            }
        }
        if(isset($_REQUEST['_taxable_amt_from'])){
            if($_REQUEST['_taxable_amt_from'] != ''){
                $subsql .=" AND pm.pm_taxable_amt >= ".$_REQUEST['_taxable_amt_from'];
                $record['filter']['_taxable_amt_from'] = $_REQUEST['_taxable_amt_from'];
            }
        }
        if(isset($_REQUEST['_taxable_amt_to'])){
            if($_REQUEST['_taxable_amt_to'] != ''){
                $subsql .=" AND pm.pm_taxable_amt <= ".$_REQUEST['_taxable_amt_to'];
                $record['filter']['_taxable_amt_to'] = $_REQUEST['_taxable_amt_to'];
            }
        }
        if(isset($_REQUEST['_sgst_amt_from'])){
            if($_REQUEST['_sgst_amt_from'] != ''){
                $subsql .=" AND pm.pm_sgst_amt >= ".$_REQUEST['_sgst_amt_from'];
                $record['filter']['_sgst_amt_from'] = $_REQUEST['_sgst_amt_from'];
            }
        }
        if(isset($_REQUEST['_sgst_amt_to'])){
            if($_REQUEST['_sgst_amt_to'] != ''){
                $subsql .=" AND pm.pm_sgst_amt <= ".$_REQUEST['_sgst_amt_to'];
                $record['filter']['_sgst_amt_to'] = $_REQUEST['_sgst_amt_to'];
            }
        }
        if(isset($_REQUEST['_cgst_amt_from'])){
            if($_REQUEST['_cgst_amt_from'] != ''){
                $subsql .=" AND pm.pm_cgst_amt >= ".$_REQUEST['_cgst_amt_from'];
                $record['filter']['_cgst_amt_from'] = $_REQUEST['_cgst_amt_from'];
            }
        }
        if(isset($_REQUEST['_cgst_amt_to'])){
            if($_REQUEST['_cgst_amt_to'] != ''){
                $subsql .=" AND pm.pm_cgst_amt <= ".$_REQUEST['_cgst_amt_to'];
                $record['filter']['_cgst_amt_to'] = $_REQUEST['_cgst_amt_to'];
            }
        }
        if(isset($_REQUEST['_igst_amt_from'])){
            if($_REQUEST['_igst_amt_from'] != ''){
                $subsql .=" AND pm.pm_igst_amt >= ".$_REQUEST['_igst_amt_from'];
                $record['filter']['_igst_amt_from'] = $_REQUEST['_igst_amt_from'];
            }
        }
        if(isset($_REQUEST['_igst_amt_to'])){
            if($_REQUEST['_igst_amt_to'] != ''){
                $subsql .=" AND pm.pm_igst_amt <= ".$_REQUEST['_igst_amt_to'];
                $record['filter']['_igst_amt_to'] = $_REQUEST['_igst_amt_to'];
            }
        }
        if(isset($_REQUEST['_bill_disc_from'])){
            if($_REQUEST['_bill_disc_from'] != ''){
                $subsql .=" AND pm.pm_bill_disc_amt >= ".$_REQUEST['_bill_disc_from'];
                $record['filter']['_bill_disc_from'] = $_REQUEST['_bill_disc_from'];
            }
        }
        if(isset($_REQUEST['_bill_disc_to'])){
            if($_REQUEST['_bill_disc_to'] != ''){
                $subsql .=" AND pm.pm_bill_disc_amt <= ".$_REQUEST['_bill_disc_to'];
                $record['filter']['_bill_disc_to'] = $_REQUEST['_bill_disc_to'];
            }
        }
        if(isset($_REQUEST['_total_amt_from'])){
            if($_REQUEST['_total_amt_from'] != ''){
                $subsql .=" AND pm.pm_total_amt >= ".$_REQUEST['_total_amt_from'];
                $record['filter']['_total_amt_from'] = $_REQUEST['_total_amt_from'];
            }
        }
        if(isset($_REQUEST['_total_amt_to'])){
            if($_REQUEST['_total_amt_to'] != ''){
                $subsql .=" AND pm.pm_total_amt <= ".$_REQUEST['_total_amt_to'];
                $record['filter']['_total_amt_to'] = $_REQUEST['_total_amt_to'];
            }
        }
        $query="SELECT 'PURCHAE' as module_name,
                pm.pm_entry_no as entry_no,
                DATE_FORMAT(pm.pm_entry_date, '%d-%m-%Y') as entry_date,
                UPPER(supplier.supplier_name) as supplier_name,
                supplier.supplier_mobile as supplier_mobile,
                pm.pm_total_mtr as total_mtr,
                pm.pm_sub_amt as sub_amt,
                pm.pm_disc_amt as disc_amt,
                pm.pm_taxable_amt as taxable_amt,
                pm.pm_sgst_amt as sgst_amt,
                pm.pm_cgst_amt as cgst_amt,
                pm.pm_igst_amt as igst_amt,
                pm.pm_bill_disc_per as bill_disc_per,
                pm.pm_bill_disc_amt as bill_disc_amt,
                pm.pm_round_off as round_off,
                pm.pm_total_amt as total_amt,
                pm.pm_created_at as created_at
                FROM purchase_master pm
                INNER JOIN supplier_master supplier ON(supplier.supplier_id = pm.pm_supplier_id)
                WHERE pm.pm_delete_status = 0
                $subsql
                ORDER BY created_at DESC";
        $data = $this->db->query($query)->result_array(); 
        // echo "<pre>"; print_r($query); exit();
        // echo "<pre>"; print_r($data); exit();
        
        $record['totals']['rows']           = count($data);
        $record['totals']['total_mtr']      = 0;
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
            foreach ($data as $key => $value) {
                array_push($record['data'], [
                                                'module_name'       => $value['module_name'],
                                                'entry_no'          => (int)$value['entry_no'],
                                                'entry_date' 	    => (int)strtotime($value['entry_date']),
                                                'entry_date1' 	    => $value['entry_date'],
                                                'supplier_name'      => $value['supplier_name'],
                                                'supplier_mobile'    => (int)$value['supplier_mobile'],
                                                'total_mtr' 		=> (float)$value['total_mtr'],
                                                'sub_amt' 		    => (float)$value['sub_amt'],
                                                'disc_amt' 		    => (float)$value['disc_amt'],
                                                'taxable_amt' 		=> (float)$value['taxable_amt'],
                                                'sgst_amt' 		    => (float)$value['sgst_amt'],
                                                'cgst_amt' 		    => (float)$value['cgst_amt'],
                                                'igst_amt' 		    => (float)$value['igst_amt'],
                                                'bill_disc_per'     => (float)$value['bill_disc_per'],
                                                'bill_disc_amt'     => (float)$value['bill_disc_amt'],
                                                'round_off'         => (float)$value['round_off'],
                                                'total_amt'         => (float)$value['total_amt'],
                                                'created_at'        => strtotime($value['created_at']),
                                            ]);

                $record['totals']['total_mtr'] 	    = $record['totals']['total_mtr'] 		+ $value['total_mtr'];
                $record['totals']['sub_amt'] 	    = $record['totals']['sub_amt'] 		    + $value['sub_amt'];
                $record['totals']['disc_amt'] 	    = $record['totals']['disc_amt'] 		+ $value['disc_amt'];
                $record['totals']['taxable_amt'] 	= $record['totals']['taxable_amt'] 		+ $value['taxable_amt'];
                $record['totals']['sgst_amt'] 	    = $record['totals']['sgst_amt'] 		+ $value['sgst_amt'];
                $record['totals']['cgst_amt'] 	    = $record['totals']['cgst_amt'] 		+ $value['cgst_amt'];
                $record['totals']['igst_amt'] 	    = $record['totals']['igst_amt'] 		+ $value['igst_amt'];
                $record['totals']['bill_disc_amt'] 	= $record['totals']['bill_disc_amt'] 	+ $value['bill_disc_amt'];
                $record['totals']['round_off'] 	    = $record['totals']['round_off'] 		+ $value['round_off'];
                $record['totals']['total_amt'] 	    = $record['totals']['total_amt'] 		+ $value['total_amt'];
            }
        }
        
        // echo "<pre>"; print_r($record); exit();
        return $record;
    }
    public function _entry_no(){
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
            $subsql .= " AND (pm.pm_entry_no LIKE '%".$name."%') ";
        }
        $query="SELECT pm.pm_entry_no as id , 
                UPPER(pm.pm_entry_no) as name 
                FROM purchase_master pm 
                WHERE pm.pm_delete_status = 0
                GROUP BY pm.pm_entry_no ASC
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
            $subsql .= " AND (supplier.supplier_name LIKE '%".$name."%') ";
        }
        $query="SELECT supplier.supplier_name as id , 
                UPPER(supplier.supplier_name) as name 
                FROM purchase_master pm 
                INNER JOIN supplier_master supplier ON(supplier.supplier_id = pm.pm_supplier_id)
                WHERE pm.pm_delete_status = 0
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
            $subsql .= " AND (supplier.supplier_mobile LIKE '%".$name."%') ";
        }
        $query="SELECT supplier.supplier_mobile as id, 
                UPPER(supplier.supplier_mobile) as name 
                FROM purchase_master pm 
                INNER JOIN supplier_master supplier ON(supplier.supplier_id = pm.pm_supplier_id)
                WHERE pm.pm_delete_status = 0
                $subsql
                GROUP BY supplier.supplier_mobile ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
}
?>