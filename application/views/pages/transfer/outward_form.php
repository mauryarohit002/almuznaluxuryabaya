<?php $this->load->view('templates/header'); ?>
<script>
    let link = "outward";
    let sub_link = "outward";
</script>
<section class="d-flex justify-content-between sticky_top">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url('transfer/outward?action=list'); ?>">OUTWARD</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?php echo empty($master_data) ? 'ADD' : 'EDIT'; ?></li>
        <li class="breadcrumb-item" aria-current="save-page">
            <button type="button" class="btn btn-sm btn-primary master_block_btn" onclick="add_update_outward(<?php echo empty($master_data) ? 0 : $master_data[0]['om_id']; ?>)" data-toggle="tooltip" data-placement="bottom" title="SAVE" tabindex="4" <?php echo empty($master_data) ? 'disabled="disabled"' : '' ?>><i class="text-success fa fa-save"></i></button>
        </li>
        <li class="breadcrumb-item" aria-current="cancel-page">
            <a type="button" class="btn btn-sm btn-primary master_block_btn" href="<?php echo base_url('transfer/outward?action=list')?>" data-toggle="tooltip" data-placement="bottom" title="CANCEL" tabindex="5"><i class="text-danger fa fa-close"></i></a>
        </li>
      </ol>
    </nav>
