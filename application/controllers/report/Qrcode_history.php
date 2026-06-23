<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Controller.php';
class qrcode_history extends my_controller{
	protected $menu;
    protected $sub_menu;
	public function __construct(){
		$this->menu     = 'report';
        $this->sub_menu = 'qrcode_history';
		parent::__construct($this->menu, $this->sub_menu); 
	}
	public function index(){	
		$result = isLoggedIn();
		// echo "<pre>"; print_r($_POST);exit;
		if(!$result['session'] || !$result['status'] || !$result['active']){
			redirect('login/logout?msg='.$result['msg']);
			return;
		}
		$result     = isMenuAssigned($this->menu, $this->sub_menu);
		$action_data= get_action_data($this->menu, $this->sub_menu);
		$menu_data  = get_submenu_data($this->menu, $this->sub_menu);
		if(!$result['session'] || !$result['status'] || !$result['active']){
			$this->load->view('errors/unauthorized'); return;
		}
		$record['menu']		    = $this->menu;
		$record['sub_menu']		= $this->sub_menu;
		$record['action_data']	= $action_data;
		$record['menu_name']    = $menu_data['menu_name'];
		$record['sub_menu_name']= $menu_data['sub_menu_name'];
		$record['data']			= $this->model->get_record();
		// echo "<pre>"; print_r($record); exit;

		$this->load->view('pages/'.$this->menu.'/'.$this->sub_menu.'/_list', $record);
	}
	public function update_mrp(){
		$post_data  = $this->input->post();
		$id         = $post_data['id'];
		$mrp        = $post_data['mrp'];

		$result     = isMenuAssigned($this->menu, $this->sub_menu, 'update_mrp');
		if(!$result['session'] || !$result['status'] || !$result['active']) return $result;

		$data = $this->db_operations->get_record('barcode_master', ['bm_id' => $id]);
		if(empty($data)) return ['msg' => 'Barcode not found.'];
		if($data[0]['bm_delete_status'] == 1) return ['msg' => 'Barcode is deleted.'];

		$this->db->trans_begin();
		if($this->db_operations->data_update('purchase_trans', ['pt_mrp' => $mrp], 'pt_id', $data[0]['bm_pt_id']) < 1){
			$this->db->trans_rollback();
			return ['msg' => 'Purchase data not updated'];
		}

		if($this->db_operations->data_update('barcode_master', ['bm_mrp' => $mrp], 'bm_id', $id) < 1){
			$this->db->trans_rollback();
			return ['msg' => 'Barcode data not updated'];
		}
		if ($this->db->trans_status() === FALSE){
		    $this->db->trans_rollback();
		    return ['msg' => 'Transaction Rollback.'];
	    }
	    $this->db->trans_commit();
		return ['status' => TRUE, 'data' => encrypt_decrypt("encrypt", $id, SECRET_KEY), 'msg' => 'MRP updated successfully.'];
	}









public function delete_transaction()
{
    // Check if it's an AJAX request
    if (!$this->input->is_ajax_request()) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
        return;
    }

    $module = $this->input->post('module');
    $entry_no = $this->input->post('entry_no');
    $item_code = $this->input->post('item_code');
    $delete_qty = $this->input->post('qty');
    
    $response = ['status' => 'error', 'message' => 'Invalid request'];
    
    try {
        $this->db->trans_start();
        
        switch($module) {
            case 'GRN':
                $response = $this->delete_grn_transaction($entry_no, $item_code, $delete_qty);
                break;
            case 'OUTWARD':
                $response = $this->delete_outward_transaction($entry_no, $item_code, $delete_qty);
                break;
            case 'PURCHASE RETURN':
                $response = $this->delete_purchase_return_transaction($entry_no, $item_code, $delete_qty);
                break;
            case 'PURCHASE':
                $response = $this->delete_purchase_readymade_transaction($entry_no, $item_code, $delete_qty);
                break;
            default:
                $response = ['status' => 'error', 'message' => 'Invalid module: ' . $module];
        }
        
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            $response = ['status' => 'error', 'message' => 'Database transaction failed'];
        }
        
    } catch(Exception $e) {
        $response = ['status' => 'error', 'message' => $e->getMessage()];
    }
    
    echo json_encode($response);
}

