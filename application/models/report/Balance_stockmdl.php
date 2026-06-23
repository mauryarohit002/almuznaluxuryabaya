<?php defined('BASEPATH') OR exit('No direct script access allowed');
	class Balance_stockmdl extends CI_model{
		protected $start_date;
		protected $end_date;
		public function __construct(){
			parent::__construct();

			$this->start_date 	= isset($_SESSION['start_year']) ? $_SESSION['start_year']." 00:00:01" : date('Y-m-d H:i:s');
			$this->end_date 	= isset($_SESSION['end_year']) ? $_SESSION['end_year']." 23:59:59" : date('Y-m-d H:i:s');

			$this->load->model('master/supplier_model');
			$this->load->model('master/Barcodemdl');
			$this->load->model('master/sku_model');
			$this->load->model('master/apparel_model');
		}
		public function get_data(){
			$record = [];
			$subsql = "";
			$having = "";
			if (isset($_GET['brmm_supplier_id']) && !empty($_GET['brmm_supplier_id'])) {
				$supplier_name = $_GET['brmm_supplier_id'];

				$subsql .= " AND supplier.supplier_name = '".$this->db->escape_str($supplier_name)."'";

				$record['search']['brmm_supplier_id'] = ['value' => $supplier_name, 'text'  => $supplier_name];
			}

			if(isset($_GET['brmm_id']) && !empty($_GET['brmm_id'])){
				$subsql .=" AND brmm.brmm_id = ".$_GET['brmm_id'];
				$record['search']['brmm_id'] = $this->Barcodemdl->get_search(['brmm_id' => $_GET['brmm_id']]);
			}
			if(isset($_GET['brmm_sku_id']) && !empty($_GET['brmm_sku_id'])){
				$sku_name = $_GET['brmm_sku_id'];
				
				$subsql .= " AND sku.sku_name = '".$this->db->escape_str($sku_name)."'";

				$record['search']['brmm_sku_id'] = ['value' => $_GET['brmm_sku_id'], 'text'  => $_GET['brmm_sku_id']];
			}
			if(isset($_GET['brmm_branch_id']) && !empty($_GET['brmm_branch_id'])){
				$branch_name = $_GET['brmm_branch_id'];
				
				$subsql .= " AND branch.branch_name = '".$this->db->escape_str($branch_name)."'";

				$record['search']['brmm_branch_id'] = ['value' => $_GET['brmm_branch_id'], 'text'  => $_GET['brmm_branch_id']];
			}
			if(isset($_GET['brmm_apparel_id']) && !empty($_GET['brmm_apparel_id'])){
				$apparel_name = $_GET['brmm_apparel_id'];
				
				$subsql .= " AND apparel.apparel_name = '".$this->db->escape_str($apparel_name)."'";

				$record['search']['brmm_apparel_id'] = ['value' => $_GET['brmm_apparel_id'], 'text'  => $_GET['brmm_apparel_id']];
			}
			if(isset($_GET['prmt_amt_frm']) && !empty($_GET['prmt_amt_frm'])){
				if($_GET['prmt_amt_frm'] != ''){
					$having .=" AND prmt_amt >= ".$_GET['prmt_amt_frm'];
				}
			}
			if(isset($_GET['prmt_amt_to']) && !empty($_GET['prmt_amt_to'])){
				if($_GET['prmt_amt_to'] != ''){
					$having .=" AND prmt_amt <= ".$_GET['prmt_amt_to'];
				}
			}
			if(isset($_GET['ot_amt_frm']) && !empty($_GET['ot_amt_frm'])){
				if($_GET['ot_amt_frm'] != ''){
					$having .=" AND ot_amt >= ".$_GET['ot_amt_frm'];
				}
			}
			if(isset($_GET['ot_amt_to']) && !empty($_GET['ot_amt_to'])){
				if($_GET['ot_amt_to'] != ''){
					$having .=" AND ot_amt <= ".$_GET['ot_amt_to'];
				}
			}
			if(isset($_GET['sold_amt_frm']) && !empty($_GET['sold_amt_frm'])){
				if($_GET['sold_amt_frm'] != ''){
					$having .=" AND sold_amt >= ".$_GET['sold_amt_frm'];
				}
			}
			if(isset($_GET['sold_amt_to']) && !empty($_GET['sold_amt_to'])){
				if($_GET['sold_amt_to'] != ''){
					$having .=" AND sold_amt <= ".$_GET['sold_amt_to'];
				}
			}
			if(isset($_GET['bal_qty_frm']) && !empty($_GET['bal_qty_frm'])){
				if($_GET['bal_qty_frm'] != ''){
					$having .=" AND bal_qty >= ".$_GET['bal_qty_frm'];
				}
			}
			// else{
			// 	$having .=" AND bal_qty >= 1";
			// }
			if(isset($_GET['bal_qty_to']) && !empty($_GET['bal_qty_to'])){
				if($_GET['bal_qty_to'] != ''){
					$having .=" AND bal_qty <= ".$_GET['bal_qty_to'];
				}
			}
			if(isset($_GET['bal_amt_frm']) && !empty($_GET['bal_amt_frm'])){
				if($_GET['bal_amt_frm'] != ''){
					$having .=" AND bal_amt >= ".$_GET['bal_amt_frm'];
				}
			}
			if(isset($_GET['bal_amt_to']) && !empty($_GET['bal_amt_to'])){
				if($_GET['bal_amt_to'] != ''){
					$having .=" AND bal_amt <= ".$_GET['bal_amt_to'];
				}
			}
			
			if($_SESSION['user_branch_id'] > 1){
				$subsql .=" AND brmm.brmm_branch_id = ".$_SESSION['user_branch_id'];
			}
			if($_SESSION['user_id'] != 1){
				$subsql .=" AND branch.branch_id = '".$_SESSION['user_branch_id']."'";
			}
// 			print_r($subsql);exit;
			$query 	="
						SELECT UPPER(supplier.supplier_name) as supplier_name, 
						UPPER(sku.sku_name) as sku_name, 
						UPPER(apparel.apparel_name) as apparel_name,
						GROUP_CONCAT(DISTINCT brmm.brmm_item_code ORDER BY brmm.brmm_item_code SEPARATOR ', ') as barcode,
						SUM(brmm.brmm_prmt_qty) as prmt_qty, (brmm.brmm_prmt_rate) as prmt_rate, 
						SUM(brmm.brmm_prmt_qty * (brmm.brmm_prmt_rate)) as prmt_amt, 
						SUM(brmm.brmm_prrt_qty) as prrt_qty, 
						SUM(brmm.brmm_ot_qty) as ot_qty, 
						(
							SELECT ot.ot_rate
							FROM order_trans ot
							WHERE ot.ot_brmm_id = brmm.brmm_id
							LIMIT 1
						) AS ot_rate,

						(
							SELECT SUM(ot.ot_qty * ot.ot_rate)
							FROM order_trans ot
							WHERE ot.ot_brmm_id = brmm.brmm_id
						) AS ot_amt,
						SUM(brmm.brmm_ort_qty) as ort_qty,
						SUM(brmm.brmm_outward_qty) as outward_qty,
						SUM(brmm.brmm_gt_qty) as inward_qty,
						((brmm.brmm_prmt_rate) * SUM(brmm.brmm_ot_qty)) as sold_amt, 

						((SUM(brmm.brmm_prmt_qty) + SUM(brmm.brmm_ort_qty) + SUM(brmm.brmm_gt_qty)) - (SUM(brmm.brmm_ot_qty) + SUM(brmm.brmm_prrt_qty) + SUM(brmm.brmm_outward_qty))) as bal_qty,

						(((SUM(brmm.brmm_prmt_qty) + SUM(brmm.brmm_ort_qty) + SUM(brmm.brmm_gt_qty)) - (SUM(brmm.brmm_ot_qty) + SUM(brmm.brmm_prrt_qty) + SUM(brmm.brmm_outward_qty))) * (brmm.brmm_prmt_rate)) as bal_amt,
						UPPER(branch.branch_name) as branch_name
						FROM barcode_readymade_master brmm
						INNER JOIN purchase_readymade_master prmm ON(prmm.prmm_id = brmm.brmm_prmm_id)
						INNER JOIN sku_master sku ON(sku.sku_id = brmm.brmm_sku_id)
						INNER JOIN supplier_master supplier ON(supplier.supplier_id = sku.sku_supplier_id)
						INNER JOIN apparel_master apparel ON(apparel.apparel_id = sku.sku_apparel_id)
						LEFT JOIN branch_master branch ON(branch.branch_id = brmm.brmm_branch_id)
						WHERE 1
						AND prmm.prmm_created_at <= '".$this->end_date."'
						AND brmm.brmm_delete_status = 0
						AND brmm.brmm_prmm_id != 0
						$subsql
						GROUP BY supplier.supplier_id, sku.sku_id, apparel.apparel_id, brmm.brmm_prmt_rate, brmm.brmm_id ASC
						HAVING 1
						$having
					 ";
// 			echo "<pre>"; print_r($query); exit();
			$record['data'] = $this->db->query($query)->result_array();
			$prmt_qty  		= 0;
			$prmt_amt  		= 0;
			$prrt_qty  		= 0;
			$outward_qty  	= 0;
			$inward_qty  	= 0;
			$ot_qty  		= 0;
			$ot_amt  		= 0;
			$ort_qty   		= 0;
			$sold_amt  		= 0;
			$bal_qty  		= 0;
			$bal_amt  		= 0;
			// echo "<pre>"; print_r($record); exit();
			
			if(!empty($record['data'])){
				foreach ($record['data'] as $key => $value) {
					$prmt_qty 		= $prmt_qty + $value['prmt_qty'];
					$prmt_amt 		= $prmt_amt + $value['prmt_amt'];
					$prrt_qty 		= $prrt_qty + $value['prrt_qty'];
					$outward_qty 	= $outward_qty + $value['outward_qty'];
					$inward_qty 	= $inward_qty + $value['inward_qty'];
					$ot_qty 		= $ot_qty + $value['ot_qty'];
					$ot_amt 		= $ot_amt + $value['ot_amt'];
					$ort_qty 		= $ort_qty + $value['ort_qty'];
					$sold_amt 		= $sold_amt + $value['sold_amt'];
					$bal_qty 		= $bal_qty + $value['bal_qty'];
					$bal_amt 		= $bal_amt + $value['bal_amt'];
				}
			}
			$record['totals']['prmt_qty'] 		= $prmt_qty;
			$record['totals']['prmt_amt'] 		= $prmt_amt;
			$record['totals']['prrt_qty'] 		= $prrt_qty;
			$record['totals']['outward_qty'] 	= $outward_qty;
			$record['totals']['inward_qty'] 	= $inward_qty;
			$record['totals']['ot_qty'] 		= $ot_qty;
			$record['totals']['ot_amt'] 		= $ot_amt;
			$record['totals']['ort_qty'] 		= $ort_qty;
			$record['totals']['sold_amt'] 		= $sold_amt;
			$record['totals']['bal_qty'] 		= $bal_qty;
			$record['totals']['bal_amt'] 		= $bal_amt;

			if(isset($_GET['submit']) && !empty($_GET['submit']) && $_GET['submit'] == 'EXCEL'){
				return $this->get_data_excel($record);
			}
			return $record;
		}
		public function get_data_excel($record){
			// echo "<pre>"; print_r($record); exit();
			$excel_array[0] = array(
                0 =>  '#',
                1 =>  'SUPPLIER',
                2 =>  'BRANCH',
                3 =>  'SKU',
                4 =>  'APPAREL',
                5 =>  'PURCHASE QTY',
                6 =>  'PURCHASE RATE',
                7 =>  'PURCHASE AMT',
                8 =>  'PURCHASE RETURN QTY',
                9 =>  'SALE QTY',
                10 =>  'SALE RATE',
                11 => 'SALE RETURN QTY',
                12 => 'SOLD QTY X PURCHASE RATE',
                13 => 'BALANCE QTY',
                14 => 'BALANCE STOCK',
            );
            $sr_no = 1;
            foreach ($record['data'] as $key => $value){
            	$excel_array[$sr_no][0] = $sr_no;
                $excel_array[$sr_no][1] = $value['supplier_name'];
                $excel_array[$sr_no][2] = $value['branch_name'];
                $excel_array[$sr_no][3] = $value['sku_name'];
                $excel_array[$sr_no][4] = $value['apparel_name'];
                $excel_array[$sr_no][5] = $value['prmt_qty'];
                $excel_array[$sr_no][6] = $value['prmt_rate'];
                $excel_array[$sr_no][7] = $value['prmt_amt'];
                $excel_array[$sr_no][8] = $value['prrt_qty'];
                $excel_array[$sr_no][9] = $value['ot_qty'];
                $excel_array[$sr_no][10] = $value['ot_rate'];
                $excel_array[$sr_no][11]= $value['ort_qty'];
                $excel_array[$sr_no][12]= $value['sold_amt'];
                $excel_array[$sr_no][13]= $value['bal_qty'];
                $excel_array[$sr_no][14]= $value['bal_amt'];
                $sr_no++;                                  
            }
            return $excel_array;            
		}
	}
?>