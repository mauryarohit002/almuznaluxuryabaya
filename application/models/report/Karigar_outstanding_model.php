<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class karigar_outstanding_model extends my_model{
    public function __construct(){ parent::__construct('report', 'karigar_outstanding'); }
    public function get_record(){
        $record     = [];
        $subsql 	= ''; 
        if(isset($_REQUEST['_karigar_name']) && !empty($_REQUEST['_karigar_name'])){
            $subsql .=" AND karigar.karigar_name = '".$_REQUEST['_karigar_name']."'";
            $record['filter']['_karigar_name']['value'] = $_REQUEST['_karigar_name'];
            $record['filter']['_karigar_name']['text']  = $_REQUEST['_karigar_name'];
        }
        if(isset($_REQUEST['_close_amt_from'])){
            if($_REQUEST['_close_amt_from'] == ''){
                $record['filter']['_close_amt_from'] = 1;
            }
        }else{
            $record['filter']['_close_amt_from'] = 1;
        }
        $query="SELECT karigar.karigar_id, 
                UPPER(karigar.karigar_name) as karigar_name, 
                0 as opening_amt
                FROM karigar_master karigar
                WHERE 1
                $subsql
                ORDER BY karigar.karigar_name ASC";
        $data = $this->db->query($query)->result_array();
        // echo "<pre>"; print_r($query); exit();
        // echo "<pre>"; print_r($data); exit();
        
        $record['data']             = [];
        $record['totals']['rows']   = 0;
        $opening_amt  	            = 0;
        $hisab_amt  	            = 0;
        $payment_amt  	            = 0;
        $closing_amt   	            = 0;
        if(!empty($data)){
            foreach ($data as $key => $value) {
                $_close_amt_from= true;
                $_close_amt_to 	= true;
                $open_amt       = $value['opening_amt'];
                $hisab_amt      = $this->get_hisab_amt($value['karigar_id']);
                $pay_amt        = $this->get_payment_amt($value['karigar_id']);
                $close_amt      = ($open_amt + $hisab_amt) - $pay_amt;
                $label 	        = $close_amt < 0 ? TO_RECEIVE : TO_PAY;
                $close_amt      = abs($close_amt);
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

                if($_close_amt_from && $_close_amt_to){
                    array_push($record['data'], [
                                                    'karigar_name' 	=> $value['karigar_name'],
                                                    'opening_amt'  	=> (float)$open_amt,
                                                    'hisab_amt'  	=> (float)$hisab_amt,
                                                    'payment_amt'  	=> (float)$pay_amt,
                                                    'closing_amt'	=> (float)$close_amt,
                                                    'label'			=> $label,
                                                ]);
                    
                    $opening_amt 	= $opening_amt 		+ $value['opening_amt'];
                    $hisab_amt 	    = $hisab_amt 	    + $hisab_amt;
                    $payment_amt 	= $payment_amt 		+ $pay_amt;
                    $closing_amt 	= $closing_amt 		+ $close_amt;                                                    
                                        
                }
            }
        }
        $record['opening_amt'] 	    = $opening_amt;
        $record['hisab_amt'] 	    = $hisab_amt;
        $record['payment_amt'] 	    = $payment_amt;
        $record['closing_amt'] 	    = abs($closing_amt);
        $record['label'] 			= $closing_amt < 0 ? TO_RECEIVE : TO_PAY;
        $record['totals']['rows']   = count($record['data']);
        // echo "<pre>"; print_r($record); exit();
        return $record;
    }
    public function get_hisab_amt($karigar_id){
        $query="SELECT ROUND(SUM(hm_total_amt)) as amt
                FROM hisab_master
                WHERE hm_delete_status = 0
                AND hm_karigar_id = $karigar_id
                GROUP BY hm_karigar_id";
        $data = $this->db->query($query)->result_array();
        return !empty($data) ? $data[0]['amt'] : 0;
    }
    public function get_payment_amt($karigar_id){
        // $query="SELECT ROUND(SUM(payment_amt)) as amt
        //         FROM payment_karigar_master
        //         WHERE payment_delete_status = 0
        //         AND payment_karigar_id = $karigar_id
        //         GROUP BY payment_karigar_id";
        // $data = $this->db->query($query)->result_array();
        $data[0]['amt'] = 0;
        return empty($data) ? 0 : $data[0]['amt'];
    }
    public function _karigar_name(){
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
            $subsql .= " AND (karigar.karigar_name LIKE '".$name."%') ";
        }
        $query="SELECT karigar.karigar_name as id, 
                UPPER(karigar.karigar_name) as name
                FROM karigar_master karigar
                WHERE karigar.karigar_status = 1
                GROUP BY karigar.karigar_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
}
?>