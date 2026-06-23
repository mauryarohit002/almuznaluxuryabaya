<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Outward extends CI_Controller{
	protected $master;
	protected $trans;
	protected $session_expired;
	public function __construct(){
		parent::__construct();

		$this->master 			= 'outward_master';
		$this->trans 			= 'outward_trans';
		$this->session_expired  = ['status' => false, 'flag' => -1, 'data' => [], 'msg' => ''];
		
		$this->load->model('transfer/Outwardmdl', 'model');
		$this->load->model('master/Barcodemdl');
		$this->load->library('pagination');
		$this->config->load('extra');
	}
	public function index(){	
		if(sessionExist()){
			if(isset($_GET['action'])){
				if($_GET['action'] == 'list'){
					$config 				= array();
					$config 				= $this->config->item('pagination');	
					$config['total_rows'] 	= $this->model->get_data(true);
					$config['base_url'] 	= base_url("outward?search=true");

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
				}else if($_GET['action'] == 'add'){
					$record = $this->model->get_data_for_add();
					// echo "<pre>"; print_r($record); exit;

					$this->load->view('pages/transfer/outward_form', $record);
				}else if($_GET['action'] == 'edit'){
					if(isset($_GET['id']) && !empty($_GET['id'])){
						$record = $this->model->get_data_for_edit($_GET['id']);
						$this->load->view('pages/transfer/outward_form', $record);	
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
		$master_data['om_entry_no']			= trim($post_data['om_entry_no']);
		$master_data['om_entry_date'] 		= date('Y-m-d',strtotime($post_data['om_entry_date']));	
		$master_data['om_branch']			= trim($post_data['om_branch']);
		$master_data['om_total_qty']		= trim($post_data['om_total_qty']);
		$master_data['om_sub_total']		= trim($post_data['om_sub_total']);
		$master_data['om_round_off']		= trim($post_data['om_round_off']);
		$master_data['om_final_amt']		= trim($post_data['om_final_amt']);
		$master_data['om_notes']			= trim($post_data['om_notes']);
		$master_data['om_updated_by'] 		= $_SESSION['user_id'];				
		$temp = $this->model->get_record(['om_id !=' => $id,'om_entry_no' => $master_data['om_entry_no'],'om_fin_year' => $_SESSION['fin_year'],'om_branch_id' => $_SESSION['user_branch_id']]);
		if(!empty($temp)){
			$master_data['om_entry_no'] = $this->db_operations->get_fin_year_branch_max_id($this->master, 'om_entry_no', 'om_fin_year', $_SESSION['fin_year'], 'om_branch_id', $_SESSION['user_branch_id']);
		}
		if($id == 0){
			$this->db->trans_begin();
			$master_data['om_created_by'] 	= $_SESSION['user_id'];
			$master_data['om_created_at'] 	= date('Y-m-d H:i:s');
			$master_data['om_fin_year'] 	= $_SESSION['fin_year'];
			$master_data['om_branch_id'] 	= $_SESSION['user_branch_id'];
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
			$prev_data = $this->model->get_record(['om_id' => $id]);
			if(empty($prev_data)){
				echo json_encode(['status' => true, 'flag' => 0, 'data' => [], 'msg' => 'Record not found.']);
				return;
			}
			$this->db->trans_begin();
			$msg = 'Updated successfully';
			if($this->db_operations->data_update($this->master, $master_data, 'om_id', $id) < 1){
				$this->db->trans_rollback();
				echo json_encode(['status' => true, 'flag' => 0, 'data' => [], 'msg' => 'Master data not updated']);
				return;
			}

			if($this->insert_update_trans($post_data, $id) < 1){
				$this->db->trans_rollback();
				echo json_encode(['status' => true, 'flag' => 0, 'data' => [], 'msg' => 'Transaction data not updated']);
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
		$trans_db_data = $this->db_operations->get_record($this->trans, ['ot_om_id' => $id]);
		if(!empty($trans_db_data)){
			foreach ($trans_db_data as $key => $value){
				if(!in_array($value['ot_id'], $post_data['ot_id'])){
					$data = $this->model->get_record(['om_id' => $id]);
					if(empty($data)) return 0;
					if($this->update_barcode_delete($value) < 1) return 0;
					if($this->db_operations->delete_record($this->trans,array('ot_id' =>$value['ot_id'])) < 1) return 0;
					if($this->db_operations->delete_record('grn_trans',array('gt_ot_id' =>$value['ot_id'])) < 1) return 0;
				} 
			}
		}
		foreach ($post_data['ot_id'] as $key => $value){
			$trans_data['ot_om_id'] 		= $id;
			$trans_data['ot_brmm_id'] 	 	= $post_data['ot_brmm_id'][$key];
			$trans_data['ot_prmm_id'] 	 	= $post_data['ot_prmm_id'][$key];
			$trans_data['ot_bill_no'] 	 	= $post_data['ot_bill_no'][$key];
			$trans_data['ot_bill_date'] 	= date('Y-m-d', strtotime($post_data['ot_bill_date'][$key]));
			$trans_data['ot_sku_id'] 		= $post_data['ot_sku_id'][$key];
			$trans_data['ot_apparel_id'] 	= $post_data['ot_apparel_id'][$key];
			$trans_data['ot_qty'] 			= $post_data['ot_qty'][$key];
			$trans_data['ot_rate']			= $post_data['ot_rate'][$key];
			$trans_data['ot_sub_total']		= $post_data['ot_sub_total'][$key];

			if($value == 0){
				$ot_id = $this->db_operations->data_insert($this->trans, $trans_data);
				if($ot_id < 1) return 0;
				if($this->update_barcode_add($trans_data, $post_data, $ot_id) < 1) return 0;
			}
		}
		return 1;
	}
	public function update_barcode_add($trans_data, $post_data, $ot_id){
		$data = $this->db_operations->get_record('barcode_readymade_master', ['brmm_id' => $trans_data['ot_brmm_id']]);
		if(empty($data)) return 0;

		if($data[0]['brmm_delete_status'] == 1) return 0;

		if($data[0]['brmm_branch_id'] != $_SESSION['user_branch_id']) return 0;

		$result = $this->Barcodemdl->get_state($data);
		        // echo "<pre>"; print_r($result); exit;

		if(($result['state'] != 'PURCHASE') && ($result['state'] != 'SALES RETURN') && ($result['state'] != 'INWARD')) return 0;

		$barcode_readymade_master['brmm_om_id']	 		= $trans_data['ot_om_id'];
		$barcode_readymade_master['brmm_outward_id'] 		= $ot_id;
		$barcode_readymade_master['brmm_outward_qty'] 	= $trans_data['ot_qty'];
		$barcode_readymade_master['brmm_gt_qty'] 		= 0;
		$barcode_readymade_master['brmm_branch_id']		= 0;
		if($this->db_operations->data_update('barcode_readymade_master', $barcode_readymade_master, 'brmm_id', $trans_data['ot_brmm_id']) < 1) return 0;
		return 1;	
	}
	public function update_barcode_delete($trans_data){
		$data = $this->db_operations->get_record('barcode_readymade_master', ['brmm_id' => $trans_data['ot_brmm_id']]);
		if(empty($data)) return 0;

		if($data[0]['brmm_delete_status'] == 1) return 0;

		if($data[0]['brmm_branch_id'] != 0) return 0;

		$result = $this->Barcodemdl->get_state($data);
		// echo "<pre>"; print_r($result); exit;
		if($result['state'] != 'OUTWARD' && $result['state'] != 'INWARD') return 0;
		if($this->model->isExist($trans_data['ot_id'], true)) return 0;

		$latest_data = $this->model->get_latest_outward($trans_data['ot_om_id'], $trans_data['ot_brmm_id']);

		$barcode_readymade_master['brmm_om_id']	 		= !empty($latest_data) ? $latest_data[0]['ot_om_id'] : 0;
		$barcode_readymade_master['brmm_outward_id'] 		= !empty($latest_data) ? $latest_data[0]['ot_id'] : 0;
		$barcode_readymade_master['brmm_outward_qty'] 	= !empty($latest_data) ? 1 : 0;
		$barcode_readymade_master['brmm_gt_qty'] 		= $data[0]['brmm_gt_id'] > 0 ? 1 : 0;
		$barcode_readymade_master['brmm_branch_id']		= $_SESSION['user_branch_id'];
		if($this->db_operations->data_update('barcode_readymade_master', $barcode_readymade_master, 'brmm_id', $trans_data['ot_brmm_id']) < 1) return 0;
		return 1;	
	}
	public function get_all_purchase_barcodes(){
		if(!sessionExist()){
			echo json_encode($this->session_expired);
			return;
		}

		$data = $this->model->get_all_purchase_barcodes();

		echo json_encode([
			'status' => true,
			'flag' => !empty($data) ? 1 : 0,
			'data' => $data
		]);
	}
	public function get_data($id){
		if(!sessionExist()){
			echo json_encode($this->session_expired);
			return;
		}
		$data = $this->model->get_record(['om_id' => $id]);
		if(empty($data)){
			echo json_encode(['status' => true, 'flag' => 0, 'data' => [], 'msg' => 'Master not found']);
			return;	
		}
		echo json_encode(['status' => true, 'flag' => 1, 'data' => $data, 'msg' => 'Data fetched successfully.']);
	}
	public function remove($id){
		if(!sessionExist()){
			echo json_encode($this->session_expired);
			return;
		}
		$data = $this->model->get_record(['om_id' => $id]);
		if(empty($data)){
			echo json_encode(['status' => true, 'flag' => 2, 'data' => [], 'msg' => ' Already deleted']);
			return;	
		}
		if($this->model->isExist($id)){
			echo json_encode(['status' => true, 'flag' => 2, 'data' => [], 'msg' => 'Already exist.']);
			return;	
		}
		$this->db->trans_begin();
		$trans_data = $this->db_operations->get_record($this->trans, ['ot_om_id' => $id]);
		if(empty($trans_data)){
			echo json_encode(['status' => true, 'flag' => 0, 'data' => [], 'msg' => ' Transaction not found.']);
			return;	
		}
		foreach ($trans_data as $key => $value) {
			if($this->update_barcode_delete($value) < 1){
				$this->db->trans_rollback();
				echo json_encode(['status' => true, 'flag' => 0, 'data' => [], 'msg' => 'Barcode not updated.']);
				return;
			}
			if($this->db_operations->delete_record($this->trans, ['ot_id' => $value['ot_id']]) < 1){
				$this->db->trans_rollback();
				echo json_encode(['status' => true, 'flag' => 0, 'data' => [], 'msg' => 'Transaction not deleted']);
				return;
			}			
		}
		if($this->db_operations->delete_record($this->master, ['om_id' => $id]) < 1){
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
	public function get_barcode_select2(){
		$json = [];
		$data = $this->model->get_barcode_select2();
		foreach ($data as $key => $value) 
		{
			$json[] = ['id'=>$value['id'], 'text'=>$value['name']];
		}
		echo json_encode($json);
	}
	public function get_barcode_data($bm_id){ 
  		if(sessionExist()){
  			$data 	= $this->model->get_barcode_data($bm_id);
  			$msg 	= 'Data fetched successfully.';
  			$flag 	= 1;
  			if(empty($data)){
  				$msg 	= 'Record not found.';
  				$flag 	= 0;
  			}
			echo json_encode(['status' => true, 'flag' => $flag, 'data' => $data, 'msg' => $msg]);
		}else {
			echo json_encode($this->session_expired);
		}
  	}

}
?>
