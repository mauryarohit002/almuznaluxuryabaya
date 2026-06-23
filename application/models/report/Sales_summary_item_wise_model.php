<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
require_once APPPATH . 'core/MY_Model.php';
class sales_summary_item_wise_model extends my_model{
    public function __construct(){ parent::__construct('report', 'sales_summary_item_wise'); }
    public function get_record(){  
        $record     = [];
        $subsql 	= '';
        $having 	= ''; 
        if(isset($_REQUEST['_entry_no']) && !empty($_REQUEST['_entry_no'])){
            $subsql .=" AND om.om_entry_no = '".$_REQUEST['_entry_no']."'";
            $record['filter']['_entry_no']['value'] = $_REQUEST['_entry_no'];
            $record['filter']['_entry_no']['text']  = $_REQUEST['_entry_no'];
        }
        if(isset($_REQUEST['_customer_name']) && !empty($_REQUEST['_customer_name'])){
            $subsql .=" AND customer.customer_name = '".$_REQUEST['_customer_name']."'";
            $record['filter']['_customer_name']['value'] = $_REQUEST['_customer_name'];
            $record['filter']['_customer_name']['text']  = $_REQUEST['_customer_name'];
        }
        if(isset($_REQUEST['_user_name']) && !empty($_REQUEST['_user_name'])){
            $subsql .=" AND user.user_fullname = '".$_REQUEST['_user_name']."'";
            $record['filter']['_user_name']['value'] = $_REQUEST['_user_name'];
            $record['filter']['_user_name']['text']  = $_REQUEST['_user_name'];
        }
        
        if(isset($_REQUEST['_trans_type']) && !empty($_REQUEST['_trans_type'])){
            $subsql .=" AND ot.ot_trans_type = '".$_REQUEST['_trans_type']."'";
            $record['filter']['_trans_type']['value'] = $_REQUEST['_trans_type'];
            $record['filter']['_trans_type']['text']  = $_REQUEST['_trans_type'];
        }
        if(isset($_REQUEST['_entry_date_from'])){
            if($_REQUEST['_entry_date_from'] != ''){
                $subsql .=" AND om.om_entry_date >= '".$_REQUEST['_entry_date_from']."'";
                $record['filter']['_entry_date_from'] = $_REQUEST['_entry_date_from'];
            }
        }
        if(isset($_REQUEST['_entry_date_to'])){
            if($_REQUEST['_entry_date_to'] != ''){
                $subsql .=" AND om.om_entry_date <= '".$_REQUEST['_entry_date_to']."'";
                $record['filter']['_entry_date_to'] = $_REQUEST['_entry_date_to'];
            }
        }
        if(isset($_REQUEST['_qty_from'])){
            if($_REQUEST['_qty_from'] != ''){
                $subsql .=" AND ot.ot_qty >= ".$_REQUEST['_qty_from'];
                $record['filter']['_qty_from'] = $_REQUEST['_qty_from'];
            }
        }
        if(isset($_REQUEST['_qty_to'])){
            if($_REQUEST['_qty_to'] != ''){
                $subsql .=" AND ot.ot_qty <= ".$_REQUEST['_qty_to'];
                $record['filter']['_qty_to'] = $_REQUEST['_qty_to'];
            }
        }
        if(isset($_REQUEST['_rate_from'])){
            if($_REQUEST['_rate_from'] != ''){
                $subsql .=" AND ot.ot_rate >= ".$_REQUEST['_rate_from'];
                $record['filter']['_rate_from'] = $_REQUEST['_rate_from'];
            }
        }
        if(isset($_REQUEST['_rate_to'])){
            if($_REQUEST['_rate_to'] != ''){
                $subsql .=" AND ot.ot_rate <= ".$_REQUEST['_rate_to'];
                $record['filter']['_rate_to'] = $_REQUEST['_rate_to'];
            }
        }
        if(isset($_REQUEST['_sub_amt_from'])){
            if($_REQUEST['_sub_amt_from'] != ''){
                $subsql .=" AND ot.ot_amt >= ".$_REQUEST['_sub_amt_from'];
                $record['filter']['_sub_amt_from'] = $_REQUEST['_sub_amt_from'];
            }
        }
        if(isset($_REQUEST['_sub_amt_to'])){
            if($_REQUEST['_sub_amt_to'] != ''){
                $subsql .=" AND ot.ot_amt <= ".$_REQUEST['_sub_amt_to'];
                $record['filter']['_sub_amt_to'] = $_REQUEST['_sub_amt_to'];
            }
        }
        if(isset($_REQUEST['_disc_amt_from'])){
            if($_REQUEST['_disc_amt_from'] != ''){
                $subsql .=" AND ot.ot_disc_amt >= ".$_REQUEST['_disc_amt_from'];
                $record['filter']['_disc_amt_from'] = $_REQUEST['_disc_amt_from'];
            }
        }
        if(isset($_REQUEST['_disc_amt_to'])){
            if($_REQUEST['_disc_amt_to'] != ''){
                $subsql .=" AND ot.ot_disc_amt <= ".$_REQUEST['_disc_amt_to'];
                $record['filter']['_disc_amt_to'] = $_REQUEST['_disc_amt_to'];
            }
        }
        if(isset($_REQUEST['_taxable_amt_from'])){
            if($_REQUEST['_taxable_amt_from'] != ''){
                $subsql .=" AND ot.ot_taxable_amt >= ".$_REQUEST['_taxable_amt_from'];
                $record['filter']['_taxable_amt_from'] = $_REQUEST['_taxable_amt_from'];
            }
        }
        if(isset($_REQUEST['_taxable_amt_to'])){
            if($_REQUEST['_taxable_amt_to'] != ''){
                $subsql .=" AND ot.ot_taxable_amt <= ".$_REQUEST['_taxable_amt_to'];
                $record['filter']['_taxable_amt_to'] = $_REQUEST['_taxable_amt_to'];
            }
        }
        if(isset($_REQUEST['_sgst_amt_from'])){
            if($_REQUEST['_sgst_amt_from'] != ''){
                $subsql .=" AND ot.ot_sgst_amt >= ".$_REQUEST['_sgst_amt_from'];
                $record['filter']['_sgst_amt_from'] = $_REQUEST['_sgst_amt_from'];
            }
        }
        if(isset($_REQUEST['_sgst_amt_to'])){
            if($_REQUEST['_sgst_amt_to'] != ''){
                $subsql .=" AND ot.ot_sgst_amt <= ".$_REQUEST['_sgst_amt_to'];
                $record['filter']['_sgst_amt_to'] = $_REQUEST['_sgst_amt_to'];
            }
        }
        if(isset($_REQUEST['_cgst_amt_from'])){
            if($_REQUEST['_cgst_amt_from'] != ''){
                $subsql .=" AND ot.ot_cgst_amt >= ".$_REQUEST['_cgst_amt_from'];
                $record['filter']['_cgst_amt_from'] = $_REQUEST['_cgst_amt_from'];
            }
        }
        if(isset($_REQUEST['_cgst_amt_to'])){
            if($_REQUEST['_cgst_amt_to'] != ''){
                $subsql .=" AND ot.ot_cgst_amt <= ".$_REQUEST['_cgst_amt_to'];
                $record['filter']['_cgst_amt_to'] = $_REQUEST['_cgst_amt_to'];
            }
        }
        if(isset($_REQUEST['_igst_amt_from'])){
            if($_REQUEST['_igst_amt_from'] != ''){
                $subsql .=" AND ot.ot_igst_amt >= ".$_REQUEST['_igst_amt_from'];
                $record['filter']['_igst_amt_from'] = $_REQUEST['_igst_amt_from'];
            }
        }
        if(isset($_REQUEST['_igst_amt_to'])){
            if($_REQUEST['_igst_amt_to'] != ''){
                $subsql .=" AND ot.ot_igst_amt <= ".$_REQUEST['_igst_amt_to'];
                $record['filter']['_igst_amt_to'] = $_REQUEST['_igst_amt_to'];
            }
        }
        if(isset($_REQUEST['_total_amt_from'])){
            if($_REQUEST['_total_amt_from'] != ''){
                $subsql .=" AND ot.ot_total_amt >= ".$_REQUEST['_total_amt_from'];
                $record['filter']['_total_amt_from'] = $_REQUEST['_total_amt_from'];
            }
        }
        if(isset($_REQUEST['_total_amt_to'])){
            if($_REQUEST['_total_amt_to'] != ''){
                $subsql .=" AND ot.ot_total_amt <= ".$_REQUEST['_total_amt_to'];
                $record['filter']['_total_amt_to'] = $_REQUEST['_total_amt_to'];
            }
        }
        $query="SELECT IF(om.om_status=0,'ESTIMATE','ORDER') as module_name, 
                IF(om.om_status=0,om.om_em_entry_no,om.om_entry_no) as entry_no,
                IF(om.om_status=0,DATE_FORMAT(om.om_em_entry_date, '%d-%m-%Y'),DATE_FORMAT(om.om_entry_date, '%d-%m-%Y')) as entry_date,
                UPPER(customer.customer_name) as customer_name,
                customer.customer_mobile as customer_mobile,
                IFNULL(UPPER(user.user_fullname),'') as user_name,
                ot.ot_trans_type as trans_type,
                ot.ot_qty as qty, 
                ot.ot_rate as rate,
                ot.ot_amt as sub_amt,
                ot.ot_disc_per as disc_per,
                ot.ot_disc_amt as disc_amt,
                ot.ot_taxable_amt as taxable_amt,
                ot.ot_sgst_per as sgst_per,
                ot.ot_sgst_amt as sgst_amt,
                ot.ot_cgst_per as cgst_per,
                ot.ot_cgst_amt as cgst_amt,
                ot.ot_igst_per as igst_per,
                ot.ot_igst_amt as igst_amt,
                ot.ot_total_amt as total_amt,
                IFNULL(UPPER(apparel.apparel_name), '') as apparel_name,
                om.om_created_at as created_at
                FROM order_master om
                INNER JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)
                INNER JOIN order_trans ot ON(ot.ot_om_id = om.om_id)
                LEFT JOIN apparel_master apparel ON(apparel.apparel_id = ot.ot_apparel_id)
                LEFT JOIN barcode_master bm ON(bm.bm_id = ot.ot_bm_id)
                LEFT JOIN user_master user ON(user.user_id = om.om_salesman_id)
                WHERE om.om_delete_status = 0
                AND ot.ot_delete_status = 0
                $subsql
                ORDER BY created_at DESC";
        $data = $this->db->query($query)->result_array();
        // echo "<pre>"; print_r($query); exit();
        // echo "<pre>"; print_r($data); exit();
        
