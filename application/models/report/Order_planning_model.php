<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class order_planning_model extends my_model{
    public function __construct(){ parent::__construct('report', 'order_planning'); }
    public function get_record(){
        $record    = [];
        $date_from = date('d-m-Y');
        $date_to   = date('d-m-Y');
        if(isset($_REQUEST['_date_from'])){
            if($_REQUEST['_date_from'] != ''){
                $date_from = date('Y-m-d', strtotime($_REQUEST['_date_from']));
                $record['filter']['_date_from'] = $_REQUEST['_date_from'];
            }
        }
        if(isset($_REQUEST['_date_to'])){
            if($_REQUEST['_date_to'] != ''){
                $date_to = date('Y-m-d', strtotime($_REQUEST['_date_to']));
                $record['filter']['_date_to'] = $_REQUEST['_date_to'];
            }
        }

        $label[-2]      = 'APPAREL';
        $label[-1]      = '';
        $totals['-2']   = '';
        $totals['-1']   = 'TOTAL';
        $record['data'] = [];

        $start = strtotime($date_from);
        $end   = strtotime($date_to);
        $diff  = ceil(abs($end - $start) / 86400);
        for ($i = 0; $i <= $diff ; $i++) { 
            $date = date('d-m-Y', strtotime($date_from." + ".$i." days"));
            $label[$i] = $date;
        }

        $query="SELECT apparel.apparel_id,
                UPPER(apparel.apparel_name) as apparel_name
                FROM order_trans ot
                INNER JOIN apparel_master apparel ON(apparel.apparel_id = ot.ot_apparel_id)
                GROUP BY apparel.apparel_id
                ORDER BY apparel.apparel_name ASC";
        $data = $this->db->query($query)->result_array();
        if(!empty($data)){
            foreach ($data as $key => $value) {
                $record['data'][$key]['apparel_name'] = $value['apparel_name'];
                $record['data'][$key][-1] = 0;
                for ($i = 0; $i <= $diff ; $i++) { 
                    $date   = date('Y-m-d', strtotime($date_from." + ".$i." days"));
                    $result = $this->get_apparel_count($value['apparel_id'], $date);
                    $record['data'][$key][$date] = $result;
                    $totals[$date] = isset($totals[$date]) ?  ($totals[$date] + $record['data'][$key][$date]) : $record['data'][$key][$date];
                    $record['data'][$key][-1] = $record['data'][$key][-1] + $result;       
                }
                if($record['data'][$key][-1] <= 0){
                    unset($record['data'][$key]);
                }
            }
        }
        $record['rows']  = isset($record['data']) ? count($record['data']) : 0;
        $record['label'] = $this->get_html($label);
        $record['totals']= $this->get_html($totals);
        // echo "<pre>"; print_r($record); exit;
        return $record;
    }
    public function get_apparel_count($apparel_id, $date) {
        $query="SELECT ot.ot_qty as qty
                FROM order_master om
                INNER JOIN order_trans ot ON(ot.ot_om_id = om.om_id)
                WHERE om.om_delete_status = 0
                AND ot.ot_delete_status = 0
                AND ot.ot_apparel_id = $apparel_id
                AND om.om_delivery_date = '".$date."'";
        $data = $this->db->query($query)->result_array();
        return empty($data) ? 0 : $data[0]['qty'];
    }
    public function get_html($data) {
        $tr = '<tr>';
        if(!empty($data)){
            foreach ($data as $key => $value) {
                $tr .= '<th width="5%">'.$value.'</th>';
            }
        }
        $tr .= '</tr>';
        return $tr;
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
            $subsql .= " AND (debit.customer_name LIKE '%".$name."%') ";
        }
        $query="SELECT debit.customer_name as id, 
                UPPER(debit.customer_name) as name
                FROM customer_master debit
                INNER JOIN customer_master customer ON(customer.customer_id = debit.customer_debit_id)
                WHERE debit.customer_status = 1
                $subsql
                GROUP BY debit.customer_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
}
?>