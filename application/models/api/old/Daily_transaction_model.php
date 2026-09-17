<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Api_Model.php';
class daily_transaction_model extends my_api_model{
	public function __construct(){ parent::__construct(); }
    public function isExist($id){
        return false;
    }
   
    public function get_data($search){ 
        $where  = '';
        $having = '';
        $record     = [];

        $date_from  = date('Y-m-d');
        $date_to    = date('Y-m-d');
        if((isset($search['date_from'])) && ($search['date_from'] != '')) $date_from = $search['date_from'];
        if((isset($search['date_to'])) && ($search['date_to'] != '')) $date_to = $search['date_to'];
        $start = strtotime($date_from);
        $end   = strtotime($date_to);
        $diff  = ceil(abs($end - $start) / 86400);
        // echo "<pre>"; print_r($diff); exit;
        for ($i = 0; $i <= $diff ; $i++) { 
            $strtotime = strtotime($date_from." + ".$i." days");
            $data = $this->get_transaction_data(date('Y-m-d', $strtotime),$search);
            if(!empty($data['data'])){
                $key = date('d-m-Y', $strtotime);
                $record[$key] = $data;
            }
        }
        // print_r($record);die;
        return $record;
    }

    public function get_transaction_data($date,$search){ 
            $subsql     = '';
            $having     = '';

            if(isset($search['payment_mode_name']) && !empty($search['payment_mode_name'])){
                $subsql .=" AND payment_mode.payment_mode_name = '".$search['payment_mode_name']."'";
            } 
            $order_query="SELECT 0 as sr_no,
                            om.om_entry_no as entry_no,
                            'ORDER' as action,
                            UPPER(customer.customer_name) as customer_name,
                            SUM(opmt.opmt_amt) as amt,
                            UPPER(payment_mode.payment_mode_name) as payment_mode_name,
                            om.om_created_at as created_at
                            FROM order_master om
                            INNER JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)
                            INNER JOIN order_payment_mode_trans opmt ON(opmt.opmt_om_id = om.om_id)
                            INNER JOIN payment_mode_master payment_mode ON(payment_mode.payment_mode_id = opmt.opmt_payment_mode_id)
                            WHERE om.om_delete_status = 0 AND om.om_advance_amt > 0
                            AND opmt.opmt_delete_status = 0
                            AND om.om_status=1
                            AND om.om_entry_date = '".$date."'
                            $subsql
                            GROUP BY om.om_id, payment_mode.payment_mode_id
                            HAVING 1
                            $having";

