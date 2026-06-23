<?php 
    $uuid       = empty($master_data) ? $receipt_uuid : $master_data[0]['receipt_uuid'];
    $id         = empty($master_data) ? 0 : $master_data[0]['receipt_id'];
    $tabindex   = 1;
?>
<div class="row">
    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
        <div class="card mb-3">
            <div class="card-header text-uppercase d-flex justify-content-between">
                <div>receipt detail</div>
                <input 
                type="hidden" 
                id="id" 
                name="id" 
                value="<?php echo $id; ?>"
                />
                <input 
                    type="hidden" 
                    id="receipt_uuid" 
                    name="receipt_uuid" 
                    value="<?php echo $uuid; ?>"
                />
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap mt-2 form-group floating-form">
                    <div class=" col-12 col-sm-12 col-md-3 col-lg-2 floating-label">
                        <input 
                            type="number" 
                            class="form-control 
                            floating-input" 
                            id="receipt_entry_no" 
                            name="receipt_entry_no" 
                            value="<?php echo empty($master_data) ? $receipt_entry_no : $master_data[0]['receipt_entry_no'] ?>" 
                            placeholder=" " 
                            readonly="readonly" 
                        />   
                        <label class="text-uppercase">entry no</label>
                        <small class="form-text text-muted helper-text" id="receipt_entry_no_msg"></small>
                    </div>
                    <div class=" col-12 col-sm-12 col-md-3 col-lg-2 floating-label">
                        <input 
                            type="date" 
                            class="form-control floating-input" 
                            id="receipt_entry_date" 
                            name="receipt_entry_date" 
                            value="<?php echo empty($master_data) ? date('Y-m-d') : date('Y-m-d', strtotime($master_data[0]['receipt_entry_date'])) ?>" 
                            placeholder=" " 
                            autocomplete="off"
                        />   
                        <label class="text-uppercase">entry date</label>
                        <small class="form-text text-muted helper-text" id="receipt_entry_date_msg"></small>
                    </div>
                    <div class=" col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                        <p class="text-uppercase">bill&nbsp;no</p>
                        <select 
                            class="form-control floating-select" 
                            id="om_id" 
                            name="om_id" 
                            placeholder="" 
                            tabindex="<?php echo $tabindex++; ?>"
                        ></select>
                        <small class="form-text text-muted helper-text" id="om_id_msg"></small>
                    </div>
                    <div class=" col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                        <p class="text-uppercase">customer&nbsp;<span class="text-danger">*</span></p>
                        <select 
                            class="form-control floating-select" 
                            id="receipt_customer_id" 
                            name="receipt_customer_id" 
                            placeholder="" 
                            tabindex="<?php echo $tabindex++; ?>"
                            onchange="validate_dropdown(this)" 
                        >
                            <?php if(!empty($master_data)): ?>
                                <option value="<?php echo $master_data[0]['receipt_customer_id'] ?>">
                                    <?php echo $master_data[0]['customer_name']; ?>
                                </option>
                                <input type="hidden" name="receipt_customer_id" value="<?php echo $master_data[0]['receipt_customer_id'] ?>" />
                            <?php endif; ?>                                                
                        </select>
                        <small class="form-text text-muted helper-text" id="receipt_customer_id_msg"></small>
                    </div>
                    <div class=" col-12 col-sm-12 col-md-6 col-lg-8 floating-label">
                        <textarea 
                            class="form-control floating-textarea" 
                            id="receipt_notes" 
                            name="receipt_notes" 
                            placeholder=" " 
                            tabindex="<?php echo $tabindex++; ?>"
                            autocomplete="off"
                        ><?php echo empty($master_data) ? '' : $master_data[0]['receipt_notes']; ?></textarea>
                        <label class="text-uppercase">notes</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between">
                <span>AMOUNT DETAIL</span>
                <button 
                    type="button" 
                    class="btn btn-xs btn-secondary text-uppercase d-none" 
                    id="btn_adjustment"
                    onclick="get_data_for_adjustment()"
                >show bill for adjustment</button>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap mt-2 form-group floating-form">
                    <div class=" col-12 col-sm-12 col-md-4 col-lg-4 floating-label">
                        <input 
                            type="hidden"
                            id="receipt_opening_amt" 
                            name="receipt_opening_amt" 
                            value="<?php echo empty($master_data) ? 0 : $master_data[0]['receipt_opening_amt'] ?>" 
                            placeholder=" "
                            readonly
                        />
                        <input 
                                type="number" 
                                class="form-control floating-input" 
                                id="receipt_order_amt" 
                                name="receipt_order_amt" 
                                value="<?php echo empty($master_data) ? 0 : $master_data[0]['receipt_order_amt'] ?>" 
                                placeholder=" "
                                readonly
                            />
                        <label class="text-uppercase">order amt</label>
                        <small class="form-text text-muted helper-text" id="receipt_order_amt_msg"></small>
                    </div>
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 d-flex flex pl-0">
                        <div class="floating-label" style="width: 100%;">
                            <input 
                                type="number" 
                                class="form-control floating-input font-weight-bold" 
                                id="receipt_amt" 
                                name="receipt_amt" 
                                value="<?php echo empty($master_data) ? 0 : $master_data[0]['receipt_amt'] ?>" 
                                placeholder=" " 
                                readonly
                            />   
                            <label class="text-uppercase">receipt amt</label>
                            <small class="form-text text-muted helper-text" id="receipt_amt_msg"></small>
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
                    <div class=" col-12 col-sm-12 col-md-4 col-lg-4 floating-label">
                        <?php if(empty($master_data)): ?>
                            <input 
                                type="text" 
                                class="form-control floating-input" 
                                id="receipt_balance_amt_show" 
                                value="" 
                                placeholder=" " 
                                readonly="readonly" 
                            />   
                            <label class="text-uppercase">balance</label>
                        <?php endif; ?>
                        <input type="hidden" id="receipt_balance_amt" value="0">
                        <input type="hidden" id="receipt_balance_type" value="">
                        <!-- <small class="form-text text-muted helper-text" id="vm_balance_show_msg"></small> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
        <ul class="nav nav-pills nav-fill nav-pills-secondary" id="pills-tab" role="tablist">
            <li class="nav-item">
                <a 
                    class="nav-link active text-uppercase" 
                    id="order_tab" 
                    data-toggle="tab"
                    href="#order_content" 
                    role="tab" 
                    aria-controls="order_content" 
                    aria-selected="false"
                >order (<span id="order_select_count">0</span> / <span id="order_count">0</span>)</a>
            </li>
        </ul>
       <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="order_content" role="tabpanel" aria-labelledby="order_tab">
                <div class="card">
                    <div class="card-body p-0">
                         <div style="max-height: 20vh; overflow-y: auto;">
                            <table class="table table-sm text-uppercase">
                                <tbody class="table-dark font-weight-bold">
                                    <tr>
                                        <td width="2%">
                                            <label class="custom-control material-checkbox-secondary">
                                                <input 
                                                    type="checkbox" 
                                                    class="material-control-input-secondary" 
                                                    id="order_checkbox" 
                                                    onclick="order_select_deselect()" 
                                                />
                                                <span class="material-control-indicator-secondary"></span>
                                                <span class="material-control-description-secondary">#</span>
                                            </label>
                                        </td>
                                        <td width="5%" align="center">entry no</td>
                                        <td width="5%" align="center">TYPE</td>
                                        <td width="5%">entry date</td>
                                        <td width="5%">balance amt</td>
                                        <td width="5%">adjust amt</td>
                                        <td width="5%">pending amt</td>
                                    </tr>
                                </tbody>
                                <tbody id="order_wrapper"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</div>
<div class="right-panel-wrapper" id="payment_mode_wrapper"><?php $this->load->view('pages/component/panel/_right'); ?></div>