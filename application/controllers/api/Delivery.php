<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    use Restserver\Libraries\REST_Controller;
    require_once APPPATH . 'core/MY_Api_Controller.php';
    class Delivery extends my_api_controller{
        public function __construct(){
            parent::__construct([
                'model' => 'api/Delivery_model',
                'table' => 'delivery_master',
                'label' => 'delivery',
            ]);
        }
        public function scan_qrcode(){
            $this->allow_method(['POST']);
            $data = $this->post_data;
            // print_r($data);exit;
            if(!isset($data['qrcode']) || (isset($data['qrcode']) && empty($data['qrcode']))) return $this->response(['message' => 'Invalid QR Code']);
            if(!isset($data['branch_id']) || (isset($data['branch_id']) && $data['branch_id'] <= 0)) return $this->response(['message' => 'Invalid Branch Id']);
            $this->db->trans_begin(); 
            $result = $this->set_master($data['qrcode'], $data['branch_id']);
            if($result['status'] == false) {
                $this->db->trans_rollback();
                return $this->response(['message' => $result['message']]);
            }
            $result = $this->get_barcode_status($data['qrcode'], $data['branch_id'], $result['data']['id']);
            if($result['status'] == false){
                $this->db->trans_rollback();
                return $this->response(['message' => $result['message']]);
            }
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return $this->response(['message' => '1. Transaction Rollback.']);
            }
            $this->db->trans_commit();
            return $this->response(['message' => $result['message'], 'data' => $result['data']]);
        }
        public function set_master($qrcode, $branch_id){
            $data = $this->db->select('*')->from('delivery_master')
            ->where('entry_date = CURDATE()')
            ->where('branch_id', $branch_id)
            ->where('delete_status', 0)
            ->get()->row_array();
            if(!empty($data)) return ['status' => true, 'data' => $data];
            
            $insert_data = [
                'entry_no' => $this->model->get_entry_no('delivery_master', 'entry_no', $branch_id, $this->user['financial_year']),
                'entry_date' => date('Y-m-d'),
                'branch_id' => $branch_id,
                'fin_year' => $this->user['financial_year'],
                'created_by' => $this->user['id'],
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $this->db->insert('delivery_master', $insert_data);
            if($this->db->affected_rows() < 1) return ['status' => false, 'message' => 'Failed to scan QR Code'];
            $insert_id = $this->db->insert_id();
            return ['status' => true, 'data' => ['id' => $insert_id]];
        }
        public function get_barcode_status($obt_item_code, $branch_id, $id){
            if(!isset($obt_item_code) || (isset($obt_item_code) && $obt_item_code <= 0)) return ['status' => false, 'message' => 'Invalid barcode'];
            $obt_item_code = trim($obt_item_code);
            if(empty($obt_item_code)) return ['status' => false, 'message' => 'Invalid barcode'];
            
            $data = $this->db->select('qrcode')->from('delivery_trans')
            ->where('qrcode', $obt_item_code)
            ->where('delivery_id', $id)
            ->where('delivery_trans.delete_status', 0)
            ->get()->row_array();
            if(!empty($data)) return ['status' => false, 'message' => 'Barcode already scanned'];
            
            $query="
                SELECT obt.obt_id, obt_delivered, obt_branch_id,
                UPPER(apparel.apparel_name) as apparel_name
                FROM order_barcode_trans obt
                INNER JOIN apparel_master apparel ON(apparel.apparel_id = obt.obt_apparel_id)
                WHERE obt.obt_delete_status = 0
                AND obt.obt_item_code = $obt_item_code
                ORDER BY apparel.apparel_name ASC";
            $data = $this->db->query($query)->result_array();
            $record = [];
            $msg = '';
            if(!empty($data)) {
                foreach ($data as $key => $value) {
                    $arr['delivery_id'] = $id;
                    $arr['obt_id'] = $value['obt_id'];
                    $arr['type'] = '1';
                    $arr['qrcode'] = $obt_item_code;
                    $arr['apparel_name'] = $value['apparel_name'];
                    $arr['status']      = ($value['obt_branch_id'] == $branch_id)? 'MATCH':'MISMATCH';
                    $arr['status_notes'] = ($value['obt_delivered'] == 1)? 'Already Delivered' : 'Delivery Pending';
                    if($value['obt_branch_id'] != $branch_id) $arr['status_notes'] = 'Other Branch! '.$arr['status_notes'];
                    $arr['created_by'] = $this->user['id'];
                    $arr['created_at'] = date('Y-m-d H:i:s');
                    array_push($record, $arr);
                    $msg = ($value['obt_branch_id'] == $branch_id)? 'Barcode scanned successfully' : 'Mismatch! Barcode scanned from other branch';
                }
            }else{
                $query="
                    SELECT brm.brmm_id,  
                    UPPER('readymade') as apparel_name
                    FROM barcode_readymade_master brm
                    WHERE brm.brmm_delete_status = 0
                    AND brm.brmm_item_code = $obt_item_code
                    ORDER BY brm.brmm_id ASC";
                $data = $this->db->query($query)->result_array(); 
                if(!empty($data)) {
                    foreach ($data as $key => $value) {
                        $arr['delivery_id'] = $id;
                        $arr['obt_id'] = $value['brmm_id'];
                        $arr['type'] = '2';
                        $arr['qrcode'] = $obt_item_code;
                        $arr['apparel_name'] = $value['apparel_name'];
                        $arr['status']      = 'MISMATCH';
                        $arr['status_notes'] = 'Readymade Item';
                        $arr['created_by'] = $this->user['id'];
                        $arr['created_at'] = date('Y-m-d H:i:s');
                        array_push($record, $arr);
                        $msg = 'Mismatch! Barcode scanned from readymade item';
                    }
                }else{
                    $arr['delivery_id'] = $id;
                    $arr['obt_id'] = 0;
                    $arr['type'] = '0';
                    $arr['qrcode'] = $obt_item_code;
                    $arr['apparel_name'] = 'UNKNOWN';
                    $arr['status']      = 'MISMATCH';
                    $arr['status_notes'] = 'Unknown Item';
                    $arr['created_by'] = $this->user['id'];
                    $arr['created_at'] = date('Y-m-d H:i:s');
                    array_push($record, $arr);
                    $msg = 'Mismatch! Unknown Item';
                }
            }
            // pre($record,$this->user);exit;
            if(!empty($record)) {
                if(!$this->db->insert_batch('delivery_trans', $record)) 
                    return ['status' => false, 'message' => 'Failed to insert delivery transaction'];

                $record[0]['created_by_name'] = $this->user['user_name'];
            }
            $this->db->select('total_qty')->from('delivery_master')->where('id', $id);
            $total_qty = $this->db->get()->row_array()['total_qty'];
            // pre($total_qty);exit;
            $this->db->where('id', $id)->update('delivery_master', ['total_qty' => $total_qty+1]);

            return ['status' => true, 'data' => $record, 'message' => $msg];
           // return $record;
        }
        public function get_detail($id=0){     
            $this->allow_method(['GET']);
            if(!isset($id)) return $this->response(['message' => 'delivery Id not defined.']);
            if(empty($id)) return $this->response(['message' => 'delivery Id is empty.']);
            $data=$this->db_operations->get_record('delivery_master',['id'=>$id]); 
            if(empty($data)) return $this->response(['message' => 'Delivery Not Found ']);
            $data = $this->model->get_details($id);
            if(empty($data)) return $this->response(['message' => '1. Delivery Not Found ']);
            return $this->response(['status' => TRUE,'data' => $data[0], 'message' => 'Data fetched successfully..', 'code' => REST_Controller::HTTP_OK]);
        }
    }
?>