/**
 * Delete GRN Transaction
 * First condition: If order exists, no delete button will show
 * Second condition: Show delete button in sequence: GRN -> Outward -> Purchase Return -> Purchase
 */
private function delete_grn_transaction($entry_no, $item_code, $delete_qty)
{
    // Check if order exists
    $order_check = "SELECT COUNT(*) as order_count 
                    FROM order_master om 
                    INNER JOIN order_trans ot ON ot.ot_om_id = om.om_id
                    INNER JOIN barcode_readymade_master brmm ON brmm.brmm_id = ot.ot_brmm_id
                    WHERE brmm.brmm_item_code = '" . $this->db->escape_str($item_code) . "'";
    
    $order_result = $this->db->query($order_check)->row();
    if($order_result->order_count > 0) {
        return ['status' => 'error', 'message' => 'Cannot delete: Order exists for this item'];
    }
    
    // Get GRN master ID first
    $master_query = "SELECT gm_id FROM grn_master gm LEFT JOIN grn_trans gt ON gt.gt_gm_id = gm.gm_id LEFT JOIN barcode_readymade_master brmm ON brmm.brmm_id = gt.gt_brmm_id WHERE brmm.brmm_item_code = '" . $this->db->escape_str($item_code) . "'";
    $master = $this->db->query($master_query)->row();
    
    if(!$master) {
        return ['status' => 'error', 'message' => 'GRN master not found'];
    }
            // echo "<pre>"; print_r($master); exit;

    // Get the specific transaction to delete
    $trans_query = "SELECT gt.*, brmm.brmm_gt_qty as current_qty 
                    FROM grn_trans gt
                    INNER JOIN barcode_readymade_master brmm ON brmm.brmm_id = gt.gt_brmm_id
                    WHERE gt.gt_gm_id = " . $master->gm_id . "
                    AND brmm.brmm_item_code = '" . $this->db->escape_str($item_code) . "'";
    
    $transaction = $this->db->query($trans_query)->row();
    
    if(!$transaction) {
        return ['status' => 'error', 'message' => 'Transaction not found'];
    }
    
    // Check total transactions
    $count_query = "SELECT COUNT(*) as total FROM grn_trans WHERE gt_gm_id = " . $master->gm_id;
    $total_trans = $this->db->query($count_query)->row()->total;
    
    // Delete the transaction
    $this->db->where('gt_id', $transaction->gt_id);
    $this->db->delete('grn_trans');
    
    if($total_trans > 1) {
        // Recalculate totals from remaining transactions
        $remaining = $this->db->query("
            SELECT 
                SUM(gt_qty) as total_qty,
                SUM(gt_rate) as total_amount
            FROM grn_trans 
            WHERE gt_gm_id = " . $master->gm_id
        )->row();
        
        // Update master with recalculated values
        $this->db->where('gm_id', $master->gm_id);
        $this->db->update('grn_master', [
            'gm_total_qty' => $remaining->total_qty,
            'gm_sub_total' => $remaining->total_amount,
            'gm_final_amt' => $remaining->total_amount
        ]);
        
    } else {
        // No transactions left, delete master
        $this->db->where('gm_id', $master->gm_id);
        $this->db->delete('grn_master');
    }
    
    // Update barcode table
    $this->db->set('brmm_gt_qty', 'brmm_gt_qty - ' . $transaction->gt_qty, FALSE);
    // if($total_trans == 1) {
        $this->db->set('brmm_gt_id', NULL);
        $this->db->set('brmm_gm_id', NULL);
    // }
    $this->db->where('brmm_id', $transaction->gt_brmm_id);
    $this->db->update('barcode_readymade_master');

    $outward_trans = $this->db->query("
            SELECT 
                ot_om_id,
                ot_id
            FROM outward_trans 
            WHERE ot_brmm_id = " . $transaction->gt_brmm_id
        )->row();

    if($outward_trans && $total_trans > 1) {
        $this->db->set('ot_gt_qty', 'ot_gt_qty - ' . $transaction->gt_qty, FALSE);
        $this->db->where('ot_id', $outward_trans->ot_id);
        $this->db->update('outward_trans');

        $remaining = $this->db->query("
                SELECT 
                    SUM(gt_qty) as total_qty,
                    SUM(gt_rate) as total_amount
                FROM grn_trans 
                WHERE gt_gm_id = " . $master->gm_id
            )->row();

        $this->db->set('om_gm_total_qty',$remaining->total_qty , FALSE);
        $this->db->set('om_gm_final_amt',$remaining->total_amount , FALSE);
        $this->db->where('om_id', $outward_trans->ot_om_id);
        $this->db->update('outward_master');
    }    
    
    return ['status' => 'success', 'message' => 'GRN transaction deleted successfully'];
}

