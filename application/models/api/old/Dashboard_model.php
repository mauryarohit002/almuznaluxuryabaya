<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Api_Model.php';
class dashboard_model extends my_api_model{
    public function __construct(){ parent::__construct(); }
    public function isExist($id){
        return false;
    }
   
    public function dashboard_read($search){ 
        $where  = '';
        $where2  = '';
        $having = '';
        $having1 = '';
        if(isset($search['date_from']) && !empty($search['date_from'])){
            $date_from = date('Y-m-d',strtotime($search['date_from']));
            $where .=" AND om.om_entry_date >= '".$date_from."'";
            $where2 .=" AND jim.jim_entry_date >= '".$date_from."'";
            $having .=" AND (delivery_date >= '".$date_from."')";
            $having1 .=" AND (trial_date >= '".$date_from."')";
        }else{
            $where .=" AND om.om_entry_date >= '".date('Y-m-d')."'";
            $where2 .=" AND jim.jim_entry_date >= '".date('Y-m-d')."'";
            $having .=" AND (delivery_date >= '".date('Y-m-d')."')";
            $having1 .=" AND (trial_date >= '".date('Y-m-d')."')";
        }

        if(isset($search['date_to']) && !empty($search['date_to'])){    
            $date_to = date('Y-m-d',strtotime($search['date_to']));
            $where .=" AND om.om_entry_date <= '".$date_to."'";
            $where2 .=" AND jim.jim_entry_date <= '".$date_to."'";
            $having .=" AND (delivery_date <= '".$date_to."')";
            $having1 .=" AND (trial_date <= '".$date_to."')";
        }else{
            $where .=" AND om.om_entry_date <= '".date('Y-m-d')."'";
            $where2 .=" AND jim.jim_entry_date <= '".date('Y-m-d')."'";
            $having .=" AND (delivery_date <= '".date('Y-m-d')."')";
            $having1 .=" AND (trial_date <= '".date('Y-m-d')."')";
        }
        
        $query="SELECT 
                SUM(om.om_total_amt) as order_amt
                FROM order_master om
                INNER JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)
                WHERE om.om_delete_status = 0 $where ";
        $order = $this->db->query($query)->result_array();
        $order_amt =  !empty($order) ? $order[0]['order_amt'] : "0";   

        $query="SELECT 
                count(om.om_id) as order_count
                FROM order_master om
                INNER JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)
                WHERE om.om_delete_status = 0 $where ";
        $order = $this->db->query($query)->result_array();
        $orderCount =  !empty($order) ? $order[0]['order_count'] : "0";      
        $query1="SELECT 
                om.om_id,
                IF(om.om_reschedule_delivery_date != '', om.om_reschedule_delivery_date, om.om_delivery_date) as delivery_date
                FROM order_master om
                INNER JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)
                WHERE om.om_delete_status = 0 
                HAVING 1
                $having ";
        $delCount =  $this->db->query($query1)->num_rows();         

        $query2="SELECT 
                om.om_id,
                IF(om.om_reschedule_trial_date != '', om.om_reschedule_trial_date, om.om_trial_date) as trial_date
                FROM order_master om
                INNER JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)
                WHERE om.om_delete_status = 0 
                HAVING 1
                $having1 ";
        // $trial = $this->db->query($query2)->result_array();
        $trialCount =  $this->db->query($query2)->num_rows();   
                 
        $query3="SELECT count(jim.jim_id) as issue_count,
                DATE_FORMAT(jim.jim_entry_date, '%d-%m-%Y') as entry_date
                FROM job_issue_master jim
                WHERE jim.jim_delete_status = 0 
                $where2";
        $issue = $this->db->query($query3)->result_array();
        $issueCount =  !empty($issue) ? $issue[0]['issue_count'] : "0";

        $collection = $this->collection_data($search);
        // print_r($collection);die;

        return [
            'order_count'           => (string)$orderCount,
            'delivery_count'        => (string)$delCount,
            'trial_count'           => (string)$trialCount,
            'issue_count'           => (string)$issueCount,
            'today_collection'      => (string)$collection,
            'today_collection_amt'  => (string)$collection,
            'order_amt'             => (string)$order_amt

        ];   
       
        
    }

    public function collection_data($search){  
            $subsql     = '';
            if(isset($search['date_from']) && !empty($search['date_from'])){
                $date_from = date('Y-m-d',strtotime($search['date_from']));
            }else{
                $date_from = date('Y-m-d');
            }

            if(isset($search['date_to']) && !empty($search['date_to'])){    
                $date_to = date('Y-m-d',strtotime($search['date_to']));
            }else{
                $date_to =date('Y-m-d');
            }

            $order_query="SELECT 
                            SUM(om.om_advance_amt) as amt
                            FROM order_master om
                            WHERE om.om_delete_status = 0 
                            AND om.om_advance_amt > 0
                            AND om.om_entry_date >='".$date_from."' AND om.om_entry_date <='".$date_to."'
                            $subsql";

            $receipt_query="SELECT 
                            SUM(receipt.receipt_amt) as amt
                            FROM receipt_master receipt
                            WHERE receipt.receipt_delete_status = 0
                            AND receipt.receipt_entry_date >='".$date_from."' AND receipt.receipt_entry_date <='".$date_to."'
                            $subsql";

            // $payment_query="SELECT 
            //                 SUM(payment.payment_amt) as amt
            //                 FROM payment_master payment
            //                 WHERE payment.payment_delete_status = 0
            //                 AND payment.payment_entry_date >='".$date_from."' AND payment.payment_entry_date <='".$date_to."'
            //                 $subsql ";

            // $karigar_query="SELECT 
            //                 SUM(karigar_payment.karigar_payment_amt) as amt
            //                 FROM karigar_payment_master karigar_payment
            //                 WHERE karigar_payment.karigar_payment_delete_status = 0
            //                  AND karigar_payment.karigar_payment_entry_date >='".$date_from."' AND karigar_payment.karigar_payment_entry_date <='".$date_to."'
            //                 $subsql ";
                                            
                // print_r($karigar_query);die;            
                
            $query="SELECT 
                    daily.amt
                    FROM ($order_query UNION ALL $receipt_query) as daily
                    ";
            $record['data'] = $this->db->query($query)->result_array();
            // echo "<pre>"; print_r($query); exit();
            // echo "<pre>"; print_r($record['data']); exit();
            $total = "0";
            if(!empty($record['data'])){
                foreach ($record['data'] as $key => $value) {
                    $total  =  $total + $value['amt'];
                }
            }
            return $total;
        }

}
?>