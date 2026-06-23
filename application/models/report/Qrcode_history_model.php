<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class qrcode_history_model extends my_model{
    public function __construct(){ parent::__construct('report', 'qrcode_history'); }
	public function get_record(){
		$record['detail_data']	= $this->model->get_detail();
		$record['history_data']	= $this->model->get_history();
		return $record;
	}
    public function get_detail(){
		$_item_code = isset($_REQUEST['_item_code']) && !empty($_REQUEST['_item_code']) ? $_REQUEST['_item_code'] : 'XXX';
		$type = (isset($_REQUEST['type']) && ($_REQUEST['type']=='true'))?'checked':'';

		if(isset($_REQUEST['type']) && ($_REQUEST['type']=='true')){
			$query="SELECT brmm.brmm_id as brmm_id,
				brmm.brmm_item_code as _item_code,
				UPPER(supplier.supplier_name) as supplier_name,
				'' as sku_name,
                brmm.brmm_prmt_rate as brmm_prmt_rate,
				brmm.brmm_mrp as mrp, 
				 ((brmm.brmm_prmt_qty - brmm.brmm_prrt_qty) - (brmm.brmm_ot_qty + brmm.brmm_et_qty)) as bal_qty,
				IF(brmm.brmm_delete_status = 0, 'active', 'deleted') as delete_status,
				brmm.brmm_delete_status as brmm_delete_status
				FROM barcode_readymade_master brmm
				INNER JOIN sku_master sku ON(sku.sku_id = brmm.brmm_sku_id)
				INNER JOIN supplier_master supplier ON(supplier.supplier_id = sku.sku_supplier_id)
				WHERE brmm.brmm_item_code = '".$_item_code."'
				LIMIT 1";
		}else{
			$query="SELECT brmm.brmm_id,
				brmm.brmm_item_code as _item_code,
				UPPER(supplier.supplier_name) as supplier_name,
				UPPER(sku.sku_name) as sku_name,
				brmm.brmm_prmt_rate as brmm_prmt_rate,
				brmm.brmm_mrp as mrp,
				((brmm.brmm_prmt_qty-brmm.brmm_prrt_qty) - (brmm.brmm_ot_qty)) as bal_qty,
				IF(brmm.brmm_delete_status = 0, 'active', 'deleted') as delete_status,
				brmm.brmm_delete_status
				FROM barcode_readymade_master brmm
			    INNER JOIN sku_master sku ON(sku.sku_id = brmm.brmm_sku_id)
				INNER JOIN supplier_master supplier ON(supplier.supplier_id = sku.sku_supplier_id)
				WHERE brmm.brmm_item_code = '".$_item_code."'
				LIMIT 1";
		}
		$data = $this->db->query($query)->result_array();
		// echo "<pre>"; print_r($query); exit();
        // echo "<pre>"; print_r($data); exit();
		return $this->get_detail_html($data,$type);
    }
	public function get_detail_html($data,$type){
		$html = "<div>
					<h6 class='text-center text-light text-uppercase neu_flat_secondary py-1'>product detail</h6>
					<table class='table table-sm table-reponsive'>
						<tbody class='font-weight-bold text-uppercase' style='font-size:0.8em;'>
							<tr>
								<td width='32%'>barcode</td>
								<td width='68%'>
									<select 
										class='form-control floating-select select2' 
										id='_item_code' 
										name='_item_code'
										><option value='".(empty($data) ? '' : $data[0]['_item_code'])."'>".(empty($data) ? '' : $data[0]['_item_code'])."</option>
									</select>
								</td>
							</tr>
							<tr>
								<td width='32%'>supplier</td>
								<td width='68%'>: ".(empty($data) ? '---' : $data[0]['supplier_name'])."</td>
							</tr>
							<tr>
								<td width='32%'>sku</td>
								<td width='68%'>: ".(empty($data) ? '---' : $data[0]['sku_name'])."</td>
							</tr>
							<tr>
								<td width='32%'>purchase rate</td>
								<td width='68%'>: ".(empty($data) ? '---' : $data[0]['brmm_prmt_rate'])."</td>
							</tr>
							<tr>
								<td width='32%'>mrp</td>
								<td width='68%'>: ".(empty($data) ? '---' : $data[0]['mrp'])."</td>
								<!-- <td width='68%' class='d-flex'>: ";
									if(empty($data)):
										$html .="---";
									else:
										if($data[0]['brmm_delete_status'] == 0 && $data[0]['bal_qty'] > 0):
											$html .="<div class='d-flex'>
														<input
															type='number' 
															class='form-control text-dark font-weight-bold mx-1' 
															id='brmm_mrp' 
															value='{$data[0]['mrp']}'
															min='{$data[0]['mrp']}' 
															oninput='this.value = Math.abs(this.value)'
															placeholder=' 
															autocomplete='off'
															style='width: 80px; height: 25px; font-size:0.8rem; background: var(--bg-color-primary); border-color: var(--bg-color-secondary);'
														/>
														<a 
															type='button' 
															class='btn btn-sm'
															data-toggle='tooltip' 
															data-placement='top' 
															title='EDIT MRP'
															onclick='update_mrp({$data[0]['brmm_id']})'
														><i class='text-info fa fa-save'></i></a>  
													</div>";
										else:
											$html .=$data[0]['mrp'];
										endif;
									endif;
						$html .= "</td> -->
							</tr>
							<tr>
								<td width='32%'>balance qty</td>
								<td width='68%'>: ".(empty($data) ? '---' : $data[0]['bal_qty'])."</td>
							</tr>
							<tr>
								<td width='32%'>status</td>
								<td width='68%'>: ".(empty($data) ? '---' : $data[0]['delete_status'])."</td>
							</tr>
						<tbody/>
					</table>
				</div>";
		return $html;
	}
	public function get_history()
{
    $_item_code = isset($_REQUEST['_item_code']) && !empty($_REQUEST['_item_code'])
        ? $_REQUEST['_item_code']
        : 'XXX';

    $record = [];

    // GRN - First priority
    $query = "SELECT
            'GRN' as module,
            gm.gm_entry_no as entry_no,
            DATE_FORMAT(gm.gm_entry_date, '%d-%m-%Y') as entry_date,
            '' as mtr,
            SUM(gt.gt_qty) as qty,
            'STOCK INWARD' as party_name,
            UPPER(user.user_fullname) as user_name,
            UPPER(branch.branch_name) as branch_name,
            gm.gm_created_at as created_at,
            DATE_FORMAT(gm.gm_created_at, '%r') as entry_time,
            CONCAT('transfer/grn?action=list&_entry_no=', gm.gm_entry_no) as url,
            gm.gm_id as master_id
        FROM grn_master gm
        INNER JOIN grn_trans gt
            ON gt.gt_gm_id = gm.gm_id
        INNER JOIN barcode_readymade_master brmm
            ON brmm.brmm_id = gt.gt_brmm_id
        INNER JOIN user_master user
            ON user.user_id = gm.gm_created_by
        INNER JOIN branch_master branch
            ON branch.branch_id = gm.gm_branch_id
        WHERE brmm.brmm_item_code = '".$_item_code."'
        GROUP BY gm.gm_id";

    $data = $this->db->query($query)->result_array();

    if (!empty($data)) {
        foreach ($data as $value) {
            $record[] = $value;
        }
    }

    // OUTWARD - Second priority
    $query = "SELECT
            'OUTWARD' as module,
            om.om_entry_no as entry_no,
            DATE_FORMAT(om.om_entry_date, '%d-%m-%Y') as entry_date,
            '' as mtr,
            SUM(-1 * ot.ot_qty) as qty,
            'STOCK OUTWARD' as party_name,
            UPPER(user.user_fullname) as user_name,
            UPPER(branch.branch_name) as branch_name,
            om.om_created_at as created_at,
            DATE_FORMAT(om.om_created_at, '%r') as entry_time,
            CONCAT('transfer/outward?action=list&_entry_no=', om.om_entry_no) as url,
            om.om_id as master_id
        FROM outward_master om
        INNER JOIN outward_trans ot
            ON ot.ot_om_id = om.om_id
        INNER JOIN barcode_readymade_master brmm
            ON brmm.brmm_id = ot.ot_brmm_id
        INNER JOIN user_master user
            ON user.user_id = om.om_created_by
        INNER JOIN branch_master branch
            ON branch.branch_id = om.om_branch_id
        WHERE brmm.brmm_item_code = '".$_item_code."'
        GROUP BY om.om_id";

    $data = $this->db->query($query)->result_array();

    if (!empty($data)) {
        foreach ($data as $value) {
            $record[] = $value;
        }
    }
    
     // PURCHASE - Third priority
    $query = "SELECT
                'PURCHASE' as module,
                prmm.prmm_entry_no as entry_no,
                DATE_FORMAT(prmm.prmm_entry_date, '%d-%m-%Y') as entry_date,
                '' as mtr,
                SUM(prmt.prmt_qty) as qty,
                UPPER(supplier.supplier_name) as party_name,
                UPPER(user.user_fullname) as user_name,
                UPPER(branch.branch_name) as branch_name,
                prmm.prmm_created_at as created_at,
                DATE_FORMAT(prmm.prmm_created_at, '%r') as entry_time,
                CONCAT('transaction/purchase_readymade?action=list&_entry_no=', prmm.prmm_entry_no) as url,
                prmm.prmm_id as master_id
            FROM purchase_readymade_master prmm
            INNER JOIN supplier_master supplier
                ON supplier.supplier_id = prmm.prmm_supplier_id
            INNER JOIN user_master user
                ON user.user_id = prmm.prmm_created_by
            INNER JOIN branch_master branch
                ON branch.branch_id = prmm.prmm_branch_id
            INNER JOIN purchase_readymade_trans prmt
                ON prmt.prmt_prmm_id = prmm.prmm_id
            INNER JOIN barcode_readymade_master brmm
                ON brmm.brmm_prmt_id = prmt.prmt_id
            WHERE brmm.brmm_item_code = '".$_item_code."'
            GROUP BY prmm.prmm_id";

    $data = $this->db->query($query)->result_array();
// echo "<pre>"; print_r($data);
    if (!empty($data)) {
        foreach ($data as $value) {
            $record[] = $value;
        }
    }

    // PURCHASE RETURN - Fourth priority
    $query = "SELECT
            'PURCHASE RETURN' as module,
            prrm.prrm_entry_no as entry_no,
            DATE_FORMAT(prrm.prrm_entry_date, '%d-%m-%Y') as entry_date,
            '' as mtr,
            SUM(-1 * prrt.prrt_qty) as qty,
            UPPER(supplier.supplier_name) as party_name,
            UPPER(user.user_fullname) as user_name,
            UPPER(branch.branch_name) as branch_name,
            prrm.prrm_created_at as created_at,
            DATE_FORMAT(prrm.prrm_created_at, '%r') as entry_time,
            CONCAT('transaction/purchase_readymade_return?action=list&_entry_no=', prrm.prrm_entry_no) as url,
            prrm.prrm_id as master_id
        FROM purchase_readymade_return_master prrm
        INNER JOIN supplier_master supplier
            ON supplier.supplier_id = prrm.prrm_supplier_id
        INNER JOIN user_master user
            ON user.user_id = prrm.prrm_created_by
        INNER JOIN branch_master branch
            ON branch.branch_id = prrm.prrm_branch_id
        INNER JOIN purchase_readymade_return_trans prrt
            ON prrt.prrt_prrm_id = prrm.prrm_id
        INNER JOIN barcode_readymade_master brmm
            ON brmm.brmm_id = prrt.prrt_brmm_id
        WHERE prrt.prrt_delete_status = 0
            AND brmm.brmm_item_code = '".$_item_code."'
        GROUP BY prrm.prrm_id";

    $data = $this->db->query($query)->result_array();

    if (!empty($data)) {
        foreach ($data as $value) {
            $record[] = $value;
        }
    }

    // ESTIMATE
    $query = "SELECT
                'ESTIMATE' as module,
                om.om_em_entry_no as entry_no,
                DATE_FORMAT(om.om_em_entry_date, '%d-%m-%Y') as entry_date,
                SUM(-1 * ot.ot_mtr) as mtr,
                '' as qty,
                UPPER(customer.customer_name) as party_name,
                UPPER(user.user_fullname) as user_name,
                UPPER(branch.branch_name) as branch_name,
                om.om_created_at as created_at,
                DATE_FORMAT(om.om_created_at, '%r') as entry_time,
                CONCAT('transaction/estimate?action=list&_entry_no=', om.om_em_entry_no) as url,
                om.om_id as master_id
            FROM order_master om
            INNER JOIN customer_master customer
                ON customer.customer_id = om.om_customer_id
            INNER JOIN user_master user
                ON user.user_id = om.om_created_by
            INNER JOIN branch_master branch
                ON branch.branch_id = om.om_branch_id
            INNER JOIN order_trans ot
                ON ot.ot_om_id = om.om_id
            INNER JOIN barcode_readymade_master brmm
                ON brmm.brmm_id = ot.ot_brmm_id
            WHERE om.om_delete_status = 0
                AND ot.ot_delete_status = 0
                AND brmm.brmm_delete_status = 0
                AND om.om_status = 0
                AND brmm.brmm_item_code = '".$_item_code."'
            GROUP BY om.om_id";

    $data = $this->db->query($query)->result_array();

    if (!empty($data)) {
        foreach ($data as $value) {
            $record[] = $value;
        }
    }

    // ORDER
    $query = "SELECT
                'ORDER' as module,
                om.om_entry_no as entry_no,
                DATE_FORMAT(om.om_entry_date, '%d-%m-%Y') as entry_date,
                SUM(-1 * ot.ot_mtr) as mtr,
                '' as qty,
                UPPER(customer.customer_name) as party_name,
                UPPER(user.user_fullname) as user_name,
                UPPER(branch.branch_name) as branch_name,
                om.om_created_at as created_at,
                DATE_FORMAT(om.om_created_at, '%r') as entry_time,
                CONCAT('transaction/order?action=list&_entry_no=', om.om_entry_no) as url,
                om.om_id as master_id
            FROM order_master om
            INNER JOIN customer_master customer
                ON customer.customer_id = om.om_customer_id
            INNER JOIN user_master user
                ON user.user_id = om.om_created_by
            INNER JOIN branch_master branch
                ON branch.branch_id = om.om_branch_id
            INNER JOIN order_trans ot
                ON ot.ot_om_id = om.om_id
            INNER JOIN barcode_readymade_master brmm
                ON brmm.brmm_id = ot.ot_brmm_id
            WHERE om.om_delete_status = 0
                AND ot.ot_delete_status = 0
                AND brmm.brmm_delete_status = 0
                AND om.om_status = 1
                AND brmm.brmm_item_code = '".$_item_code."'
            GROUP BY om.om_id";

    $data = $this->db->query($query)->result_array();

    if (!empty($data)) {
        foreach ($data as $value) {
            $record[] = $value;
        }
    }

    usort($record, function ($a, $b) {
        return strtotime($a['created_at']) - strtotime($b['created_at']);
    });

    return $this->get_history_html($record);
}
	public function get_history_html($data){
    $html = "<div>
                <h6 class='text-center text-light text-uppercase neu_flat_secondary py-1'>history detail</h6>
                <div class='table-responsive'>
                    <table class='table table-sm font-weight-bold text-uppercase'>
                        <thead>
                            <tr style='font-size:0.8em;'>
                                <th width='2%'>#</th>
                                <th width='8%'>action</th>
                                <th width='6%'>entry no</th>
                                <th width='7%'>entry date</th>
                                <th width='7%'>entry time</th>
                                <th width='8%'>entry by</th>
                                <th width='15%'>party</th>
                                <th width='12%'>branch</th>
                                <th width='8%'>opening qty</th>
                                <th width='5%'>qty</th>
                                <th width='8%'>closing qty</th>
                                <th width='5%'>delete</th>
                            </tr>
                        </thead>
                        <tbody>";
    
    if(empty($data)):
        $html .= "<tr><td colspan='12' class='text-center text-danger'>no record found !!!</td></tr>";
    else:
        $open_mtr = 0;
        $close_mtr = 0;
        $open_qty = 0;
        $close_qty = 0;
        $prev_branch = '';
        
        // Get existing orders to check if order exists
        $_item_code = isset($_REQUEST['_item_code']) && !empty($_REQUEST['_item_code']) 
            ? $_REQUEST['_item_code'] 
            : 'XXX';
        
        $order_exists = $this->check_order_exists($_item_code);
        
        // Define module sequence
        $module_sequence = ['GRN', 'OUTWARD', 'PURCHASE RETURN', 'PURCHASE'];
        
        // Check which modules exist in the data
        $existing_modules = [];
        foreach ($data as $item) {
            if (!in_array($item['module'], $existing_modules)) {
                $existing_modules[] = $item['module'];
            }
        }
        
        // Determine which delete buttons to show based on sequence
        $can_show_delete_for = [];
        
        if (!$order_exists) {
            // Check GRN exists
            $grn_exists = in_array('GRN', $existing_modules);
            $outward_exists = in_array('OUTWARD', $existing_modules);
            $purchase_return_exists = in_array('PURCHASE RETURN', $existing_modules);
            $purchase_exists = in_array('PURCHASE', $existing_modules);
            
            if ($grn_exists) {
                // Only show delete for GRN
                $can_show_delete_for['GRN'] = true;
                $can_show_delete_for['OUTWARD'] = false;
                $can_show_delete_for['PURCHASE RETURN'] = false;
                $can_show_delete_for['PURCHASE'] = false;
            } else if ($outward_exists) {
                // GRN is deleted, show delete for OUTWARD
                $can_show_delete_for['GRN'] = false;
                $can_show_delete_for['OUTWARD'] = true;
                $can_show_delete_for['PURCHASE RETURN'] = false;
                $can_show_delete_for['PURCHASE'] = false;
            } else if ($purchase_return_exists) {
                // GRN and OUTWARD are deleted, show delete for PURCHASE RETURN
                $can_show_delete_for['GRN'] = false;
                $can_show_delete_for['OUTWARD'] = false;
                $can_show_delete_for['PURCHASE RETURN'] = true;
                $can_show_delete_for['PURCHASE'] = false;
            } else if ($purchase_exists) {
                // GRN, OUTWARD, PURCHASE RETURN are deleted, show delete for PURCHASE
                $can_show_delete_for['GRN'] = false;
                $can_show_delete_for['OUTWARD'] = false;
                $can_show_delete_for['PURCHASE RETURN'] = false;
                $can_show_delete_for['PURCHASE'] = true;
            }
        }
        
        foreach ($data as $key => $value):
            
            if($prev_branch != '' && $prev_branch != $value['branch_name']):
                $html .= "<tr><td colspan='12' style='border-top:3px solid #000;'></td></tr>";
            endif;
            
            $prev_branch = $value['branch_name'];
            $sr_no = $key+1;
            $close_qty = $close_qty + floatval($value['qty']);
            
            // Check if delete button should be shown for this module
            $show_delete = isset($can_show_delete_for[$value['module']]) ? $can_show_delete_for[$value['module']] : false;
            
            $html .= "<tr style='font-size:0.7em;'>
                        <td>{$sr_no}</td>
                        <td>{$value['module']}</td>
                        <td>
                            <a 
                                type='button' 
                                class='btn btn-sm font-weight-bold' 
                                target='_blank' 
                                data-toggle='tooltip' 
                                data-placement='top' 
                                title='SHOW ENTRY'
                                href='".base_url($value['url'])."'
                                style='font-size:0.8rem;'
                            >{$value['entry_no']} <i class='text-info fa fa-external-link'></i></a>    
                        </td>
                        <td>{$value['entry_date']}</td>
                        <td>{$value['entry_time']}</td>
                        <td>{$value['user_name']}</td>
                        <td>{$value['party_name']}</td>
                        <td>{$value['branch_name']}</td>
                        <td>".number_format($open_qty, 2, '.', '')."</td>
                        <td>{$value['qty']}</td>
                        <td>".number_format($close_qty, 2, '.', '')."</td>";
            
            if($show_delete):
                $html .= "<td class='text-center'>
                            <button 
                                type='button' 
                                class='btn btn-danger btn-sm delete-history-btn'
                                data-module='{$value['module']}'
                                data-entry-no='{$value['entry_no']}'
                                data-item-code='{$_item_code}'
                                data-qty='{$value['qty']}'
                                onclick='deleteHistoryEntry(this)'
                            >
                                <i class='fa fa-trash'></i> Delete
                            </button>
                           </td>";
            else:
                $html .= "<td class='text-center'>--</td>";
            endif;
            
            $html .= "</tr>";
            
            $open_qty = $close_qty;
        endforeach;
    endif;
    
    $html .= "  </tbody>
                </table>
                </div>
            </div>";
    
    // Add JavaScript for delete functionality
    $html .= "
    <script>
    function deleteHistoryEntry(button) {
        if(confirm('Are you sure you want to delete this transaction? This action cannot be undone.')) {
            var module = button.getAttribute('data-module');
            var entryNo = button.getAttribute('data-entry-no');
            var itemCode = button.getAttribute('data-item-code');
            var qty = button.getAttribute('data-qty');
            
            // Disable button to prevent double submission
            button.disabled = true;
            button.innerHTML = '<i class=\"fa fa-spinner fa-spin\"></i> Deleting...';
            
            $.ajax({
                url: '" . base_url('report/qrcode_history/delete_transaction') . "',
                type: 'POST',
                data: {
                    module: module,
                    entry_no: entryNo,
                    item_code: itemCode,
                    qty: qty
                },
                dataType: 'json',
                success: function(response) {
                    if(response.status == 'success') {
                        toastr.success(response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        toastr.error(response.message);
                        button.disabled = false;
                        button.innerHTML = '<i class=\"fa fa-trash\"></i> Delete';
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('An error occurred while deleting the transaction: ' + error);
                    button.disabled = false;
                    button.innerHTML = '<i class=\"fa fa-trash\"></i> Delete';
                }
            });
        }
    }
    </script>";
    
    return $html;
}
// Check if order exists for the item
private function check_order_exists($item_code) {
    $query = "SELECT COUNT(*) as count 
              FROM order_trans ot
              INNER JOIN order_master om ON om.om_id = ot.ot_om_id
              INNER JOIN barcode_readymade_master brmm ON brmm.brmm_id = ot.ot_brmm_id
              WHERE om.om_delete_status = 0 
                AND ot.ot_delete_status = 0 
                -- AND om.om_status = 1
                AND brmm.brmm_item_code = '{$item_code}'";
    
    $result = $this->db->query($query)->row();
    return ($result->count > 0);
}

     public function _item_code(){
		$subsql = "";
		$limit  = PER_PAGE;
		$offset = OFFSET;
		$page 	= 1;
		if(isset($_GET['limit']) && !empty($_GET['limit'])){
			$limit = $_GET['limit'];
		}
		if(isset($_GET['page']) && !empty($_GET['page'])){
			$page 	= $_GET['page'];
			$offset = $limit * ($page - 1);
		}
      	
	    if(isset($_GET['param']) && $_GET['param']=='true') { 
		
			if((isset($_GET['name']) && !empty($_GET['name']))){   
				$name 	= $_GET['name'];
	            $subsql .= " AND (brmm.brmm_item_code = '".$name."') ";
			}else{
				$subsql .= " AND (brmm.brmm_item_code = 'XXX') ";
			}
			$query="SELECT brmm.brmm_item_code as id, brmm.brmm_item_code as name
					FROM barcode_readymade_master brmm
					WHERE 1
					$subsql
					GROUP BY brmm.brmm_item_code 
					ORDER BY brmm.brmm_item_code ASC
					LIMIT 1";
			// echo "<pre>"; print_r($query); exit;
			return $this->db->query($query)->result_array();
		}else{
           
			if((isset($_GET['name']) && !empty($_GET['name']))){
				$name 	= $_GET['name'];
	            $subsql .= " AND (brmm.brmm_item_code = '".$name."') ";
			}else{
				$subsql .= " AND (brmm.brmm_item_code = 'XXX') ";
			}
			$query="SELECT brmm.brmm_item_code as id, brmm.brmm_item_code as name
					FROM barcode_readymade_master brmm
					WHERE 1
					$subsql
					GROUP BY brmm.brmm_item_code 
					ORDER BY brmm.brmm_item_code ASC
					LIMIT 1";
			// echo "<pre>"; print_r($query); exit;
			return $this->db->query($query)->result_array();
		}	
    }
}
?>