<?php 
    $general_action = get_action_data('master', 'general');
    $uuid           = empty($master_data) ? $payment_general_uuid : $master_data[0]['payment_general_uuid'];
    $id             = empty($master_data) ? 0 : $master_data[0]['payment_general_id'];
    $tabindex       = 1;
?>
<div class="row">
    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
        <div class="card mb-3">
            <div class="card-header text-uppercase d-flex justify-content-between">
                <div>payment detail</div>
                <input 
                type="hidden" 
                id="id" 
                name="id" 
                value="<?php echo $id; ?>"
                />
                <input 
                    type="hidden" 
                    id="payment_general_uuid" 
                    name="payment_general_uuid" 
                    value="<?php echo $uuid; ?>"
                />
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap mt-2 form-group floating-form">
                    <div class=" col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                        <input 
                            type="number" 
                            class="form-control 
                            floating-input" 
                            id="payment_general_entry_no" 
                            name="payment_general_entry_no" 
                            value="<?php echo empty($master_data) ? $payment_general_entry_no : $master_data[0]['payment_general_entry_no'] ?>" 
                            placeholder=" " 
                            readonly="readonly" 
                        />   
                        <label class="text-uppercase">entry no</label>
                        <small class="form-text text-muted helper-text" id="payment_general_entry_no_msg"></small>
                    </div>
                    <div class=" col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                        <input 
                            type="date" 
                            class="form-control floating-input" 
                            id="payment_general_entry_date" 
                            name="payment_general_entry_date" 
                            value="<?php echo empty($master_data) ? date('Y-m-d') : date('Y-m-d', strtotime($master_data[0]['payment_general_entry_date'])) ?>" 
                            placeholder=" " 
                            autocomplete="off"
                        />   
                        <label class="text-uppercase">entry date</label>
                        <small class="form-text text-muted helper-text" id="payment_general_entry_date_msg"></small>
                    </div>
                    <div class=" col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                        <p class="text-uppercase">general&nbsp;<span class="text-danger">*</span>
                            <?php if(empty($master_data)): ?>
                                <?php if(in_array('add', $general_action)): ?>
                                    <span> 
                                        <a
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="ADD GENERAL A/C"
                                            style="cursor: pointer;"
                                            onclick='general_popup(<?php echo json_encode(["field" => "payment_general_general_id"]) ?>)'
                                        ><i class="fa fa-plus"></i></a>
                                    </span>
                                <?php endif; ?>
                            <?php endif; ?>

                        </p>
                        <select 
                            class="form-control floating-select" 
                            id="payment_general_general_id" 
                            name="payment_general_general_id" 
                            placeholder="" 
                            tabindex="<?php echo $tabindex++; ?>"
                            onchange="validate_dropdown(this)" 
                        >
                            <?php if(!empty($master_data)): ?>
                                <option value="<?php echo $master_data[0]['payment_general_general_id'] ?>">
                                    <?php echo $master_data[0]['general_name']; ?>
                                </option>
                                <input type="hidden" name="payment_general_general_id" value="<?php echo $master_data[0]['payment_general_general_id'] ?>" />
                            <?php endif; ?>                                                
                        </select>
                        <small class="form-text text-muted helper-text" id="payment_general_general_id_msg"></small>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 d-flex flex pl-0">
                        <div class="floating-label" style="width: 100%;">
                            <input 
                                type="number" 
                                class="form-control floating-input font-weight-bold" 
                                id="payment_general_amt" 
                                name="payment_general_amt" 
                                value="<?php echo empty($master_data) ? 0 : $master_data[0]['payment_general_amt'] ?>" 
                                placeholder=" " 
                                readonly
                            />   
                            <label class="text-uppercase">payment amt</label>
                            <small class="form-text text-muted helper-text" id="payment_general_amt_msg"></small>
                        </div>
                        <div>
                            <button
                                type="button"
                                class="btn btn-md btn-primary"
                                onclick="toggle_payment_mode_popup()"
                                data-toggle="tooltip" 
                                data-placement="bottom" 
                                title="PAYMENT MODE" 
                            ><i class="text-success fa fa-rupee"></i></button>
                        </div>
                    </div>
                    <div class=" col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                        <textarea 
                            class="form-control floating-textarea" 
                            id="payment_general_notes" 
                            name="payment_general_notes" 
                            placeholder=" " 
                            tabindex="<?php echo $tabindex++; ?>"
                            autocomplete="off"
                        ><?php echo empty($master_data) ? '' : $master_data[0]['payment_general_notes']; ?></textarea>
                        <label class="text-uppercase">notes</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="right-panel-wrapper" id="payment_mode_wrapper"><?php $this->load->view('pages/component/panel/_right'); ?></div>