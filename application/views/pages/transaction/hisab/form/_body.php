<?php 
    $karigar_action = get_action_data('master', 'karigar');
    $id             = empty($master_data) ? 0 : $master_data[0]['hm_id'];
    $uuid           = empty($master_data) ? $hm_uuid : $master_data[0]['hm_uuid'];
    $tabindex       = 1;
?>
<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
        <div class="d-flex flex-wrap">
            <div class="col-12 col-sm-12 col-md-4 col-lg-3">
                <div class="card mb-3">
                    <div class="card-header text-uppercase">general detail</div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap pt-2 form-group floating-form">
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                                <input 
                                    type="hidden" 
                                    id="id" 
                                    name="id" 
                                    value="<?php echo $id; ?>"
                                />
                                <input 
                                    type="hidden" 
                                    id="hm_uuid" 
                                    name="hm_uuid" 
                                    value="<?php echo $uuid; ?>"
                                />
                                <input 
                                    type="number" 
                                    class="form-control floating-input" 
                                    id="hm_entry_no" 
                                    name="hm_entry_no" 
                                    value="<?php echo empty($master_data) ? $hm_entry_no : $master_data[0]['hm_entry_no'] ?>" 
                                    placeholder=" " 
                                    readonly="readonly" 
                                />   
                                <label class="text-uppercase">entry no</label>
                                <small class="form-text text-muted helper-text" id="hm_entry_no_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                                <input 
                                    type="date" 
                                    class="form-control floating-input" 
                                    id="hm_entry_date" 
                                    name="hm_entry_date" 
                                    value="<?php echo empty($master_data) ? date('Y-m-d') : date('Y-m-d', strtotime($master_data[0]['hm_entry_date'])) ?>" 
                                    placeholder=" " 
                                    autocomplete="off"
                                    tabindex= "<?php echo $tabindex++; ?>"
                                />   
                                <label class="text-uppercase">entry date</label>
                                <small class="form-text text-muted helper-text" id="hm_entry_date_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label">
                                <p class="text-uppercase">karigar&nbsp;<span class="text-danger">*</span></p>
                                <select 
                                    class="form-control floating-select" 
                                    id="hm_karigar_id" 
                                    name="hm_karigar_id" 
                                    placeholder=" " 
                                    tabindex= "<?php echo $tabindex++; ?>"
                                    onchange="validate_dropdown(this)" 
                                    <?php echo (!empty($master_data) && $master_data[0]['isExist']) ? 'disabled="disabled"' : ''; ?>
                                >
                                    <?php if(!empty($master_data) && !empty($master_data[0]['hm_karigar_id'])): ?>
                                        <option value="<?php echo $master_data[0]['hm_karigar_id'] ?>" selected>
                                            <?php echo $master_data[0]['karigar_name']; ?> 
                                        </option>
                                    <?php endif; ?>
                                </select>
                                <small class="form-text text-muted helper-text" id="hm_karigar_id_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label">
                                <textarea
                                    class="form-control floating-textarea"
                                    id="hm_notes"
                                    name="hm_notes"
                                    placeholder=" "
                                    autocomplete="off"
                                    tabindex= "<?php echo $tabindex++; ?>"
                                ><?php echo empty($master_data) ? '' : $master_data[0]['hm_notes']; ?></textarea>
                                <label class="text-uppercase">notes</label>
                                <small class="form-text text-muted helper-text" id="hm_notes_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                                <input 
                                    type="number" 
                                    class="form-control floating-input" 
                                    id="hm_total_qty" 
                                    name="hm_total_qty" 
                                    value="<?php echo empty($master_data) ? 0 : $master_data[0]['hm_total_qty'] ?>" 
                                    placeholder=" " 
                                    readonly="readonly" 
                                />   
                                <label class="text-uppercase">total job</label>
                                <small class="form-text text-muted helper-text" id="hm_total_qty_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                                <input 
                                    type="number" 
                                    class="form-control floating-input font-weight-bold" 
                                    id="hm_total_amt" 
                                    name="hm_total_amt" 
                                    value="<?php echo empty($master_data) ? 0 : $master_data[0]['hm_total_amt'] ?>" 
                                    placeholder=" " 
                                    readonly
                                    style="font-size: 1.5rem;"
                                />   
                                <label class="text-uppercase">net payable amt</label>
                                <small class="form-text text-muted helper-text" id="hm_total_amt_msg"></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-8 col-lg-9">
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
                            >added item list (<span id="transaction_count">0</span>)</a>
                        </h5>
                    </div>
                    <div id="added_item_list_tab" class="collapse show" aria-labelledby="added_item_list_tabs" data-parent="#accordion">
                        <div class="card-body p-0" style="max-width:100vw; max-height:50vh; overflow:auto;" id="div_wrapper">
                            <table class="table table-sm table-reponsive table-hover text-uppercase">
                                <tbody class="table-dark border-0">
                                    <tr style="font-weight:bold; font-size: 0.8rem;">
                                        <td class="border-bottom border-top-0" >job&nbsp;no&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td class="border-bottom border-top-0" >job&nbsp;date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td class="border-bottom border-top-0" >apparel&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td class="border-bottom border-top-0" >barcode&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td class="border-bottom border-top-0" >rate</td>
                                        <td class="border-bottom border-top-0" >remove</td>
                                    </tr>
                                </tbody>
                                <tbody id="transaction_wrapper" style="font-weight: bold; font-size: 0.8rem;"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>