<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class customer_outstanding_model extends my_model{
    public function __construct(){ parent::__construct('report', 'customer_outstanding'); }
    public function get_record(){
        $record     = [];
        $subsql 	= ''; 
        if(isset($_REQUEST['_customer_name']) && !empty($_REQUEST['_customer_name'])){
            $subsql .=" AND customer.customer_name = '".$_REQUEST['_customer_name']."'";
            $record['filter']['_customer_name']['value'] = $_REQUEST['_customer_name'];
            $record['filter']['_customer_name']['text']  = $_REQUEST['_customer_name'];
        }
        $query="SELECT customer.customer_id, 
                UPPER(customer.customer_name) as customer_name, 
                ROUND(customer.customer_opening_amt) as opening_amt
                FROM customer_master customer
                WHERE 1
                $subsql
                ORDER BY customer.customer_name ASC";
        $data = $this->db->query($query)->result_array();
        // echo "<pre>"; print_r($query); exit();
        // echo "<pre>"; print_r($data); exit();
        
        $record['data']             = [];
        $record['totals']['rows']   = 0;
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
                $pur_amt        = 0;
                $pret_amt       = 0;
                $sale_amt       = $this->get_sales_amt($value['customer_id']);
                $sret_amt       = 0;
                $pay_amt        = 0;
                $rec_amt        = $this->get_receipt_amt($value['customer_id']);
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
                                                    'customer_name' 	=> $value['customer_name'],
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
        $record['totals']['rows']   = count($record['data']);
        // echo "<pre>"; print_r($record); exit();
        return $record;
    }
    public function get_sales_amt($customer_id){
        $query="SELECT ROUND(SUM(om_total_amt)) as amt
                FROM order_master
                WHERE om_delete_status = 0
                AND om_customer_id = $customer_id
                GROUP BY om_customer_id";
        $data = $this->db->query($query)->result_array();
        return !empty($data) ? $data[0]['amt'] : 0;
    }
    public function get_preturn_amt($customer_id){
        $query="SELECT ROUND(SUM(prm_total_amt)) as amt
                FROM purchase_return_master
                WHERE prm_delete_status = 0
                AND prm_customer_id = $customer_id
                GROUP BY prm_customer_id";
        $data = $this->db->query($query)->result_array();
        return !empty($data) ? $data[0]['amt'] : 0;
    }
    public function get_payment_amt($customer_id){
        $query="SELECT ROUND(SUM(vm_total_amt)) as amt
                FROM voucher_master
                WHERE vm_delete_status = 0
                AND vm_party_id = $customer_id
                AND vm_constant NOT IN ('DEBIT_NOTE', 'CREDIT_NOTE')
                AND vm_type = 'PAYMENT'
                AND vm_group = 'SUPPLIER'
                GROUP BY vm_party_id";
        $data = $this->db->query($query)->result_array();
        return !empty($data) ? $data[0]['amt'] : 0;
    }
    public function get_receipt_amt($customer_id){  
        $query="SELECT ROUND(SUM(om.om_advance_amt)) as amt
                FROM order_master om
                WHERE om.om_delete_status = 0
                AND om.om_customer_id = $customer_id
                GROUP BY om.om_customer_id";
        $data = $this->db->query($query)->result_array();
        $amt  = !empty($data) ? $data[0]['amt'] : 0;

        $query="SELECT ROUND(SUM(receipt.receipt_amt)) as amt
                FROM receipt_master receipt
                WHERE receipt.receipt_delete_status = 0
                AND receipt.receipt_customer_id = $customer_id
                GROUP BY receipt.receipt_customer_id";
        $data = $this->db->query($query)->result_array();
        return !empty($data) ? $amt + $data[0]['amt'] : $amt;
    }
    public function get_debit_note_amt($customer_id){
        $query="SELECT ROUND(SUM(vm_total_amt)) as amt
                FROM voucher_master
                WHERE vm_delete_status = 0
                AND vm_party_id = $customer_id
                AND vm_constant = 'DEBIT_NOTE'
                AND vm_type = 'PAYMENT'
                AND vm_group = 'SUPPLIER'
                GROUP BY vm_party_id";
        $data = $this->db->query($query)->result_array();
        return !empty($data) ? $data[0]['amt'] : 0;
    }
    public function get_credit_note_amt($customer_id){
        $query="SELECT ROUND(SUM(vm_total_amt)) as amt
                FROM voucher_master
                WHERE vm_delete_status = 0
                AND vm_party_id = $customer_id
                AND vm_constant = 'CREDIT_NOTE'
                AND vm_type = 'PAYMENT'
                AND vm_group = 'SUPPLIER'
                GROUP BY vm_party_id";
        $data = $this->db->query($query)->result_array();
        return !empty($data) ? $data[0]['amt'] : 0;
    }
    public function _customer_name(){
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
            $subsql .= " AND (customer.customer_name LIKE '".$name."%') ";
        }
        $query="SELECT customer.customer_name as id, 
                UPPER(customer.customer_name) as name
                FROM customer_master customer
                WHERE customer.customer_status = 1
                GROUP BY customer.customer_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
}
?>