        $record['totals']['rows']           = count($data);
        $record['totals']['qty']            = 0;
        $record['totals']['sub_amt']        = 0;
        $record['totals']['disc_amt']       = 0;
        $record['totals']['taxable_amt']    = 0;
        $record['totals']['sgst_amt']       = 0;
        $record['totals']['cgst_amt']       = 0;
        $record['totals']['igst_amt']       = 0;
        $record['totals']['total_amt']      = 0;
        $record['data']                     = [];
        if(!empty($data)){
            foreach ($data as $key => $value) {
                array_push($record['data'], [
                                                'module_name'       => $value['module_name'],
                                                'entry_no'          => (int)$value['entry_no'],
                                                'entry_date' 	    => (int)strtotime($value['entry_date']),
                                                'entry_date1' 	    => $value['entry_date'],
                                                'customer_name'     => $value['customer_name'],
                                                'user_name'     => $value['user_name'],
                                                'trans_type'        => $value['trans_type'],
                                                'qty' 		        => (int)$value['qty'],
                                               
                                                'rate' 		        => (float)$value['rate'],
                                                'sub_amt' 		    => (float)$value['sub_amt'],
                                                'disc_amt' 		    => (float)$value['disc_amt'],
                                                'taxable_amt' 		=> (float)$value['taxable_amt'],
                                                'sgst_amt' 		    => (float)$value['sgst_amt'],
                                                'cgst_amt' 		    => (float)$value['cgst_amt'],
                                                'igst_amt' 		    => (float)$value['igst_amt'],
                                                'total_amt'         => (float)$value['total_amt'],
                                                'apparel_name'      => $value['apparel_name'],
                                                
                                                'created_at'        => strtotime($value['created_at']),
                                            ]);

                $record['totals']['sub_amt'] 	    = $record['totals']['sub_amt'] 		    + $value['sub_amt'];
                $record['totals']['disc_amt'] 	    = $record['totals']['disc_amt'] 		+ $value['disc_amt'];
                $record['totals']['taxable_amt'] 	= $record['totals']['taxable_amt'] 		+ $value['taxable_amt'];
                $record['totals']['sgst_amt'] 	    = $record['totals']['sgst_amt'] 		+ $value['sgst_amt'];
                $record['totals']['cgst_amt'] 	    = $record['totals']['cgst_amt'] 		+ $value['cgst_amt'];
                $record['totals']['igst_amt'] 	    = $record['totals']['igst_amt'] 		+ $value['igst_amt'];
                $record['totals']['total_amt'] 	    = $record['totals']['total_amt'] 		+ $value['total_amt'];
            }
        }
        
