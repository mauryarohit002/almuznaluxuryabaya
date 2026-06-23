<?php $this->load->view('templates/header'); ?>
<script>
    let link = "grn_pending";
    let sub_link = "grn_pending";
</script>
<nav class="sticky_top" aria-label="breadcrumb">
  <ol class="breadcrumb custom_breadcrumb neu_flat_primary">
    <li class="breadcrumb-item"><a href="<?php echo base_url('transfer/grn?action=view'); ?>">GRN</a></li>
    <li class="breadcrumb-item"><a href="<?php echo base_url('transfer/grn/pending?action=view'); ?>">PENDING</a></li>
    <li class="breadcrumb-item active" aria-current="page"><?php echo empty($master_data) ? 'ADD' : 'EDIT'; ?></li>
    <li class="breadcrumb-item" aria-current="save-page">
    	<button type="button" class="btn btn-sm btn-primary master_block_btn" onclick="add_update_grn(<?php echo empty($master_data) ? 0 : $master_data[0]['om_id']; ?>)" data-toggle="tooltip" data-placement="bottom" title="SAVE" tabindex="4" <?php echo empty($master_data) ? 'disabled="disabled"' : '' ?>><i class="text-success fa fa-save"></i></button>
    </li>
    <li class="breadcrumb-item" aria-current="cancel-page">
    	<a type="button" class="btn btn-sm btn-primary master_block_btn" href="<?php echo base_url('transfer/grn/pending?action=view')?>" data-toggle="tooltip" data-placement="bottom" title="CANCEL" tabindex="5"><i class="text-danger fa fa-close"></i></a>
    </li>
  </ol>
