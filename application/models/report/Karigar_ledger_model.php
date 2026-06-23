<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class karigar_ledger_model extends my_model{
    public function __construct(){ parent::__construct('report', 'karigar_ledger'); }
    public function get_record(){  
        $record     = [];
        $karigar_name = 'XXX';
        // echo "<pre>"; print_r($_REQUEST);die; 
        if(isset($_REQUEST['_karigar_name']) && !empty($_REQUEST['_karigar_name'])){
            $karigar_name = $_REQUEST['_karigar_name'];
            $record['filter']['_karigar_name']['value'] = $_REQUEST['_karigar_name'];
            $record['filter']['_karigar_name']['text']  = $_REQUEST['_karigar_name'];
        }
        
        $hisab_query = "
            SELECT
                'HISAB' AS action,
                UPPER(k.karigar_name) AS karigar_name,
                hm.hm_entry_no AS entry_no,
                hm.hm_entry_date AS entry_date,
                hm.hm_total_amt AS hisab_amt,
                0 AS payment_amt,
                hm.hm_notes AS remark,
                hm.hm_created_at AS created_at
            FROM hisab_master hm
            INNER JOIN karigar_master k
                ON k.karigar_id = hm.hm_karigar_id
            WHERE hm.hm_delete_status = 0
            AND k.karigar_name = '".$karigar_name."'
            ";

            $payment_query = "
            SELECT
                'PAYMENT' AS action,
                UPPER(k.karigar_name) AS karigar_name,
                kp.karigar_payment_entry_no AS entry_no,
                kp.karigar_payment_entry_date AS entry_date,
                0 AS hisab_amt,
                kp.karigar_payment_amt AS payment_amt,
                kp.karigar_payment_notes AS remark,
                kp.karigar_payment_created_at AS created_at
            FROM karigar_payment_master kp
            INNER JOIN karigar_master k
                ON k.karigar_id = kp.karigar_payment_karigar_id
            WHERE kp.karigar_payment_delete_status = 0
            AND k.karigar_name = '".$karigar_name."'
            ";

            $query = "
            SELECT *
            FROM (
                $hisab_query
                UNION ALL
                $payment_query
            ) ledger
            ORDER BY ledger.entry_date ASC,
                    ledger.created_at ASC
            ";
        $record['data'] = $this->db->query($query)->result_array();
        $record['totals']['rows'] = count($record['data']);
        $running_balance = 0;

        foreach($record['data'] as $key => $row)
        {
            $running_balance += $row['hisab_amt'];
            $running_balance -= $row['payment_amt'];

            $record['data'][$key]['closing_amt'] = abs($running_balance);

            $record['data'][$key]['label'] =
                ($running_balance >= 0)
                ? 'TO PAY'
                : 'TO RECEIVE';
        }
        return $record;
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
                FROM  karigar_master karigar
                WHERE karigar.karigar_status = 1
                $subsql
                GROUP BY karigar.karigar_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }

}?>