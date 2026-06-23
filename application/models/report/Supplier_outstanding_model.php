<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class supplier_outstanding_model extends my_model{
    public function __construct(){ parent::__construct('report', 'supplier_outstanding'); }
    public function get_record(){ 
        $record     = [];
        $subsql 	= '';
        if(isset($_REQUEST['_supplier_name']) && !empty($_REQUEST['_supplier_name'])){
            $subsql .=" AND supplier.supplier_name = '".$_REQUEST['_supplier_name']."'";
            $record['filter']['_supplier_name']['value'] = $_REQUEST['_supplier_name'];
            $record['filter']['_supplier_name']['text']  = $_REQUEST['_supplier_name'];
        }
        $query="SELECT supplier.supplier_id, 
                UPPER(supplier.supplier_name) as supplier_name, 
                ROUND(supplier.supplier_opening_amt) as opening_amt
                FROM supplier_master supplier
                WHERE 1
                $subsql
                ORDER BY supplier.supplier_name ASC";
        $data = $this->db->query($query)->result_array();
        // echo "<pre>"; print_r($query); exit();
        // echo "<pre>"; print_r($data); exit();
        
        $record['totals']['rows']   = count($data);
        $record['data']             = [];
        $opening_amt  	            = 0;
        $purchase_amt  	            = 0;
        $preturn_amt  	            = 0;
        $sales_amt  	            = 0;
        $sreturn_amt  	            = 0;
        $payment_amt  	            = 0;
        $receipt_amt  	            = 0;
        $debit_note_amt             = 0;
        $credit_note_amt            = 0;
        $closing_amt   	            = 0;
        if(!empty($data)){
            foreach ($data as $key => $value) {
                $_pur_amt_from 	= true;
                $_pur_amt_to 	= true;
                $_pay_amt_from 	= true;
                $_pay_amt_to 	= true;
                $_close_amt_from= true;
                $_close_amt_to 	= true;
                $open_amt       = $value['opening_amt'];
                $pur_amt        = $this->get_purchase_amt($value['supplier_id']);
                $pret_amt       = 0;
                $sale_amt       = 0;
                $sret_amt       = 0;
                $pay_amt        = $this->get_payment_amt($value['supplier_id']);
                $rec_amt        = 0;
                $dnote_amt      = 0;
                $cnote_amt      = 0;
                $close_amt      = ($open_amt + $pur_amt + $sret_amt + $rec_amt + $cnote_amt) - ($sale_amt + $pret_amt + $pay_amt + $dnote_amt);
                $label 	        = $close_amt < 0 ? TO_RECEIVE : TO_PAY;
                $close_amt      = abs($close_amt);
                if(isset($_REQUEST['_pur_amt_from'])){
                    if($_REQUEST['_pur_amt_from'] != ''){
                        if($pur_amt >= $_REQUEST['_pur_amt_from']){
                            $_pur_amt_from = true;
                        }else{
                            $_pur_amt_from = false;
                        }
                    }
                }
                if(isset($_REQUEST['_pur_amt_to'])){
                    if($_REQUEST['_pur_amt_to'] != ''){
                        if($pur_amt <= $_REQUEST['_pur_amt_to']){
                            $_pur_amt_to = true;
                        }else{
                            $_pur_amt_to = false;
                        }
                    }
                }

                if(isset($_REQUEST['_pay_amt_from'])){
                    if($_REQUEST['_pay_amt_from'] != ''){
                        if($pay_amt >= $_REQUEST['_pay_amt_from']){
                            $_pay_amt_from = true;
                        }else{
                            $_pay_amt_from = false;
                        }
                    }
                }
                if(isset($_REQUEST['_pay_amt_to'])){
                    if($_REQUEST['_pay_amt_to'] != ''){
                        if($pay_amt <= $_REQUEST['_pay_amt_to']){
                            $_pay_amt_to = true;
                        }else{
                            $_pay_amt_to = false;
                        }
                    }
                }

                if(isset($_REQUEST['_close_amt_from'])){
                    if($_REQUEST['_close_amt_from'] != ''){
                        if($close_amt >= $_REQUEST['_close_amt_from']){
                            $_close_amt_from = true;
                        }else{
                            $_close_amt_from = false;
                        }
                    }
                }else{
                    if($close_amt == 0){
                        $_close_amt_from = false;
                    }
                }
                if(isset($_REQUEST['_close_amt_to'])){
                    if($_REQUEST['_close_amt_to'] != ''){
                        if($close_amt <= $_REQUEST['_close_amt_to']){
                            $_close_amt_to = true;
                        }else{
                            $_close_amt_to = false;
                        }
                    }
                }

                if($_pur_amt_from && $_pur_amt_to && $_pay_amt_from && $_pay_amt_to && $_close_amt_from && $_close_amt_to){
                    array_push($record['data'], [
                                                    'supplier_name' 	=> $value['supplier_name'],
                                                    'opening_amt'  		=> (float)$open_amt,
                                                    'purchase_amt'  	=> (float)$pur_amt,
                                                    'preturn_amt'  		=> (float)$pret_amt,
                                                    'sales_amt'  		=> (float)$sale_amt,
                                                    'sreturn_amt'  		=> (float)$sret_amt,
                                                    'payment_amt'  		=> (float)$pay_amt,
                                                    'receipt_amt'  		=> (float)$rec_amt,
                                                    'debit_note_amt'	=> (float)$dnote_amt,
                                                    'credit_note_amt'	=> (float)$cnote_amt,
                                                    'closing_amt'		=> (float)$close_amt,
                                                    'label'				=> $label,
                                                ]);
                    
                    $opening_amt 	= $opening_amt 		+ $value['opening_amt'];
                    $purchase_amt 	= $purchase_amt 	+ $pur_amt;
                    $preturn_amt 	= $preturn_amt 		+ $pret_amt;
                    $sales_amt 		= $sales_amt 		+ $sale_amt;
                    $sreturn_amt 	= $sreturn_amt 		+ $sret_amt;
                    $payment_amt 	= $payment_amt 		+ $pay_amt;
                    $receipt_amt 	= $receipt_amt 		+ $rec_amt;
                    $debit_note_amt = $debit_note_amt 	+ $dnote_amt;
                    $credit_note_amt= $credit_note_amt 	+ $cnote_amt;
                    $closing_amt 	= $closing_amt 		+ $close_amt;                                                    
                                        
                }
            }
        }
        $record['opening_amt'] 	    = $opening_amt;
        $record['purchase_amt'] 	= $purchase_amt;
        $record['preturn_amt'] 	    = $preturn_amt;
        $record['sales_amt'] 		= $sales_amt;
        $record['sreturn_amt'] 	    = $sreturn_amt;
        $record['payment_amt'] 	    = $payment_amt;
        $record['receipt_amt'] 	    = $receipt_amt;
        $record['debit_note_amt']   = $debit_note_amt;
        $record['credit_note_amt']  = $credit_note_amt;
        $record['closing_amt'] 	    = abs($closing_amt);
        $record['label'] 			= $closing_amt < 0 ? TO_RECEIVE : TO_PAY;
        
        // echo "<pre>"; print_r($record); exit();
        return $record;
    }
    public function get_purchase_amt($supplier_id){
        $query="SELECT ROUND(SUM(pm_total_amt)) as amt
                FROM purchase_master
                WHERE pm_delete_status = 0
                AND pm_supplier_id = $supplier_id
                GROUP BY pm_supplier_id";
        $data = $this->db->query($query)->result_array();
        $amt1  = (!empty($data[0]['amt'])) ? $data[0]['amt'] : 0;
        $query="SELECT ROUND(SUM(prmm_total_amt)) as amt
                FROM purchase_readymade_master
                WHERE prmm_delete_status = 0
                AND prmm_supplier_id = $supplier_id
                GROUP BY prmm_supplier_id";
        $data = $this->db->query($query)->result_array();
        $amt2  = (!empty($data[0]['amt'])) ? $data[0]['amt'] : 0; 
        $amt    = $amt1 + $amt2;
        return $amt;
    }
    public function get_preturn_amt($supplier_id){
        $query="SELECT ROUND(SUM(prm_total_amt)) as amt
                FROM purchase_return_master
                WHERE prm_delete_status = 0
                AND prm_supplier_id = $supplier_id
                GROUP BY prm_supplier_id";
        $data = $this->db->query($query)->result_array();
        return !empty($data) ? $data[0]['amt'] : 0;
    }
    public function get_payment_amt($supplier_id){  
        $query="SELECT ROUND(SUM(payment.payment_amt)) as amt
                FROM payment_master payment
                WHERE payment.payment_delete_status = 0
                AND payment.payment_supplier_id = $supplier_id
                GROUP BY payment.payment_supplier_id";
        $data = $this->db->query($query)->result_array();
         // echo $data[0]['amt'];die; 
        return !empty($data[0]['amt']) ? $data[0]['amt'] : 0;
    } 
    public function get_receipt_amt($supplier_id){
        $query="SELECT ROUND(SUM(vm_total_amt)) as amt
                FROM voucher_master
                WHERE vm_delete_status = 0
                AND vm_party_id = $supplier_id
                AND vm_constant NOT IN ('DEBIT_NOTE', 'CREDIT_NOTE')
                AND vm_type = 'RECEIPT'
                AND vm_group = 'SUPPLIER'
                GROUP BY vm_party_id";
        $data = $this->db->query($query)->result_array();
        return !empty($data) ? $data[0]['amt'] : 0;
    }
    public function get_debit_note_amt($supplier_id){
        $query="SELECT ROUND(SUM(vm_total_amt)) as amt
                FROM voucher_master
                WHERE vm_delete_status = 0
                AND vm_party_id = $supplier_id
                AND vm_constant = 'DEBIT_NOTE'
                AND vm_type = 'PAYMENT'
                AND vm_group = 'SUPPLIER'
                GROUP BY vm_party_id";
        $data = $this->db->query($query)->result_array();
        return !empty($data) ? $data[0]['amt'] : 0;
    }
    public function get_credit_note_amt($supplier_id){
        $query="SELECT ROUND(SUM(vm_total_amt)) as amt
                FROM voucher_master
                WHERE vm_delete_status = 0
                AND vm_party_id = $supplier_id
                AND vm_constant = 'CREDIT_NOTE'
                AND vm_type = 'PAYMENT'
                AND vm_group = 'SUPPLIER'
                GROUP BY vm_party_id";
        $data = $this->db->query($query)->result_array();
        return !empty($data) ? $data[0]['amt'] : 0;
    }
    public function _supplier_name(){
        $subsql = '';
        $limit  = PER_PAGE;
        $offset = OFFSET;
        $page   = 1;
        if(isset($_REQUEST['limit']) && !empty($_REQUEST['limit'])){
            $limit = $_REQUEST['limit'];
        }
        if(isset($_REQUEST['page']) && !empty($_REQUEST['page'])){
            $page   = $_REQUEST['page'];
            $offset = $limit * ($page - 1);
        }
        if(isset($_REQUEST['name']) && !empty($_REQUEST['name'])){
            $name   = $_REQUEST['name'];
            $subsql .= " AND (supplier.supplier_name LIKE '%".$name."%') ";
        }
        $query="SELECT supplier.supplier_name as id, 
                UPPER(supplier.supplier_name) as name
                FROM supplier_master supplier
                WHERE supplier.supplier_status = 1
                GROUP BY supplier.supplier_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
}
?>