            $estimate_query="SELECT 0 as sr_no,
                            om.om_em_entry_no as entry_no,
                            'ESTIMATE' as action,
                            UPPER(customer.customer_name) as customer_name,
                            SUM(opmt.opmt_amt) as amt,
                            UPPER(payment_mode.payment_mode_name) as payment_mode_name,
                            om.om_created_at as created_at
                            FROM order_master om
                            INNER JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)
                            INNER JOIN order_payment_mode_trans opmt ON(opmt.opmt_om_id = om.om_id)
                            INNER JOIN payment_mode_master payment_mode ON(payment_mode.payment_mode_id = opmt.opmt_payment_mode_id)
                            WHERE om.om_delete_status = 0 AND om.om_advance_amt > 0
                            AND opmt.opmt_delete_status = 0
                            AND om.om_status=0
                            AND om.om_entry_date = '".$date."'
                            $subsql
                            GROUP BY om.om_id, payment_mode.payment_mode_id
                            HAVING 1
                            $having";   

            $receipt_query="SELECT 0 as sr_no,
                            receipt.receipt_entry_no as entry_no,
                            'RECEIPT' as action,
                            UPPER(customer.customer_name) as customer_name,
                            SUM(rpmt.rpmt_amt) as amt,
                            UPPER(payment_mode.payment_mode_name) as payment_mode_name,
                            receipt.receipt_created_at as created_at
                            FROM receipt_master receipt
                            INNER JOIN customer_master customer ON(customer.customer_id = receipt.receipt_customer_id)
                            INNER JOIN receipt_payment_mode_trans rpmt ON(rpmt.rpmt_receipt_id = receipt.receipt_id)
                            INNER JOIN payment_mode_master payment_mode ON(payment_mode.payment_mode_id = rpmt.rpmt_payment_mode_id)
                            WHERE receipt.receipt_delete_status = 0
                            AND rpmt.rpmt_delete_status = 0
                            AND receipt.receipt_entry_date = '".$date."'
                            $subsql
                            GROUP BY receipt.receipt_id, payment_mode.payment_mode_id
                            HAVING 1
                            $having";

            $payment_query="SELECT 0 as sr_no,
                            payment.payment_entry_no as entry_no,
                            'PAYMENT' as action,
                            UPPER(supplier.supplier_name) as customer_name,
                            SUM(ppmt.ppmt_amt) as amt,
                            UPPER(payment_mode.payment_mode_name) as payment_mode_name,
                            payment.payment_created_at as created_at
                            FROM payment_master payment
                            INNER JOIN supplier_master supplier ON(supplier.supplier_id = payment.payment_supplier_id)
                            INNER JOIN payment_payment_mode_trans ppmt ON(ppmt.ppmt_payment_id = payment.payment_id)
                            INNER JOIN payment_mode_master payment_mode ON(payment_mode.payment_mode_id = ppmt.ppmt_payment_mode_id)
                            WHERE payment.payment_delete_status = 0
                            AND ppmt.ppmt_delete_status = 0 
                            AND payment.payment_entry_date = '".$date."'
                            $subsql 
                            GROUP BY payment.payment_id, payment_mode.payment_mode_id
                            HAVING 1
                            $having";

            $karigar_query="SELECT 0 as sr_no,
                            karigar_payment.karigar_payment_entry_no as entry_no,
                            'KARIGAR' as action,
                            UPPER(karigar.karigar_name) as customer_name,
                            SUM(kpmt.kpmt_amt) as amt,
                            UPPER(payment_mode.payment_mode_name) as payment_mode_name,
                            karigar_payment.karigar_payment_created_at as created_at
                            FROM karigar_payment_master karigar_payment
                            INNER JOIN karigar_master karigar ON(karigar.karigar_id = karigar_payment.karigar_payment_karigar_id)
                            INNER JOIN  karigar_payment_mode_trans kpmt ON(kpmt.kpmt_karigar_payment_id = karigar_payment.karigar_payment_id )
                            INNER JOIN payment_mode_master payment_mode ON(payment_mode.payment_mode_id = kpmt.kpmt_payment_mode_id)
                            WHERE karigar_payment.karigar_payment_delete_status = 0
                            AND kpmt.kpmt_delete_status = 0 
                            AND karigar_payment.karigar_payment_entry_date = '".$date."'
                            $subsql 
                            GROUP BY karigar_payment.karigar_payment_id, payment_mode.payment_mode_id
                            HAVING 1
                            $having";
             // print_r($karigar_query);die;               
                
            $query="SELECT daily.sr_no,
                    daily.entry_no,
                    daily.action,
                    daily.customer_name,
                    daily.amt,
                    daily.payment_mode_name,
                    daily.created_at
                    FROM ($order_query UNION ALL $estimate_query UNION ALL $receipt_query UNION ALL $payment_query UNION ALL $karigar_query) as daily
                    ORDER BY daily.created_at ASC";
            $record['data'] = $this->db->query($query)->result_array();
            // echo "<pre>"; print_r($query); exit();
            // echo "<pre>"; print_r($record['data']); exit();
            $record['total']        = [];
            $record['total']['TOTAL']= 0;
            if(!empty($record['data'])){
                foreach ($record['data'] as $key => $value) {
                    $record['data'][$key]['sr_no'] = ($key+1);
                    $record['total'][$value['payment_mode_name']]   = isset($record['total'][$value['payment_mode_name']]) ? ($record['total'][$value['payment_mode_name']] + $value['amt']) : $value['amt'];
                    $record['total']['TOTAL']                       = isset($record['total']['TOTAL']) ? ($record['total']['TOTAL'] + $value['amt']) : $value['amt'];
                }
            }
            return $record;
        }
    
   
   

}
?>