/**
 * Delete Outward Transaction
 */
private function delete_outward_transaction($entry_no, $item_code, $delete_qty)
{
    // Check if order exists for this item
    $order_check = "SELECT COUNT(*) as order_count 
                    FROM order_master om 
                    INNER JOIN order_trans ot ON ot.ot_om_id = om.om_id
                    INNER JOIN barcode_readymade_master brmm ON brmm.brmm_id = ot.ot_brmm_id
                    WHERE brmm.brmm_item_code = '" . $this->db->escape_str($item_code) . "'";
    
    $order_result = $this->db->query($order_check)->row();
    if($order_result->order_count > 0) {
        return ['status' => 'error', 'message' => 'Cannot delete: Order exists for this item'];
    }
    
    // Get Outward master ID first
    $master_query = "SELECT om_id FROM outward_master om LEFT JOIN outward_trans ot ON ot.ot_om_id = om.om_id LEFT JOIN barcode_readymade_master brmm ON brmm.brmm_id = ot.ot_brmm_id WHERE brmm.brmm_item_code = '" . $this->db->escape_str($item_code) . "'";

    $master = $this->db->query($master_query)->row();
    
    if(!$master) {
        return ['status' => 'error', 'message' => 'Outward master not found'];
    }
    
    // Get the specific transaction to delete
    $trans_query = "SELECT ot.*, brmm.brmm_gt_qty as current_qty 
                    FROM outward_trans ot
                    INNER JOIN barcode_readymade_master brmm ON brmm.brmm_id = ot.ot_brmm_id
                    WHERE ot.ot_om_id = " . $master->om_id . "
                    AND brmm.brmm_item_code = '" . $this->db->escape_str($item_code) . "'";
    
    $transaction = $this->db->query($trans_query)->row();
    
    if(!$transaction) {
        return ['status' => 'error', 'message' => 'Outward transaction not found'];
    }
    
    // Check total transactions count for this outward entry
    $count_query = "SELECT COUNT(*) as total FROM outward_trans WHERE ot_om_id = " . $master->om_id;
    $total_trans = $this->db->query($count_query)->row()->total;
    
    if($total_trans > 1) {
        // Delete only the specific barcode entry from transaction
        $this->db->where('ot_id', $transaction->ot_id);
        $this->db->delete('outward_trans');
        
        // Recalculate totals from remaining transactions
        $remaining = $this->db->query("
            SELECT 
                SUM(ot_qty) as total_qty,
                SUM(ot_rate) as total_amount
            FROM outward_trans 
            WHERE ot_om_id = " . $master->om_id
        )->row();
        
        // Update outward master with recalculated values
        $this->db->where('om_id', $master->om_id);
        $this->db->update('outward_master', [
            'om_total_qty' => $remaining->total_qty,
            'om_sub_total' => $remaining->total_amount,
            'om_final_amt' => $remaining->total_amount
            // 'om_gm_final_amt' => $remaining->total_amount
        ]);
        
        // Update barcode table - subtract quantity back and remove references
        // $this->db->set('brmm_gt_qty', 'brmm_gt_qty - ' . $transaction->ot_qty, FALSE);
        $this->db->set('brmm_outward_qty', 0);
        $this->db->set('brmm_outward_id', 0);
        $this->db->set('brmm_om_id', 0);
        $this->db->where('brmm_id', $transaction->ot_brmm_id);
        $this->db->update('barcode_readymade_master');
        
    } else {
        // Only 1 transaction exists - delete transaction and master
        // Delete transaction
        $this->db->where('ot_om_id', $master->om_id);
        $this->db->delete('outward_trans');
        
        // Delete master
        $this->db->where('om_id', $master->om_id);
        $this->db->delete('outward_master');
        
        // Update barcode table - add quantity back and remove references
        // $this->db->set('brmm_gt_qty', 'brmm_gt_qty - ' . $transaction->ot_qty, FALSE);
        $this->db->set('brmm_outward_qty', 0);
        $this->db->set('brmm_outward_id', 0);
        $this->db->set('brmm_om_id', 0);
        $this->db->where('brmm_id', $transaction->ot_brmm_id);
        $this->db->update('barcode_readymade_master');
    }
    
    return ['status' => 'success', 'message' => 'Outward transaction deleted successfully'];
}

