<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class customer_ledger_model extends my_model{
    public function __construct(){ parent::__construct('report', 'customer_ledger'); }
    public function get_record(){  
        $record     = [];
        $customer_name = 'XXX';
        // echo "<pre>"; print_r($_REQUEST);die; 
        if(isset($_REQUEST['_debit_name']) && !empty($_REQUEST['_debit_name'])){
            $customer_name = $_REQUEST['_debit_name'];
            $record['filter']['_debit_name']['value'] = $_REQUEST['_debit_name'];
            $record['filter']['_debit_name']['text']  = $_REQUEST['_debit_name'];
        }
        $order_query="SELECT 
                        IF(om.om_status=0,'ESTIMATE','ORDER') as action,
                        UPPER(customer.customer_name) as customer_name,
                        IF(om.om_status=0,om.om_em_entry_no,om.om_entry_no) as entry_no,
                        IF(om.om_status=0,DATE_FORMAT(om.om_em_entry_date, '%d-%m-%Y'),DATE_FORMAT(om.om_entry_date, '%d-%m-%Y')) as entry_date,
                        om.om_total_amt as order_amt,
                        om.om_advance_amt as advance_amt,
                        0 as receipt_amt,
                        om.om_created_at as created_at
                        FROM order_master om
                        INNER JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)
                        WHERE om.om_delete_status = 0
                        AND customer.customer_name = '".$customer_name."'";
        
     
        $receipt_query="SELECT 'RECEIPT' as action, 
            UPPER(customer.customer_name) as customer_name,
            receipt.receipt_entry_no as entry_no,
            DATE_FORMAT(receipt.receipt_entry_date, '%d-%m-%Y') as entry_date,
            0 as order_amt,
          
            0 as advance_amt,
            receipt.receipt_amt as receipt_amt,
            receipt.receipt_created_at as created_at
            FROM receipt_master receipt
            INNER JOIN customer_master customer ON(customer.customer_id = receipt.receipt_customer_id)
            WHERE receipt.receipt_delete_status = 0
            AND customer.customer_name = '".$customer_name."'";                        

        $query="SELECT ledger.action,
                ledger.entry_no,
                ledger.entry_date,
                ledger.customer_name,
                ledger.order_amt,
                ledger.advance_amt,
                ledger.receipt_amt,
                ((ledger.order_amt) - (ledger.advance_amt + ledger.receipt_amt)) as closing_amt,
                IF(((ledger.order_amt) - (ledger.advance_amt + ledger.receipt_amt)) < 0, 'TO PAY', 'TO RECEIVE') as label,
                ledger.created_at
                FROM($order_query UNION ALL $receipt_query) as ledger
                ORDER BY ledger.entry_date DESC, ledger.created_at ASC";
        $record['data'] = $this->db->query($query)->result_array();
        $record['totals']['rows'] = count($record['data']);
        // echo "<pre>"; print_r($query); exit();
        // echo "<pre>"; print_r($record); exit();
        if(!empty($record['data'])){
            $closing_amt = 0;
            foreach ($record['data'] as $key => $value) {
                $label       = 'TO RECEIVE';
                $closing_amt = $closing_amt + $value['closing_amt'];
                if($closing_amt < 0) $label = 'TO PAY';
                $record['data'][$key]['closing_amt'] = $closing_amt;
                $record['data'][$key]['label']       = $label;
            }
        }
        return $record;
    }

    public function _debit_name(){  
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
                FROM  customer_master customer
                WHERE customer.customer_status = 1
                $subsql
                GROUP BY customer.customer_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }

}?>