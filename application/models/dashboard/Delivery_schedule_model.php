<?php defined('BASEPATH') OR exit('No direct script access allowed');
	class delivery_schedule_model extends CI_model{
		public function __construct(){ parent::__construct('dashboard', 'delivery_schedule'); }
		public function get_record($flag = false){  
			$record     = [];
			$subsql 	= ''; 
			$having 	= '';
			$order_by 	= " ORDER BY om.om_delivery_date DESC";
			$page       = isset($_REQUEST['page']) ? $_REQUEST['page'] : 0;
			if(isset($_REQUEST['order_by']) && isset($_REQUEST['sort_by'])){
				if(!empty($_REQUEST['order_by']) && !empty($_REQUEST['sort_by'])){
					$order_by = " ORDER BY ".$_REQUEST['order_by']." ".$_REQUEST['sort_by'];
				}
			}
			
			if(isset($_REQUEST['_customer_name']) && !empty($_REQUEST['_customer_name'])){
				$subsql .=" AND customer.customer_name = '".$_REQUEST['_customer_name']."'";
				$record['filter']['_customer_name']['value'] = $_REQUEST['_customer_name'];
				$record['filter']['_customer_name']['text']  = $_REQUEST['_customer_name'];
			}
			if(isset($_REQUEST['_customer_mobile']) && !empty($_REQUEST['_customer_mobile'])){
				$subsql .=" AND customer.customer_mobile = '".$_REQUEST['_customer_mobile']."'";
				$record['filter']['_customer_mobile']['value'] = $_REQUEST['_customer_mobile'];
				$record['filter']['_customer_mobile']['text']  = $_REQUEST['_customer_mobile'];
			}
			if(isset($_REQUEST['_apparel_name']) && !empty($_REQUEST['_apparel_name'])){
				$explode= (is_array($_REQUEST['_apparel_name'])) ? $_REQUEST['_apparel_name'] : explode(',', $_REQUEST['_apparel_name']);
				$record['filter']['_apparel_name'] = $explode;
			}
			if(isset($_REQUEST['_date_from']) && $_REQUEST['_date_from'] != ''){
				$having .=" AND (delivery_date >= '".$_REQUEST['_date_from']."')";
				$record['filter']['_date_from'] = $_REQUEST['_date_from'];
			}else{
				$having .=" AND (delivery_date >= '".date('Y-m-d', strtotime('-6 days'))."')";
				$record['filter']['_date_from'] = date('Y-m-d', strtotime('-6 days'));
			}

			if(isset($_REQUEST['_date_to']) && $_REQUEST['_date_to'] != ''){
				$having .=" AND (delivery_date <= '".$_REQUEST['_date_to']."')";
				$record['filter']['_date_to'] = $_REQUEST['_date_to'];
			}else{
				$having .=" AND (delivery_date <= '".date('Y-m-d')."')";
				$record['filter']['_date_to'] = date('Y-m-d');
			}

			if(isset($_REQUEST['_branch_name']) && !empty($_REQUEST['_branch_name'])){
				$subsql .=" AND branch.branch_name = '".$_REQUEST['_branch_name']."'";
				$record['filter']['_branch_name']['value'] = $_REQUEST['_branch_name'];
				$record['filter']['_branch_name']['text']  = $_REQUEST['_branch_name'];
			}

			if($_SESSION['user_branch_id'] != 1){
				$subsql .=" AND branch.branch_id = '".$_SESSION['user_branch_id']."'";
			}

			$query="SELECT om.om_id,
					IF(om.om_status=0,om.om_em_entry_no,om.om_entry_no) as entry_no,
                    IF(om.om_status=0,DATE_FORMAT(om.om_em_entry_date, '%d-%m-%Y'),DATE_FORMAT(om.om_entry_date, '%d-%m-%Y')) as entry_date,
					UPPER(branch.branch_name) as branch_name,
					UPPER(customer.customer_name) as customer_name,
					customer.customer_mobile as customer_mobile,
					IF(om.om_reschedule_delivery_date != '', om.om_reschedule_delivery_date, om.om_delivery_date) as delivery_date,
					om.om_reschedule_delivery_date as reschedule_delivery_date,
					IFNULL((SELECT wmt.wmt_entry_date FROM whatsapp_message_trans wmt WHERE wmt.wmt_type = 'DELIVERY_REMINDER' AND wmt.wmt_ref_id = om.om_id ORDER BY wmt.wmt_id DESC LIMIT 1), '') as delivery_reminder_date,
					IFNULL((SELECT wmt.wmt_entry_date FROM whatsapp_message_trans wmt WHERE wmt.wmt_type = 'DELIVERY_RESCHEDULE_REMINDER' AND wmt.wmt_ref_id = om.om_id ORDER BY wmt.wmt_id DESC LIMIT 1), '') as reschedule_reminder_date,
					'' as apparel_name,
					UPPER(om.om_notes) as notes
					FROM order_master om
					INNER JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)
					LEFT JOIN branch_master branch ON(branch.branch_id = om.om_branch_id)
					WHERE om.om_delete_status = 0
					$subsql
					HAVING 1
					AND (delivery_date != '')
					$having
					$order_by";
			$data = $this->db->query($query)->result_array();
			// echo "<pre>"; print_r($query); exit();
			// echo "<pre>"; print_r($data); exit();
			$record['data'] = [];
			if(!empty($data)){
				$cnt    = 0;
				$start  = $page * 20;
				$end    = (($page+1) * 20) - 1;
				foreach ($data as $key => $value) {
					$apparel_data = $this->get_apparel($value['om_id']);
					if(!empty($apparel_data)){
						$value['cnt']           			= $cnt;
						$value['page']          			= $page;
						$value['delivery_date']    			= empty($value['delivery_date']) ? '' : date('d-m-Y', strtotime($value['delivery_date']));
						$value['reschedule_delivery_date'] 	= empty($value['reschedule_delivery_date']) ? 'NA' : date('d-m-Y', strtotime($value['reschedule_delivery_date']));
						$value['delivery_reminder_date']  		= empty($value['delivery_reminder_date']) ? 'NA' : date('d-m-Y', strtotime($value['delivery_reminder_date']));
						$value['reschedule_reminder_date']  = empty($value['reschedule_reminder_date']) ? 'NA' : date('d-m-Y', strtotime($value['reschedule_reminder_date']));
						$value['apparel_data']  			= $apparel_data;
						array_push($record['data'], $value);
						$cnt++;
					}
				}
			}
			$record['totals']['rows'] 		= count($record['data']);
			// echo "<pre>"; print_r($record); exit();
			return $record;
		}
		public function get_apparel($om_id){
			$subsql = '';
			if(isset($_REQUEST['_apparel_name']) && !empty($_REQUEST['_apparel_name'])){
				$explode= (is_array($_REQUEST['_apparel_name'])) ? $_REQUEST['_apparel_name'] : explode(',', $_REQUEST['_apparel_name']);
				$subsql .= " AND apparel.apparel_name IN ('".implode("', '", $explode)."')";
				$record['filter']['_apparel_name'] = $explode;
			}
			if(isset($_REQUEST['_apparel_group_name']) && !empty($_REQUEST['_apparel_group_name'])){
				$subsql .=" AND apparel_group.apparel_group_name = '".$_REQUEST['_apparel_group_name']."'";
				$record['filter']['_apparel_group_name']['value'] = $_REQUEST['_apparel_group_name'];
				$record['filter']['_apparel_group_name']['text']  = $_REQUEST['_apparel_group_name'];
			}
			$query="SELECT apparel.apparel_id,
					UPPER(apparel.apparel_name) as apparel_name, 
					SUM(obt.obt_qty) as qty, 
					0 as jrt_qty
					FROM order_barcode_trans obt
					INNER JOIN apparel_master apparel ON(apparel.apparel_id = obt.obt_apparel_id)
					WHERE obt.obt_delete_status = 0
					AND obt.obt_om_id = $om_id
					$subsql
					GROUP BY apparel.apparel_id
					ORDER BY apparel.apparel_name ASC";
			$data = $this->db->query($query)->result_array();
			if(empty($data)) return '';
			$table = '';
			foreach ($data as $key => $value) {
				$value['jrt_qty'] = $this->get_job_receive_qty($om_id, $value['apparel_id']);
				$table .= '<br/><span>* '.$value['apparel_name'].' ('.$value['qty'].' / '.$value['jrt_qty'].') PCS</span>';
			}
			return $table;
		}
		public function get_job_receive_qty($om_id, $apparel_id) {
			$query="SELECT obt.*
					FROM order_barcode_trans obt
					WHERE obt.obt_delete_status = 0
					AND obt.obt_apparel_id = $apparel_id
					AND obt.obt_om_id = $om_id
					GROUP BY obt.obt_id";
			return $this->db->query($query)->num_rows();
		}
		public function get_order_data($om_id){
			$subsql = "";
			if($_SESSION['user_id'] != 1){
				$subsql =" AND branch.branch_id = '".$_SESSION['user_branch_id']."'";
			}
			$query="SELECT 
					UPPER(customer.customer_name) as customer_name,
					customer.customer_mobile,
					om.om_entry_date as entry_date,
					om.om_entry_no as entry_no,
					IF(om.om_reschedule_delivery_date != '', om.om_reschedule_delivery_date, om.om_delivery_date) as delivery_date,
					om.om_reschedule_delivery_date as reschedule_delivery_date,
					UPPER(branch.branch_name) as branch_name
					FROM order_master om
					INNER JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)
					LEFT JOIN branch_master branch ON(branch.branch_id = om.om_branch_id)
					WHERE om.om_id = $om_id
					$subsql";
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
			if($_SESSION['user_id'] != 1){
				$subsql .=" AND branch.branch_id = '".$_SESSION['user_branch_id']."'";
			}
			$query="SELECT customer.customer_name as id, 
					UPPER(customer.customer_name) as name
					FROM order_master om
					INNER JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)
					LEFT JOIN branch_master branch ON(branch.branch_id = om.om_branch_id)
					WHERE customer.customer_name != ''
					AND (om.om_delivery_date != '')
					$subsql
					GROUP BY customer.customer_name ASC
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
				$subsql .= " AND (branch.branch_name LIKE '".$name."%') ";
			}
			if($_SESSION['user_id'] != 1){
				$subsql .=" AND branch.branch_id = '".$_SESSION['user_branch_id']."'";
			}
			$query="SELECT branch.branch_name as id, 
					UPPER(branch.branch_name) as name
					FROM order_master om
					INNER JOIN branch_master branch ON(branch.branch_id = om.om_branch_id)
					WHERE branch.branch_name != ''
					AND (om.om_delivery_date != '')
					$subsql
					GROUP BY branch.branch_name ASC
					LIMIT $limit
					OFFSET $offset";
			// echo $query; exit();
			return $this->db->query($query)->result_array();
		}
	}
?>