/**
 * Delete Purchase Return Transaction
 */
private function delete_purchase_return_transaction($entry_no, $item_code, $delete_qty)
{
    // Check if order exists
    $order_check = "SELECT COUNT(*) as order_count 
                    FROM order_master om 
                    INNER JOIN order_trans ot ON ot.ot_om_id = om.om_id
                    INNER JOIN barcode_readymade_master brmm ON brmm.brmm_id = ot.ot_brmm_id
                    WHERE brmm.brmm_item_code = '" . $this->db->escape_str($item_code) . "'";
    
    $order_result = $this->db->query($order_check)->row();
    if($order_result->order_count > 0) {
        return ['status' => 'error', 'message' => 'Cannot delete: Order exists for this item'];
    }
    
    // Get Purchase Return master ID
    $master_query = "SELECT prrm_id FROM purchase_readymade_return_master prrm LEFT JOIN purchase_readymade_return_trans prrt ON prrt.prrt_prrm_id = prrm.prrm_id LEFT JOIN barcode_readymade_master brmm ON brmm.brmm_id = prrt.prrt_brmm_id WHERE brmm.brmm_item_code = '" . $this->db->escape_str($item_code) . "'";

    $master = $this->db->query($master_query)->row();
    
    if(!$master) {
        return ['status' => 'error', 'message' => 'Purchase Return master not found'];
    }
    
    // Get the specific transaction to delete
    $trans_query = "SELECT prrt.*, brmm.brmm_gt_qty as current_qty 
                    FROM purchase_readymade_return_trans prrt
                    INNER JOIN barcode_readymade_master brmm ON brmm.brmm_id = prrt.prrt_brmm_id
                    WHERE prrt.prrt_prrm_id = " . $master->prrm_id . "
                    AND brmm.brmm_item_code = '" . $this->db->escape_str($item_code) . "'";
    
    $transaction = $this->db->query($trans_query)->row();
    
    if(!$transaction) {
        return ['status' => 'error', 'message' => 'Purchase Return transaction not found'];
    }
    
    // Check total transactions count for this purchase return entry
    $count_query = "SELECT COUNT(*) as total FROM purchase_readymade_return_trans WHERE prrt_prrm_id = " . $master->prrm_id;
    $total_trans = $this->db->query($count_query)->row()->total;
    
    if($total_trans > 1) {
        // Delete only the specific barcode entry from transaction
        $this->db->where('prrt_id', $transaction->prrt_id);
        $this->db->delete('prrt_prrm_id');
        
        // Recalculate totals from remaining transactions
        $remaining = $this->db->query("
            SELECT 
                SUM(prrt_qty) as total_qty,
                SUM(prrt_amount) as total_amount
            FROM prrt_prrm_id 
            WHERE prrt_prrm_id = " . $master->prrm_id
        )->row();
        
        // Update purchase return master with recalculated values
        $this->db->where('prrm_id', $master->prrm_id);
        $this->db->update('purchase_returpurchase_readymade_return_mastern_master', [
            'prrm_total_qty' => $remaining->total_qty,
            'prrm_sub_total' => $remaining->total_amount,
            'prrm_final_amt' => $remaining->total_amount
        ]);
        
        // Update barcode table - decrease quantity (since purchase return added stock)
        $this->db->set('brmm_gt_qty', 'brmm_gt_qty - ' . $transaction->prrt_qty, FALSE);
        $this->db->where('brmm_id', $transaction->prrt_brmm_id);
        $this->db->update('barcode_readymade_master');
        
    } else {
        // Only 1 transaction exists - delete transaction and master
        // Delete transaction first
        $this->db->where('prrt_prrm_id', $master->prrm_id);
        $this->db->delete('prrt_prrm_id');
        
        // Delete master
        $this->db->where('prrm_id', $master->prrm_id);
        $this->db->delete('purchase_readymade_return_master');
        
        // Update barcode table - remove references and decrease quantity
        $this->db->set('brmm_gt_qty', 'brmm_gt_qty - ' . $transaction->prrt_qty, FALSE);
        $this->db->set('brmm_prrt_id', NULL);
        $this->db->set('brmm_prrm_id', NULL);
        $this->db->where('brmm_id', $transaction->prrt_brmm_id);
        $this->db->update('barcode_readymade_master');
    }
    
    return ['status' => 'success', 'message' => 'Purchase return transaction deleted successfully'];
}

