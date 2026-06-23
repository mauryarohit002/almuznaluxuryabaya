<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php'; 
class order_status_model extends my_model{
    public function __construct(){ parent::__construct('report', 'order_status'); }
    public function get_record(){
        $record     = [];
        $subsql     = '';
        $having     =''; 
        if(isset($_REQUEST['_customer_name']) && !empty($_REQUEST['_customer_name'])){
            $subsql .=" AND customer.customer_name = '".$_REQUEST['_customer_name']."'";
            $record['filter']['_customer_name']['value'] = $_REQUEST['_customer_name'];
            $record['filter']['_customer_name']['text']  = $_REQUEST['_customer_name'];
        }

        $date_from = isset($_REQUEST['_date_from']) && !empty($_REQUEST['_date_from']) 
        ? $_REQUEST['_date_from'] 
        : date('Y-m-d');
        $date_to = isset($_REQUEST['_date_to']) && !empty($_REQUEST['_date_to']) 
        ? $_REQUEST['_date_to'] 
        : date('Y-m-d');

        $subsql .= " AND (
                (om.om_status = 0 AND om.om_em_entry_date BETWEEN '$date_from' AND '$date_to')
                OR
                (om.om_status != 0 AND om.om_entry_date BETWEEN '$date_from' AND '$date_to'))";

        $record['filter']['_date_from'] = $date_from;
        $record['filter']['_date_to'] = $date_to;

        // if(isset($_REQUEST['_date_from']) && !empty($_REQUEST['_date_from'])){ 
        //     $having .=" AND entry_date >= '".$_REQUEST['_date_from']."'";
        //     $record['filter']['_date_from'] = $_REQUEST['_date_from'];
        // }else{
        //     $having .=" AND entry_date >= '".date('Y-m-d')."'";
        // }  
        // if(isset($_REQUEST['_date_to']) && !empty($_REQUEST['_date_to'])){ 
        //         $having .=" AND entry_date <= '".$_REQUEST['_date_to']."'";
        //         $record['filter']['_date_to'] = $_REQUEST['_date_to'];
        // }else{
        //     $having .=" AND entry_date <= '".date('Y-m-d')."'";
        // }  

        $query = "SELECT 
            obt.obt_id,  
            UPPER(customer.customer_name) as customer_name,
            customer.customer_mobile,
            UPPER(apparel.apparel_name) as apparel_name,
            IF(om.om_status=0,om.om_em_entry_no,om.om_entry_no) as entry_no,
            IF(om.om_status=0,DATE_FORMAT(om.om_em_entry_date, '%d-%m-%Y'),DATE_FORMAT(om.om_entry_date, '%d-%m-%Y')) as entry_date,
            obt.obt_item_code as qrcode
        FROM order_master om
        INNER JOIN customer_master customer ON customer.customer_id = om.om_customer_id
        INNER JOIN order_trans ot ON ot.ot_om_id = om.om_id
        INNER JOIN order_barcode_trans obt ON obt.obt_ot_id = ot.ot_id
        INNER JOIN apparel_master apparel ON apparel.apparel_id = obt.obt_apparel_id
        WHERE om.om_delete_status = 0
          AND ot.ot_delete_status = 0
          AND obt.obt_delete_status = 0
          $subsql
        GROUP BY obt.obt_id
        ORDER BY 
            IF(om.om_status = 0, om.om_em_entry_date, om.om_entry_date) DESC";
        $data = $this->db->query($query)->result_array();
        // echo "<pre>"; print_r($query); exit();
        // echo "<pre>"; print_r($data); exit();
        
