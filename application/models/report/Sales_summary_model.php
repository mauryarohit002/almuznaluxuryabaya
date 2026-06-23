<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class sales_summary_model extends my_model{ 
    public function __construct(){ parent::__construct('report', 'sales_summary'); }
    public function get_record(){
        $record     = [];
        $subsql 	= '';
        $subsql1 	= '';
        $having 	= ''; 
        if(isset($_REQUEST['_entry_no']) && !empty($_REQUEST['_entry_no'])){
            $subsql .=" AND om.om_em_entry_no = '".$_REQUEST['_entry_no']."'";
            // $subsql1.=" AND em.em_entry_no = '".$_REQUEST['_entry_no']."'";
            $record['filter']['_entry_no']['value'] = $_REQUEST['_entry_no'];
            $record['filter']['_entry_no']['text']  = $_REQUEST['_entry_no'];
        }
        // if(isset($_REQUEST['_module_name']) && !empty($_REQUEST['_module_name'])){
        //     if($_REQUEST['_module_name'] == 'ORDER'){
        //         $subsql1.=" AND em.em_id = 'XXX'";
        //     }else{
        //         $subsql.=" AND om.om_id = 'XXX'";
        //     }
        //     $record['filter']['_module_name']['value'] = $_REQUEST['_module_name'];
        //     $record['filter']['_module_name']['text']  = $_REQUEST['_module_name'];
        // }
        if(isset($_REQUEST['_billing_name']) && !empty($_REQUEST['_billing_name'])){
            $subsql .=" AND billing.customer_name = '".$_REQUEST['_billing_name']."'";
            $subsql1.=" AND billing.customer_name = '".$_REQUEST['_billing_name']."'";
            $record['filter']['_billing_name']['value'] = $_REQUEST['_billing_name'];
            $record['filter']['_billing_name']['text']  = $_REQUEST['_billing_name'];
        }
        if(isset($_REQUEST['_billing_mobile']) && !empty($_REQUEST['_billing_mobile'])){
            $subsql .=" AND billing.customer_mobile = '".$_REQUEST['_billing_mobile']."'";
            $subsql1.=" AND billing.customer_mobile = '".$_REQUEST['_billing_mobile']."'";
            $record['filter']['_billing_mobile']['value'] = $_REQUEST['_billing_mobile'];
            $record['filter']['_billing_mobile']['text']  = $_REQUEST['_billing_mobile'];
        }
        if(isset($_REQUEST['_user_name']) && !empty($_REQUEST['_user_name'])){
            $subsql .=" AND user.user_fullname = '".$_REQUEST['_user_name']."'";
            $subsql1.=" AND user.user_name = '".$_REQUEST['_user_name']."'";
            $record['filter']['_user_name']['value'] = $_REQUEST['_user_name'];
            $record['filter']['_user_name']['text']  = $_REQUEST['_user_name'];
        }

        if(isset($_REQUEST['_entry_date_from'])){
            if($_REQUEST['_entry_date_from'] != ''){
                $subsql .=" AND om.om_entry_date >= '".$_REQUEST['_entry_date_from']."'";
                $subsql1.=" AND em.em_entry_date >= '".$_REQUEST['_entry_date_from']."'";
                $record['filter']['_entry_date_from'] = $_REQUEST['_entry_date_from'];
            }
        }
        if(isset($_REQUEST['_entry_date_to'])){
            if($_REQUEST['_entry_date_to'] != ''){
                $subsql .=" AND om.om_entry_date <= '".$_REQUEST['_entry_date_to']."'";
                $subsql1.=" AND em.em_entry_date <= '".$_REQUEST['_entry_date_to']."'";
                $record['filter']['_entry_date_to'] = $_REQUEST['_entry_date_to'];
            }
        }
        if(isset($_REQUEST['_total_mtr_from'])){
            if($_REQUEST['_total_mtr_from'] != ''){
                $subsql .=" AND om.om_total_mtr >= ".$_REQUEST['_total_mtr_from'];
                $subsql1.=" AND em.em_total_mtr >= ".$_REQUEST['_total_mtr_from'];
                $record['filter']['_total_mtr_from'] = $_REQUEST['_total_mtr_from'];
            }
        }
        if(isset($_REQUEST['_total_mtr_to'])){
            if($_REQUEST['_total_mtr_to'] != ''){
                $subsql .=" AND om.om_total_mtr <= ".$_REQUEST['_total_mtr_to'];
                $subsql1.=" AND em.em_total_mtr <= ".$_REQUEST['_total_mtr_to'];
                $record['filter']['_total_mtr_to'] = $_REQUEST['_total_mtr_to'];
            }
        }
        if(isset($_REQUEST['_sub_amt_from'])){
            if($_REQUEST['_sub_amt_from'] != ''){
                $subsql .=" AND om.om_sub_amt >= ".$_REQUEST['_sub_amt_from'];
                $subsql1.=" AND em.em_sub_amt >= ".$_REQUEST['_sub_amt_from'];
                $record['filter']['_sub_amt_from'] = $_REQUEST['_sub_amt_from'];
            }
        }
        if(isset($_REQUEST['_sub_amt_to'])){
            if($_REQUEST['_sub_amt_to'] != ''){
                $subsql .=" AND om.om_sub_amt <= ".$_REQUEST['_sub_amt_to'];
                $subsql1.=" AND em.em_sub_amt <= ".$_REQUEST['_sub_amt_to'];
                $record['filter']['_sub_amt_to'] = $_REQUEST['_sub_amt_to'];
            }
        }
        if(isset($_REQUEST['_disc_amt_from'])){
            if($_REQUEST['_disc_amt_from'] != ''){
                $subsql .=" AND om.om_disc_amt >= ".$_REQUEST['_disc_amt_from'];
                $subsql1.=" AND em.em_disc_amt >= ".$_REQUEST['_disc_amt_from'];
                $record['filter']['_disc_amt_from'] = $_REQUEST['_disc_amt_from'];
            }
        }
        if(isset($_REQUEST['_disc_amt_to'])){
            if($_REQUEST['_disc_amt_to'] != ''){
                $subsql .=" AND om.om_disc_amt <= ".$_REQUEST['_disc_amt_to'];
                $subsql1.=" AND em.em_disc_amt <= ".$_REQUEST['_disc_amt_to'];
                $record['filter']['_disc_amt_to'] = $_REQUEST['_disc_amt_to'];
            }
        }
        if(isset($_REQUEST['_taxable_amt_from'])){
            if($_REQUEST['_taxable_amt_from'] != ''){
                $subsql .=" AND om.om_taxable_amt >= ".$_REQUEST['_taxable_amt_from'];
                $subsql1.=" AND em.em_taxable_amt >= ".$_REQUEST['_taxable_amt_from'];
                $record['filter']['_taxable_amt_from'] = $_REQUEST['_taxable_amt_from'];
            }
        }
        if(isset($_REQUEST['_taxable_amt_to'])){
            if($_REQUEST['_taxable_amt_to'] != ''){
                $subsql .=" AND om.om_taxable_amt <= ".$_REQUEST['_taxable_amt_to'];
                $subsql1.=" AND em.em_taxable_amt <= ".$_REQUEST['_taxable_amt_to'];
                $record['filter']['_taxable_amt_to'] = $_REQUEST['_taxable_amt_to'];
            }
        }
        if(isset($_REQUEST['_sgst_amt_from'])){
            if($_REQUEST['_sgst_amt_from'] != ''){
                $subsql .=" AND om.om_sgst_amt >= ".$_REQUEST['_sgst_amt_from'];
                $subsql1.=" AND em.em_sgst_amt >= ".$_REQUEST['_sgst_amt_from'];
                $record['filter']['_sgst_amt_from'] = $_REQUEST['_sgst_amt_from'];
            }
        }
        if(isset($_REQUEST['_sgst_amt_to'])){
            if($_REQUEST['_sgst_amt_to'] != ''){
                $subsql .=" AND om.om_sgst_amt <= ".$_REQUEST['_sgst_amt_to'];
                $subsql1.=" AND em.em_sgst_amt <= ".$_REQUEST['_sgst_amt_to'];
                $record['filter']['_sgst_amt_to'] = $_REQUEST['_sgst_amt_to'];
            }
        }
        if(isset($_REQUEST['_cgst_amt_from'])){
            if($_REQUEST['_cgst_amt_from'] != ''){
                $subsql .=" AND om.om_cgst_amt >= ".$_REQUEST['_cgst_amt_from'];
                $subsql1.=" AND em.em_cgst_amt >= ".$_REQUEST['_cgst_amt_from'];
                $record['filter']['_cgst_amt_from'] = $_REQUEST['_cgst_amt_from'];
            }
        }
        if(isset($_REQUEST['_cgst_amt_to'])){
            if($_REQUEST['_cgst_amt_to'] != ''){
                $subsql .=" AND om.om_cgst_amt <= ".$_REQUEST['_cgst_amt_to'];
                $subsql1.=" AND em.em_cgst_amt <= ".$_REQUEST['_cgst_amt_to'];
                $record['filter']['_cgst_amt_to'] = $_REQUEST['_cgst_amt_to'];
            }
        }
        if(isset($_REQUEST['_igst_amt_from'])){
            if($_REQUEST['_igst_amt_from'] != ''){
                $subsql .=" AND om.om_igst_amt >= ".$_REQUEST['_igst_amt_from'];
                $subsql1.=" AND em.em_igst_amt >= ".$_REQUEST['_igst_amt_from'];
                $record['filter']['_igst_amt_from'] = $_REQUEST['_igst_amt_from'];
            }
        }
        if(isset($_REQUEST['_igst_amt_to'])){
            if($_REQUEST['_igst_amt_to'] != ''){
                $subsql .=" AND om.om_igst_amt <= ".$_REQUEST['_igst_amt_to'];
                $subsql1.=" AND em.em_igst_amt <= ".$_REQUEST['_igst_amt_to'];
                $record['filter']['_igst_amt_to'] = $_REQUEST['_igst_amt_to'];
            }
        }
        if(isset($_REQUEST['_bill_disc_from'])){
            if($_REQUEST['_bill_disc_from'] != ''){
                $subsql .=" AND om.om_bill_disc_amt >= ".$_REQUEST['_bill_disc_from'];
                $subsql1.=" AND em.em_bill_disc_amt >= ".$_REQUEST['_bill_disc_from'];
                $record['filter']['_bill_disc_from'] = $_REQUEST['_bill_disc_from'];
            }
        }
        if(isset($_REQUEST['_bill_disc_to'])){
            if($_REQUEST['_bill_disc_to'] != ''){
                $subsql .=" AND om.om_bill_disc_amt <= ".$_REQUEST['_bill_disc_to'];
                $subsql1.=" AND em.em_bill_disc_amt <= ".$_REQUEST['_bill_disc_to'];
                $record['filter']['_bill_disc_to'] = $_REQUEST['_bill_disc_to'];
            }
        }
        if(isset($_REQUEST['_total_amt_from'])){
            if($_REQUEST['_total_amt_from'] != ''){
                $subsql .=" AND om.om_total_amt >= ".$_REQUEST['_total_amt_from'];
                $subsql1.=" AND em.em_total_amt >= ".$_REQUEST['_total_amt_from'];
                $record['filter']['_total_amt_from'] = $_REQUEST['_total_amt_from'];
            }
        }
        if(isset($_REQUEST['_total_amt_to'])){
            if($_REQUEST['_total_amt_to'] != ''){
                $subsql .=" AND om.om_total_amt <= ".$_REQUEST['_total_amt_to'];
                $subsql1.=" AND em.em_total_amt <= ".$_REQUEST['_total_amt_to'];
                $record['filter']['_total_amt_to'] = $_REQUEST['_total_amt_to'];
            }
        }
        if(isset($_REQUEST['_advance_amt_from'])){
            if($_REQUEST['_advance_amt_from'] != ''){
                $subsql .=" AND om.om_advance_amt >= ".$_REQUEST['_advance_amt_from'];
                $subsql1.=" AND em.em_advance_amt >= ".$_REQUEST['_advance_amt_from'];
                $record['filter']['_advance_amt_from'] = $_REQUEST['_advance_amt_from'];
            }
        }
        if(isset($_REQUEST['_advance_amt_to'])){
            if($_REQUEST['_advance_amt_to'] != ''){
                $subsql .=" AND om.om_advance_amt <= ".$_REQUEST['_advance_amt_to'];
                $subsql1.=" AND em.em_advance_amt <= ".$_REQUEST['_advance_amt_to'];
                $record['filter']['_advance_amt_to'] = $_REQUEST['_advance_amt_to'];
            }
        } 
        if(isset($_REQUEST['_balance_amt_from'])){
            if($_REQUEST['_balance_amt_from'] != ''){
                $subsql .=" AND om.om_balance_amt >= ".$_REQUEST['_balance_amt_from'];
                $subsql1.=" AND em.em_balance_amt >= ".$_REQUEST['_balance_amt_from'];
                $record['filter']['_balance_amt_from'] = $_REQUEST['_balance_amt_from'];
            }
        }
        if(isset($_REQUEST['_balance_amt_to'])){
            if($_REQUEST['_balance_amt_to'] != ''){
                $subsql .=" AND om.om_balance_amt <= ".$_REQUEST['_balance_amt_to'];
                $subsql1.=" AND em.em_balance_amt <= ".$_REQUEST['_balance_amt_to'];
                $record['filter']['_balance_amt_to'] = $_REQUEST['_balance_amt_to'];
            }
        }

        if($_SESSION['user_id'] != 1){
			$subsql .=" AND om.om_branch_id = '".$_SESSION['user_branch_id']."'";
		}

        $query="
                    SELECT 
                    IF(om.om_status=0,'ESTIMATE','ORDER') as module_name, 
                    IF(om.om_status=0,om.om_em_entry_no,om.om_em_entry_no) as entry_no,
                    IF(om.om_status=0,DATE_FORMAT(om.om_em_entry_date, '%d-%m-%Y'),DATE_FORMAT(om.om_entry_date, '%d-%m-%Y')) as entry_date,
                    IF(om.om_trial_date = '' , '', DATE_FORMAT(om.om_trial_date, '%d-%m-%Y')) as trial_date,
                    IF(om.om_delivery_date = '' , '', DATE_FORMAT(om.om_delivery_date, '%d-%m-%Y')) as delivery_date,
                    UPPER(billing.customer_name) as billing_name,
                    billing.customer_mobile as billing_mobile,
                    UPPER(om.om_customer_name) as customer_name,
                    om.om_customer_mobile as customer_mobile,
                    IFNULL(UPPER(user.user_fullname),'') as user_name,
                    om.om_total_mtr as total_mtr,  
                    om.om_sub_amt as sub_amt,
                    om.om_disc_amt as disc_amt,
                    om.om_taxable_amt as taxable_amt,
                    om.om_sgst_amt as sgst_amt,
                    om.om_cgst_amt as cgst_amt,
                    om.om_igst_amt as igst_amt,
                    om.om_bill_disc_per as bill_disc_per,
                    om.om_bill_disc_amt as bill_disc_amt,
                    om.om_round_off as round_off,
                    om.om_total_amt as total_amt,
                    om.om_advance_amt as advance_amt,
                    om.om_balance_amt as balance_amt,
                    om.om_created_at as created_at
                    FROM order_master om 
                    INNER JOIN customer_master billing ON(billing.customer_id = om.om_customer_id)
                    LEFT JOIN user_master user ON(user.user_id = om.om_salesman_id)
                    WHERE om.om_delete_status = 0
                    $subsql 
                ORDER BY om.om_created_at DESC";
        $data = $this->db->query($query)->result_array();
        // echo "<pre>"; print_r($query); exit();
        // echo "<pre>"; print_r($data); exit();
        
        $record['totals']['rows']           = count($data);
        $record['totals']['total_mtr']      = 0;
        $record['totals']['sub_amt']        = 0;
        $record['totals']['disc_amt']       = 0;
        $record['totals']['taxable_amt']    = 0;
        $record['totals']['sgst_amt']       = 0;
        $record['totals']['cgst_amt']       = 0;
        $record['totals']['igst_amt']       = 0;
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
                                                'user_name'         => $value['user_name'],
                                                'total_mtr' 		=> (float)$value['total_mtr'],
                                                'sub_amt' 		    => (float)$value['sub_amt'],
                                                'disc_amt' 		    => (float)$value['disc_amt'],
                                                'taxable_amt' 		=> (float)$value['taxable_amt'],
                                                'sgst_amt' 		    => (float)$value['sgst_amt'],
                                                'cgst_amt' 		    => (float)$value['cgst_amt'],
                                                'igst_amt' 		    => (float)$value['igst_amt'],
                                                'bill_disc_per'     => (float)$value['bill_disc_per'],
                                                'bill_disc_amt'     => (float)$value['bill_disc_amt'],
                                                'round_off'         => (float)$value['round_off'],
                                                'total_amt'         => (float)$value['total_amt'],
                                                'advance_amt'       => (float)$value['advance_amt'],
                                                'balance_amt'       => (float)$value['balance_amt'],
                                                'created_at'        => strtotime($value['created_at']),
                                            ]);

                $record['totals']['total_mtr'] 	    = $record['totals']['total_mtr'] 		+ $value['total_mtr'];
                $record['totals']['sub_amt'] 	    = $record['totals']['sub_amt'] 		    + $value['sub_amt'];
                $record['totals']['disc_amt'] 	    = $record['totals']['disc_amt'] 		+ $value['disc_amt'];
                $record['totals']['taxable_amt'] 	= $record['totals']['taxable_amt'] 		+ $value['taxable_amt'];
                $record['totals']['sgst_amt'] 	    = $record['totals']['sgst_amt'] 		+ $value['sgst_amt'];
                $record['totals']['cgst_amt'] 	    = $record['totals']['cgst_amt'] 		+ $value['cgst_amt'];
                $record['totals']['igst_amt'] 	    = $record['totals']['igst_amt'] 		+ $value['igst_amt'];
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
            $subsql .= " AND (om.om_em_entry_no LIKE '%".$name."%') ";
        }
        $query="SELECT om.om_em_entry_no as id , UPPER(om.om_em_entry_no) as name 
                    FROM order_master om 
                    WHERE om.om_delete_status = 0
                    $subsql
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
                    ) temp
                WHERE 1
                GROUP BY id ASC
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
                    ) temp
                WHERE 1
                GROUP BY id ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
}
?>