/**
 * Delete purchase_readymade Transaction
 */
private function delete_purchase_readymade_transaction($entry_no, $item_code, $delete_qty)
{
    // Check if order exists
    $order_check = "SELECT COUNT(*) as order_count 
                    FROM order_master om 
                    INNER JOIN order_trans ot ON ot.ot_om_id = om.om_id
                    INNER JOIN barcode_readymade_master brmm ON brmm.brmm_id = ot.ot_brmm_id
                    WHERE brmm.brmm_item_code = '" . $this->db->escape_str($item_code) . "'";
    
    $order_result = $this->db->query($order_check)->row();
    if($order_result->order_count > 0) {
        return ['status' => 'error', 'message' => 'Cannot delete: Order exists for this item'];
    }
    
    // Get Purchase master ID
    $master_query = "SELECT prmm_id FROM purchase_readymade_master prmm LEFT JOIN purchase_readymade_trans prmt ON prmt.prmt_prmm_id = prmm.prmm_id LEFT JOIN barcode_readymade_master brmm ON brmm.brmm_prmt_id = prmt.prmt_id WHERE brmm.brmm_item_code = '" . $this->db->escape_str($item_code) . "'";

    $master = $this->db->query($master_query)->row();
    
    if(!$master) {
        return ['status' => 'error', 'message' => 'Purchase master not found'];
    }
    
    // Get the specific transaction to delete
    $trans_query = "SELECT prmt.*, brmm.brmm_id, brmm.brmm_prmt_qty as current_qty 
                    FROM purchase_readymade_trans prmt
                    INNER JOIN barcode_readymade_master brmm ON brmm.brmm_prmt_id = prmt.prmt_id
                    WHERE prmt.prmt_prmm_id = " . $master->prmm_id . "
                    AND brmm.brmm_item_code = '" . $this->db->escape_str($item_code) . "'";
    
    $transaction = $this->db->query($trans_query)->row();
    
    if(!$transaction) {
        return ['status' => 'error', 'message' => 'Purchase transaction not found'];
    }
    
    // Check total transactions
    $count_query = "SELECT COUNT(*) as total FROM purchase_readymade_trans WHERE prmt_prmm_id = " . $master->prmm_id;
    $total_trans = $this->db->query($count_query)->row()->total;
    
    if($total_trans > 1) {
        // Delete transaction
        $this->db->where('prmt_id', $transaction->prmt_id);
        $this->db->delete('purchase_readymade_trans');
        
        // Recalculate all totals from remaining transactions
        $remaining = $this->db->query("
            SELECT 
                SUM(prmt_qty) as total_qty,
                SUM(prmt_amt) as total_amt,
                SUM(prmt_taxable_amt) as total_taxable_amt,
                SUM(prmt_extra_amt) as total_extra_amt,
                SUM(prmt_actual_taxable_amt) as total_actual_taxable_amt,
                SUM(prmt_total_amt) as total_total_amt
            FROM purchase_readymade_trans 
            WHERE prmt_prmm_id = " . $master->prmm_id
        )->row();
        
        // Update master with recalculated values
        $this->db->where('prmm_id', $master->prmm_id);
        $this->db->update('purchase_readymade_master', [
            'prmm_total_qty' => $remaining->total_qty,
            'prmm_sub_amt' => $remaining->total_amt,
            'prmm_taxable_amt' => $remaining->total_taxable_amt,
            'prmm_extra_amt' => $remaining->total_extra_amt,
            'prmm_total_amt' => $remaining->total_total_amt,
            'prmm_round_off' => 0
        ]);
        
        // Update barcode - reduce quantity only, keep the link
        $new_qty = $transaction->current_qty - $transaction->prmt_qty;
        $this->db->set('brmm_prmt_qty', $new_qty);
        $this->db->where('brmm_id', $transaction->brmm_id);
        $this->db->update('barcode_readymade_master');
        
    } else {
        // Only 1 transaction exists - delete everything
        // First, clear the link in barcode table
        $this->db->set('brmm_prmt_id', NULL);
        $this->db->set('brmm_prmt_qty', $transaction->current_qty - $transaction->prmt_qty);
        $this->db->where('brmm_id', $transaction->brmm_id);
        $this->db->update('barcode_readymade_master');
        
        // Delete transaction
        $this->db->where('prmt_prmm_id', $master->prmm_id);
        $this->db->delete('purchase_readymade_trans');
        
        // Delete master
        $this->db->where('prmm_id', $master->prmm_id);
        $this->db->delete('purchase_readymade_master');
    }
    
    // Now check if barcode should be deleted
    $new_qty = $transaction->current_qty - $transaction->prmt_qty;
    if($new_qty <= 0) {
        $deleted = $this->check_and_delete_barcode($transaction->brmm_id);
        if($deleted) {
            return ['status' => 'success', 'message' => 'Purchase transaction deleted successfully and barcode removed'];
        }
    }
    
    return ['status' => 'success', 'message' => 'Purchase transaction deleted successfully'];
}

