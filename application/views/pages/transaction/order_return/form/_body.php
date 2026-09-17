<?php 
    $customer_action= get_action_data('master', 'customer');
    $id             = empty($master_data) ? 0 : $master_data[0]['orm_id'];
    $uuid           = empty($master_data) ? $orm_uuid : $master_data[0]['orm_uuid'];
    $tabindex       = 1;
    $bill_type      = 'checked';
?> 

<div class="row"> 
     <div class="col-12 col-sm-12 col-md-12 col-lg-12">
        <div class="d-flex flex-wrap mb-2">
           <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header text-uppercase">general detail</div>
                        
                    <div class="card-body">
                        <div class="d-flex flex-wrap form-group floating-form">
                            <div class="col-12 col-sm-12 col-md-2 col-lg-1 floating-label">
                                <input 
                                    type="hidden" 
                                    id="orm_id" 
                                    name="orm_id" 
                                    value="<?php echo $id; ?>"
                                />
                                <input 
                                    type="hidden" 
                                    id="orm_uuid" 
                                    name="orm_uuid" 
                                    value="<?php echo $uuid; ?>"
                                />
                                <input 
                                    type="text" 
                                    class="form-control floating-input" 
                                    id="orm_entry_no" 
                                    name="orm_entry_no" 
                                    value="<?php echo empty($master_data) ? $orm_entry_no : $master_data[0]['orm_entry_no'] ?>" 
                                    placeholder=" " 
                                    readonly="readonly" 
                                />   
                                <label class="text-uppercase">entry no</label>
                                <small class="form-text text-muted helper-text" id="orm_entry_no_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-3 col-lg-2 floating-label" >
                                <input 
                                    type="date" 
                                    class="form-control floating-input" 
                                    id="orm_entry_date" 
                                    name="orm_entry_date" 
                                    value="<?php echo empty($master_data) ? date('Y-m-d') : date('Y-m-d', strtotime($master_data[0]['orm_entry_date'])) ?>"
                                    placeholder=" " 
                                    readonly="readonly" 
                                />   
                                <label class="text-uppercase">entry date</label>
                                <small class="form-text text-muted helper-text" id="orm_entry_date_msg"></small>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-3 floating-label">
                                <p for="inputEmail3">BARCODE</p>
                                <select class="form-control floating-select" id="brmm_id" placeholder="" tabindex="1">                                                
                                </select>
                                <input type="hidden" id="orm_id" value="<?php echo empty($master_data) ? 0 : $master_data[0]['orm_id'] ?>">
                                <small class="form-text text-muted helper-text" id="brmm_id_msg"></small>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-3 floating-label">
                                <input type="text" class="form-control floating-input" id="customer_name" value="<?php echo empty($master_data) ? '' : $master_data[0]['customer_name'] ?>" placeholder=" " readonly/>   
                                <input type="hidden" id="orm_customer_id" name="orm_customer_id" value="<?php echo empty($master_data) ? '' : $master_data[0]['orm_customer_id'] ?>"/>   
                                <label for="inputEmail3">CUSTOMER <span class="text-danger">*</span></label>
                                <small class="form-text text-muted helper-text" id="orm_customer_id_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-3 col-lg-3 floating-label">
                                    <textarea
                                        class="form-control floating-textarea"
                                        id="orm_notes"
                                        name="orm_notes"
                                        placeholder=" "
                                        autocomplete="off"
                                        tabindex= "<?php echo $tabindex++; ?>"
                                    ><?php echo empty($master_data) ? '' : $master_data[0]['orm_notes']; ?></textarea>
                                    <label class="text-uppercase">notes</label>
                                    <small class="form-text text-muted helper-text d-none" id="orm_notes_msg"></small>
                            </div>

                        </div>
                    </div>
                </div>
           </div>
        </div>
    </div>
    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card mb-3">
            <div class="card-header text-uppercase">
                <h5 class="mb-0">
                    <a 
                        type="button" 
                        class="btn btn-sm btn-secondary" 
                        id="added_item_list_tabs"
                        data-toggle="collapse" 
                        data-target="#added_item_list_tab" 
                        aria-expanded="true" 
                        aria-controls="added_item_list_tab"
                    >added Product list (<span id="transaction_count">0</span>)</a>
                </h5>
            </div>
            <div id="added_item_list_tab" class="collapse show" aria-labelledby="added_item_list_tabs" data-parent="#accordion">
                <div class="card-body p-0" style="max-width:100vw; max-height:50vh; overflow:auto;" id="div_wrapper">
                    <table class="table table-sm text-uppercase">
                        <tbody class="table-dark border-0">
                            <tr style="font-weight:bold; font-size: 0.8rem;">
                                <td class="border-bottom border-top-0" >Sr No.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td class="border-bottom border-top-0" >Item&nbsp;code&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td class="border-bottom border-top-0" >qty&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td class="border-bottom border-top-0" >rate&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td class="border-bottom border-top-0" >amt&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td class="border-bottom border-top-0" >disc&nbsp;%&nbsp;&nbsp;</td>
                                <td class="border-bottom border-top-0" >disc&nbsp;amt&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td class="border-bottom border-top-0" >taxable&nbsp;amt&nbsp;</td>
                                <td class="border-bottom border-top-0" >sgst&nbsp;%&nbsp;</td>
                                <td class="border-bottom border-top-0" >sgst&nbsp;amt&nbsp;</td>
                                <td class="border-bottom border-top-0" >cgst&nbsp;%&nbsp;</td>
                                <td class="border-bottom border-top-0" >cgst&nbsp;amt&nbsp;</td>
                                <td class="border-bottom border-top-0" >igst&nbsp;%&nbsp;</td>
                                <td class="border-bottom border-top-0" >igst&nbsp;amt&nbsp;</td>
                                <td class="border-bottom border-top-0" >total&nbsp;amt&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td class="border-bottom border-top-0" >actions</td>
                            </tr>
                        </tbody> 
                        <tbody id="transaction_wrapper" style="font-weight: bold; font-size: 0.8rem;"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="card-header text-uppercase">amount detail</div>
            <div class="card-body">
                <div class="d-flex flex-wrap form-group floating-form">
                    <div class="col-12 col-sm-12 col-md-3 col-lg-2 floating-label">
                        <input 
                            type="number" 
                            class="form-control floating-input" 
                            id="orm_total_qty" 
                            name="orm_total_qty" 
                            value="<?php echo empty($master_data) ? 0 : $master_data[0]['orm_total_qty'] ?>" 
                            placeholder=" " 
                            readonly="readonly" 
                        />   
                        <label class="text-uppercase">total qty</label>
                        <small class="form-text text-muted helper-text" id="orm_total_qty_msg"></small>
                    </div>
                    <div class="col-12 col-sm-12 col-md-3 col-lg-2 floating-label">
                        <input 
                            type="number" 
                            class="form-control floating-input" 
                            id="orm_sub_amt" 
                            name="orm_sub_amt" 
                            value="<?php echo empty($master_data) ? 0 : $master_data[0]['orm_sub_amt'] ?>" 
                            placeholder=" " 
                            readonly="readonly" 
                        />   
                        <label class="text-uppercase">gross amt</label>
                        <small class="form-text text-muted helper-text" id="orm_sub_amt_msg"></small>
                    </div> 
                    <div class="col-12 col-sm-12 col-md-3 col-lg-2 floating-label">
                        <input 
                            type="number" 
                            class="form-control floating-input" 
                            id="orm_disc_amt" 
                            name="orm_disc_amt" 
                            value="<?php echo empty($master_data) ? 0 : $master_data[0]['orm_disc_amt'] ?>"
                            readonly>
                        <label class="text-uppercase">disc amt</label>
                        <small class="form-text text-muted helper-text" id="orm_disc_amt_msg"></small>
                    </div>
                    <div class="col-12 col-sm-12 col-md-3 col-lg-2 floating-label">
                        <input 
                            type="number" 
                            class="form-control floating-input" 
                            id="orm_taxable_amt" 
                            name="orm_taxable_amt" 
                            value="<?php echo empty($master_data) ? 0 : $master_data[0]['orm_taxable_amt'] ?>" 
                            readonly
                        />   
                        <label class="text-uppercase">taxable amt</label>
                        <small class="form-text text-muted helper-text" id="orm_taxable_amt_msg"></small>
                    </div>
                    <div class="col-12 col-sm-12 col-md-3 col-lg-2 floating-label" style="display: none;">
                        <input 
                            type="number" 
                            class="form-control floating-input" 
                            id="orm_sgst_amt" 
                            name="orm_sgst_amt" 
                            value="<?php echo empty($master_data) ? 0 : $master_data[0]['orm_sgst_amt'] ?>" 
                            readonly
                        />   
                        <label class="text-uppercase">sgst amt</label>
                        <small class="form-text text-muted helper-text" id="orm_sgst_amt_msg"></small>
                    </div>
                    <div class="col-12 col-sm-12 col-md-3 col-lg-2 floating-label"  style="display: none;">
                        <input 
                            type="number" 
                            class="form-control floating-input" 
                            id="orm_cgst_amt" 
                            name="orm_cgst_amt" 
                            value="<?php echo empty($master_data) ? 0 : $master_data[0]['orm_cgst_amt'] ?>" 
                            readonly
                        />   
                        <label class="text-uppercase">cgst amt</label>
                        <small class="form-text text-muted helper-text" id="orm_cgst_amt_msg"></small>
                    </div>
                    <div class="col-12 col-sm-12 col-md-3 col-lg-2 floating-label"  style="display: none;">
                        <input 
                            type="number" 
                            class="form-control floating-input" 
                            id="orm_igst_amt" 
                            name="orm_igst_amt" 
                            value="<?php echo empty($master_data) ? 0 : $master_data[0]['orm_igst_amt'] ?>" 
                            readonly/>   
                        <label class="text-uppercase">igst amt</label>
                        <small class="form-text text-muted helper-text" id="orm_igst_amt_msg"></small>
                    </div>
                    <div class="col-12 col-sm-12 col-md-3 col-lg-2 floating-label">
                        <input 
                            type="number" 
                            class="form-control floating-input" 
                            id="orm_gst_amt" 
                            name="orm_gst_amt" 
                            value="<?php echo empty($master_data) ? 0 : $master_data[0]['orm_gst_amt'] ?>" 
                            readonly/>   
                        <label class="text-uppercase">gst amt</label>
                        <small class="form-text text-muted helper-text" id="orm_gst_amt_msg"></small>
                    </div>
                    <div class="col-12 col-sm-12 col-md-3 col-lg-2 floating-label">
                        <input 
                            type="number" 
                            class="form-control floating-input font-weight-bold" 
                            id="orm_total_amt" 
                            name="orm_total_amt" 
                            value="<?php echo empty($master_data) ? 0 : $master_data[0]['orm_total_amt'] ?>" 
                            placeholder=" " 
                            readonly/>   
                        <label class="text-uppercase">Total amt</label>
                        <small class="form-text text-muted helper-text" id="orm_total_amt_msg"></small>
                    </div>

                </div>
            </div>
        </div>
    </div>        
   
</div>
<script>
</script>