</section>
<section class="container-fluid my-3">
	<form class="form-horizontal" id="outward_form">
		<div class="row">

            <div id="barcodeLoader" style="
                display:none;
                position:fixed;
                top:0; left:0;
                width:100%; height:100%;
                background:rgba(0,0,0,0.6);
                z-index:9999;
                color:#fff;
                font-size:20px;
                text-align:center;
                padding-top:20%;
            ">
                Processing barcodes... ⏳
            </div>

			<div class="col-sm-12 col-md-6">
				<div class="card mb-3">
					<div class="card-header">OUTWARD DETAIL</div>
					<div class="card-body">
						<div class="d-flex flex-wrap mt-2 form-group floating-form">
                            <div class="col-sm-12 col-md-4 col-lg-3 floating-label">
                                <input type="number" class="form-control floating-input" id="om_entry_no" name="om_entry_no" value="<?php echo empty($master_data) ? $om_entry_no : $master_data[0]['om_entry_no'] ?>" placeholder=" " readonly="readonly" />   
                                <label for="inputEmail3">ENTRY NO</label>
                                <small class="form-text text-muted helper-text" id="om_entry_no_msg"></small>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-3 floating-label">
                                <input type="text" class="form-control floating-input" id="om_entry_date" name="om_entry_date" value="<?php echo empty($master_data) ? date('d-m-Y') : date('d-m-Y', strtotime($master_data[0]['om_entry_date'])) ?>" placeholder=" " readonly="readonly" />   
                                <label for="inputEmail3">ENTRY DATE</label>
                                <small class="form-text text-muted helper-text" id="om_entry_date_msg"></small>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-6 floating-label">
                                <?php if(!empty($master_data) && $trans_data[0]['isExist']): ?>
                                    <p for="inputEmail3"> OUTWARD TO BRANCH&nbsp;<span class="text-danger">*</span></p>
                                    <input type="text" class="form-control floating-input" value="<?php echo $master_data[0]['branch_name'] ?>" readonly="readonly" />
                                    <input type="hidden" name="om_branch" id="om_branch" value="<?php echo $master_data[0]['om_branch']; ?>" />
                                <?php else: ?>
                                    <p for="inputEmail3">
                                        OUTWARD TO BRANCH&nbsp;<span class="text-danger">*</span>
                                        <span>
                                            <a onclick="branch_popup(0, 'om_branch');"
                                                data-toggle="tooltip"  title="ADD BRANCH" 
                                                data-placement="top" >
                                                <i class="text-success fa fa-plus"></i>
                                            </a>
                                        </span>
                                </p>                                                
                                <?php echo form_dropdown('', $branches, empty($master_data) ? 0 : $master_data[0]['om_branch'],'class="form-control floating-select" id="om_branch" name="om_branch" onchange="validate_dropdown(this)" tabindex="1"');?>
                                <?php endif; ?>
                                <small class="form-text text-muted helper-text" id="om_branch_msg"></small>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6 floating-label">
                                <p for="inputEmail3">BARCODE</p>
                                <select class="form-control floating-select" id="brmm_id" placeholder="" tabindex="2">                                                
                                </select>
                                <input type="hidden" id="om_id" value="<?php echo empty($master_data) ? 0 : $master_data[0]['om_id'] ?>">
                                <small class="form-text text-muted helper-text" id="brmm_id_msg"></small>
                            </div>

                            <!--<div class="col-sm-12 col-md-6 col-lg-6 mt-2">-->
                            <!--    <button type="button" class="btn btn-success" onclick="open_barcode_modal()">-->
                            <!--        Scan All Barcodes-->
                            <!--    </button>-->
                            <!--</div>-->

                            <div class="col-sm-12 col-md-6 col-lg-6 floating-label">
                                <textarea class="form-control floating-textarea" id="om_notes" name="om_notes" placeholder=" " tabindex="3" autocomplete="off"><?php echo empty($master_data) ? '' : $master_data[0]['om_notes']; ?></textarea>
                                <label for="inputEmail3">NOTES</label>
                            </div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-12 col-md-6">
				<div class="card mb-3">
					<div class="card-header">AMOUNT DETAIL</div>
					<div class="card-body">
						<div class="d-flex flex-wrap mt-2 form-group floating-form">
							<div class="col-sm-12 col-md-4 col-lg-3 floating-label">
								<input type="number" class="form-control floating-input" id="om_total_qty" name="om_total_qty" value="<?php echo empty($master_data) ? 0 : $master_data[0]['om_total_qty'] ?>" placeholder=" " readonly="readonly" />   
                                <label for="inputEmail3">TOTAL QTY</label>
                                <small class="form-text text-muted helper-text" id="om_total_qty_msg"></small>
							</div>
							<div class="col-sm-12 col-md-4 col-lg-3 floating-label">
								<input type="number" class="form-control floating-input" id="om_sub_total" name="om_sub_total" value="<?php echo empty($master_data) ? 0 : $master_data[0]['om_sub_total'] ?>" placeholder=" " readonly="readonly" />   
                                <label for="inputEmail3">SUB TOTAL AMT.</label>
                                <small class="form-text text-muted helper-text" id="om_sub_total_msg"></small>
							</div>
							<div class="col-sm-12 col-md-4 col-lg-3 floating-label">
								<input type="number" class="form-control floating-input" id="om_round_off" name="om_round_off" value="<?php echo empty($master_data) ? 0 : $master_data[0]['om_round_off'] ?>" placeholder=" " readonly="readonly" />   
                                <label for="inputEmail3">ROUND OFF AMT.</label>
                                <small class="form-text text-muted helper-text" id="om_round_off_msg"></small>
							</div>
							<div class="col-sm-12 col-md-4 col-lg-3 floating-label">
								<input type="number" class="form-control floating-input" id="om_final_amt" name="om_final_amt" value="<?php echo empty($master_data) ? 0 : $master_data[0]['om_final_amt'] ?>" placeholder=" " readonly="readonly" style="font-size: 20px; font-weight: bold;" />   
                                <label for="inputEmail3">TOTAL AMT.</label>
                                <small class="form-text text-muted helper-text" id="om_final_amt_msg"></small>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-12">
				<div class="card mb-3">
					<div class="card-header">PRODUCT DETAIL</div>
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
                                    <th width="2%">ACTION</th>
    	                        </tr>
                            </thead>
                            <tbody id="outward_material_wrapper">
    	                        <?php
                                    $outward_cnt = 1;
                                    if(!empty($trans_data)):
                                        foreach ($trans_data as $key => $value):
                                            
                                ?>
                                        <tr id="rowid_<?php echo $outward_cnt; ?>" class="floating-form">
                                            <td>
                                                <input type="number" class="form-control floating-input" id="brmm_item_code_<?php echo $outward_cnt ?>" value="<?php echo $value['brmm_item_code'] ?>" readonly />
                                                <input type="hidden" name="ot_brmm_id[]" id="ot_brmm_id_<?php echo $outward_cnt ?>" value="<?php echo $value['ot_brmm_id']; ?>" />
                                                <input type="hidden" name="ot_id[]" id="ot_id_<?php echo $outward_cnt ?>" value="<?php echo $value['ot_id'] ?>" />
                                            </td>          

                                            <!-- <td> -->
                                                <input type="hidden" class="form-control floating-input" name="ot_bill_no[]" id="ot_bill_no_<?php echo $outward_cnt ?>" value="<?php echo $value['ot_bill_no'] ?>" readonly />
                                                <input type="hidden" name="ot_prmm_id[]" id="ot_prmm_id_<?php echo $outward_cnt ?>" value="<?php echo $value['ot_prmm_id'] ?>" />
                                            <!-- </td> -->

                                            <!-- <td> -->
                                                <input type="hidden" class="form-control floating-input" name="ot_bill_date[]" id="ot_bill_date_<?php echo $outward_cnt ?>" value="<?php echo date('d-m-Y', strtotime($value['ot_bill_date'])) ?>" readonly />
                                            <!-- </td> -->

                                            <td>
                                                <input type="text" class="form-control floating-input" id="sku_name_<?php echo $outward_cnt ?>" value="<?php echo $value['sku_name'] ?>" readonly />
                                                <input type="hidden" name="ot_sku_id[]" id="ot_sku_id_<?php echo $outward_cnt ?>" value="<?php echo $value['ot_sku_id'] ?>" />
                                            </td>

                                            <td>
                                                <input type="text" class="form-control floating-input" id="apparel_name_<?php echo $outward_cnt ?>" value="<?php echo $value['apparel_name'] ?>" readonly />
                                                <input type="hidden" name="ot_apparel_id[]" id="ot_apparel_id_<?php echo $outward_cnt ?>" value="<?php echo $value['ot_apparel_id'] ?>" />
                                            </td>

                                            <td>
                                                <input type="number" class="form-control floating-input" name="ot_qty[]" id="ot_qty_<?php echo $outward_cnt ?>" value="<?php echo $value['ot_qty'] ?>" readonly />
                                            </td>

                                            <td>
                                                <input type="number" class="form-control floating-input" name="ot_rate[]" id="ot_rate_<?php echo $outward_cnt ?>" value="<?php echo $value['ot_rate'] ?>" readonly />
                                            </td>

                                            <td>
                                                <input type="number" class="form-control floating-input" name="ot_sub_total[]" id="ot_sub_total_<?php echo $outward_cnt ?>" value="<?php echo $value['ot_sub_total'] ?>" readonly />
                                            </td>

                                            <td>
                                                <?php if($value['isExist']): ?>
                                                    <button type="button" class="btn btn-sm btn-primary" ><i class="text-danger fa fa-ban"></i></button>
                                                <?php else: ?>
                                                    <a type="button" class="btn btn-primary" href="javascript:void(0)" onclick="remove_row(<?php echo $outward_cnt ?>)">
                                                        <i class="text-danger fa fa-trash"></i>
                                                    </a>
                                                <?php endif; ?>
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


    <div class="modal fade" id="barcodeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Barcode List (<span id="barcode_count">0</span>)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" id="barcode_list_wrapper" style="max-height:400px;overflow:auto;">
    
            <input type="text" id="barcode_search" placeholder="Search..." class="form-control mb-2">
                <div style="border-bottom:1px solid #ddd; padding-bottom:8px; margin-bottom:10px;">
                    <input type="checkbox" id="select_all_barcodes"> 
                    <b>Select All</b>
                </div>
                

                <div id="barcode_list"></div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" onclick="add_selected_barcodes()">Add Selected</button>
            </div>
        </div>
    </div>
</div>

</section>
<?php $this->load->view('templates/footer'); ?>
<script src="<?php echo assets('dist/js/transfer/outward.js?v=1')?>"></script>
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