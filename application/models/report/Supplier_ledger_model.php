<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class supplier_ledger_model extends my_model{
    public function __construct(){ parent::__construct('report', 'supplier_ledger'); }
    public function get_record(){ 
        $record     = [];
        $supplier_name = 'XXX'; 
        if(isset($_REQUEST['_supplier_name']) && !empty($_REQUEST['_supplier_name'])){
            $supplier_name = $_REQUEST['_supplier_name'];
            $record['filter']['_supplier_name']['value'] = $_REQUEST['_supplier_name'];
            $record['filter']['_supplier_name']['text']  = $_REQUEST['_supplier_name'];
        }
        $purchase_query="SELECT 'PURCHASE' as action, 
                        UPPER(supplier.supplier_name) as supplier_name,
                        pm.pm_entry_no as entry_no,
                        DATE_FORMAT(pm.pm_entry_date, '%d-%m-%Y') as entry_date,
                        pm.pm_total_amt as purchase_amt,
                        0 as payment_amt,
                        pm.pm_created_at as created_at
                        FROM purchase_master pm
                        INNER JOIN supplier_master supplier ON(supplier.supplier_id = pm.pm_supplier_id)
                        WHERE pm.pm_delete_status = 0
                        AND supplier.supplier_name = '".$supplier_name."'";
        $readymade_query="SELECT 'READYMADE' as action, 
                        UPPER(supplier.supplier_name) as supplier_name,
                        prmm.prmm_entry_no as entry_no,
                        DATE_FORMAT(prmm.prmm_entry_date, '%d-%m-%Y') as entry_date,
                        prmm.prmm_total_amt as purchase_amt,
                        0 as payment_amt,
                        prmm.prmm_created_at as created_at
                        FROM purchase_readymade_master prmm
                        INNER JOIN supplier_master supplier ON(supplier.supplier_id = prmm.prmm_supplier_id)
                        WHERE prmm.prmm_delete_status = 0
                        AND supplier.supplier_name = '".$supplier_name."'";
                                        

        $payment_query="SELECT 'PAYMENT' as action, 
            UPPER(supplier.supplier_name) as supplier_name,
            payment.payment_entry_no as entry_no,
            DATE_FORMAT(payment.payment_entry_date, '%d-%m-%Y') as entry_date,
            0 as purchase_amt,
            payment.payment_amt as payment_amt,
            payment.payment_created_at as created_at
            FROM payment_master payment
            INNER JOIN supplier_master supplier ON(supplier.supplier_id = payment.payment_supplier_id)
            WHERE payment.payment_delete_status = 0
            AND supplier.supplier_name = '".$supplier_name."'";                        

        $query="SELECT ledger.action,
                ledger.entry_no,
                ledger.entry_date,
                ledger.supplier_name,
                ledger.purchase_amt,
                ledger.payment_amt,
                (ledger.purchase_amt - ledger.payment_amt) as closing_amt,
                IF((ledger.purchase_amt - ledger.payment_amt) < 0, 'TO RECEIVE', 'TO PAY') as label,
                ledger.created_at
                FROM($purchase_query  UNION ALL $readymade_query UNION ALL $payment_query) as ledger
                ORDER BY ledger.entry_date DESC, ledger.created_at ASC";
        $record['data'] = $this->db->query($query)->result_array();
        $record['totals']['rows'] = count($record['data']);
        // echo "<pre>"; print_r($query); exit();
        // echo "<pre>"; print_r($record); exit();
        if(!empty($record['data'])){
            $closing_amt = 0;
            foreach ($record['data'] as $key => $value) {
                $label       = 'TO PAY';
                $closing_amt = $closing_amt + $value['closing_amt'];
                if($closing_amt < 0) $label = 'TO RECEIVE';
                $record['data'][$key]['closing_amt'] = $closing_amt;
                $record['data'][$key]['label']       = $label;
            }
        }
        return $record;
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
            $subsql .= " AND (supplier.supplier_name LIKE '".$name."%') ";
        }
        $query="SELECT supplier.supplier_name as id, 
                UPPER(supplier.supplier_name) as name
                FROM supplier_master supplier
                WHERE supplier.supplier_status = 1
                $subsql
                GROUP BY supplier.supplier_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
}
?>