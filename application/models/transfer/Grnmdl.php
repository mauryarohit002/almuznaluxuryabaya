<?php defined('BASEPATH') OR exit('No direct script access allowed');
	class Grnmdl extends CI_model{
		protected $master;
		protected $trans;
		public function __construct(){
			parent::__construct();

			$this->master = 'grn_master';
			$this->trans  = 'grn_trans';

			$this->load->model('master/Barcodemdl');
			$this->load->model('master/Branch_model', 'Branchmdl');

		}
		public function check_barcode($data){
			if(!empty($data)){
	        	foreach ($data as $key => $value) {
	        		$brmm_id 		= $value['brmm_id'];
	            	$created_at = $value['created_at'];

	            	$query = "
	            				SELECT prrm.prrm_id
	            				FROM purchase_readymade_return_master prrm
	            				INNER JOIN purchase_readymade_return_trans prrt ON(prrt.prrt_prrm_id = prrm.prrm_id)
	            				WHERE prrt.prrt_brmm_id = $brmm_id
	            				AND prrm.prrm_created_at >= '".$created_at."'
	            				LIMIT 1
	        				";
	        		// echo "<pre>"; print_r($query); exit;
					$data = $this->db->query($query)->result_array();
		            // echo "<pre>"; print_r($data);exit;			
		            if(!empty($data)) return true;			

		            $query = "
	            				SELECT om.om_id
	            				FROM order_master om
	            				INNER JOIN order_trans ot ON(ot.ot_om_id = om.om_id)
	            				WHERE ot.ot_brmm_id = $brmm_id
	            				AND om.om_created_at >= '".$created_at."'
	            				LIMIT 1
	        				";
	        		// echo "<pre>"; print_r($query); exit;
					$data = $this->db->query($query)->result_array();
		            // echo "<pre>"; print_r($data);exit;			
		            if(!empty($data)) return true;			

		            $query = "
	            				SELECT om.om_id
	            				FROM outward_master om
	            				INNER JOIN outward_trans ot ON(ot.ot_om_id = om.om_id)
	            				WHERE ot.ot_brmm_id = $brmm_id
	            				AND om.om_created_at >= '".$created_at."'
	            				LIMIT 1
	        				";
	        		// echo "<pre>"; print_r($query); exit;
					$data = $this->db->query($query)->result_array();
		            // echo "<pre>"; print_r($data);exit;			
		            if(!empty($data)) return true;			
	        	}
	        }
	        return false;
		}
		public function isOutwardExist($ot_id){
			$query = "
						SELECT om.om_created_at as created_at, ot.ot_brmm_id as brmm_id
						FROM outward_master om 
						INNER JOIN outward_trans ot ON(ot.ot_om_id = om.om_id)
						WHERE ot.ot_id = $ot_id
					";
		    // echo $query; exit;
			$data = $this->db->query($query)->result_array();
            // echo "<pre>"; print_r($data);exit;			
            if(!empty($data)){
	        	foreach ($data as $key => $value) {
	        		$brmm_id 		= $value['brmm_id'];
	            	$created_at = $value['created_at'];

	            	 $query = "
	            				SELECT gm.gm_id
	            				FROM grn_master gm
	            				INNER JOIN grn_trans gt ON(gt.gt_gm_id = gm.gm_id)
	            				WHERE gt.gt_brmm_id = $brmm_id
	            				AND gm.gm_created_at >= '".$created_at."'
	            				LIMIT 1
	        				";
	        		// echo "<pre>"; print_r($query); exit;
					$data = $this->db->query($query)->result_array();
		            // echo "<pre>"; print_r($data);exit;			
		            if(!empty($data)) return true;			
	        	}
	        }
	        return false;
		}
		public function isExist($gm_id){
			$query = "
						SELECT gm.gm_created_at as created_at, gt.gt_brmm_id as brmm_id, gm.gm_allocated_amt
						FROM grn_master gm
						INNER JOIN grn_trans gt ON(gt.gt_gm_id = gm.gm_id)
						WHERE gm.gm_id = $gm_id
					";
		    // echo $query; exit;
			$data = $this->db->query($query)->result_array();
            // echo "<pre>"; print_r($data);exit;			
            if(!empty($data) && $data[0]['gm_allocated_amt'] > 0) return true;
			return $this->check_barcode($data);
		}
		public function get_entry_no($condition){
            $data   = $this->db->get_where($this->master,$condition)->result_array();
            if(empty($data)) return ['value' => '', 'text' => ''];
            $value  = $data[0]['gm_id'];
            $text   = $data[0]['gm_entry_no'];
            return ['value' => $value, 'text' => $text];
        }
		public function isAnyReceived($id){
			$data = $this->db_operations->get_record('barcode_readymade_master', ['brmm_om_id' => $id, 'brmm_gt_qty' => 1, 'brmm_delete_status' => 0]);
			// echo "<pre>"; print_r($data);exit;			
			if(empty($data)) return 0;
			foreach ($data as $key => $value) {
				if($value['brmm_gm_id'] != 0) return $value['brmm_gm_id'];
			}
		}
		public function get_record($condition, $wantDropDown = false){
			$record = [];
			$data 	= $this->db->get_where($this->master,$condition)->result_array();
			if(!$wantDropDown) return $data;
			if(empty($data)){
				$record[0] = 'NO ENTRY ADDED';
			}else{
				$record[0] = 'SELECT';
				foreach ($data as $key => $value) {
					$record[$value['gm_id']] = strtoupper($value['gm_entry_no']);
				}
			}
			return $record;
		}
		public function get_data($wantCount, $per_page = PER_PAGE, $offset = OFFSET){
			$record 	= [];
			$subsql 	= '';
			$limit  	= '';
			$ofset  	= '';
			
			if(!$wantCount){
				$limit .= " LIMIT $per_page";
				$ofset .= " OFFSET $offset";
			}
			
			if(isset($_GET['entry_no']) && !empty($_GET['entry_no'])){
                $subsql .=" AND gm.gm_id = ".$_GET['entry_no'];
                $record['search']['entry_no'] = $this->get_entry_no(['gm_id' => $_GET['entry_no']]);
            }
            if(isset($_GET['from_entry_date']) && !empty($_GET['from_entry_date'])){
                $from_entry_date = date('Y-m-d', strtotime($_GET['from_entry_date']));
                $subsql .= " AND gm.gm_entry_date >= '".$from_entry_date."'";
            }
            if(isset($_GET['to_entry_date']) && !empty($_GET['to_entry_date'])){
                $to_entry_date = date('Y-m-d', strtotime($_GET['to_entry_date']));
                $subsql .= " AND gm.gm_entry_date <= '".$to_entry_date."'";
            }
            if(isset($_GET['branch_id']) && !empty($_GET['branch_id'])){
                $subsql .=" AND gm.gm_branch = ".$_GET['branch_id'];
                $record['search']['branch_id'] = $this->Branchmdl->get_search(['branch_id' => $_GET['branch_id']]);
            }
            if(isset($_GET['to_qty'])){
                if($_GET['to_qty'] != ''){
                    $subsql .=" AND gm.gm_total_qty <= ".$_GET['to_qty'];
                }
            }
            if(isset($_GET['from_bill_amt'])){
                if($_GET['from_bill_amt'] != ''){
                    $subsql .=" AND gm.gm_final_amt >= ".$_GET['from_bill_amt'];
                }
            }
            if(isset($_GET['to_bill_amt'])){
                if($_GET['to_bill_amt'] != ''){
                    $subsql .=" AND gm.gm_final_amt <= ".$_GET['to_bill_amt'];
                }
            }
			$query ="
						SELECT gm.*, branch.branch_name, om.om_total_qty, om.om_final_amt
						FROM ".$this->master." gm
						LEFT JOIN branch_master branch ON(branch.branch_id = gm.gm_branch)
						LEFT JOIN outward_master om ON(om.om_id = gm.gm_om_id)
						WHERE gm.gm_branch_id = ".$_SESSION['user_branch_id']."
						AND gm.gm_fin_year = '".$_SESSION['fin_year']."'
						$subsql
						ORDER BY gm.gm_id ASC
						$limit
						$ofset
					";
			// echo "<pre>"; print_r($query); exit;
			if($wantCount){
				return $this->db->query($query)->num_rows();
			}
			$record['data'] = $this->db->query($query)->result_array();

			if(!empty($record['data'])){
				foreach ($record['data'] as $key => $value) {
					$record['data'][$key]['isExist']		= $this->isExist($value['gm_id']);
					$record['data'][$key]['mis_qty'] 		= $value['om_total_qty'] - $value['gm_total_qty'];
					$record['data'][$key]['mis_amt'] 		= $value['om_final_amt'] - $value['gm_final_amt'];
				}
			}
			return $record;
		}
		public function get_pending($wantCount, $per_page = PER_PAGE, $offset = OFFSET){
			$record 	= [];
			$subsql 	= '';
			$limit  	= '';
			$ofset  	= '';
			
			if(!$wantCount){
				$limit .= " LIMIT $per_page";
				$ofset .= " OFFSET $offset";
			}
			
			if(isset($_GET['id']) && !empty($_GET['id'])){
				$subsql .= " AND gm.gm_id = ". $_GET['id'];
			}
			$query ="
						SELECT om.*, branch.branch_name
						FROM outward_master om
						LEFT JOIN barcode_readymade_master brmm ON(brmm.brmm_om_id = om.om_id)
						LEFT JOIN branch_master branch ON(branch.branch_id = om.om_branch_id)
						WHERE om.om_branch = ".$_SESSION['user_branch_id']."
						AND brmm.brmm_branch_id = 0
						$subsql
						GROUP BY om.om_id
						ORDER BY om.om_id ASC
						$limit
						$ofset
					";
			// echo "<pre>"; print_r($_SESSION['user_branch_id']); exit;
			if($wantCount){
				return $this->db->query($query)->num_rows();
			}
			$record['data'] = $this->db->query($query)->result_array();
			// echo "<pre>"; print_r($record); exit;

			if(!empty($record['data'])){
				foreach ($record['data'] as $key => $value) {
					$record['data'][$key]['isAnyReceived'] 	= $this->isAnyReceived($value['om_id']);
					$record['data'][$key]['mis_qty'] 		= $value['om_total_qty'] - $value['om_gm_total_qty'];
					$record['data'][$key]['mis_amt'] 		= $value['om_final_amt'] - $value['om_gm_final_amt'];
				}
			}
			return $record;
		}
		public function get_data_for_edit($gm_id){
			$master_query = "
                                SELECT gm.*
                                FROM ".$this->master." gm
                                WHERE gm.gm_id = $gm_id
                            ";
            $record['master_data'] = $this->db->query($master_query)->result_array();

            $master_query = "
                                SELECT om.*, UPPER(branch.branch_name) as branch_name
                                FROM outward_master om
                                LEFT JOIN branch_master branch ON(branch.branch_id = om.om_branch_id)
                                WHERE om.om_id = ".$record['master_data'][0]['gm_om_id'];
            $record['outward_master'] = $this->db->query($master_query)->result_array();

            $trans_query = "
                                SELECT gt.gt_id as _id, gt.gt_brmm_id as _brmm_id, gt.gt_bill_no as _bill_no, gt.gt_prmm_id as _prmm_id,
                                gt.gt_om_id as _om_id, gt.gt_ot_id as _ot_id,
                                gt.gt_bill_date as _bill_date, gt.gt_sku_id as _sku_id,  
                                gt.gt_apparel_id as _apparel_id, gt.gt_qty as _qty, gt.gt_rate as _rate, 
                                gt.gt_sub_total as _sub_total, gt.gt_status as _status, brmm.brmm_item_code, 
                                UPPER(sku.sku_name) as sku_name,
                                UPPER(apparel.apparel_name) as apparel_name
                                FROM grn_trans gt
                                INNER JOIN outward_trans ot ON(ot.ot_id = gt.gt_ot_id)
                                LEFT JOIN sku_master sku ON(sku.sku_id = gt.gt_sku_id)
                                LEFT JOIN apparel_master apparel ON(apparel.apparel_id = gt.gt_apparel_id)
                                LEFT JOIN barcode_readymade_master brmm ON(brmm.brmm_id = gt.gt_brmm_id)
                                WHERE gt.gt_gm_id = $gm_id
                            ";
            // echo $trans_query; exit();
            $record['trans_data'] = $this->db->query($trans_query)->result_array();
            $ot_id = [];
            if(!empty($record['trans_data'])){
                foreach ($record['trans_data'] as $key => $value) {
                    $record['trans_data'][$key]['isExist'] = $this->isExist($gm_id);
                    array_push($ot_id,$value['_ot_id']);
                }
            }
            if(!empty($ot_id)){
                
                $ot_id = implode(", ", $ot_id);

                $pendind_out_trans = $this->db->query(" SELECT 0 as _id, ot_brmm_id as _brmm_id, '' as _bill_no, ot_prmm_id as _prmm_id,
                                ot_om_id as _om_id, ot_id as _ot_id,
                                '' as _bill_date, ot_sku_id as _sku_id,  
                                ot_apparel_id as _apparel_id, ot_qty as _qty, ot_rate as _rate, 
                                ot_sub_total as _sub_total, 0 as _status, brmm.brmm_item_code, 
                                UPPER(sku.sku_name) as sku_name,
                                UPPER(apparel.apparel_name) as apparel_name
                                FROM outward_trans ot
                                LEFT JOIN apparel_master apparel ON(apparel.apparel_id = ot.ot_apparel_id)
                                LEFT JOIN sku_master sku ON(sku.sku_id = ot.ot_sku_id)
                                LEFT JOIN barcode_readymade_master brmm ON(brmm.brmm_id = ot.ot_brmm_id)
                                WHERE ot.ot_om_id = '".$record['master_data'][0]['gm_om_id']."' AND ot_id NOT IN($ot_id) ")->result_array();
                if(!empty($pendind_out_trans)){
                    foreach($pendind_out_trans as $key => $value){
                        $value['isExist'] = false;
                        $record['trans_data'][] = $value;
                    }
                }
            }
            // echo "<pre>"; print_r($record); exit();
            return $record; 
		}
		public function get_data_for_add($om_id){
			$record['gm_entry_no'] = $this->db_operations->get_fin_year_branch_max_id($this->master, 'gm_entry_no', 'gm_fin_year', $_SESSION['fin_year'], 'gm_branch_id', $_SESSION['user_branch_id']);
			$master_query = "
                                SELECT om.*, UPPER(branch.branch_name) as branch_name
                                FROM outward_master om
                                LEFT JOIN branch_master branch ON(branch.branch_id = om.om_branch_id)
                                WHERE om.om_id = $om_id
                            ";
            $record['outward_master'] = $this->db->query($master_query)->result_array();

            $trans_query = "
                                SELECT ot.ot_id as _id, ot.ot_id as _ot_id, ot.ot_om_id as _om_id, ot.ot_brmm_id as _brmm_id, 
                                ot.ot_bill_no as _bill_no, ot.ot_prmm_id as _prmm_id,
                                ot.ot_bill_date as _bill_date, ot.ot_sku_id as _sku_id, 
                                ot.ot_apparel_id as _apparel_id,  ot.ot_qty as _qty, ot.ot_rate as _rate, 
                                ot.ot_sub_total as _sub_total, brmm.brmm_item_code, 
                                UPPER(sku.sku_name) as sku_name,
                                UPPER(apparel.apparel_name) as apparel_name
                                FROM outward_trans ot
                                LEFT JOIN sku_master sku ON(sku.sku_id = ot.ot_sku_id)
                                LEFT JOIN apparel_master apparel ON(apparel.apparel_id = ot.ot_apparel_id)
                                LEFT JOIN barcode_readymade_master brmm ON(brmm.brmm_id = ot.ot_brmm_id)
                                WHERE ot.ot_om_id = $om_id
                            ";
            $record['trans_data'] = $this->db->query($trans_query)->result_array();
            if(!empty($record['trans_data'])){
                foreach ($record['trans_data'] as $key => $value) {
                    $record['trans_data'][$key]['isExist'] = $this->isOutwardExist($value['_ot_id']);
                }
            }
            // echo "<pre>"; print_r($record); exit;
            return $record; 
		}
		public function get_latest_grn($gm_id, $brmm_id){
			$query="
						SELECT gt.*
						FROM grn_trans gt
						WHERE gt.gt_gm_id != $gm_id 
						AND gt.gt_brmm_id = $brmm_id
						ORDER BY gt.gt_id DESC
						LIMIT 1
					";
			return $this->db->query($query)->result_array();
		}
		public function get_select2_entry_no(){
            $subsql = "";

            if(isset($_GET['name']) && !empty($_GET['name'])){
                $name   = $_GET['name'];
                $subsql .= " AND (gm_entry_no LIKE '%".$name."%') ";
            }
            $query ="
                        SELECT gm_id as id, gm_entry_no as name
                        FROM ".$this->master."
                        WHERE gm_branch_id = ".$_SESSION['user_branch_id']."
                        $subsql
                        ORDER BY gm_entry_no ASC
                        LIMIT 10
                    ";
            // echo $query; exit();
            return $this->db->query($query)->result_array();
        }
        public function get_select2_branch_id(){
            $subsql = "";

            if(isset($_GET['name']) && !empty($_GET['name'])){
                $name   = $_GET['name'];
                $subsql .= " AND (branch.branch_name LIKE '%".$name."%') ";
            }
            $query ="
                        SELECT branch.branch_id as id, UPPER(branch.branch_name) as name
                        FROM ".$this->master." gm
                        INNER JOIN branch_master branch ON(branch.branch_id = gm.gm_branch)
                        WHERE gm_branch_id = ".$_SESSION['user_branch_id']."
                        $subsql
                        GROUP BY branch.branch_id 
                        ORDER BY branch.branch_name ASC
                        LIMIT 10
                    ";
            // echo $query; exit();
            return $this->db->query($query)->result_array();
        }
	}
?>