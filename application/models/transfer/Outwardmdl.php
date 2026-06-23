<?php defined('BASEPATH') OR exit('No direct script access allowed');
	class Outwardmdl extends CI_model{
		protected $master;
		protected $trans;
		public function __construct(){
			parent::__construct();

			$this->master = 'outward_master';
			$this->trans  = 'outward_trans';

            $this->load->model('master/Branch_model', 'Branchmdl');
		}
		public function isExist($id, $trans = false){
            $inward = $trans ? " AND ot_id = $id" : " AND ot_om_id = $id";
            $data = $this->db->query("SELECT ot_id FROM outward_trans WHERE ot_gt_qty = 1 $inward LIMIT 1")->result_array();
            if(!empty($data)) return true;

            return false;
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
					$record[$value['om_id']] = strtoupper($value['om_entry_no']);
				}
			}
			return $record;
		}
		public function get_entry_no($condition){
            $data   = $this->db->get_where($this->master,$condition)->result_array();
            if(empty($data)) return ['value' => '', 'text' => ''];
            $value  = $data[0]['om_id'];
            $text   = $data[0]['om_entry_no'];
            return ['value' => $value, 'text' => $text];
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
                $subsql .=" AND om.om_id = ".$_GET['entry_no'];
                $record['search']['entry_no'] = $this->get_entry_no(['om_id' => $_GET['entry_no']]);
            }
            if(isset($_GET['from_entry_date']) && !empty($_GET['from_entry_date'])){
                $from_entry_date = date('Y-m-d', strtotime($_GET['from_entry_date']));
                $subsql .= " AND om.om_entry_date >= '".$from_entry_date."'";
            }
            if(isset($_GET['to_entry_date']) && !empty($_GET['to_entry_date'])){
                $to_entry_date = date('Y-m-d', strtotime($_GET['to_entry_date']));
                $subsql .= " AND om.om_entry_date <= '".$to_entry_date."'";
            }
            if(isset($_GET['branch_id']) && !empty($_GET['branch_id'])){
                $subsql .=" AND om.om_branch = ".$_GET['branch_id'];
                $record['search']['branch_id'] = $this->Branchmdl->get_search(['branch_id' => $_GET['branch_id']]);
            }
            if(isset($_GET['from_qty'])){
                if($_GET['from_qty'] != ''){
                    $subsql .=" AND om.om_total_qty >= ".$_GET['from_qty'];
                }
            }
            if(isset($_GET['to_qty'])){
                if($_GET['to_qty'] != ''){
                    $subsql .=" AND om.om_total_qty <= ".$_GET['to_qty'];
                }
            }
            if(isset($_GET['from_bill_amt'])){
                if($_GET['from_bill_amt'] != ''){
                    $subsql .=" AND om.om_final_amt >= ".$_GET['from_bill_amt'];
                }
            }
            if(isset($_GET['to_bill_amt'])){
                if($_GET['to_bill_amt'] != ''){
                    $subsql .=" AND om.om_final_amt <= ".$_GET['to_bill_amt'];
                }
            }
			$query ="
						SELECT om.*, branch.branch_name
						FROM ".$this->master." om
						LEFT JOIN branch_master branch ON(branch.branch_id = om.om_branch)
						WHERE om.om_branch_id = ".$_SESSION['user_branch_id']."
                        AND om.om_fin_year = '".$_SESSION['fin_year']."'
						$subsql
						ORDER BY om.om_entry_no DESC
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
					$record['data'][$key]['isExist'] 	= $this->isExist($value['om_id']);
					$record['data'][$key]['mis_qty'] 	= $value['om_total_qty'] - $value['om_gm_total_qty'];
					$record['data'][$key]['mis_amt'] 	= $value['om_final_amt'] - $value['om_gm_final_amt'];
				}
			}
			return $record;
		}
		public function get_data_for_add(){
			$record['om_entry_no'] = $this->db_operations->get_fin_year_branch_max_id($this->master, 'om_entry_no', 'om_fin_year', $_SESSION['fin_year'], 'om_branch_id', $_SESSION['user_branch_id']);
			$record['branches'] = $this->get_list(['branch_id !=' => $_SESSION['user_branch_id'],'branch_status' => true], true);
			return $record;
		}
        public function get_list($condition, $wantDropDown = false){
			$record = [];
			$data 	= $this->db->get_where('branch_master',$condition)->result_array();
			if(!$wantDropDown) return $data;
			if(empty($data)){
				$record[0] = 'NO '.strtoupper('BRANCH').' ADDED';

			}else{
				$record[0] = 'SELECT';
				foreach ($data as $key => $value) {
					$record[$value['branch_id']] = strtoupper($value['branch_name']);
				}
			}
			return $record;
		}
		public function get_data_for_edit($om_id){
			$master_query = "
                                SELECT om.*, UPPER(branch.branch_name) as branch_name
                                FROM ".$this->master." om
                                LEFT JOIN branch_master branch ON(branch.branch_id = om.om_branch)
                                WHERE om.om_id = $om_id
                            ";
            $record['master_data'] = $this->db->query($master_query)->result_array();

            $trans_query = "
                                SELECT ot.*, brmm.brmm_item_code, 
                                UPPER(sku.sku_name) as sku_name,
                                UPPER(apparel.apparel_name) as apparel_name
                                FROM ".$this->trans." ot
                                LEFT JOIN sku_master sku ON(sku.sku_id = ot.ot_sku_id)
                                LEFT JOIN apparel_master apparel ON(apparel.apparel_id = ot.ot_apparel_id)
                                LEFT JOIN barcode_readymade_master brmm ON(brmm.brmm_id = ot.ot_brmm_id)
                                WHERE ot.ot_om_id = $om_id
                            ";
            $record['trans_data'] = $this->db->query($trans_query)->result_array();
            if(!empty($record['trans_data'])){
                foreach ($record['trans_data'] as $key => $value) {
                    $record['trans_data'][$key]['isExist'] = $this->isExist($value['ot_id'], true);
                }
            }

			$record['branches'] = $this->get_list(['branch_id !=' => $_SESSION['user_branch_id'],'branch_status' => true], true);
            
            return $record; 
		}
		public function get_latest_outward($om_id, $brmm_id){
			$query="
						SELECT ot.*
						FROM outward_trans ot
						WHERE ot.ot_om_id != $om_id
						AND ot.ot_brmm_id = $brmm_id
						ORDER BY ot.ot_id DESC
						LIMIT 1
					";
			return $this->db->query($query)->result_array();
		}
		public function get_all_purchase_barcodes(){
			$get_query = "
				SELECT 
					brmm.brmm_id,
					brmm.brmm_item_code,
					UPPER(supplier.supplier_name) as supplier_name,
					UPPER(sku.sku_name) as sku_name,
					UPPER(size.size_name) as size_name
				FROM barcode_readymade_master brmm

				LEFT JOIN purchase_readymade_master prmm 
					ON (prmm.prmm_id = brmm.brmm_prmm_id)

				INNER JOIN supplier_master supplier 
					ON (supplier.supplier_id = prmm.prmm_supplier_id)

				LEFT JOIN purchase_readymade_trans prmt 
					ON (prmt.prmt_id = brmm.brmm_prmt_id)

				LEFT JOIN sku_master sku 
					ON (sku.sku_id = prmt.prmt_sku_id)

				LEFT JOIN size_master size 
					ON (size.size_id = prmt.prmt_size_id)

				WHERE brmm.brmm_delete_status = 0
				AND prmm.prmm_delete_status = 0
				AND prmm.prmm_branch_id = ".$_SESSION['user_branch_id']."

				-- ✅ IMPORTANT filters
				AND brmm.brmm_outward_qty = 0
				AND brmm.brmm_om_id = 0
				GROUP BY brmm.brmm_item_code
				ORDER BY brmm.brmm_item_code DESC
			";
			// echo "<pre>"; print_r($get_query); exit;

			$data = $this->db->query($get_query)->result_array();
			return $data;
		}
		public function get_select2_entry_no(){
            $subsql = "";

            if(isset($_GET['name']) && !empty($_GET['name'])){
                $name   = $_GET['name'];
                $subsql .= " AND (om.om_entry_no LIKE '%".$name."%') ";
            }
            $query ="
                        SELECT om_id as id, om_entry_no as name
                        FROM ".$this->master." om
                        WHERE om.om_branch_id = ".$_SESSION['user_branch_id']."
                        $subsql
                        ORDER BY om_entry_no ASC
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
                        FROM ".$this->master." om
                        INNER JOIN branch_master branch ON(branch.branch_id = om.om_branch)
                        WHERE om.om_branch_id = ".$_SESSION['user_branch_id']."
                        $subsql
                        GROUP BY branch.branch_id 
                        ORDER BY branch.branch_name ASC
                        LIMIT 10
                    ";
            // echo $query; exit();
            return $this->db->query($query)->result_array();
        }
        public function get_subsql($value=''){
			$subsql = "";
			if(isset($_GET['name']) && !empty($_GET['name'])){
				$name 	= $_GET['name'];
				$subsql .= " AND (brmm.brmm_item_code LIKE '%".$name."%')";
			}
			if(isset($_GET['param']) && !empty($_GET['param']) && $_GET['param'] == 'PRETURN'){
				$subsql .= " AND ((brmm.brmm_prmt_qty - brmm.brmm_prrt_qty) = 1 AND (brmm.brmm_ot_qty - brmm.brmm_srt_qty) = 0)";
				$subsql .=" AND brmm.brmm_branch_id = ".$_SESSION['user_branch_id'];
			}else if(isset($_GET['param']) && !empty($_GET['param']) && $_GET['param'] == 'SALES'){
				$subsql .= " AND ((brmm.brmm_prmt_qty - brmm.brmm_prrt_qty) = 1 AND (brmm.brmm_ot_qty - brmm.brmm_srt_qty) = 0)";				
				$subsql .=" AND brmm.brmm_branch_id = ".$_SESSION['user_branch_id'];
			}else if(isset($_GET['param']) && !empty($_GET['param']) && $_GET['param'] == 'SRETURN'){
				$subsql .= " AND ((brmm.brmm_prmt_qty - brmm.brmm_prrt_qty) = 1 AND (brmm.brmm_ot_qty - brmm.brmm_srt_qty) = 1)";
				$subsql .=" AND brmm.brmm_branch_id = ".$_SESSION['user_branch_id'];
			}else if(isset($_GET['param']) && !empty($_GET['param']) && $_GET['param'] == 'OUTWARD'){
				$subsql .= " AND ((brmm.brmm_prmt_qty - brmm.brmm_prrt_qty) = 1 AND (brmm.brmm_ot_qty - brmm.brmm_srt_qty) = 0)";
				$subsql .=" AND brmm.brmm_branch_id = ".$_SESSION['user_branch_id'];
			}else if(isset($_GET['param']) && !empty($_GET['param']) && $_GET['param'] == 'GRN'){
				if(isset($_GET['param1']) && !empty($_GET['param1'])){
					$subsql .= " AND brmm.brmm_om_id = ".$_GET['param1'];
					// $subsql .= " AND brmm.brmm_branch_id = 0";
				}else{
					$subsql .= " AND brmm_id = 0";
				}
				
			}
			return $subsql;
		}
        public function get_barcode_select2(){
			$subsql = $this->get_subsql();
			$query ="
						SELECT brmm.brmm_id as id, brmm.brmm_item_code as name
						FROM barcode_readymade_master brmm
						WHERE brmm.brmm_delete_status = 0 
						$subsql
						LIMIT 10
					";
			// echo $query; exit;
			return $this->db->query($query)->result_array();
		}

        public function get_barcode_data($brmm_id){  
	        $query ="
	                    SELECT 
						supplier.supplier_id, UPPER(supplier.supplier_name) as supplier_name,
	                    prmm.prmm_id, prmm.prmm_bill_no, DATE_FORMAT(prmm.prmm_bill_date, '%d-%m-%Y') as prmm_bill_date,
	                    brmm.brmm_id, brmm.brmm_prmt_id,
	                    brmm.brmm_prmt_qty, brmm.brmm_prmt_rate, 
	                    prmt.prmt_taxable_amt as prt_taxable_amt,
	                    brmm.brmm_item_code,
	                    sku.sku_id, UPPER(sku.sku_name) as sku_name,
						apparel.apparel_id, UPPER(apparel.apparel_name) as apparel_name,
	                    supplier.supplier_id as supplier_id, UPPER(supplier.supplier_name) as supplier_name, 
	                    user.user_id, UPPER(user.user_fullname) as user_name, 
	                    supplier.supplier_mobile as supplier_mobile,
	                    ot.ot_id, ot.ot_rate, 
	                    ot.ot_taxable_amt as srt_taxable_amt,
	                    om.om_id, 
	                    om.om_entry_no, DATE_FORMAT(om.om_entry_date, '%d-%m-%Y') as om_entry_date
	                    FROM barcode_readymade_master brmm
	                    LEFT JOIN purchase_readymade_master prmm ON(prmm.prmm_id = brmm.brmm_prmm_id)
	                    LEFT JOIN purchase_readymade_trans prmt ON(prmt.prmt_id = brmm.brmm_prmt_id)
	                    LEFT JOIN order_trans ot ON(ot.ot_brmm_id = brmm.brmm_id)
	                    LEFT JOIN order_master om ON(om.om_id = ot.ot_om_id)
	                    LEFT JOIN sku_master sku ON(sku.sku_id = brmm.brmm_sku_id)
						LEFT JOIN apparel_master apparel ON(apparel.apparel_id = sku.sku_apparel_id)
	                    LEFT JOIN supplier_master supplier ON(supplier.supplier_id = brmm.brmm_supplier_id)
	                    LEFT JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)
	                    LEFT JOIN user_master user ON(user.user_id = om.om_created_by)
	                    WHERE brmm.brmm_id = $brmm_id
	                    AND brmm.brmm_delete_status = 0 
	                    AND brmm.brmm_branch_id = ".$_SESSION['user_branch_id']."
	                ";
		        $data = $this->db->query($query)->result_array(); 
		        // echo "<pre>"; print_r($data); exit;
		        return $data;
	    }
	}
?>