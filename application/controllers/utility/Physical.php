<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Physical extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->master 			= 'physical_stock_master';
		$this->trans 			= 'physical_scan_trans';
		$this->trans1 			= 'physical_unscan_trans';
		$this->session_expired  = ['status' => false, 'flag' => -1, 'data' => [], 'msg' => ''];

        $this->load->model('utility/Physicalmdl', 'model');
        $this->load->library('pagination');
        $this->config->load('extra');
    }
    // core_functions
        public function index(){    
            $result = isLoggedIn();
            // echo "<pre>"; print_r($result);exit;
            if(!$result['session'] || !$result['status'] || !$result['active']){
                redirect('login/logout?msg='.$result['msg']);
                return;
            }
            if($_GET['action'] == 'add'){
                $record = $this->model->get_data_for_add();
                $this->load->view('pages/utility/physical/_form', $record); return ;
            }
            if($_GET['action'] == 'edit' || $_GET['action'] == 'view'){
                if(!isset($_GET['id']) || (isset($_GET['id']) && empty($_GET['id']))){
                    $this->load->view('errors/error'); return;
                }
                $id = encrypt_decrypt("decrypt", $_GET['id'], SECRET_KEY);
                if(empty($id)){
                    $this->load->view('errors/error');return;   
                }
                $record = $this->model->get_data_for_edit($id);
                // echo "<pre>"; print_r($record); exit;
                $this->load->view('pages/utility/physical/_form', $record);  return; 
            }
            $config                 = array();
            $config                 = $this->config->item('pagination');    
            $config['total_rows']   = $this->model->get_list(true);
            $config['base_url']     = base_url('utility/physical?search=true');

            foreach ($_GET as $key => $value){
                if($key != 'search' && $key != 'offset'){
                    $config['base_url'] .= "&" . $key . "=" .$value;
                }
            }

            $offset = (!empty($_GET['offset'])) ? $_GET['offset'] : 0;
            $this->pagination->initialize($config);

            $record['count']        = $offset;
            $record['total_rows']   = $config['total_rows'];
            $record['data']         = $this->model->get_list(false, $config['per_page'], $offset);
            // echo "<pre>"; print_r($record); exit;
            
            $this->load->view('pages/utility/physical/_list', $record);
        }
        public function get_transaction($id){
            $result = isLoggedIn();
            if(!$result['session'] || !$result['status'] || !$result['active']){
                echo json_encode($result);
                return;
            }
            $id = encrypt_decrypt("decrypt", $id, SECRET_KEY);
            if(empty($id)){
                echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Id not found.']);
                return; 
            }
            echo json_encode(['session' => TRUE, 'status' => TRUE, 'data' => $this->model->get_transaction($id), 'msg' => 'Transaction fetched successfully.']);
        }
        public function remove($id){
            $result = isLoggedIn();
            if(!$result['session'] || !$result['status'] || !$result['active']){
                echo json_encode($result);
                return;
            }
            $data = $this->db_operations->get_record('physical_stock_master', ['psm_id' => $id]);
            if(empty($data)){
                echo json_encode(['session' => TRUE, 'status' => REFRESH, 'data' => [], 'msg' => '2. Physical stock not found']);
                return; 
            }
            if($this->model->isExist($id)){
                echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => '1. Not allowed to delete.']);
                return; 
            }
            $this->db->trans_begin();
            $arr = [];
            $scan_data = $this->db_operations->get_record('physical_scan_trans', ['psst_psm_id' => $id]);
            if(!empty($scan_data)){
                foreach ($scan_data as $key => $value) {
                    $result = $this->update_barcode_master_delete($id, $value['psst_brmm_id']);
                    if(!$result['status']){
                        $this->db->trans_rollback();
                        echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => $result['status']]);
                        return ;
                    }
                    array_push($arr, $value['psst_brmm_id']);
                }
                if($this->db_operations->delete_record('physical_scan_trans', ['psst_psm_id' => $id]) < 1){
                    $this->db->trans_rollback();
                    echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Transaction not deleted.']);
                    return ;
                }
            }
            
            $unscan_data = $this->db_operations->get_record('physical_unscan_trans', ['pust_psm_id' => $id]);
            if(!empty($unscan_data)){
                foreach ($unscan_data as $key => $value) {
                    if(!in_array($value['pust_brmm_id'], $arr)){
                        $result = $this->update_barcode_master_delete($id, $value['pust_brmm_id']);
                        if(!$result['status']){
                            $this->db->trans_rollback();
                            echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => $result['status']]);
                            return ;
                        }
                    }
                }
                if($this->db_operations->delete_record('physical_unscan_trans', ['pust_psm_id' => $id]) < 1){
                    $this->db->trans_rollback();
                    echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Transaction not deleted.']);
                    return ;
                }
            }
            if($this->db_operations->delete_record('physical_stock_master', ['psm_id' => $id]) < 1){
                $this->db->trans_rollback();
                echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Master not deleted']);
                return;
            }
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Transaction Rollback']);
                return;
            }
            $this->db->trans_commit();
            echo json_encode(['session' => TRUE, 'status' => TRUE, 'data' => [], 'msg' => 'Deleted successfully']);
        }
        public function get_select2($func){
            $json = [];
            $data = $this->model->$func();
            foreach ($data as $key => $value){
                $json[] = ['id'=>$value['id'], 'text'=>$value['name']];
            }
            echo json_encode($json);
        }
    // core_functions

    // additional_functions
        
    // additional_functions

    // physical_stock_master
        public function initiate_process(){
            $result = isLoggedIn();
            if(!$result['session'] || !$result['status'] || !$result['active']){
                echo json_encode($result);
                return;
            }
            $master_data['psm_entry_no']    = $this->db_operations->get_fin_year_branch_max_id('physical_stock_master', 'psm_entry_no', 'psm_fin_year', $_SESSION['fin_year'], 'psm_branch_id', $_SESSION['user_branch_id']);
            $master_data['psm_entry_date']  = date('Y-m-d');                
            $master_data['psm_fin_year']    = $_SESSION['fin_year'];
            $master_data['psm_branch_id']   = $_SESSION['user_branch_id'];
            $master_data['psm_created_by']  = $_SESSION['user_id'];
            $master_data['psm_created_at']  = date('Y-m-d H:i:s');
            $master_data['psm_updated_by']  = $_SESSION['user_id'];
            $master_data['psm_updated_at']  = date('Y-m-d H:i:s');
            // echo "<pre>"; print_r($master_data); exit();
            $this->db->trans_begin();
            $psm_id  = $this->db_operations->data_insert('physical_stock_master', $master_data);
            if($psm_id < 1){
                $this->db->trans_rollback();
                echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Master data not inserted']);
                return;
            }

            $result = $this->insert_unscan_data($psm_id);
            if(!$result['status']){
                $this->db->trans_rollback();
                echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => $result['msg']]);
                return;
            }       
            
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Data not inserted']);
                return;
            }
            $this->db->trans_commit();
            $data['id'] = encrypt_decrypt("encrypt", $psm_id, SECRET_KEY);

            echo json_encode(['session' => TRUE, 'status' => TRUE, 'data' => $data, 'msg' => 'Process initiated successfully.']);
        }
        public function add_update($psm_id){
            $result = isLoggedIn();
            if(!$result['session'] || !$result['status'] || !$result['active']){
                echo json_encode($result);
                return;
            }
            if($this->model->isExist($psm_id)){
                echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Not allowed.']);
                return;
            }
            $post_data = $this->input->post();
            // echo "<pre>"; print_r($post_data); exit;
            if(empty($post_data)){
                echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Form data is empty.']);
                return;
            }
            $scan_trans     = $this->model->get_scan_trans($psm_id);
            $unscan_trans   = $this->model->get_unscan_trans($psm_id);
            $unscan_barcode = $this->model->get_unscan_barcode($psm_id);
    
            $master_data                    = [];
            $master_data['psm_scan_qty']    = $scan_trans['qty'];
            $master_data['psm_scan_amt']    = $scan_trans['amt'];
            $master_data['psm_unscan_qty']  = ($unscan_trans['qty'] + $unscan_barcode['qty']);
            $master_data['psm_unscan_amt']  = ($unscan_trans['amt'] + $unscan_barcode['amt']);
            $master_data['psm_notes']       = trim($post_data['psm_notes']);
            $master_data['psm_updated_by']  = $_SESSION['user_id'];
            
            $this->db->trans_begin();
            $prev_data = $this->db_operations->get_record('physical_stock_master', ['psm_id' => $psm_id]);
            if(empty($prev_data)){
                $this->db->trans_rollback();
                echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Record not found.']);
                return;
            }
            $msg = 'Physical stock updated successfully';
            if($this->db_operations->data_update('physical_stock_master', $master_data, 'psm_id', $psm_id) < 1){
                $this->db->trans_rollback();
                echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Master data not updated']);
                return;
            }
    
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Data not inserted']);
                return;
            }
            $this->db->trans_commit();
            $data['id'] = encrypt_decrypt("encrypt", $psm_id, SECRET_KEY);
            echo json_encode(['session' => TRUE, 'status' => TRUE, 'data' => $data,  'msg' => $msg]);
        }
    // physical_stock_master

    // physical_scan_trans
    // physical_scan_trans
    
    // physical_unscan_trans
        public function insert_unscan_data($psm_id){
            // echo "<pre>"; print_r($psm_id); exit;
            $unscan_data = $this->model->get_unscan_trans_insert($psm_id);
            // echo "<pre>"; print_r($unscan_data); exit;
            if(!empty($unscan_data)){
                foreach ($unscan_data as $key => $value) {
                    $trans_data                      = [];
                    $trans_data['pust_psm_id']       = $psm_id;
                    $trans_data['pust_brmm_id']        = $value['brmm_id'];
                    $trans_data['pust_size_id']      = $value['brmm_sku_id'];
                    $trans_data['pust_qty']          = $value['bal_qty'];
                    $trans_data['pust_bal_qty']      = $value['bal_qty'];
                    $trans_data['pust_rate']         = $value['brmm_prmt_rate'];
                    // echo "<pre>"; print_r($trans_data); exit;

                    $pust_id = $this->db_operations->data_insert('physical_unscan_trans', $trans_data);
                    if($pust_id < 1){
                        return ['status' => FALSE, 'data' => [], 'msg' => 'Unscan data not added.'];
                    }
                    $barcode_master                       = [];
                    $barcode_master['brmm_psm_id']        = $psm_id;
                    $barcode_master['brmm_psst_id']       = 0;
                    $barcode_master['brmm_pust_id']       = $pust_id;
                    $barcode_master['brmm_pust_qty']      = $value['brmm_pust_qty'] + $value['bal_qty'];
                    $barcode_master['brmm_delete_status'] = 1;
                    if($this->db_operations->data_update('barcode_readymade_master', $barcode_master, 'brmm_id', $value['brmm_id']) < 1){
                        return ['status' => TRUE, 'data' => [], 'msg' => 'Unscan barcode not updated.'];
                    }
                }
            }

            $unscan_trans   = $this->model->get_unscan_trans($psm_id);
            $unscan_barcode = $this->model->get_unscan_barcode($psm_id);

            $physical_stock_master = [];
            $physical_stock_master['psm_unscan_qty']= ($unscan_trans['qty'] + $unscan_barcode['qty']);
            $physical_stock_master['psm_unscan_amt']= ($unscan_trans['amt'] + $unscan_barcode['amt']);

            if($this->db_operations->data_update('physical_stock_master', $physical_stock_master, 'psm_id', $psm_id) < 1){
                return ['status' => FALSE, 'data' => [], 'msg' => 'Master not updated'];
            }
            return ['status' => TRUE, 'data' => [], 'msg' => ''];
        }
    // physical_unscan_trans

    // barcode_master
        public function get_barcode_data($psm_id, $bm_id){
            $result = isLoggedIn();
            if(!$result['session'] || !$result['status'] || !$result['active']){
                echo json_encode($result);
                return;
            }

            if($this->model->isExist($psm_id)){
                echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Not allowed.']);
                return;
            }

            $data = $this->model->get_barcode_data($bm_id);
            // echo "<pre>"; print_r($data); exit;
            if(empty($data)){
                echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Barcode not found.']);
                return;
            }
            
            if($data[0]['brmm_branch_id'] != $_SESSION['user_branch_id']){
                echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Barcode is used in other branch.']);
                return; 
            }
            if($data[0]['brmm_psm_id'] == 0 && $data[0]['brmm_psst_id'] == 0 && $data[0]['brmm_pust_id'] == 0){
                echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => '1. Barcode not available.']);
                return;
            }
            if($data[0]['brmm_psm_id'] != $psm_id){
                if(!empty($data[0]['brmm_psm_id'])){
                    echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'This barcode from previous entry.']);
                    return; 
                }
                if($data[0]['brmm_delete_status'] == 1){
                    echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Barcode is deleted.']);
                    return; 
                }
            }else{
                if($data[0]['brmm_psst_id'] != 0 && $data[0]['brmm_pust_id'] == 0){
                    echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Barcode already added.']);
                    return; 
                }

            }

            $prev_scan_data     = $this->db_operations->get_record('physical_scan_trans', ['psst_psm_id' => $psm_id, 'psst_brmm_id' => $bm_id]);
            $prev_unscan_data   = $this->db_operations->get_record('physical_unscan_trans', ['pust_psm_id' => $psm_id, 'pust_brmm_id' => $bm_id]);
            // echo "<pre>"; print_r($prev_unscan_data); exit;


            $this->db->trans_begin();
            if(empty($prev_scan_data)){
                $trans_data                     = [];
                $trans_data['psst_psm_id']      = $psm_id;
                $trans_data['psst_brmm_id']     = $bm_id;
                $trans_data['psst_sku_id']      = $data[0]['brmm_sku_id'];
                $trans_data['psst_rate']        = $data[0]['brmm_prmt_rate'];
                $trans_data['psst_qty']         = 1;

                $psst_id = $this->db_operations->data_insert('physical_scan_trans', $trans_data);
                if($psst_id < 1){
                    $this->db->trans_rollback();
                    echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Scan data not inserted']);
                    return;
                }
                $psst_qty = 1;
            }else{
                $psst_id = $prev_scan_data[0]['psst_id'];
                $psst_qty= $prev_scan_data[0]['psst_qty'] + 1;
                if($this->db_operations->data_update('physical_scan_trans', ['psst_qty' => $psst_qty], 'psst_id', $psst_id) < 1){
                    $this->db->trans_rollback();
                    echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Scan data not updated']);
                    return;
                }
            }

            if(!empty($prev_unscan_data)){
                if($psst_qty > $prev_unscan_data[0]['pust_bal_qty']){
                    echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Barcode not available.']);
                    return; 
                }
                $pust_id = $prev_unscan_data[0]['pust_id'];
                if($prev_unscan_data[0]['pust_qty'] == 0){
                    if($this->db_operations->delete_record('physical_unscan_trans', ['pust_id' => $pust_id]) < 1){
                        $this->db->trans_rollback();
                        echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Unscan data not deleted']);
                        return;
                    }

                    $barcode_master                     = [];
                    $barcode_master['brmm_psm_id']        = $psm_id; 
                    $barcode_master['brmm_psst_id']       = $psst_id; 
                    $barcode_master['brmm_pust_id']       = 0;
                    $barcode_master['brmm_pust_qty']      = 0;
                    $barcode_master['brmm_delete_status'] = 0;
                    if($this->db_operations->data_update('barcode_readymade_master', $barcode_master, 'brmm_id', $bm_id) < 1){
                        $this->db->trans_rollback();
                        echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Barcode not updated']);
                        return;
                    }
                }else{
                    $pust_qty= $prev_unscan_data[0]['pust_qty'] - 1;
                    if($this->db_operations->data_update('physical_unscan_trans', ['pust_qty' => $pust_qty], 'pust_id', $pust_id) < 1){
                        $this->db->trans_rollback();
                        echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Unscan data not updated']);
                        return;
                    }

                    $barcode_master                       = [];
                    $barcode_master['brmm_psm_id']        = $psm_id; 
                    $barcode_master['brmm_psst_id']       = $psst_id; 
                    $barcode_master['brmm_pust_qty']      = $data[0]['brmm_pust_qty'] - 1;
                    $barcode_master['brmm_delete_status'] = 0;
                    if($this->db_operations->data_update('barcode_readymade_master', $barcode_master, 'brmm_id', $bm_id) < 1){
                        $this->db->trans_rollback();
                        echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Barcode not updated']);
                        return;
                    }                    
                }
            }

            $scan_trans     = $this->model->get_scan_trans($psm_id);
            $unscan_trans   = $this->model->get_unscan_trans($psm_id);
            
            $physical_stock_master = [];
            $physical_stock_master['psm_scan_qty']  = $scan_trans['qty'];
            $physical_stock_master['psm_scan_amt']  = $scan_trans['amt'];
            $physical_stock_master['psm_unscan_qty']= $unscan_trans['qty'];
            $physical_stock_master['psm_unscan_amt']= $unscan_trans['amt'];


            if($this->db_operations->data_update('physical_stock_master', $physical_stock_master, 'psm_id', $psm_id) < 1){
                $this->db->trans_rollback();
                echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Master not updated']);
                return;
            }

            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Transaction Rollback']);
                return;
            }
            $this->db->trans_commit();
            $data[0]['psst_id']     = $psst_id;
            $data[0]['scan_qty']    = $physical_stock_master['psm_scan_qty'];
            $data[0]['unscan_qty']  = $physical_stock_master['psm_unscan_qty'];
            echo json_encode(['session' => TRUE, 'status' => TRUE, 'data' => $data, 'msg' => 'Barcode fetched successfully.']);
        }
        public function remove_barcode($psm_id, $psst_id){
            $result = isLoggedIn();
            if(!$result['session'] || !$result['status'] || !$result['active']){
                echo json_encode($result);
                return;
            }
            if($this->model->isExist($psm_id)){
                echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Not allowed.']);
                return;
            }
            $data = $this->model->get_barcode_by_psst_id($psst_id);
            // echo "<pre>"; print_r($data); exit;
            if(empty($data)){
                echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Transaction not found.']);
                return;
            }
            
            if($data[0]['brmm_psm_id'] != $psm_id && $data[0]['brmm_psst_id'] != $psst_id){
                echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Barcode already removed.']);
                return; 
            }
            
            $this->db->trans_begin();
            
            if($this->db_operations->delete_record('physical_scan_trans', ['psst_id' => $psst_id]) < 1){
                $this->db->trans_rollback();
                echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Scan data not deleted']);
                return;
            }

            $prev_data = $this->db_operations->get_record('physical_unscan_trans', ['pust_psm_id' => $psm_id, 'pust_brmm_id' => $data[0]['brmm_id']]);

            if(empty($prev_data)){
                $trans_data['pust_psm_id']   = $psm_id;
                $trans_data['pust_brmm_id']  = $data[0]['psst_brmm_id'];
                $trans_data['psst_sku_id']   = $data[0]['psst_sku_id'];
                $trans_data['pust_qty']      = $data[0]['psst_qty'];
                $trans_data['pust_rate']     = $data[0]['psst_rate'];
                $pust_id = $this->db_operations->data_insert('physical_unscan_trans', $trans_data);
                if($pust_id < 1){
                    $this->db->trans_rollback();
                    echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Unscan data not added.']);
                    return;
                }
            }else{
                $pust_id  = $prev_data[0]['pust_id'];
                $pust_qty = $prev_data[0]['pust_qty'] + $data[0]['psst_qty'];
                if($this->db_operations->data_update('physical_unscan_trans', ['pust_qty' => $pust_qty], 'pust_id', $pust_id) < 1){
                    $this->db->trans_rollback();
                    echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Unscan barcode not updated.']);
                }
            }

            $barcode_master                     = [];
            $barcode_master['brmm_psm_id']      = $psm_id;
            $barcode_master['brmm_psst_id']     = 0;
            $barcode_master['brmm_pust_id']     = $pust_id;
            $barcode_master['brmm_pust_qty']    = $data[0]['brmm_pust_qty'] + $data[0]['psst_qty'];
            $barcode_master['brmm_delete_status'] = 1;
            if($this->db_operations->data_update('barcode_readymade_master', $barcode_master, 'brmm_id', $data[0]['psst_brmm_id']) < 1){
                $this->db->trans_rollback();
                echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Unscan barcode not updated.']);
            }
            
            
            $scan_trans     = $this->model->get_scan_trans($psm_id);
            $unscan_trans   = $this->model->get_unscan_trans($psm_id);
            
            $physical_stock_master = [];
            $physical_stock_master['psm_scan_qty']  = $scan_trans['qty'];
            $physical_stock_master['psm_scan_amt']  = $scan_trans['amt'];
            $physical_stock_master['psm_unscan_qty']= $unscan_trans['qty'];
            $physical_stock_master['psm_unscan_amt']= $unscan_trans['amt'];


            if($this->db_operations->data_update('physical_stock_master', $physical_stock_master, 'psm_id', $psm_id) < 1){
                $this->db->trans_rollback();
                echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Master not updated']);
                return;
            }

            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Transaction Rollback']);
                return;
            }
            $this->db->trans_commit();
            $data[0]['psst_id']      = $psst_id;
            $data[0]['scan_qty']    = $physical_stock_master['psm_scan_qty'];
            $data[0]['unscan_qty']  = $physical_stock_master['psm_unscan_qty'];
            echo json_encode(['session' => TRUE, 'status' => TRUE, 'data' => $data, 'msg' => 'Barcode fetched successfully.']);
        }
        public function update_barcode_master_delete($psm_id, $bm_id){
            if($this->model->isExist($psm_id)){
                return ['status' => FALSE, 'data' => FALSE, 'msg' => 'Not allowed.'];
            }
            $data = $this->model->get_prev_scan_data($psm_id, $bm_id);
            if(empty($data)){
                $barcode_master['brmm_psm_id']        = 0;
                $barcode_master['brmm_psst_id']       = 0;
                $barcode_master['brmm_pust_id']       = 0;
                $barcode_master['brmm_pust_qty']      = 0;
                $barcode_master['brmm_delete_status'] = 0;
                if($this->db_operations->data_update('barcode_readymade_master', $barcode_master, 'brmm_id', $bm_id) < 1){
                    return ['status' => TRUE, 'data' => TRUE, 'msg' => 'Barcode not updated'];      
                }
            }else{
                $temp       = $this->db_operations->get_record('barcode_readymade_master', ['brmm_id' => $bm_id]);
                $pust_qty   = empty($temp) ? 0 : $temp[0]['brmm_pust_qty'];

                $barcode_master['brmm_psm_id']        = $data[0]['psst_psm_id'];
                $barcode_master['brmm_psst_id']       = $data[0]['psst_id'];
                $barcode_master['brmm_pust_id']       = 0;
                $barcode_master['brmm_pust_qty']      = $pust_qty - $data[0]['psst_qty'];
                $barcode_master['brmm_delete_status'] = 0;
                if($this->db_operations->data_update('barcode_readymade_master', $barcode_master, 'brmm_id', $bm_id) < 1){
                    return ['status' => TRUE, 'data' => TRUE, 'msg' => 'Barcode not updated'];      
                }
            }
            return ['status' => TRUE, 'data' => TRUE, 'msg' => ''];
        }
    // barcode_master
}
?>