        $record['totals']['rows']   = count($data);
        $record['data']             = [];
        if(!empty($data)){ 
            foreach ($data as $key => $value) { 
                $status_data = $this->get_status_data($value['obt_id']);
                array_push($record['data'], [
                                    'customer_name'         => $value['customer_name'],
                                    'apparel_name'          => $value['apparel_name'],
                                    'qrcode'                => $value['qrcode'],
                                    'entry_no'              => (int)$value['entry_no'],
                                    'entry_date'            => $value['entry_date'],
                                    'entry_date1'           => (int)strtotime(date('d-m-Y',strtotime($value['entry_date'])).'-'.date('Y')),
                                   
                                    'customer_mobile'       => $value['customer_mobile'],
                                    'karigar_name'          => $status_data['karigar_name'],
                                    'proces_name'           => $status_data['proces_name'],
                                    'status'                => $status_data['status'],
                                ]);
            }
        }
        
        // echo "<pre>"; print_r($record); exit();
        return $record;
    }
    
    public function get_status_data($obt_id) {
        $query="SELECT 
                UPPER(karigar.karigar_name) as karigar_name,
                UPPER(proces.proces_name) as proces_name,
                'ISSUED' as status
                FROM job_issue_trans jit
                INNER JOIN job_issue_master jim ON(jim.jim_id = jit.jit_jim_id)
                INNER JOIN karigar_master karigar ON(karigar.karigar_id = jim.jim_karigar_id)
                INNER JOIN proces_master proces ON(proces.proces_id = jim.jim_proces_id)
                LEFT JOIN job_receive_trans jrt ON(jrt.jrt_jit_id = jit.jit_id)
                WHERE jim.jim_delete_status = 0
                AND jit.jit_obt_id = $obt_id
                AND IFNULL(jrt.jrt_jit_id, 0)=0
                ORDER BY jit.jit_id DESC
                LIMIT 1";
        $data = $this->db->query($query)->result_array();
        // echo "<pre>"; print_r($query); exit();
        // echo "<pre>"; print_r($data); exit();
        if(!empty($data)){
            return ['karigar_name' => $data[0]['karigar_name'], 'proces_name' => $data[0]['proces_name'], 'status' =>  $data[0]['status']];
        }

        $query="SELECT 
                UPPER(karigar.karigar_name) as karigar_name,
                UPPER(proces.proces_name) as proces_name,
                'RECEIVED' as status
                FROM job_receive_trans jrt
                INNER JOIN job_issue_master jim ON(jim.jim_id = jrt.jrt_jim_id)
                INNER JOIN job_receive_master jrm ON(jrm.jrm_id = jrt.jrt_jrm_id)
                INNER JOIN karigar_master karigar ON(karigar.karigar_id = jim.jim_karigar_id)
                INNER JOIN proces_master proces ON(proces.proces_id = jim.jim_proces_id)
                WHERE jrm.jrm_delete_status = 0
                AND jrt.jrt_obt_id = $obt_id
                AND jrt.jrt_jim_id != 0
                ORDER BY jrt.jrt_id DESC
                LIMIT 1";
        $data = $this->db->query($query)->result_array();
        // echo "<pre>"; print_r($query); exit();
        // echo "<pre>"; print_r($data); exit();

        if(!empty($data)){
            return ['karigar_name' => $data[0]['karigar_name'], 'proces_name' => $data[0]['proces_name'], 'status' =>  $data[0]['status']];
        }

        

        return ['karigar_name' => 'NOT ASSIGN', 'proces_name' => 'NOT DEFINE', 'status' => 'PENDING'];
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
            $subsql .= " AND (om.om_entry_no LIKE '%".$name."%') ";
        }
        $query="SELECT om.om_entry_no as id, 
                UPPER(om.om_entry_no) as name
                FROM order_master om
                WHERE om.om_status = 1
                AND om.om_entry_no != '' 
                $subsql
                GROUP BY om.om_entry_no ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _customer_name(){
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
            $subsql .= " AND (customer.customer_name LIKE '".$name."%') ";
        } 
        $query="SELECT customer.customer_name as id, 
                UPPER(customer.customer_name) as name
                FROM order_master om
                INNER JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)
                WHERE customer.customer_status = 1
                AND customer.customer_name != '' 
                AND customer.customer_mobile != ''
                $subsql
                GROUP BY customer.customer_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
}
?>