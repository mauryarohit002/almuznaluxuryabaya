<?php defined('BASEPATH') OR exit('No direct script access allowed');
	class Homemdl extends CI_model{
		protected $start_date;
		protected $end_date;
		public function __construct(){
			parent::__construct();
			$this->start_date 	= isset($_SESSION['start_year']) ? $_SESSION['start_year']." 00:00:01" : date('Y-m-d H:i:s');
			$this->end_date 	= isset($_SESSION['end_year']) ? $_SESSION['end_year']." 23:59:59" : date('Y-m-d H:i:s');

			// $this->load->model('report/DailyProfitmdl');
		}
		public function get_first(){  
			$pur_query ="
							SELECT SUM(pm.pm_total_mtr) as qty, 0 as ret_qty
							FROM purchase_master pm
							WHERE pm.pm_delete_status = 0 
							AND pm.pm_created_at <= '".$this->end_date."'
							AND pm.pm_branch_id = ".$_SESSION['user_branch_id']."
						";
			// echo "<pre>"; print_r($pur_query); exit;
			$pur_data = $this->db->query($pur_query)->result_array();

			$order_query ="
							SELECT SUM(om.om_total_mtr) as qty, 0 as ret_qty
							FROM order_master om
							WHERE om.om_delete_status = 0 
							AND om.om_created_at <= '".$this->end_date."'
							AND om.om_branch_id = ".$_SESSION['user_branch_id']."
						";
			// echo "<pre>"; print_r($pur_query); exit;
			$sales_data = $this->db->query($order_query)->result_array();

			// echo "<pre>"; print_r($pur_data); exit;

			$pur_qty  = 0;
			$pret_qty = 0;
			$sale_qty = 0;
			$sret_qty = 0;

			if(!empty($pur_data)){
				$pur_qty  = $pur_data[0]['qty'];
				$pret_qty = $pur_data[0]['ret_qty'];
			}

			if(!empty($sales_data)){
				$sale_qty = $sales_data[0]['qty'];
				$sret_qty = $sales_data[0]['ret_qty'];
			}

			$bal_qty = (($pur_qty + $sret_qty) - ($sale_qty + $pret_qty));
			return [
					'pur_qty' 	=> round($pur_qty, 2), 
					'pret_qty' 	=> round($pret_qty, 2), 
					'sale_qty' 	=> round($sale_qty, 2), 
					'sret_qty' 	=> round($sret_qty, 2),
					'bal_qty' 	=> round($bal_qty, 2)
				];
		}
		public function get_second($start_date, $end_date){
			$start_date = date('Y-m-d', strtotime($start_date));
			$end_date 	= date('Y-m-d', strtotime($end_date));
			$record = [];
			
			$record = [];
			if(!empty($data)){
				foreach ($data as $key => $value) {
					$from_date 	= date('Y-m-01', strtotime($value['sm_entry_date']));
					$to_date 	= date('Y-m-t', strtotime($value['sm_entry_date']));
					$record[$key] = $this->DailyProfitmdl->get_data(true, $from_date, $to_date);  
					// echo "<pre>"; print_r($record); exit;
					$record[$key]['month_year'] = date('M-Y', strtotime($value['sm_entry_date']));  
				}
			}
			// echo "<pre>"; print_r($record); exit;

			return $record;
		}
		public function get_third($start_date, $end_date){
			$record 	= [];
			$modes 		= $this->config->item('payment_mode'); 
			$start_date = date('Y-m-d', strtotime($start_date));
			$end_date 	= date('Y-m-d', strtotime($end_date));
			// echo "<pre>"; print_r($record);exit();
			return $record;
		}
		public function get_fourth($start_date, $end_date){
			$record 	= [];
			$start_date = date('Y-m-d', strtotime($start_date));
			$end_date 	= date('Y-m-d', strtotime($end_date));
			// echo "<pre>"; print_r($query); exit;
			return $record;
		}
		public function get_fifth($start_date, $end_date){
			$record 	= [];
			$start_date = date('Y-m-d', strtotime($start_date));
			$end_date 	= date('Y-m-d', strtotime($end_date));
			return $record;
		}

		public function get_last(){   
			$record = [];
			$data =$this->db->query("SELECT SUM(om.om_total_amt) as amt
						FROM order_master om WHERE om_delete_status=0 AND om_branch_id = ".$_SESSION['user_branch_id']." AND om_fin_year = '".$_SESSION['fin_year']."'")->result_array();
			$record['amt']['total_amt'] =  (!empty($data[0]['amt']))? $data[0]['amt']:0;

			$data =$this->db->query("SELECT SUM(om.om_advance_amt) as amt
						FROM order_master om WHERE om_delete_status=0 AND om_branch_id = ".$_SESSION['user_branch_id']." AND om_fin_year = '".$_SESSION['fin_year']."'")->result_array();
			$record['amt']['advance_amt'] =  (!empty($data[0]['amt']))? $data[0]['amt']:0;

			$data =$this->db->query("SELECT SUM(om.om_total_amt - om.om_advance_amt) as amt
						FROM order_master om WHERE om_delete_status=0 AND om_branch_id = ".$_SESSION['user_branch_id']." AND om_fin_year = '".$_SESSION['fin_year']."'")->result_array();
			$record['amt']['balance_amt'] =  (!empty($data[0]['amt']))? $data[0]['amt']:0;

			$data =$this->db->query("SELECT SUM(om.om_allocated_amt) as amt
						FROM order_master om WHERE om_delete_status=0 AND om_branch_id = ".$_SESSION['user_branch_id']." AND om_fin_year = '".$_SESSION['fin_year']."' ")->result_array();
			$record['amt']['allocated_amt'] =  (!empty($data[0]['amt']))? $data[0]['amt']:0;

			$data =$this->db->query("SELECT SUM(om.om_advance_amt + om.om_allocated_amt) as amt
						FROM order_master om WHERE om_delete_status=0 AND om_branch_id = ".$_SESSION['user_branch_id']." AND om_fin_year = '".$_SESSION['fin_year']."'")->result_array();
			$record['amt']['total_collected_amt'] = (!empty($data))? $data[0]['amt']:0;

			$data =$this->db->query("SELECT SUM(om.om_total_amt - (om.om_advance_amt + om.om_allocated_amt)) as amt
						FROM order_master om  WHERE om_delete_status=0 AND om_branch_id = ".$_SESSION['user_branch_id']."  AND om_fin_year = '".$_SESSION['fin_year']."'")->result_array();
			$record['amt']['total_due_amt'] =  (!empty($data[0]['amt']))? $data[0]['amt']:0;
			
		   
	       	$subsql = " AND om.om_entry_date = '".date('Y-m-d')."'";
			$subsql2 = " AND receipt_entry_date = '".date('Y-m-d')."'";
	       $record['today'] = $this->get_today_amount($subsql,$subsql2); 
	       // echo "<pre>"; print_r($record);die;

	       // $from_dt = date('Y-m-01');
	       // $to_dt = date('Y-m-t');
	       // $subsql = " AND om.om_entry_date >= '".$from_dt."'   AND om.om_entry_date <= '".$to_dt."'";
	       // $subsql2 = " AND receipt_entry_date >= '".$from_dt."'   AND receipt_entry_date <= '".$to_dt."'";
	       // $record['monthly'] = $this->get_today_amount($subsql,$subsql2);                                 

			return $record;
		}

		public function get_today_amount($subsql,$subsql2){ 
			
			$record = [];
			$data =$this->db->query("SELECT SUM(om.om_total_amt) as amt
						FROM order_master om WHERE om_delete_status=0 $subsql AND om_branch_id = ".$_SESSION['user_branch_id']." AND om_fin_year = '".$_SESSION['fin_year']."'")->result_array();
			$record['total_amt'] = (!empty($data[0]['amt']))? $data[0]['amt']:0;	

			$data =$this->db->query("SELECT SUM(om.om_advance_amt) as amt
						FROM order_master om WHERE om_delete_status=0 $subsql AND om_branch_id = ".$_SESSION['user_branch_id']." AND om_fin_year = '".$_SESSION['fin_year']."'")->result_array();
			$record['advance_amt'] = (!empty($data[0]['amt']))? $data[0]['amt']:0;	

			$data =$this->db->query("SELECT SUM(om.om_total_amt - om.om_balance_amt) as amt
						FROM order_master om WHERE om_delete_status=0 $subsql AND om_branch_id = ".$_SESSION['user_branch_id']." AND om_fin_year = '".$_SESSION['fin_year']."'")->result_array();
			$record['balance_amt'] = (!empty($data[0]['amt']))? $data[0]['amt']:0;	

			$data =$this->db->query("SELECT SUM(receipt_amt) as amt
						FROM receipt_master WHERE receipt_delete_status=0 $subsql2 AND receipt_branch_id = ".$_SESSION['user_branch_id']."")->result_array();
			$receipt_amt = (!empty($data[0]['amt']))? $data[0]['amt']:0;			 
			$record['allocated_amt'] =  (!empty($data[0]['amt']))? $data[0]['amt']:0;	
			$record['total_collected_amt'] = ($record['advance_amt'] + $receipt_amt);

			$data =$this->db->query("SELECT SUM(om.om_total_amt - (om.om_advance_amt + om.om_allocated_amt)) as amt
				FROM order_master om WHERE om_delete_status=0 $subsql AND om_branch_id = ".$_SESSION['user_branch_id']."")->result_array();
			$record['total_due_amt'] =  (!empty($data[0]['amt']))? $data[0]['amt']:0;

			return $record;
		}

		public function get_fabric_stock(){   
			$pt_mtr  = 0;
			$prt_mtr = 0;
			$ot_mtr = 0;
			$ort_mtr = 0;
			$bal_mtr = 0;
			$fab_query ="SELECT SUM(bm.bm_pt_mtr) as pt_mtr,
							SUM(bm.bm_prt_mtr) as prt_mtr,
                			SUM(bm.bm_ot_mtr) as ot_mtr,
                			0 as ort_mtr,
                			SUM((bm.bm_pt_mtr-bm.bm_prt_mtr) - bm.bm_ot_mtr) as bal_mtr
							FROM barcode_master bm
							WHERE bm.bm_delete_status = 0 ";
			// echo "<pre>"; print_r($fab_query); exit;
			$fab_data = $this->db->query($fab_query)->result_array();
			if(!empty($fab_data)){
				$pt_mtr  = $fab_data[0]['pt_mtr'];
				$prt_mtr = $fab_data[0]['prt_mtr'];
				$ot_mtr = $fab_data[0]['ot_mtr'];
				$ort_mtr = $fab_data[0]['ort_mtr'];
				$bal_mtr = $fab_data[0]['bal_mtr'];
			}
			
			return [
					'pt_mtr' 	=> round($pt_mtr, 2), 
					'prt_mtr' 	=> round($prt_mtr, 2), 
					'ot_mtr' 	=> round($ot_mtr, 2), 
					'ort_mtr' 	=> round($ort_mtr, 2),
					'bal_mtr' 	=> round($bal_mtr, 2),
				];
		}

		public function get_other_stock(){   
			$pt_qty  = 0;
			$prt_qty = 0;
			$ot_qty = 0;
			$ort_qty = 0;
			$bal_qty = 0;
			$pur_query ="SELECT SUM(brmm.brmm_prmt_qty) as pt_qty,
							SUM(brmm.brmm_prrt_qty) as prt_qty,
                			SUM(brmm.brmm_ot_qty) as ot_qty,
                			0 as ort_qty,
                			SUM((brmm.brmm_prmt_qty-brmm.brmm_prrt_qty) - brmm.brmm_ot_qty) as bal_qty
							FROM barcode_readymade_master brmm
							WHERE brmm.brmm_delete_status = 0 ";
			// echo "<pre>"; print_r($fab_query); exit;
			$fab_data = $this->db->query($pur_query)->result_array();
			if(!empty($fab_data)){
				$pt_qty  = $fab_data[0]['pt_qty'];
				$prt_qty = $fab_data[0]['prt_qty'];
				$ot_qty = $fab_data[0]['ot_qty'];
				$ort_qty = $fab_data[0]['ort_qty'];
				$bal_qty = $fab_data[0]['bal_qty'];
			}
			
			return [
					'pt_qty' 	=> round($pt_qty, 2), 
					'prt_qty' 	=> round($prt_qty, 2), 
					'ot_qty' 	=> round($ot_qty, 2), 
					'ort_qty' 	=> round($ort_qty, 2),
					'bal_qty' 	=> round($bal_qty, 2),
				];
		}

	}
?>