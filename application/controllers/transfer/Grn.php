<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Grn extends CI_Controller{
	protected $master;
	protected $trans;
	protected $session_expired;
	public function __construct(){
		parent::__construct();

		$this->master 			= 'grn_master';
		$this->trans 			= 'grn_trans';
		$this->session_expired  = ['status' => false, 'flag' => -1, 'data' => [], 'msg' => ''];
		
		$this->load->model('transfer/Grnmdl', 'model');
		$this->load->model('master/Barcodemdl');
		$this->load->library('pagination');
		$this->config->load('extra');
	}
	public function index(){	
		if(sessionExist()){
			if(isset($_GET['action'])){
				if($_GET['action'] == 'view'){
					$config 				= array();
					$config 				= $this->config->item('pagination');	
					$config['total_rows'] 	= $this->model->get_data(true);
					$config['base_url'] 	= base_url("transfer/grn?search=true");

					foreach ($_GET as $key => $value) 
					{
						if($key != 'search' && $key != 'offset')
						{
							$config['base_url'] .= "&" . $key . "=" .$value;
						}
					}

					$offset = (!empty($_GET['offset'])) ? $_GET['offset'] : 0;
					$this->pagination->initialize($config);

					$record['count']		= $offset;
					$record['total_rows'] 	= $config['total_rows'];
					$record['data']			= $this->model->get_data(false, $config['per_page'], $offset);
					// echo "<pre>"; print_r($record); exit;
					
					$this->load->view('pages/transfer/'.$this->master, $record);
				}else if($_GET['action'] == 'edit'){
					if(isset($_GET['id']) && !empty($_GET['id'])){
						$record = $this->model->get_data_for_edit($_GET['id']);
						// echo "<pre>"; print_r($record); exit;

						$this->load->view('pages/transfer/grn_form', $record);	
					}else{
						$this->load->view('errors/error');
					}
				}else if($_GET['action'] == 'print'){
					if(isset($_GET['id']) && !empty($_GET['id'])){
						$record = $this->model->get_data_for_print($_GET['id']);
						$this->load->view('pdfs/barcode_pdf', $record);
					}else{
						$this->load->view('errors/error');
					}
				}else{
					$this->load->view('errors/error');
				}
			}else{
				$this->load->view('errors/error');
			}
		}else{
			redirect('login/logout');	
		}
	}
	public function pending(){	
		if(sessionExist()){
			if(isset($_GET['action'])){
				if($_GET['action'] == 'view'){
					$config 				= array();
					$config 				= $this->config->item('pagination');	
					$config['total_rows'] 	= $this->model->get_pending(true);
					$config['base_url'] 	= base_url("grn/pending?search=true");

					foreach ($_GET as $key => $value) 
					{
						if($key != 'search' && $key != 'offset')
						{
							$config['base_url'] .= "&" . $key . "=" .$value;
						}
					}

					$offset = (!empty($_GET['offset'])) ? $_GET['offset'] : 0;
					$this->pagination->initialize($config);

					$record['count']		= $offset;
					$record['total_rows'] 	= $config['total_rows'];
					$record['data']			= $this->model->get_pending(false, $config['per_page'], $offset);
					// echo "<pre>"; print_r($record); exit;
					
					$this->load->view('pages/transfer/pending_master', $record);
				}else if($_GET['action'] == 'add'){
					if(isset($_GET['id']) && !empty($_GET['id'])){
						$record = $this->model->get_data_for_add($_GET['id']);
						// echo "<pre>"; print_r($record); exit;
						$this->load->view('pages/transfer/grn_form', $record);	
					}else{
						$this->load->view('errors/error');
					}
				}else if($_GET['action'] == 'print'){
					if(isset($_GET['id']) && !empty($_GET['id'])){
						$record = $this->model->get_data_for_print($_GET['id']);
						$this->load->view('pdfs/barcode_pdf', $record);
					}else{
						$this->load->view('errors/error');
					}
				}else{
					$this->load->view('errors/error');
				}
			}else{
				$this->load->view('errors/error');
			}
		}else{
			redirect('login/logout');	
		}
	}
	public function add_update($id){
		if(!sessionExist()){
			echo json_encode($this->session_expired);
			return;
		}
		$post_data = $this->input->post();
		// echo "<pre>"; print_r($post_data); exit;
		if(empty($post_data)){
			echo json_encode(['status' => true, 'flag' => 0, 'data' => [], 'msg' => 'Form data is empty.']);
			return;
		}
		$master_data['gm_entry_no']			= trim($post_data['gm_entry_no']);
		$master_data['gm_entry_date'] 		= date('Y-m-d',strtotime($post_data['gm_entry_date']));	
		$master_data['gm_branch']			= trim($post_data['gm_branch']);
		$master_data['gm_om_id']			= trim($post_data['gm_om_id']);
		$master_data['gm_total_qty']		= trim($post_data['gm_total_qty']);
		$master_data['gm_sub_total']		= trim($post_data['gm_sub_total']);
		$master_data['gm_round_off']		= trim($post_data['gm_round_off']);
		$master_data['gm_final_amt']		= trim($post_data['gm_final_amt']);
		$master_data['gm_notes']			= trim($post_data['gm_notes']);
		$master_data['gm_updated_by'] 		= $_SESSION['user_id'];				
		$temp = $this->model->get_record(['gm_id !=' => $id,'gm_entry_no' => $master_data['gm_entry_no'],'gm_fin_year' => $_SESSION['fin_year'],'gm_branch_id' => $_SESSION['user_branch_id']]);
		if(!empty($temp)){
			$master_data['gm_entry_no'] = $this->db_operations->get_fin_year_branch_max_id($this->master, 'gm_entry_no', 'gm_fin_year', $_SESSION['fin_year'], 'gm_branch_id', $_SESSION['user_branch_id']);
		}
		if($id == 0){
			$this->db->trans_begin();
			$master_data['gm_created_by'] 	= $_SESSION['user_id'];
			$master_data['gm_created_at'] 	= date('Y-m-d H:i:s');
			$master_data['gm_fin_year'] 	= $_SESSION['fin_year'];
			$master_data['gm_branch_id'] 	= $_SESSION['user_branch_id'];
			$id  = $this->db_operations->data_insert($this->master, $master_data);
			$msg = 'Added successfully';
			if($id < 1){
				$this->db->trans_rollback();
				echo json_encode(['status' => true, 'flag' => 0, 'data' => [], 'msg' => 'Master data not inserted']);
				return;
			}

			if($this->insert_update_trans($post_data, $id) < 1){
				$this->db->trans_rollback();
				echo json_encode(['status' => true, 'flag' => 0, 'data' => [], 'msg' => 'Transaction data not inserted']);
				return;
			}
			if ($this->db->trans_status() === FALSE){
			    $this->db->trans_rollback();
			    echo json_encode(['status' => true, 'flag' => 0, 'data' => [], 'msg' => 'Data not inserted']);
				return;
		    }
		    $this->db->trans_commit();
		}else{
			$prev_data = $this->model->get_record(['gm_id' => $id]);
			if(empty($prev_data)){
				echo json_encode(['status' => true, 'flag' => 0, 'data' => [], 'msg' => 'Record not found.']);
				return;
			}
			$this->db->trans_begin();
			$msg = 'Updated successfully';
			if($this->db_operations->data_update($this->master, $master_data, 'gm_id', $id) < 1){
				$this->db->trans_rollback();
				echo json_encode(['status' => true, 'flag' => 0, 'data' => [], 'msg' => 'Master data not updated']);
				return;
			}

			$result = $this->insert_update_trans($post_data, $id);
			if(!isset($result['status'])){
				$this->db->trans_rollback();
				echo json_encode(['status' => true, 'flag' => 0, 'data' => [], 'msg' => $result['msg']]);
				return;
			}
			if ($this->db->trans_status() === FALSE){
			    $this->db->trans_rollback();
			    echo json_encode(['status' => true, 'flag' => 0, 'data' => [], 'msg' => 'Rollback']);
				return;
		    }
		    $this->db->trans_commit();
		}
		$data['id'] = $id;
		echo json_encode(['status' => true, 'flag' => 1, 'data' => $data,  'msg' => $msg]);
	}
	public function insert_update_trans($post_data, $id){
		$trans_db_data = $this->db_operations->get_record($this->trans, ['gt_gm_id' => $id]);
		if(!empty($trans_db_data)){
			foreach ($trans_db_data as $key => $value){
				if(!in_array($value['gt_id'], $post_data['gt_id'])){
					if($this->db_operations->delete_record($this->trans,array('gt_id' =>$value['gt_id'])) < 1) return ['msg'=>'Trans not deleted'];
				} 
			}
		}
		$total_qty = 0;
		$total_amt = 0;
		foreach ($post_data['gt_id'] as $key => $value){
			$trans_data = [];	
			$trans_data['gt_gm_id'] 		= $id;
			$trans_data['gt_brmm_id'] 	 	= $post_data['gt_brmm_id'][$key];
			$trans_data['gt_om_id'] 	 	= $post_data['gt_om_id'][$key];
			$trans_data['gt_ot_id'] 	 	= $post_data['gt_ot_id'][$key];
			$trans_data['gt_prmm_id'] 	 	= $post_data['gt_prmm_id'][$key];
			$trans_data['gt_bill_no'] 	 	= $post_data['gt_bill_no'][$key];
			$trans_data['gt_bill_date'] 	= date('Y-m-d', strtotime($post_data['gt_bill_date'][$key]));
			$trans_data['gt_sku_id'] 		= $post_data['gt_sku_id'][$key];
			$trans_data['gt_apparel_id'] 	= $post_data['gt_apparel_id'][$key];
			$trans_data['gt_qty'] 			= $post_data['gt_qty'][$key];
			$trans_data['gt_rate']			= $post_data['gt_rate'][$key];
			$trans_data['gt_sub_total']		= $post_data['gt_sub_total'][$key];
			$trans_data['gt_status']		= $post_data['gt_status'][$key];

			if($value == 0){
				$gt_id = $this->db_operations->data_insert($this->trans, $trans_data);
				if($gt_id < 1) return ['msg'=>'Trans not inserted'];
			}else{
				if($this->db_operations->data_update($this->trans, $trans_data, 'gt_id', $value) < 1) return ['msg'=>'Trans not updated'];
				$gt_id = $value;
			}
			if($trans_data['gt_status'] == 1){
				$result = $this->update_barcode_received($trans_data, $gt_id);
				if(!isset($result['status'])) return $result;
				$total_qty = $total_qty + $trans_data['gt_qty'];
				$total_amt = $total_amt + ($trans_data['gt_qty'] * $trans_data['gt_rate']);
				if($this->db_operations->data_update('outward_trans', ['ot_gt_qty' => $trans_data['gt_qty']], 'ot_id', $trans_data['gt_ot_id']) < 1) return ['msg'=>'2.trans not updated'];
			}else{
				$result = $this->update_barcode_delete($trans_data);
				if(!isset($result['status'])) return $result;
				if($this->db_operations->data_update('outward_trans', ['ot_gt_qty' => 0], 'ot_id', $trans_data['gt_ot_id']) < 1) return ['msg'=>'3.trans not updated'];
			}

		}
		if($this->db_operations->data_update('outward_master', ['om_gm_total_qty' => $total_qty, 'om_gm_final_amt' => $total_amt], 'om_id', $post_data['gm_om_id']) < 1) return ['msg'=>'outward master not updated'];

		return ['status' => true];
	}
	public function update_barcode_received($trans_data, $gt_id){
		$data = $this->db_operations->get_record('barcode_readymade_master', ['brmm_id' => $trans_data['gt_brmm_id']]);
		if(empty($data)) return ['msg'=>'barcode not found'];

		if($data[0]['brmm_delete_status'] == 1) return ['msg'=>'barcode deleted'];

		if(!empty($data[0]['brmm_branch_id']) && $data[0]['brmm_branch_id'] != $_SESSION['user_branch_id']) return ['msg'=>'barcode has in different branch'];

		$result = $this->Barcodemdl->get_state($data);
		// echo "<pre>"; print_r($result); exit;
		if(($result['state'] != 'OUTWARD') && ($result['state'] != 'INWARD')) return ['msg'=>$result['msg']];
		$barcode_readymade_master['brmm_gm_id']	 		= $trans_data['gt_gm_id'];
		$barcode_readymade_master['brmm_gt_id'] 		= $gt_id;
		$barcode_readymade_master['brmm_gt_qty'] 		= 1;
		$barcode_readymade_master['brmm_branch_id']		= $_SESSION['user_branch_id'];
// 		$barcode_readymade_master['brmm_outward_qty']	= 0;
// 		$barcode_readymade_master['brmm_ort_qty']		= 0;

		if($this->db_operations->data_update('barcode_readymade_master', $barcode_readymade_master, 'brmm_id', $trans_data['gt_brmm_id']) < 1) return ['msg'=>'4.barcode not updated'];
		return ['status' => true];	
	}
	public function update_barcode_delete($trans_data){
		$data = $this->db_operations->get_record('barcode_readymade_master', ['brmm_id' => $trans_data['gt_brmm_id']]);
		// echo "<pre>"; print_r($data);exit();
		if(empty($data)) return ['msg'=>'barcode not found'];

		if($data[0]['brmm_delete_status'] == 1) return ['msg'=>'barcode deleted'];
		
		if(!empty($data[0]['brmm_branch_id']) && $data[0]['brmm_branch_id'] != $_SESSION['user_branch_id']) return ['msg'=>'barcode is in different branch'];

		$result = $this->Barcodemdl->get_state($data);
		if(($result['state'] != 'OUTWARD') && ($result['state'] != 'INWARD')) return ['msg'=>$data[0]['brmm_item_code'].' '.$result['msg']];

		$latest_data = $this->model->get_latest_grn($trans_data['gt_gm_id'], $trans_data['gt_brmm_id']);
		// echo "<pre>"; print_r($latest_data);exit();

		$barcode_readymade_master['brmm_gm_id']	 		= !empty($latest_data) ? $latest_data[0]['gt_gm_id'] : 0;
		$barcode_readymade_master['brmm_gt_id'] 		= !empty($latest_data) ? $latest_data[0]['gt_id'] : 0;
		$barcode_readymade_master['brmm_gt_qty'] 		= !empty($latest_data) ? 1 : 0;
		$barcode_readymade_master['brmm_branch_id']		= 0;
		$barcode_readymade_master['brmm_outward_qty']		= empty($data[0]['brmm_outward_id']) ? 0 : 1;
		$barcode_readymade_master['brmm_ort_qty']		= empty($data[0]['brmm_ort_id']) ? 0 : 1;
		// echo "<pre>"; print_r($barcode_readymade_master);exit();
		if($this->db_operations->data_update('barcode_readymade_master', $barcode_readymade_master, 'brmm_id', $trans_data['gt_brmm_id']) < 1) return ['msg'=>'barcode master not updated'];

		return ['status' => true];
	}
	public function get_data($id){
		if(!sessionExist()){
			echo json_encode($this->session_expired);
			return;
		}
		$data = $this->model->get_record(['gm_id' => $id]);
		if(empty($data)){
			echo json_encode(['status' => true, 'flag' => 0, 'data' => [], 'msg' => 'Master not found']);
			return;	
		}
		echo json_encode(['status' => true, 'flag' => 1, 'data' => $data, 'msg' => 'Data fetched successfully.']);
	}
	public function get_pending_count(){
		if(!sessionExist()){
			echo json_encode($this->session_expired);
			return;
		}
		$data = $this->model->get_pending(true);
		echo json_encode(['status' => true, 'flag' => 1, 'data' => $data, 'msg' => 'Data fetched successfully.']);
	}
	public function remove($id){
		if(!sessionExist()){
			echo json_encode($this->session_expired);
			return;
		}
		$data = $this->model->get_record(['gm_id' => $id]);
		if(empty($data)){
			echo json_encode(['status' => true, 'flag' => 2, 'data' => [], 'msg' => ' Already deleted']);
			return;	
		}
		if($this->model->isExist($id)){
			echo json_encode(['status' => true, 'flag' => 2, 'data' => [], 'msg' => 'Already exist.']);
			return;	
		}
		$this->db->trans_begin();
		$trans_data = $this->db_operations->get_record($this->trans, ['gt_gm_id' => $id]);
		if(empty($trans_data)){
			echo json_encode(['status' => true, 'flag' => 0, 'data' => [], 'msg' => ' Transaction not found.']);
			return;	
		}
		foreach ($trans_data as $key => $value) {
			$result = $this->update_barcode_delete($value);
			if(!isset($result['status'])){
				$this->db->trans_rollback();
				echo json_encode(['status' => true, 'flag' => 0, 'data' => [], 'msg' => $result['msg']]);
				return ;
			}
			if($this->db_operations->data_update('outward_trans', ['ot_gt_qty' => 0], 'ot_id', $value['gt_ot_id']) < 1){
				$this->db->trans_rollback();
				echo json_encode(['status' => true, 'flag' => 0, 'data' => [], 'msg' => 'Outward Transaction data not updated.']);
				return ;
			}
			if($this->db_operations->delete_record($this->trans, ['gt_id' => $value['gt_id']]) < 1){
				$this->db->trans_rollback();
				echo json_encode(['status' => true, 'flag' => 0, 'data' => [], 'msg' => 'Transaction not deleted']);
				return;
			}			
		}
		if($this->db_operations->data_update('outward_master', ['om_gm_total_qty' => 0, 'om_gm_final_amt' => 0], 'om_id', $data[0]['gm_om_id']) < 1){
			$this->db->trans_rollback();
			echo json_encode(['status' => true, 'flag' => 0, 'data' => [], 'msg' => 'Outward not updated']);
			return;
		}
		if($this->db_operations->delete_record($this->master, ['gm_id' => $id]) < 1){
			$this->db->trans_rollback();
			echo json_encode(['status' => true, 'flag' => 0, 'data' => [], 'msg' => 'Master not deleted']);
			return;
		}
		if ($this->db->trans_status() === FALSE){
		    $this->db->trans_rollback();
		    echo json_encode(['status' => true, 'flag' => 0, 'data' => [], 'msg' => 'Rollback']);
			return;
	    }
	    $this->db->trans_commit();
		echo json_encode(['status' => true, 'flag' => 1, 'data' => [], 'msg' => 'Deleted successfully']);
	}
	public function get_select2_entry_no(){
		$json = [];
		$data = $this->model->get_select2_entry_no();
		foreach ($data as $key => $value){
			$json[] = ['id'=>$value['id'], 'text'=>$value['name']];
		}
		echo json_encode($json);
	}
	public function get_select2_branch_id(){
		$json = [];
		$data = $this->model->get_select2_branch_id();
		foreach ($data as $key => $value){
			$json[] = ['id'=>$value['id'], 'text'=>$value['name']];
		}
		echo json_encode($json);
	}
}
?>
