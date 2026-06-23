<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class daily_transaction_model extends my_model{
    public function __construct(){ parent::__construct('report', 'daily_transaction'); }
    public function get_record(){
        $record     = [];
        $subsql 	= ''; 
        $subsql2    ='';   
        $subsql3    ='';  
        $subsql4    ='';
        $subsql5    ='';   

        if(isset($_REQUEST['_customer']) && !empty($_REQUEST['_customer'])){
            $subsql2 .= " AND customer.customer_name = '".$_REQUEST['_customer']."'";
			$subsql3 .= " AND customer.customer_name = '".$_REQUEST['_customer']."'";
            $record['filter']['_customer']['value'] = $_REQUEST['_customer'];
            $record['filter']['_customer']['text']  = $_REQUEST['_customer'];
        }

       if(isset($_REQUEST['_branch']) && !empty($_REQUEST['_branch'])){
    
            $subsql2 .= " AND branch.branch_name = '".$_REQUEST['_branch']."'";
            $subsql3 .= " AND branch.branch_name = '".$_REQUEST['_branch']."'";
            $subsql4 .= " AND branch.branch_name = '".$_REQUEST['_branch']."'";
            $subsql5 .= " AND branch.branch_name = '".$_REQUEST['_branch']."'";

            $record['filter']['_branch']['value'] = $_REQUEST['_branch'];
            $record['filter']['_branch']['text']  = $_REQUEST['_branch'];
        }
      
        if(isset($_GET['_entry_date_from']) && !empty($_GET['_entry_date_from'])){
                $subsql2 .= " AND om.om_em_entry_date >= '". date('Y-m-d', strtotime($_GET['_entry_date_from']))."'";
                $subsql3 .= " AND receipt.receipt_entry_date >= '". date('Y-m-d', strtotime($_GET['_entry_date_from']))."'";
                $subsql4 .= " AND payment.payment_entry_date >= '". date('Y-m-d', strtotime($_GET['_entry_date_from']))."'";
                $subsql5 .= " AND payment_general.payment_general_entry_date >= '". date('Y-m-d 00:00:00', strtotime($_GET['_entry_date_from']))."'";
                $record['filter']['_entry_date_from'] = $_REQUEST['_entry_date_from'];
            }else{ 
                $subsql2  .= " AND om.om_em_entry_date >= '". date('Y-m-d')."'";
                $subsql3 .= " AND receipt.receipt_entry_date >= '". date('Y-m-d')."'";
                $subsql4 .= " AND payment.payment_entry_date >= '". date('Y-m-d')."'";
                $subsql5 .= " AND payment_general.payment_general_entry_date >= '". date('Y-m-d')."'";
            }

            if(isset($_GET['_entry_date_to']) && !empty($_GET['_entry_date_to'])){
                $subsql2 .= " AND om.om_em_entry_date <= '". date('Y-m-d', strtotime($_GET['_entry_date_to']))."'";
                $subsql3 .= " AND receipt.receipt_entry_date <= '". date('Y-m-d', strtotime($_GET['_entry_date_to']))."'";
                $subsql4 .= " AND payment.payment_entry_date <= '". date('Y-m-d', strtotime($_GET['_entry_date_to']))."'";
                $subsql5 .= " AND payment_general.payment_general_entry_date <= '". date('Y-m-d 00:00:00', strtotime($_GET['_entry_date_to']))."'";

                $record['filter']['_entry_date_to'] = $_REQUEST['_entry_date_to'];
            }else{
                $subsql2  .= " AND om.om_em_entry_date <= '". date('Y-m-d')."'";
                $subsql3 .= " AND receipt.receipt_entry_date <= '". date('Y-m-d')."'";
                $subsql4 .= " AND payment.payment_entry_date <= '". date('Y-m-d')."'";
                $subsql5 .= " AND payment_general.payment_general_entry_date <= '". date('Y-m-d')."'";
            }

			if($_SESSION['user_id'] != 1){
				$subsql2 .= " AND om.om_branch_id = '".$_SESSION['user_branch_id']."'";
				$subsql3 .= " AND receipt.receipt_branch_id = '".$_SESSION['user_branch_id']."'";
				$subsql4 .= " AND payment.payment_branch_id = '".$_SESSION['user_branch_id']."'";
				$subsql5 .= " AND payment_general.payment_general_branch_id = '".$_SESSION['user_branch_id']."'";
			}

            $query="SELECT 
                    om.om_em_entry_no as om_entry_no, 
                    DATE_FORMAT(om.om_em_entry_date, '%d-%m-%Y') as om_entry_date, 
                    DATE_FORMAT(om.om_delivery_date, '%d-%m-%Y') as om_delivery_date,
                    om.om_total_amt, 
                    om.om_advance_amt, 
                    (om.om_allocated_amt) as om_allocated_amt, 
                    (om.om_total_amt - (om.om_advance_amt + om.om_allocated_amt)) as om_balance_amt,
                    CONCAT(UPPER(customer.customer_name),' - ', customer.customer_mobile) as customer_name,
                    GROUP_CONCAT(DISTINCT pm.payment_mode_name SEPARATOR ', ') AS payment_mode_name
                    FROM order_master om
                    INNER JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)

                    LEFT JOIN order_payment_mode_trans opmt
                        ON(opmt.opmt_om_id = om.om_id AND opmt.opmt_delete_status = 0)
                    LEFT JOIN payment_mode_master pm
                        ON(pm.payment_mode_id = opmt.opmt_payment_mode_id)

                    INNER JOIN branch_master branch ON(branch.branch_id = om.om_branch_id) 
                    WHERE om.om_delete_status = 0
                    ".($_SESSION['user_id'] != 1 ? " AND om.om_branch_id = '".$_SESSION['user_branch_id']."'" : "")."
                    $subsql2
                    GROUP BY om.om_id
                    ORDER BY om.om_em_entry_date DESC
                    "; 
            // echo "<pre>"; print_r($query);exit;
            $record['order_data'] = $this->db->query($query)->result_array();

            $query="SELECT
                    'RECEIPT ORDER' as module_name,  
                    receipt.receipt_entry_no as entry_no, 
                    DATE_FORMAT(receipt.receipt_entry_date, '%d-%m-%Y') as entry_date, 
                    UPPER(customer.customer_name) as customer_name,
                    rot.rot_adjust_amt,
                    GROUP_CONCAT(DISTINCT pm.payment_mode_name SEPARATOR ', ') AS payment_mode_name
                    FROM receipt_master receipt
                    INNER JOIN receipt_order_trans rot ON(rot.rot_receipt_id = receipt.receipt_id)
                    INNER JOIN order_master om ON(om.om_id = rot.rot_om_id)
                    INNER JOIN customer_master customer ON(customer.customer_id = om.om_customer_id) 
                    LEFT JOIN receipt_payment_mode_trans rpmt
                        ON(rpmt.rpmt_receipt_id = receipt.receipt_id
                        AND rpmt.rpmt_delete_status = 0)

                    LEFT JOIN payment_mode_master pm
                        ON(pm.payment_mode_id = rpmt.rpmt_payment_mode_id)
                    INNER JOIN branch_master branch ON(branch.branch_id = receipt.receipt_branch_id) 
                    WHERE rot.rot_delete_status =0
                    ".($_SESSION['user_id'] != 1 ? " AND receipt.receipt_branch_id = '".$_SESSION['user_branch_id']."'" : "")."
                    $subsql3
                    GROUP BY receipt.receipt_id, rot.rot_id
                    ORDER BY receipt.receipt_entry_date DESC";
            // echo "<pre>"; print_r($query);exit;
            $record['receipt_order_data'] = $this->db->query($query)->result_array();

            $query="SELECT
                    payment.payment_entry_no as entry_no, 
                    DATE_FORMAT(payment.payment_entry_date, '%d-%m-%Y') as entry_date, 
                    UPPER(supplier.supplier_name) as supplier_name,
                    pprt.pprt_adjust_amt,
                    GROUP_CONCAT(DISTINCT pm.payment_mode_name SEPARATOR ', ') AS payment_mode_name
                    FROM payment_master payment
                    INNER JOIN payment_purchase_readymade_trans pprt ON(pprt.pprt_payment_id = payment.payment_id)
                    INNER JOIN purchase_readymade_master prmm ON(prmm.prmm_id = pprt.pprt_prmm_id)
                    INNER JOIN supplier_master supplier ON(supplier.supplier_id = prmm.prmm_supplier_id)
                    LEFT JOIN payment_payment_mode_trans ppmt
                        ON(ppmt.ppmt_payment_id = payment.payment_id
                        AND ppmt.ppmt_delete_status = 0)
                    LEFT JOIN payment_mode_master pm
                        ON(pm.payment_mode_id = ppmt.ppmt_payment_mode_id)
                    INNER JOIN branch_master branch 
                        ON(branch.branch_id = payment.payment_branch_id)
                    WHERE pprt.pprt_delete_status =0
                    $subsql4
                    GROUP BY payment.payment_id, pprt.pprt_id
                    ORDER BY payment.payment_entry_date DESC
                    ";
            // echo "<pre>"; print_r($query);exit;
            $record['payment_data'] = $this->db->query($query)->result_array();

            $query="SELECT
                    payment_general.payment_general_entry_no as entry_no, 
                    DATE_FORMAT(payment_general.payment_general_entry_date, '%d-%m-%Y') as entry_date, 
                    UPPER(general.general_name) as general_name,
                    payment_general.payment_general_amt,
                    GROUP_CONCAT(DISTINCT pm.payment_mode_name SEPARATOR ', ') AS payment_mode_name
                    FROM payment_general_master payment_general
                    INNER JOIN general_master general ON(general.general_id = payment_general.payment_general_general_id)
                    LEFT JOIN payment_general_payment_mode_trans pgpmt
                        ON(pgpmt.pgpmt_payment_general_id = payment_general.payment_general_id
                        AND pgpmt.pgpmt_delete_status = 0)
                    LEFT JOIN payment_mode_master pm
                        ON(pm.payment_mode_id = pgpmt.pgpmt_payment_mode_id)
                    INNER JOIN branch_master branch 
                        ON(branch.branch_id = payment_general.payment_general_branch_id)
                    WHERE payment_general.payment_general_delete_status =0
                    ".($_SESSION['user_id'] != 1 ? " AND payment_general.payment_general_branch_id = '".$_SESSION['user_branch_id']."'" : "")."
                    $subsql5
                    GROUP BY payment_general.payment_general_id
                    ORDER BY payment_general.payment_general_entry_date DESC
                    ";
            // echo "<pre>"; print_r($query);exit;
            $record['general_data'] = $this->db->query($query)->result_array();

            $payment_data = $this->db->query("SELECT UPPER(payment_mode_name) as mode_name,payment_mode_id FROM payment_mode_master WHERE payment_mode_status = 1")->result_array();
           
            $record['receipt_payment']  = [];
            $record['order_payment']    = [];
            $record['payment_payment']  = [];
            $record['general_payment']  = [];
            if(!empty($payment_data)){
                foreach($payment_data as $key=>$value){
                        $payment_mode_id = $value['payment_mode_id'];

                    //receipt query start   
                        $order_data = $this->db->query("
                                SELECT SUM(IFNULL(rpmt.rpmt_amt,0)) AS amount,
                                rpmt.rpmt_payment_mode_id as id, 
                                receipt.receipt_created_at as created_at
                                FROM receipt_payment_mode_trans rpmt 
                                LEFT JOIN receipt_master receipt ON(rpmt.rpmt_receipt_id = receipt.receipt_id)
								LEFT JOIN customer_master customer ON(customer.customer_id = receipt.receipt_customer_id)
                                INNER JOIN branch_master branch ON(branch.branch_id = receipt.receipt_branch_id) 
                                WHERE receipt.receipt_delete_status = 0 AND rpmt.rpmt_delete_status = 0
                                AND rpmt.rpmt_payment_mode_id = $payment_mode_id
                                $subsql3
                                GROUP BY rpmt.rpmt_payment_mode_id
                           
                        ")->result_array();

                    $record['receipt_payment'][$key]['payment_mode_name'] = $value['mode_name'];
                    $record['receipt_payment'][$key]['payment_mode_amt']  = (!empty($order_data)) ? (float)$order_data[0]['amount'] : 0;
                    //receipt query end 

                    //payment query start   
					$order_data = $this->db->query("
							SELECT SUM(IFNULL(ppmt.ppmt_amt,0)) AS amount,
							ppmt.ppmt_payment_mode_id as id, 
							payment.payment_created_at as created_at
							FROM payment_payment_mode_trans ppmt 
							LEFT JOIN payment_master payment ON(ppmt.ppmt_payment_id = payment.payment_id)
                            INNER JOIN branch_master branch 
                                ON(branch.branch_id = payment.payment_branch_id)
							WHERE payment.payment_delete_status = 0 AND ppmt.ppmt_delete_status = 0
							AND ppmt.ppmt_payment_mode_id = $payment_mode_id
							$subsql4
							GROUP BY ppmt.ppmt_payment_mode_id
						
					")->result_array();
                    $record['payment_payment'][$key]['payment_mode_name'] = $value['mode_name'];
                    $record['payment_payment'][$key]['payment_mode_amt']  = (!empty($order_data))?(float)$order_data[0]['amount']:0;
                    //payment query end 

                    //general query start   
                        $order_data = $this->db->query("
                                SELECT SUM(IFNULL(pgpmt.pgpmt_amt,0)) AS amount,
                                pgpmt.pgpmt_payment_mode_id as id, 
                                payment_general.payment_general_created_at as created_at
                                FROM payment_general_payment_mode_trans pgpmt 
                                LEFT JOIN payment_general_master payment_general ON(pgpmt.pgpmt_payment_general_id = payment_general.payment_general_id)
                                INNER JOIN branch_master branch 
                                    ON(branch.branch_id = payment_general.payment_general_branch_id)
                                WHERE payment_general.payment_general_delete_status = 0 AND pgpmt.pgpmt_delete_status = 0
                                AND pgpmt.pgpmt_payment_mode_id = $payment_mode_id
                                $subsql5
                                GROUP BY pgpmt.pgpmt_payment_mode_id
                           
                        ")->result_array();
                    $record['general_payment'][$key]['payment_mode_name'] = $value['mode_name'];
                    $record['general_payment'][$key]['payment_mode_amt']  = (!empty($order_data))?(float)$order_data[0]['amount']:0;
                    //general query end 
 

                    //ORDER query start   
                        $order_data = $this->db->query("
                            SELECT SUM(amount) as amount,id,created_at 
                            FROM (
                                SELECT SUM(IFNULL(opmt_amt,0)) AS amount,
                                opmt.opmt_payment_mode_id as id, 
                                om.om_created_at as created_at
                                FROM order_payment_mode_trans opmt 
                                LEFT JOIN order_master om ON(opmt.opmt_om_id = om.om_id)
								LEFT JOIN customer_master customer ON(om.om_customer_id = customer.customer_id)
                                INNER JOIN branch_master branch ON(branch.branch_id = om.om_branch_id) 
                                WHERE om.om_delete_status = 0 AND opmt.opmt_delete_status = 0
                                AND opmt.opmt_payment_mode_id = $payment_mode_id
                                $subsql2
                                GROUP BY opmt.opmt_payment_mode_id
                            )temp WHERE 1 
                            GROUP BY id
                            ORDER BY created_at DESC
                        ")->result_array();
						// echo "<pre>"; print_r($order_data); exit;
                    $record['order_payment'][$key]['payment_mode_name'] = $value['mode_name'];
                    $record['order_payment'][$key]['payment_mode_amt']  = (!empty($order_data))?(float)$order_data[0]['amount']:0;
                    //ORDER query end     
                }
            }
        // echo "<pre>"; print_r($record);die;     
        return $record;  
       
    }
    public function _payment_mode_name(){
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
            $subsql .= " AND (payment_mode.payment_mode_name LIKE '%".$name."%') ";
        }
        $query="SELECT id, name
                FROM (
                        SELECT payment_mode.payment_mode_name as id , UPPER(payment_mode.payment_mode_name) as name 
                        FROM order_master om 
                        INNER JOIN order_payment_mode_trans opmt ON(opmt.opmt_om_id = om.om_id)
                        INNER JOIN payment_mode_master payment_mode ON(payment_mode.payment_mode_id = opmt.opmt_payment_mode_id)
                        WHERE om.om_delete_status = 0
                        AND opmt.opmt_delete_status = 0
                        $subsql
                    ) temp
                WHERE 1
                GROUP BY id ASC
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
            $subsql .= " AND (customer.customer_name = '".$name."') ";
        }
		if($_SESSION['user_id'] != 1){
			$subsql .= " AND om.om_branch_id = '".$_SESSION['user_branch_id']."'";
		}
        $query="SELECT id, name
                FROM (
                        SELECT customer.customer_name as id , CONCAT(UPPER(customer.customer_name),' - ',customer.customer_mobile) as name 
                        FROM order_master om 
                        INNER JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)
                        WHERE om.om_delete_status = 0
                        AND om.om_delete_status = 0
                        $subsql
                    ) temp
                WHERE 1
                GROUP BY id ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }

    public function _branch_name(){
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
            $subsql .= " AND (branch.branch_name = '".$name."') ";
        }
        $query="SELECT id, name
                FROM (
                        SELECT branch.branch_name as id , UPPER(branch.branch_name) as name 
                        FROM branch_master branch 
                        WHERE branch.branch_status = 1
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