<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Controller.php';
class order_return extends my_controller{ 
    protected $menu;
    protected $sub_menu;
    public $customer_model;
    public function __construct(){
        $this->menu     = 'transaction'; 
        $this->sub_menu = 'order_return'; 
        parent::__construct($this->menu, $this->sub_menu);
        $this->load->model('master/Customer_model', 'customer_model');
    } 

    public function remove(){ 
        $post_data  = $this->input->post();
        $id         = $post_data['id'];
        $result     = isMenuAssigned($this->menu, $this->sub_menu, 'delete');
        if(!$result['session'] || !$result['status'] || !$result['active']) return $result;

        $this->db->trans_begin();
           $result = $this->delete_trans(['ort_orm_id' => $id, 'ort_delete_status' => false]);
            if(!isset($result['status'])){
                $this->db->trans_rollback();
                return $result;
            }

            $result = $this->delete_master(['orm_id' => $id, 'orm_delete_status' => false]);
            if(!isset($result['status'])){
                $this->db->trans_rollback();
                return $result;
            }
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return ['msg' => '3. Transaction Rollback.'];
            }
        $this->db->trans_commit();

        return ['status' => TRUE, 'msg' => 'Order deleted successfully'];
    }

    public function delete_trans($clause)
    {   
        $data = $this->db_operations->get_record($this->sub_menu . '_trans', $clause);
        if (empty($data)) return ['status' => TRUE];
        foreach ($data as $value) {
            $result = $this->update_readymade_barcode_delete($value);
            if(!isset($result['status'])) return $result;   
            
            $result = $this->update_order_master_delete($value);
            if(!isset($result['status'])) return $result; 

            $update_data                        = [];
            $update_data['ort_delete_status']    = true; 
            $update_data['ort_updated_by']       = $_SESSION['user_id']; 
            $update_data['ort_updated_at']       = date('Y-m-d H:i:s'); 
            if($this->db_operations->data_update($this->sub_menu.'_trans', $update_data, 'ort_id', $value['ort_id']) < 1) return ['msg' => '2. Transaction not deleted.'];
        }

        return ['status' => TRUE];
    }

    public function delete_master($clause)
    {
        $data = $this->db_operations->get_record($this->sub_menu . '_master', $clause);
        if (empty($data)) return ['msg' => 'Order not found.'];
        foreach ($data as $value) {
            $prev_data = $this->db_operations->get_record($this->sub_menu . '_master', [
                'orm_id' => $value['orm_id'],'orm_delete_status' => 0]);
            if (!empty($prev_data)) { 
                $soft_delete_master = [
                    'orm_delete_status' => 1,
                    'orm_updated_by'    => $_SESSION['user_id'],
                    'orm_updated_at'    => date('Y-m-d H:i:s')];
                if ($this->db_operations->data_update('order_return_master',$soft_delete_master,
                        'orm_id',$value['orm_id']) < 1) {
                    return ['msg' => 'Order master not deleted.'];
                }
            }
        } 
        return ['status' => TRUE];
    } 

    public function get_readymade_barcode_data(){  
        $post_data  = $this->input->post();
        $id             = $post_data['id'];
        $customer_id    = $post_data['customer_id']; 
        $trans_data = isset($post_data['trans_data']) ? json_decode($post_data['trans_data'], true) : [];
        $data = $this->model->get_readymade_barcode_data($id);
        // echo "<pre>"; print_r($post_data); 
        // echo "<pre>"; print_r($trans_data); exit;
        if((empty($data))) return ['msg' => '1. Readymade Barcode not found.'];
        if($data[0]['brmm_delete_status'] == 1) return ['msg' => '1. Readymade Barcode is deleted.'];
        if($data[0]['bal_qty'] <= 0) return ['msg' => '1. Readymade Barcode not available.'];
        if(!empty($trans_data)){ 
            foreach ($trans_data as $key => $value) {
                if($customer_id != $value['ort_customer_id']){
                    return ['msg' => 'This barcode is from diffrent Customer'];
                }
            }
        }
        $result = $this->add_transaction($data[0]);
        if(!isset($result['status'])) return $result;
        return['status' => TRUE, 'data' => $result['data'], 'msg' => ' Readymade Barcode scanned.'];
    }

    public function add_transaction($data){          
        $post_data  = $this->input->post();
        // echo "<pre>"; print_r($data);die;
        $trans_data                     = [];
        $trans_data['ort_orm_uuid']      = trim($post_data['orm_uuid']);
        $trans_data['ort_customer_id'] = $data['customer_id'];
        $trans_data['ort_brmm_id']       = $data['brmm_id'];
        $trans_data['ort_qty']           = trim($data['ot_qty']);
        $trans_data['ort_ot_id']         = trim($data['ot_id']);
        $trans_data['ort_om_id']         = trim($data['ot_om_id']);

        $trans_data['ort_rate']          = trim($data['ot_rate']);
        $trans_data['ort_amt']           = trim($data['ot_amt']);
        $trans_data['ort_disc_per']      = $data['ot_disc_per'];
        $trans_data['ort_disc_amt']      =  $data['ot_disc_amt'];
        $trans_data['ort_taxable_amt']   = trim($data['ot_taxable_amt']);
        $trans_data['ort_sgst_per']      = trim($data['ot_sgst_per']);
        $trans_data['ort_sgst_amt']      = trim($data['ot_sgst_amt']);
        $trans_data['ort_cgst_per']      = trim($data['ot_cgst_per']);
        $trans_data['ort_cgst_amt']      = trim($data['ot_cgst_amt']);
        $trans_data['ort_igst_per']      = trim($data['ot_igst_per']);
        $trans_data['ort_igst_amt']      = trim($data['ot_igst_amt']);
        $trans_data['ort_total_amt']     = trim($data['ot_total_amt']);

        $trans_data['ort_delete_status'] = true;
        $trans_data['ort_created_by']    = $_SESSION['user_id'];
        $trans_data['ort_updated_by']    = $_SESSION['user_id'];
        $trans_data['ort_created_at']    = date('Y-m-d H:i:s');
        $trans_data['ort_updated_at']    = date('Y-m-d H:i:s');
        
        $trans_data['ort_id'] = $this->db_operations->data_insert('order_return_trans', $trans_data);
        if($trans_data['ort_id'] < 1) return ['msg' => '1. Order Transaction not added.'];
        $trans_data['isExist'] = false;
        $trans_data['ort_orm_id'] = 0;
        $trans_data['item_code'] = $this->model->get_readymade_item_code($trans_data['ort_brmm_id']);
        $trans_data['customer_name']     = trim($data['customer_name']);

        // echo "<pre>"; print_r($result);die;
        return ['status' => TRUE, 'data' => $trans_data,  'msg' => 'Order Transaction added successfully.'];
    }


 
    public function add_edit(){      
        $post_data  = $this->input->post();
        $id         = isset($post_data['orm_id']) ? $post_data['orm_id'] : 0;
        $post_data['trans_data'] = isset($post_data['trans_data']) ? json_decode($post_data['trans_data'], true) : [];
        if(empty($post_data['trans_data'])) return ['msg' => 'No Products Added'];
        // echo "<pre>"; print_r($post_data);die;
        $master_data = [
            'orm_uuid'           => trim($post_data['orm_uuid']),
            'orm_entry_no'       => trim($post_data['orm_entry_no']),
            'orm_entry_date'     => date('Y-m-d', strtotime($post_data['orm_entry_date'])),
            'orm_customer_id'    => isset($post_data['orm_customer_id']) ? trim($post_data['orm_customer_id']) : 0,
            'orm_total_qty'      => $post_data['orm_total_qty'],
            'orm_total_amt'      => $post_data['orm_total_amt'],
            'orm_sub_amt'        => $post_data['orm_sub_amt'],
            'orm_disc_amt'       => $post_data['orm_disc_amt'],
            'orm_taxable_amt'   => $post_data['orm_taxable_amt'],
            'orm_sgst_amt'      => $post_data['orm_sgst_amt'],
            'orm_cgst_amt'      => $post_data['orm_cgst_amt'],
            'orm_igst_amt'      => $post_data['orm_igst_amt'],
            'orm_gst_amt'        => $post_data['orm_gst_amt'],
            'orm_updated_by'     => $_SESSION['user_id']
        ];
   // echo "<pre>"; print_r($master_data);die;
        $this->db->trans_begin();
        if ($id == 0) {  
            $master_data['orm_entry_no']= $this->model->get_max_entry_no([
                'entry_no'     => 'orm_entry_no',
                'delete_status'=> 'orm_delete_status',
                'fin_year'     => 'orm_fin_year']);

            $master_data['orm_created_by']   = $_SESSION['user_id'];
            $master_data['orm_created_at']   = date('Y-m-d H:i:s');
            $master_data['orm_fin_year']     = $_SESSION['fin_year'];
            $master_data['orm_branch_id']    = $_SESSION['user_branch_id'];

            $uuidExist = $this->db_operations->get_cnt('order_return_master', ['orm_uuid' => $master_data['orm_uuid']]);
            if($uuidExist > 0){
                $this->db->trans_rollback();
                return ['msg' => 'Form already submitted.'];
            }
            $id = $this->db_operations->data_insert('order_return_master', $master_data);
            $msg = 'Order added successfully.';
            if($id < 1){
                $this->db->trans_rollback();
                return ['msg' => 'Order not added.'];
            }
        } 
        else 
        {
            $prev_data = $this->db_operations->get_record('order_return_master', [
                'orm_id' => $id,'orm_delete_status' => false]);
            if(empty($prev_data)){
                $this->db->trans_rollback();
                return ['status' => REFRESH, 'msg' => 'Order not found.'];
            }
            $msg = 'Order updated successfully.';
            if($this->db_operations->data_update('order_return_master', $master_data, 'orm_id', $id) < 1){
                $this->db->trans_rollback();
                return ['msg' => 'Order not updated.'];
            }
        }

        $transResult = $this->add_update_trans($post_data, $id);
        if(!isset($transResult['status'])){
            $this->db->trans_rollback();
            return $transResult;
        }
        if ($this->db->trans_status() === FALSE){ 
            $this->db->trans_rollback();
            return ['msg' => 'Transaction Rollback.'];
        }
        $this->db->trans_commit();
        $data['id']   = encrypt_decrypt("encrypt", $id, SECRET_KEY);
        $data['name'] = strtoupper($master_data['orm_entry_no']);
        return ['status' => TRUE, 'data' => $data, 'msg' => $msg];
    } 
    public function add_update_trans($post_data, $id){
             // echo "<pre>"; print_r($post_data); exit;  
            $trans_db_data = $this->db_operations->get_record($this->sub_menu.'_trans', ['ort_orm_id' => $id, 'ort_delete_status' => false]);
            $ids = $this->get_id($post_data);
            if(!empty($trans_db_data)){
                foreach ($trans_db_data as $key => $value){
                    if(!in_array($value['ort_id'], $ids)){
                       $result = $this->delete_trans(['ort_id' => $value['ort_id'], 'ort_delete_status' => false]);
                        if(!isset($result['status'])) return $result;
                    } 
                }
            }

            foreach ($post_data['trans_data'] as $key => $value){   
                $trans_data                         = [];
                $trans_data['ort_orm_id']           = $id;
                $trans_data['ort_customer_id']      = $value['ort_customer_id'];
                $trans_data['ort_ot_id']            = $value['ort_ot_id'];
                $trans_data['ort_om_id']            = $value['ort_om_id'];
                $trans_data['ort_brmm_id']           = $value['ort_brmm_id'];
                $trans_data['ort_qty']               = $value['ort_qty'];
                $trans_data['ort_rate']              = $value['ort_rate'];

                $trans_data['ort_amt']               = $value['ort_amt'];
                $trans_data['ort_disc_per']          = $value['ort_disc_per'];
                $trans_data['ort_disc_amt']          = $value['ort_disc_amt'];
                $trans_data['ort_taxable_amt']       = $value['ort_taxable_amt'];
                $trans_data['ort_sgst_per']          = $value['ort_sgst_per'];
                $trans_data['ort_sgst_amt']          = $value['ort_sgst_amt'];
                $trans_data['ort_cgst_per']          = $value['ort_cgst_per'];
                $trans_data['ort_cgst_amt']          = $value['ort_cgst_amt'];
                $trans_data['ort_igst_per']          = $value['ort_igst_per'];
                $trans_data['ort_igst_amt']          = $value['ort_igst_amt'];
                $trans_data['ort_total_amt']         = $value['ort_total_amt']; 

                $trans_data['ort_delete_status']     = false;
                $trans_data['ort_updated_by']        = $_SESSION['user_id'];
                $trans_data['ort_updated_at']        = date('Y-m-d H:i:s');
                // trans_data
                if($value['ort_id'] != 0){  
                    $prev_data = $this->db_operations->get_record($this->sub_menu.'_trans', ['ort_id' => $value['ort_id']]);
                    if(empty($prev_data)) return ['msg' => '5. Transaction not found.'];
                        if($this->db_operations->data_update($this->sub_menu.'_trans', $trans_data, 'ort_id', $value['ort_id']) < 1){
                            return ['msg' => 'Transaction not updated.'];
                        }
                            
                        if(empty($prev_data[0]['ort_orm_id'])){ 
                            $result = $this->update_readymade_barcode_add($trans_data);
                            if(!isset($result['status'])) return $result;
                            $result = $this->update_order_master_add($trans_data);
                            if(!isset($result['status'])) return $result;
                        }else{ 
                            $result=$this->update_readymade_barcode_delete($prev_data[0]);
                            if(!isset($result['status'])) return $result;

                            $result = $this->update_readymade_barcode_add($trans_data);
                            if(!isset($result['status'])) return $result;

                            $result=$this->update_order_master_delete($prev_data[0]);
                            if(!isset($result['status'])) return $result;

                            $result = $this->update_order_master_add($trans_data);
                            if(!isset($result['status'])) return $result;
                        }
                }
            }
            return ['status' => TRUE];
    }

    public function get_id($post_data){
        $record = [];
        foreach ($post_data['trans_data'] as $key => $value) {
            array_push($record, $value['ort_id']);
        }
        return $record;
    }

    // readymade_barcode_master
    public function update_readymade_barcode_add($trans_data){
        $data = $this->model->get_readymade_barcode_data($trans_data['ort_brmm_id']);
        if(empty($data)) return ['msg' => '2. Readymade Barcode not found.'];
        if($data[0]['brmm_delete_status'] == 1) return ['msg' => '2. Readymade Barcode is deleted.'];
        if($trans_data['ort_qty'] > $data[0]['bal_qty']) return ['msg' => '3. Readymade Barcode not available.'];
        $barcode_master = [];
        $barcode_master['brmm_ort_qty'] = $data[0]['brmm_ort_qty'] + $trans_data['ort_qty'];
        if($this->db_operations->data_update('barcode_readymade_master', $barcode_master, 'brmm_id', $trans_data['ort_brmm_id']) < 1){
            return ['msg' => 'Readymade Barcode not updated.'];   
        }
        return ['status' => TRUE];
    }
    public function update_readymade_barcode_delete($trans_data){  
        if($this->model->isTransExist($trans_data['ort_id'])) return ['msg' => '6. Not allowed to Change Bill'];
        $data = $this->model->get_readymade_barcode_data($trans_data['ort_brmm_id']);
        if(empty($data)) return ['msg' => '3. Readymade Barcode not found'];
       
        if($trans_data['ort_qty'] > ($data[0]['brmm_ort_qty'])) return ['msg' => '4. Readymade Barcode not available.'];
        $barcode_readymade_master['brmm_ort_qty']= $data[0]['brmm_ort_qty'] - $trans_data['ort_qty'];
        if($this->db_operations->data_update('barcode_readymade_master', $barcode_readymade_master, 'brmm_id', $trans_data['ort_brmm_id']) < 1){
            return ['msg' => 'Readymade Barcode not updated.'];   
        }
        return ['status' => TRUE];
    }

    public function update_order_master_add($trans_data){
        $data = $this->db_operations->get_record('order_master',['om_id'=>$trans_data['ort_om_id']]);
        if(empty($data)) return ['msg' => 'order master not found.'];
        $order_master = [];
        $order_master['om_return_qty']=$data[0]['om_return_qty'] + $trans_data['ort_qty'];
        $order_master['om_return_amt']=$data[0]['om_return_amt'] + $trans_data['ort_total_amt'];
        if($this->db_operations->data_update('order_master', $order_master, 'om_id', $trans_data['ort_om_id']) < 1){
            return ['msg' => 'Order master not updated.'];   
        }
        return ['status' => TRUE];
    }
    public function update_order_master_delete($trans_data){  
        $data = $this->db_operations->get_record('order_master',['om_id'=>$trans_data['ort_om_id']]);
        $order_master['om_return_qty']= $data[0]['om_return_qty'] - $trans_data['ort_qty'];
        $order_master['om_return_amt']= $data[0]['om_return_amt'] - $trans_data['ort_total_amt'];
        if($this->db_operations->data_update('order_master', $order_master, 'om_id', $trans_data['ort_om_id']) < 1){
            return ['msg' => 'Order Master not updated.'];   
        }
        return ['status' => TRUE];
    }
   

        
}
?>