        // echo "<pre>"; print_r($record); exit();
        return $record;
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
        $query="SELECT om.om_entry_no as id , 
                UPPER(om.om_entry_no) as name 
                FROM order_master om 
                WHERE om.om_delete_status = 0
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
            $subsql .= " AND (customer.customer_name LIKE '%".$name."%') ";
        }
        $query="SELECT customer.customer_name as id , 
                UPPER(customer.customer_name) as name 
                FROM order_master om 
                INNER JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)
                WHERE om.om_delete_status = 0
                $subsql
                GROUP BY customer.customer_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _apparel_name(){
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
            $subsql .= " AND (apparel.apparel_name LIKE '%".$name."%') ";
        }
        $query="SELECT apparel.apparel_name as id, 
                UPPER(apparel.apparel_name) as name 
                FROM order_master om 
                INNER JOIN order_trans ot ON(ot.ot_om_id = om.om_id)
                INNER JOIN apparel_master apparel ON(apparel.apparel_id = ot.ot_apparel_id)
                WHERE om.om_delete_status = 0
                AND ot.ot_delete_status = 0
                $subsql
                GROUP BY apparel.apparel_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    
    public function _user_name(){
        $subsql = "";
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
            $subsql .= " AND (user.user_fullname LIKE '%".$name."%') ";
        }
        if(isset($_GET['param']) && !empty($_GET['param'])){
            $name   = $_GET['param'];
            $subsql .= " AND (user.user_status = ".$name.") ";
        }
        if(isset($_GET['param1']) && !empty($_GET['param1'])){
            $name   = $_GET['param1'];
            $subsql .= " AND (role.role_name like '%".$name."%') ";
        }
        $query="SELECT user.user_fullname as id, UPPER(user.user_fullname) as name
                FROM user_master user
                left JOIN role_master role ON(role.role_id = user.user_role_id)
                WHERE 1
                $subsql
                GROUP BY user.user_fullname ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }

}
?>