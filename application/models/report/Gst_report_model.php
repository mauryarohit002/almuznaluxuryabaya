<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class gst_report_model extends my_model{
    public function __construct(){ parent::__construct('report', 'gst_report'); }
    public function get_record(){ 
        $record     = [];
        $subsql 	= '';
        $having 	= '';
        if(isset($_REQUEST['_entry_no']) && !empty($_REQUEST['_entry_no'])){
            $having .=" AND entry_no = '".$_REQUEST['_entry_no']."'";
            $record['filter']['_entry_no']['value'] = $_REQUEST['_entry_no'];
            $record['filter']['_entry_no']['text']  = $_REQUEST['_entry_no'];
        }
        if(isset($_REQUEST['_memo_no']) && !empty($_REQUEST['_memo_no'])){
            $having .=" AND memo_no = '".$_REQUEST['_memo_no']."'";
            $record['filter']['_memo_no']['value'] = $_REQUEST['_memo_no'];
            $record['filter']['_memo_no']['text']  = $_REQUEST['_memo_no'];
        }
        if(isset($_REQUEST['_company_name']) && !empty($_REQUEST['_company_name'])){
            $having .=" AND company_name = '".$_REQUEST['_company_name']."'";
            $record['filter']['_company_name']['value'] = $_REQUEST['_company_name'];
            $record['filter']['_company_name']['text']  = $_REQUEST['_company_name'];
        }
        if(isset($_REQUEST['_module_name']) && !empty($_REQUEST['_module_name'])){
            $having .=" AND module_name = '".$_REQUEST['_module_name']."'";
            $record['filter']['_module_name']['value'] = $_REQUEST['_module_name'];
            $record['filter']['_module_name']['text']  = $_REQUEST['_module_name'];
        }
        if(isset($_REQUEST['_billing_name']) && !empty($_REQUEST['_billing_name'])){
            $having .=" AND billing_name = '".$_REQUEST['_billing_name']."'";
            $record['filter']['_billing_name']['value'] = $_REQUEST['_billing_name'];
            $record['filter']['_billing_name']['text']  = $_REQUEST['_billing_name'];
        }
        if(isset($_REQUEST['_billing_mobile']) && !empty($_REQUEST['_billing_mobile'])){
            $having .=" AND billing_mobile = '".$_REQUEST['_billing_mobile']."'";
            $record['filter']['_billing_mobile']['value'] = $_REQUEST['_billing_mobile'];
            $record['filter']['_billing_mobile']['text']  = $_REQUEST['_billing_mobile'];
        }
        if(isset($_REQUEST['_apparel_name']) && !empty($_REQUEST['_apparel_name'])){
            $having .=" AND apparel_name = '".$_REQUEST['_apparel_name']."'";
            $record['filter']['_apparel_name']['value'] = $_REQUEST['_apparel_name'];
            $record['filter']['_apparel_name']['text']  = $_REQUEST['_apparel_name'];
        }
        if(isset($_REQUEST['_hsn_name']) && !empty($_REQUEST['_hsn_name'])){
            $having .=" AND hsn_name = '".$_REQUEST['_hsn_name']."'";
            $record['filter']['_hsn_name']['value'] = $_REQUEST['_hsn_name'];
            $record['filter']['_hsn_name']['text']  = $_REQUEST['_hsn_name'];
        }
        if(isset($_REQUEST['_entry_date_from'])){
            if($_REQUEST['_entry_date_from'] != ''){
                $having .=" AND entry_date >= '".$_REQUEST['_entry_date_from']."'";
                $record['filter']['_entry_date_from'] = $_REQUEST['_entry_date_from'];
            }
        }
        if(isset($_REQUEST['_entry_date_to'])){
            if($_REQUEST['_entry_date_to'] != ''){
                $having .=" AND entry_date <= '".$_REQUEST['_entry_date_to']."'";
                $record['filter']['_entry_date_to'] = $_REQUEST['_entry_date_to'];
            }
        }
        if(isset($_REQUEST['_total_mtr_from'])){
            if($_REQUEST['_total_mtr_from'] != ''){
                $having .=" AND total_mtr >= ".$_REQUEST['_total_mtr_from'];
                $record['filter']['_total_mtr_from'] = $_REQUEST['_total_mtr_from'];
            }
        }
        if(isset($_REQUEST['_total_mtr_to'])){
            if($_REQUEST['_total_mtr_to'] != ''){
                $having .=" AND total_mtr <= ".$_REQUEST['_total_mtr_to'];
                $record['filter']['_total_mtr_to'] = $_REQUEST['_total_mtr_to'];
            }
        }
        if(isset($_REQUEST['_rate_from'])){
            if($_REQUEST['_rate_from'] != ''){
                $having .=" AND rate >= ".$_REQUEST['_rate_from'];
                $record['filter']['_rate_from'] = $_REQUEST['_rate_from'];
            }
        }
        if(isset($_REQUEST['_rate_to'])){
            if($_REQUEST['_rate_to'] != ''){
                $having .=" AND rate <= ".$_REQUEST['_rate_to'];
                $record['filter']['_rate_to'] = $_REQUEST['_rate_to'];
            }
        }
        if(isset($_REQUEST['_amt_from'])){
            if($_REQUEST['_amt_from'] != ''){
                $having .=" AND amt >= ".$_REQUEST['_amt_from'];
                $record['filter']['_amt_from'] = $_REQUEST['_amt_from'];
            }
        }
        if(isset($_REQUEST['_amt_to'])){
            if($_REQUEST['_amt_to'] != ''){
                $having .=" AND amt <= ".$_REQUEST['_amt_to'];
                $record['filter']['_amt_to'] = $_REQUEST['_amt_to'];
            }
        }
        if(isset($_REQUEST['_taxable_amt_from'])){
            if($_REQUEST['_taxable_amt_from'] != ''){
                $having .=" AND taxable_amt >= ".$_REQUEST['_taxable_amt_from'];
                $record['filter']['_taxable_amt_from'] = $_REQUEST['_taxable_amt_from'];
            }
        }
        if(isset($_REQUEST['_taxable_amt_to'])){
            if($_REQUEST['_taxable_amt_to'] != ''){
                $having .=" AND taxable_amt <= ".$_REQUEST['_taxable_amt_to'];
                $record['filter']['_taxable_amt_to'] = $_REQUEST['_taxable_amt_to'];
            }
        }
        if(isset($_REQUEST['_sgst_amt_from'])){
            if($_REQUEST['_sgst_amt_from'] != ''){
                $having .=" AND sgst_amt >= ".$_REQUEST['_sgst_amt_from'];
                $record['filter']['_sgst_amt_from'] = $_REQUEST['_sgst_amt_from'];
            }
        }
        if(isset($_REQUEST['_sgst_amt_to'])){
            if($_REQUEST['_sgst_amt_to'] != ''){
                $having .=" AND sgst_amt <= ".$_REQUEST['_sgst_amt_to'];
                $record['filter']['_sgst_amt_to'] = $_REQUEST['_sgst_amt_to'];
            }
        }
        if(isset($_REQUEST['_cgst_amt_from'])){
            if($_REQUEST['_cgst_amt_from'] != ''){
                $having .=" AND cgst_amt >= ".$_REQUEST['_cgst_amt_from'];
                $record['filter']['_cgst_amt_from'] = $_REQUEST['_cgst_amt_from'];
            }
        }
        if(isset($_REQUEST['_cgst_amt_to'])){
            if($_REQUEST['_cgst_amt_to'] != ''){
                $having .=" AND cgst_amt <= ".$_REQUEST['_cgst_amt_to'];
                $record['filter']['_cgst_amt_to'] = $_REQUEST['_cgst_amt_to'];
            }
        }
        if(isset($_REQUEST['_igst_amt_from'])){
            if($_REQUEST['_igst_amt_from'] != ''){
                $having .=" AND igst_amt >= ".$_REQUEST['_igst_amt_from'];
                $record['filter']['_igst_amt_from'] = $_REQUEST['_igst_amt_from'];
            }
        }
        if(isset($_REQUEST['_igst_amt_to'])){
            if($_REQUEST['_igst_amt_to'] != ''){
                $having .=" AND igst_amt <= ".$_REQUEST['_igst_amt_to'];
                $record['filter']['_igst_amt_to'] = $_REQUEST['_igst_amt_to'];
            }
        }
        if(isset($_REQUEST['_bill_disc_from'])){
            if($_REQUEST['_bill_disc_from'] != ''){
                $having .=" AND bill_disc_amt >= ".$_REQUEST['_bill_disc_from'];
                $record['filter']['_bill_disc_from'] = $_REQUEST['_bill_disc_from'];
            }
        }
        if(isset($_REQUEST['_bill_disc_to'])){
            if($_REQUEST['_bill_disc_to'] != ''){
                $having .=" AND bill_disc_amt <= ".$_REQUEST['_bill_disc_to'];
                $record['filter']['_bill_disc_to'] = $_REQUEST['_bill_disc_to'];
            }
        }
        if(isset($_REQUEST['_total_amt_from'])){
            if($_REQUEST['_total_amt_from'] != ''){
                $having .=" AND total_amt >= ".$_REQUEST['_total_amt_from'];
                $record['filter']['_total_amt_from'] = $_REQUEST['_total_amt_from'];
            }
        }
        if(isset($_REQUEST['_total_amt_to'])){
            if($_REQUEST['_total_amt_to'] != ''){
                $having .=" AND total_amt <= ".$_REQUEST['_total_amt_to'];
                $record['filter']['_total_amt_to'] = $_REQUEST['_total_amt_to'];
            }
        }
        if(isset($_REQUEST['_advance_amt_from'])){
            if($_REQUEST['_advance_amt_from'] != ''){
                $having .=" AND advance_amt >= ".$_REQUEST['_advance_amt_from'];
                $record['filter']['_advance_amt_from'] = $_REQUEST['_advance_amt_from'];
            }
        }
        if(isset($_REQUEST['_advance_amt_to'])){
            if($_REQUEST['_advance_amt_to'] != ''){
                $having .=" AND advance_amt <= ".$_REQUEST['_advance_amt_to'];
                $record['filter']['_advance_amt_to'] = $_REQUEST['_advance_amt_to'];
            }
        }
        if(isset($_REQUEST['_balance_amt_from'])){
            if($_REQUEST['_balance_amt_from'] != ''){
                $having .=" AND balance_amt >= ".$_REQUEST['_balance_amt_from'];
                $record['filter']['_balance_amt_from'] = $_REQUEST['_balance_amt_from'];
            }
        }
        if(isset($_REQUEST['_balance_amt_to'])){
            if($_REQUEST['_balance_amt_to'] != ''){
                $having .=" AND balance_amt <= ".$_REQUEST['_balance_amt_to'];
                $record['filter']['_balance_amt_to'] = $_REQUEST['_balance_amt_to'];
            }
        }
        
        $of_query ="SELECT 'ORDER' as module_name,
                    'fabric' as company_name,
                    om.om_entry_no as entry_no,
                    DATE_FORMAT(om.om_entry_date, '%d-%m-%Y') as entry_date,
                    IF(om.om_trial_date = '' , '', DATE_FORMAT(om.om_trial_date, '%d-%m-%Y')) as trial_date,
                    IF(om.om_delivery_date = '' , '', DATE_FORMAT(om.om_delivery_date, '%d-%m-%Y')) as delivery_date,
                    UPPER(billing.customer_name) as billing_name,
                    billing.customer_mobile as billing_mobile,
                    UPPER(om.om_customer_name) as customer_name,
                    om.om_customer_mobile as customer_mobile,
                    IF(ot.ot_bm_id > 0, IFNULL(UPPER(fabric.fabric_name), ''), IFNULL(UPPER(apparel.apparel_name), '')) as apparel_name,
                    IFNULL(UPPER(hsn.hsn_name), '') as hsn_name,
                    SUM(ot.ot_total_mtr) as total_mtr, 
                    ot.ot_rate as rate,
                    SUM(ot.ot_taxable_amt) as amt,
                    0 as disc_amt,
                    0 as taxable_amt,
                    SUM(ot.ot_sgst_amt) as sgst_amt,
                    SUM(ot.ot_cgst_amt) as cgst_amt,
                    SUM(ot.ot_igst_amt) as igst_amt,
                    IF(ot.ot_igst_amt > 0 , ot.ot_igst_per, (ot.ot_sgst_per + ot.ot_cgst_per)) as tax_per,
                    SUM(ot.ot_sgst_amt + ot.ot_cgst_amt + ot.ot_igst_amt) as tax_amt,
                    0 as bill_disc_per,
                    0 as bill_disc_amt,
                    0 as round_off,
                    SUM(ot.ot_total_amt) as total_amt,
                    0 as advance_amt,
                    0 as balance_amt,
                    om.om_created_at as created_at
                    FROM order_master om
                    INNER JOIN customer_master billing ON(billing.customer_id = om.om_customer_id)
                    INNER JOIN order_trans ot ON(ot.ot_om_id = om.om_id)
                    LEFT JOIN apparel_master apparel ON(apparel.apparel_id = ot.ot_apparel_id)
                    LEFT JOIN barcode_master bm ON(bm.bm_id = ot.ot_bm_id)
                    LEFT JOIN fabric_master fabric ON(fabric.fabric_id = bm.bm_fabric_id)
                    LEFT JOIN hsn_master hsn ON(hsn.hsn_id = bm.bm_hsn_id)
                    WHERE om.om_delete_status = 0
                    AND ot.ot_delete_status = 0
                    AND ot.ot_trans_type = 'FABRIC'
                    GROUP BY fabric.fabric_id, hsn.hsn_id"; 
        $os_query ="SELECT 'ORDER' as module_name,
                    'stitching' as company_name,
                    om.om_entry_no as entry_no,
                    DATE_FORMAT(om.om_entry_date, '%d-%m-%Y') as entry_date,
                    IF(om.om_trial_date = '' , '', DATE_FORMAT(om.om_trial_date, '%d-%m-%Y')) as trial_date,
                    IF(om.om_delivery_date = '' , '', DATE_FORMAT(om.om_delivery_date, '%d-%m-%Y')) as delivery_date,
                    UPPER(billing.customer_name) as billing_name, 
                    billing.customer_mobile as billing_mobile,
                    UPPER(om.om_customer_name) as customer_name,
                    om.om_customer_mobile as customer_mobile,
                    IF(ot.ot_bm_id > 0, IFNULL(UPPER(fabric.fabric_name), ''), IFNULL(UPPER(apparel.apparel_name), '')) as apparel_name,
                    IFNULL(UPPER(hsn.hsn_name), '') as hsn_name,
                    0 as total_mtr,
                    ot.ot_rate as rate,
                    SUM(ot.ot_taxable_amt) as amt,
                    0 as disc_amt,
                    0 as taxable_amt,
                    SUM(ot.ot_sgst_amt) as sgst_amt,
                    SUM(ot.ot_cgst_amt) as cgst_amt,
                    SUM(ot.ot_igst_amt) as igst_amt,
                    IF(ot.ot_igst_amt > 0 , ot.ot_igst_per, (ot.ot_sgst_per + ot.ot_cgst_per)) as tax_per,
                    SUM(ot.ot_sgst_amt + ot.ot_cgst_amt + ot.ot_igst_amt) as tax_amt,
                    0 as bill_disc_per,
                    0 as bill_disc_amt,
                    0 as round_off,
                    SUM(ot.ot_total_amt) as total_amt,
                    0 as advance_amt,
                    0 as balance_amt,
                    om.om_created_at as created_at
                    FROM order_master om
                    INNER JOIN customer_master billing ON(billing.customer_id = om.om_customer_id)
                    INNER JOIN order_trans ot ON(ot.ot_om_id = om.om_id)
                    LEFT JOIN apparel_master apparel ON(apparel.apparel_id = ot.ot_apparel_id)
                    LEFT JOIN barcode_master bm ON(bm.bm_id = ot.ot_bm_id)
                    LEFT JOIN fabric_master fabric ON(fabric.fabric_id = bm.bm_fabric_id)
                    LEFT JOIN hsn_master hsn ON(hsn.hsn_id = bm.bm_hsn_id)
                    WHERE om.om_delete_status = 0
                    AND ot.ot_delete_status = 0
                    AND ot.ot_trans_type = 'STITCHING'
                    GROUP BY apparel.apparel_id"; 

        $op_query ="SELECT 'ORDER' as module_name, 
                    'package' as company_name,
                    om.om_entry_no as entry_no,
                    DATE_FORMAT(om.om_entry_date, '%d-%m-%Y') as entry_date,
                    IF(om.om_trial_date = '' , '', DATE_FORMAT(om.om_trial_date, '%d-%m-%Y')) as trial_date,
                    IF(om.om_delivery_date = '' , '', DATE_FORMAT(om.om_delivery_date, '%d-%m-%Y')) as delivery_date,
                    UPPER(billing.customer_name) as billing_name,
                    billing.customer_mobile as billing_mobile,
                    UPPER(om.om_customer_name) as customer_name,
                    om.om_customer_mobile as customer_mobile,
                    IF(ot.ot_bm_id > 0, IFNULL(UPPER(fabric.fabric_name), ''), IFNULL(UPPER(apparel.apparel_name), '')) as apparel_name,
                    IFNULL(UPPER(hsn.hsn_name), '') as hsn_name,
                    SUM(ot.ot_total_mtr) as total_mtr,  
                    ot.ot_rate as rate,
                    SUM(ot.ot_taxable_amt) as amt,
                    0 as disc_amt,
                    0 as taxable_amt,
                    SUM(ot.ot_sgst_amt) as sgst_amt,
                    SUM(ot.ot_cgst_amt) as cgst_amt,
                    SUM(ot.ot_igst_amt) as igst_amt,
                    IF(ot.ot_igst_amt > 0 , ot.ot_igst_per, (ot.ot_sgst_per + ot.ot_cgst_per)) as tax_per,
                    SUM(ot.ot_sgst_amt + ot.ot_cgst_amt + ot.ot_igst_amt) as tax_amt,
                    0 as bill_disc_per,
                    0 as bill_disc_amt,
                    0 as round_off,
                    SUM(ot.ot_total_amt) as total_amt,
                    0 as advance_amt,
                    0 as balance_amt,
                    om.om_created_at as created_at
                    FROM order_master om
                    INNER JOIN customer_master billing ON(billing.customer_id = om.om_customer_id)
                    INNER JOIN order_trans ot ON(ot.ot_om_id = om.om_id)
                    LEFT JOIN apparel_master apparel ON(apparel.apparel_id = ot.ot_apparel_id)
                    LEFT JOIN barcode_master bm ON(bm.bm_id = ot.ot_bm_id)
                    LEFT JOIN fabric_master fabric ON(fabric.fabric_id = bm.bm_fabric_id)
                    LEFT JOIN hsn_master hsn ON(hsn.hsn_id = bm.bm_hsn_id)
                    WHERE om.om_delete_status = 0
                    AND ot.ot_delete_status = 0
                    AND ot.ot_trans_type = 'PACKAGE'
                    GROUP BY fabric.fabric_id,apparel.apparel_id";             

        $query="SELECT module_name,
                company_name,
                entry_no,
                entry_date,
                trial_date,
                delivery_date,
                billing_name,
                billing_mobile,
                customer_name,
                customer_mobile,
                apparel_name,
                hsn_name,
                total_mtr,
                rate,
                amt,
                disc_amt,
                taxable_amt,
                sgst_amt,
                cgst_amt,
                igst_amt,
                tax_per,
                tax_amt,
                bill_disc_per,
                bill_disc_amt,
                round_off,
                total_amt,
                advance_amt,
                balance_amt,
                created_at
                FROM ($of_query UNION ALL $os_query UNION ALL $op_query) temp
                WHERE 1
                HAVING 1
                $having
                ORDER BY apparel_name ASC";
        $data = $this->db->query($query)->result_array();
        // echo "<pre>"; print_r($query); exit();
        // echo "<pre>"; print_r($data); exit();
        
        $record['totals']['rows']           = count($data);
        $record['totals']['total_mtr']      = 0;
        $record['totals']['amt']            = 0;
        $record['totals']['disc_amt']       = 0;
        $record['totals']['taxable_amt']    = 0;
        $record['totals']['sgst_amt']       = 0;
        $record['totals']['cgst_amt']       = 0;
        $record['totals']['igst_amt']       = 0;
        $record['totals']['tax_amt']        = 0;
        $record['totals']['bill_disc_amt']  = 0;
        $record['totals']['round_off']      = 0;
        $record['totals']['total_amt']      = 0;
        $record['totals']['advance_amt']    = 0;
        $record['totals']['balance_amt']    = 0;
        $record['data']                     = [];
        if(!empty($data)){
            foreach ($data as $key => $value) { 
                array_push($record['data'], [
                                                'module_name'       => $value['module_name'],
                                                'entry_no'          => (int)$value['entry_no'],
                                                'entry_date' 	    => (int)strtotime($value['entry_date']),
                                                'entry_date1' 	    => $value['entry_date'],
                                                'trial_date' 	    => $value['trial_date'],
                                                'delivery_date'     => $value['delivery_date'],
                                                'billing_name'      => $value['billing_name'],
                                                'billing_mobile'    => (int)$value['billing_mobile'],
                                                'customer_name'     => $value['customer_name'],
                                                'customer_mobile'   => (int)$value['customer_mobile'],
                                                'apparel_name'      => $value['apparel_name'],
                                                'hsn_name'          => $value['hsn_name'],
                                                'total_mtr' 		=> (float)$value['total_mtr'],
                                                'rate' 		        => (float)$value['rate'],
                                                'amt' 		        => (float)$value['amt'],
                                                'disc_amt' 		    => (float)$value['disc_amt'],
                                                'taxable_amt' 		=> (float)$value['taxable_amt'],
                                                'sgst_amt' 		    => (float)$value['sgst_amt'],
                                                'cgst_amt' 		    => (float)$value['cgst_amt'],
                                                'igst_amt' 		    => (float)$value['igst_amt'],
                                                'tax_per' 		    => (float)$value['tax_per'],
                                                'tax_amt' 		    => (float)$value['tax_amt'],
                                                'bill_disc_per'     => (float)$value['bill_disc_per'],
                                                'bill_disc_amt'     => (float)$value['bill_disc_amt'],
                                                'round_off'         => (float)$value['round_off'],
                                                'total_amt'         => (float)$value['total_amt'],
                                                'advance_amt'       => (float)$value['advance_amt'],
                                                'balance_amt'       => (float)$value['balance_amt'],
                                                'created_at'        => strtotime($value['created_at']),
                                            ]);

                $record['totals']['total_mtr'] 	    = $record['totals']['total_mtr'] 		+ $value['total_mtr'];
                $record['totals']['amt'] 	        = $record['totals']['amt'] 		        + $value['amt'];
                $record['totals']['disc_amt'] 	    = $record['totals']['disc_amt'] 		+ $value['disc_amt'];
                $record['totals']['taxable_amt'] 	= $record['totals']['taxable_amt'] 		+ $value['taxable_amt'];
                $record['totals']['sgst_amt'] 	    = $record['totals']['sgst_amt'] 		+ $value['sgst_amt'];
                $record['totals']['cgst_amt'] 	    = $record['totals']['cgst_amt'] 		+ $value['cgst_amt'];
                $record['totals']['igst_amt'] 	    = $record['totals']['igst_amt'] 		+ $value['igst_amt'];
                $record['totals']['tax_amt'] 	    = $record['totals']['tax_amt'] 		    + $value['tax_amt'];
                $record['totals']['bill_disc_amt'] 	= $record['totals']['bill_disc_amt'] 	+ $value['bill_disc_amt'];
                $record['totals']['round_off'] 	    = $record['totals']['round_off'] 		+ $value['round_off'];
                $record['totals']['total_amt'] 	    = $record['totals']['total_amt'] 		+ $value['total_amt'];
                $record['totals']['advance_amt'] 	= $record['totals']['advance_amt'] 		+ $value['advance_amt'];
                $record['totals']['balance_amt'] 	= $record['totals']['balance_amt'] 		+ $value['balance_amt'];
            }
        }
        
        // echo "<pre>"; print_r($record); exit();
        return $record;
    }
    public function _module_name(){
        return [0 => ['id' => 'ESTIMATE', 'name' => 'ESTIMATE'], 1 => ['id' => 'ORDER', 'name' => 'ORDER']];
    }
    public function _entry_no(){
        $having = '';
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
            $having.= " AND (name LIKE '%".$name."%') ";
        }
        $query="SELECT id, name
                FROM (
                        SELECT om.om_fabric_no as id , UPPER(om.om_fabric_no) as name 
                        FROM order_master om 
                        WHERE om.om_delete_status = 0
                        UNION ALL
                        SELECT om.om_stitching_no as id , UPPER(om.om_stitching_no) as name 
                        FROM order_master om 
                        WHERE om.om_delete_status = 0
                        UNION ALL
                        SELECT em.em_fabric_no as id , UPPER(em.em_fabric_no) as name 
                        FROM estimate_master em 
                        WHERE em.em_delete_status = 0
                        UNION ALL
                        SELECT em.em_stitching_no as id , UPPER(em.em_stitching_no) as name 
                        FROM estimate_master em 
                        WHERE em.em_delete_status = 0
                    ) temp
                WHERE 1
                GROUP BY id ASC
                HAVING name > 0
                $having
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _memo_no(){
        $subsql = '';
        $subsql1= '';
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
            $subsql .= " AND (om.om_memo_no LIKE '%".$name."%') ";
            $subsql1.= " AND (em.em_memo_no LIKE '%".$name."%') ";
        }
        $query="SELECT id, name
                FROM (
                        SELECT om.om_memo_no as id , UPPER(om.om_memo_no) as name 
                        FROM order_master om 
                        WHERE om.om_delete_status = 0
                        $subsql
                        UNION ALL
                        SELECT em.em_memo_no as id , UPPER(em.em_memo_no) as name 
                        FROM estimate_master em 
                        WHERE em.em_delete_status = 0
                        $subsql1
                    ) temp
                WHERE 1
                GROUP BY id ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _billing_name(){
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
        $query="SELECT id, name
                FROM (
                        SELECT customer.customer_name as id , UPPER(customer.customer_name) as name 
                        FROM order_master om 
                        INNER JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)
                        WHERE om.om_delete_status = 0
                        $subsql
                        UNION ALL
                        SELECT customer.customer_name as id , UPPER(customer.customer_name) as name 
                        FROM estimate_master em 
                        INNER JOIN customer_master customer ON(customer.customer_id = em.em_customer_id)
                        WHERE em.em_delete_status = 0
                        $subsql
                    ) temp
                WHERE 1
                GROUP BY id ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _billing_mobile(){
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
            $subsql .= " AND (customer.customer_mobile LIKE '%".$name."%') ";
        }
        $query="SELECT id, name
                FROM (
                        SELECT customer.customer_mobile as id , UPPER(customer.customer_mobile) as name 
                        FROM order_master om 
                        INNER JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)
                        WHERE om.om_delete_status = 0
                        $subsql
                        UNION ALL
                        SELECT customer.customer_mobile as id , UPPER(customer.customer_mobile) as name 
                        FROM estimate_master em 
                        INNER JOIN customer_master customer ON(customer.customer_id = em.em_customer_id)
                        WHERE em.em_delete_status = 0
                        $subsql
                    ) temp
                WHERE 1
                GROUP BY id ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _hsn_name(){
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
            $subsql .= " AND (hsn.hsn_name LIKE '%".$name."%') ";
        }
        $query="SELECT hsn.hsn_name as id , 
                UPPER(hsn.hsn_name) as name 
                FROM order_master om 
                INNER JOIN order_trans ot ON(ot.ot_om_id = om.om_id)
                INNER JOIN barcode_master bm ON(bm.bm_id = ot.ot_bm_id)
                INNER JOIN hsn_master hsn ON(hsn.hsn_id = bm.bm_hsn_id)
                WHERE om.om_delete_status = 0
                AND ot.ot_delete_status = 0
                AND bm.bm_delete_status = 0
                GROUP BY hsn.hsn_name ASC
                $subsql
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
            $subsql .= " AND (apparel.apparel_name LIKE '%".$name."%' OR fabric.fabric_name LIKE '%".$name."%') ";
        }
        $query="SELECT id, name
                FROM (
                        SELECT fabric.fabric_name as id , UPPER(fabric.fabric_name) as name 
                        FROM order_master om 
                        INNER JOIN order_trans ot ON(ot.ot_om_id = om.om_id)
                        INNER JOIN barcode_master bm ON(bm.bm_id = ot.ot_bm_id)
                        INNER JOIN fabric_master fabric ON(fabric.fabric_id = bm.bm_fabric_id)
                        WHERE om.om_delete_status = 0
                        AND ot.ot_delete_status = 0
                        AND bm.bm_delete_status = 0
                        AND ot.ot_trans_type = 'FABRIC'
                        $subsql
                        UNION ALL
                        SELECT apparel.apparel_name as id , UPPER(apparel.apparel_name) as name 
                        FROM order_master om 
                        INNER JOIN order_trans ot ON(ot.ot_om_id = om.om_id)
                        INNER JOIN apparel_master apparel ON(apparel.apparel_id = ot.ot_apparel_id)
                        WHERE om.om_delete_status = 0
                        AND ot.ot_delete_status = 0
                        AND ot.ot_trans_type = 'STITCHING'
                    ) temp
                WHERE 1
                GROUP BY id 
                ORDER BY name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
}
?>