/**
 * Check if barcode entry is used anywhere else and delete if not
 */
private function check_and_delete_barcode($brmm_id)
{
    // Get the barcode record to check quantity
    $barcode_query = "SELECT 
                        brmm_gt_qty,
                        brmm_gt_id,
                        brmm_outward_id,
                        brmm_ot_id
                      FROM barcode_readymade_master 
                      WHERE brmm_id = " . $brmm_id;
    
    $barcode = $this->db->query($barcode_query)->row();
    
    if(!$barcode) {
        log_message('info', 'Barcode entry ' . $brmm_id . ' not found');
        return false;
    }
    
    // Check if barcode is linked to any transaction through its own reference columns
    $has_links = false;
    
    if($barcode->brmm_gt_id != NULL && $barcode->brmm_gt_id != 0) {
        log_message('info', 'Barcode ' . $brmm_id . ' has brmm_gt_id = ' . $barcode->brmm_gt_id);
        $has_links = true;
    }
    if($barcode->brmm_outward_id != NULL && $barcode->brmm_outward_id != 0) {
        log_message('info', 'Barcode ' . $brmm_id . ' has brmm_outward_id = ' . $barcode->brmm_outward_id);
        $has_links = true;
    }
    if($barcode->brmm_ot_id != NULL && $barcode->brmm_ot_id != 0) {
        log_message('info', 'Barcode ' . $brmm_id . ' has brmm_ot_id = ' . $barcode->brmm_ot_id);
        $has_links = true;
    }
    
    log_message('info', 'Barcode ' . $brmm_id . ' has_links = ' . ($has_links ? 'true' : 'false'));
    log_message('info', 'Barcode ' . $brmm_id . ' quantity = ' . $barcode->brmm_gt_qty);
    
    // Debug output (remove this after testing)
    // echo "has_links: " . ($has_links ? 'true' : 'false') . "<br>";
    // echo "quantity: " . $barcode->brmm_gt_qty . "<br>";
    
    // If no links and quantity is 0, delete the barcode entry
    if(!$has_links && $barcode->brmm_gt_qty <= 0) {
        $this->db->where('brmm_id', $brmm_id);
        $this->db->delete('barcode_readymade_master');
        
        // echo "Barcode deleted successfully!<br>";
        log_message('info', 'Barcode entry ' . $brmm_id . ' deleted as it has no references and quantity is 0');
        return true; // Deleted
    }
    
    // echo "Barcode NOT deleted!<br>";
    log_message('info', 'Barcode entry ' . $brmm_id . ' NOT deleted');
    return false; // Not deleted
}
}
?>