</nav>
<div class="alert_msg"></div>
<section class="container-fluid my-3">
	<form class="form-horizontal" id="grn_form" onsubmit="add_update_grn(<?php echo empty($master_data) ? 0 : $master_data[0]['om_id']; ?>)">
		<div class="row">
			<div class="col-sm-12 col-md-6">
				<div class="card mb-3">
					<div class="card-header">GRN DETAIL</div>
					<div class="card-body">
						<div class="d-flex flex-wrap mt-2 form-group floating-form">
                            <div class="col-sm-12 col-md-4 col-lg-3 floating-label">
                                <input type="number" class="form-control floating-input" id="gm_entry_no" name="gm_entry_no" value="<?php echo empty($master_data) ? $gm_entry_no : $master_data[0]['gm_entry_no'] ?>" placeholder=" " readonly="readonly" />   
                                <label for="inputEmail3">ENTRY NO</label>
                                <small class="form-text text-muted helper-text" id="gm_entry_no_msg"></small>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-3 floating-label">
                                <input type="text" class="form-control floating-input" id="gm_entry_date" name="gm_entry_date" value="<?php echo empty($master_data) ? date('d-m-Y') : date('d-m-Y', strtotime($master_data[0]['gm_entry_date'])) ?>" placeholder=" " readonly="readonly" />   
                                <label for="inputEmail3">ENTRY DATE</label>
                                <small class="form-text text-muted helper-text" id="gm_entry_date_msg"></small>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6 floating-label">
                                <textarea class="form-control floating-textarea" id="gm_notes" name="gm_notes" placeholder=" " tabindex="3" autocomplete="off"><?php echo empty($master_data) ? '' : $master_data[0]['gm_notes']; ?></textarea>
                                <label for="inputEmail3">NOTES</label>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-3 floating-label">
                                <input type="number" class="form-control floating-input text-danger" id="gm_total_qty" name="gm_total_qty" value="<?php echo empty($master_data) ? 0 : $master_data[0]['gm_total_qty'] ?>" placeholder=" " readonly="readonly" />   
                                <label for="inputEmail3">TOTAL QTY</label>
                                <small class="form-text text-muted helper-text" id="gm_total_qty_msg"></small>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-3 floating-label">
                                <input type="number" class="form-control floating-input text-danger" id="gm_sub_total" name="gm_sub_total" value="<?php echo empty($master_data) ? 0 : $master_data[0]['gm_sub_total'] ?>" placeholder=" " readonly="readonly" />   
                                <label for="inputEmail3">SUB TOTAL AMT.</label>
                                <small class="form-text text-muted helper-text" id="gm_sub_total_msg"></small>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-3 floating-label">
                                <input type="number" class="form-control floating-input" id="gm_round_off" name="gm_round_off" value="<?php echo empty($master_data) ? 0 : $master_data[0]['gm_round_off'] ?>" placeholder=" " readonly="readonly" />   
                                <label for="inputEmail3">ROUND OFF AMT.</label>
                                <small class="form-text text-muted helper-text" id="gm_round_off_msg"></small>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-3 floating-label">
                                <input type="number" class="form-control floating-input text-danger" id="gm_final_amt" name="gm_final_amt" value="<?php echo empty($master_data) ? 0 : $master_data[0]['gm_final_amt'] ?>" placeholder=" " readonly="readonly" style="font-size: 20px; font-weight: bold;" />   
                                <label for="inputEmail3">TOTAL AMT.</label>
                                <small class="form-text text-muted helper-text" id="gm_final_amt_msg"></small>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6 floating-label">
                                <p for="inputEmail3">BARCODE</p>
                                <select class="form-control floating-select" id="brmm_id" placeholder="" tabindex="2">                                                
                                </select>
                                <small class="form-text text-muted helper-text" id="brmm_id_msg"></small>
                            </div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-12 col-md-6">
				<div class="card mb-3">
					<div class="card-header">OUTWARD DETAIL</div>
					<div class="card-body">
						<div class="d-flex flex-wrap mt-2 form-group floating-form">
                            <div class="col-sm-12 col-md-4 col-lg-3 floating-label">
                                <input type="number" class="form-control floating-input" id="om_entry_no" value="<?php echo empty($outward_master) ? '' : $outward_master[0]['om_entry_no'] ?>" placeholder=" " readonly="readonly" />   
                                <label for="inputEmail3">OUTWARD NO</label>
                                <small class="form-text text-muted helper-text" id="om_entry_no_msg"></small>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-3 floating-label">
                                <input type="text" class="form-control floating-input" id="om_entry_date" value="<?php echo empty($outward_master) ? '' : date('d-m-Y', strtotime($outward_master[0]['om_entry_date'])) ?>" placeholder=" " readonly="readonly" />   
                                <label for="inputEmail3">OUTWARD DATE</label>
                                <small class="form-text text-muted helper-text" id="om_entry_date_msg"></small>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-6 floating-label">
                                <input type="text" class="form-control floating-input" id="branch_name" value="<?php echo empty($outward_master) ? 0 : $outward_master[0]['branch_name'] ?>" placeholder=" " readonly="readonly" />   
                                <input type="hidden" id="gm_om_id" name="gm_om_id" value="<?php echo empty($outward_master) ? 0 : $outward_master[0]['om_id'] ?>" />   
                                <input type="hidden" id="gm_branch" name="gm_branch" value="<?php echo empty($outward_master) ? 0 : $outward_master[0]['om_branch_id'] ?>" />   
                                <label for="inputEmail3">OUTWARD FROM BRANCH</label>
                                <small class="form-text text-muted helper-text" id="gm_branch_msg"></small>
                            </div>
							<div class="col-sm-12 col-md-4 col-lg-3 floating-label">
								<input type="number" class="form-control floating-input" id="om_total_qty" value="<?php echo empty($outward_master) ? 0 : $outward_master[0]['om_total_qty'] ?>" placeholder=" " readonly="readonly" />   
                                <label for="inputEmail3">TOTAL QTY</label>
                                <small class="form-text text-muted helper-text" id="om_total_qty_msg"></small>
							</div>
							<div class="col-sm-12 col-md-4 col-lg-3 floating-label">
								<input type="number" class="form-control floating-input" id="om_sub_total" value="<?php echo empty($outward_master) ? 0 : $outward_master[0]['om_sub_total'] ?>" placeholder=" " readonly="readonly" />   
                                <label for="inputEmail3">SUB TOTAL AMT.</label>
                                <small class="form-text text-muted helper-text" id="om_sub_total_msg"></small>
							</div>
							<div class="col-sm-12 col-md-4 col-lg-3 floating-label">
								<input type="number" class="form-control floating-input" id="om_round_off" value="<?php echo empty($outward_master) ? 0 : $outward_master[0]['om_round_off'] ?>" placeholder=" " readonly="readonly" />   
                                <label for="inputEmail3">ROUND OFF AMT.</label>
                                <small class="form-text text-muted helper-text" id="om_round_off_msg"></small>
							</div>
							<div class="col-sm-12 col-md-4 col-lg-3 floating-label">
								<input type="number" class="form-control floating-input" id="om_final_amt" value="<?php echo empty($outward_master) ? 0 : $outward_master[0]['om_final_amt'] ?>" placeholder=" " readonly="readonly" style="font-size: 20px; font-weight: bold;" />   
                                <label for="inputEmail3">TOTAL AMT.</label>
                                <small class="form-text text-muted helper-text" id="om_final_amt_msg"></small>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-12">
				<div class="card mb-3">
					<div class="card-header d-flex">
                        <span class="mx-2">PRODUCT DETAIL</span>
                        <div class="mx-2">
                            <input type="checkbox" id="show" data-toggle="toggle" data-on="SHOW RECEIVED" data-off="SHOW PENDING" data-onstyle="success" data-offstyle="danger" data-size="xs" style="width: 100px;" onchange="show_product()">
                        </div>               
                    </div>
					<div class="card-body mt-2">
						<table class="table table-hover table-sm table-responsive">
                            <thead>
    							<tr style="font-size: 15px;">
                                    <th width="10%">BARCODE</th>
                                    <!-- <th width="8%">BILL NO</th>
                                    <th width="8%">BILL DATE</th> -->
                                    <th width="10%">SKU</th>
                                    <th width="10%">APPAREL</th>
                                    <th width="6%">QUANTITY</th>
                                    <th width="6%">RATE</th>
                                    <th width="8%">TOTAL</th>
                                    <th width="2%">RECEIVED</th>
    	                        </tr>
                            </thead>
                            <tbody id="outward_material_wrapper">
    	                        <?php
                                    $outward_cnt = 1;
                                    if(!empty($trans_data)):
                                        foreach ($trans_data as $key => $value):
                                            
                                ?>
                                        <tr id="rowid_<?php echo $outward_cnt; ?>" class="floating-form" >
                                            <td>
                                                <input type="number" class="form-control floating-input" id="brmm_item_code_<?php echo $outward_cnt ?>" value="<?php echo $value['brmm_item_code'] ?>" readonly />
                                                <input type="hidden" name="gt_brmm_id[]" id="gt_brmm_id_<?php echo $outward_cnt ?>" value="<?php echo $value['ot_brmm_id']; ?>" />
                                                <input type="hidden" name="gt_id[]" id="gt_id_<?php echo $outward_cnt ?>" value="0" />
                                            </td>          

                                            <!-- <td> -->
                                                <input type="hidden" class="form-control floating-input" name="gt_bill_no[]" id="gt_bill_no_<?php echo $outward_cnt ?>" value="<?php echo $value['ot_bill_no'] ?>" readonly />
                                                <input type="hidden" name="gt_prmm_id[]" id="gt_prmm_id_<?php echo $outward_cnt ?>" value="<?php echo $value['ot_prmm_id'] ?>" />
                                            <!-- </td> -->

                                            <!-- <td> -->
                                                <input type="hidden" class="form-control floating-input" name="gt_bill_date[]" id="gt_bill_date_<?php echo $outward_cnt ?>" value="<?php echo date('d-m-Y', strtotime($value['ot_bill_date'])) ?>" readonly />
                                            <!-- </td> -->

                                            <td>
                                                <input type="text" class="form-control floating-input" id="sku_name_<?php echo $outward_cnt ?>" value="<?php echo $value['sku_name'] ?>" readonly />
                                                <input type="hidden" name="gt_sku_id[]" id="gt_sku_id_<?php echo $outward_cnt ?>" value="<?php echo $value['ot_sku_id'] ?>" />
                                            </td>

                                            <td>
                                                <input type="text" class="form-control floating-input" id="apparel_name_<?php echo $outward_cnt ?>" value="<?php echo $value['apparel_name'] ?>" readonly />
                                                <input type="hidden" name="gt_apparel_id[]" id="gt_apparel_id_<?php echo $outward_cnt ?>" value="<?php echo $value['ot_apparel_id'] ?>" />
                                            </td>
                                            <td>
                                                <input type="number" class="form-control floating-input" name="gt_qty[]" id="gt_qty_<?php echo $outward_cnt ?>" value="<?php echo $value['ot_qty'] ?>" readonly />
                                            </td>

                                            <td>
                                                <input type="number" class="form-control floating-input" name="gt_rate[]" id="gt_rate_<?php echo $outward_cnt ?>" value="<?php echo $value['ot_rate'] ?>" readonly />
                                            </td>

                                            <td>
                                                <input type="number" class="form-control floating-input" name="gt_sub_total[]" id="gt_sub_total_<?php echo $outward_cnt ?>" value="<?php echo $value['ot_sub_total'] ?>" readonly />
                                            </td>

                                            <td>
                                                <input type="checkbox" id="received_status_<?php echo $outward_cnt ?>" data-toggle="toggle" data-on="YES" data-off="NO" data-onstyle="success" data-offstyle="danger" data-width="50" data-size="sm" onchange="set_gt_status(<?php echo $outward_cnt; ?>)">
                                                <input type="hidden" id="gt_status_<?php echo$outward_cnt ?>" name="gt_status[]" value="<?php echo !isset($value['gt_status']) ? 0 : $value['gt_status']  ?>">
                                            </td>
                                        </tr>
                                    <?php 
                                        $outward_cnt++;
                                        endforeach; 
                                    endif;
                                ?>
                            </tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</form>
</section>
<?php $this->load->view('templates/footer'); ?>
<script src="<?php echo assets('dist/js/grn.js')?>"></script>
<?php 
    if(!empty($master_data))
    {
        echo "<script>";
        echo "outward_cnt = $outward_cnt;";
        echo "</script>";
    }
?>
</body>
</html>