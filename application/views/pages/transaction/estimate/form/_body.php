<?php 
    $customer_action= get_action_data('master', 'customer');
    $id             = empty($master_data) ? 0 : $master_data[0]['om_id'];
    $uuid           = empty($master_data) ? $om_uuid : $master_data[0]['om_uuid'];
    $tabindex       = 1;
?>
<style>  
    .floating-label { 
        margin-bottom:8px !important;
    }
    .select2-lock {
        pointer-events: none;
        cursor: not-allowed !important;
    }
    .select2-lock .select2-selection {
        cursor: not-allowed !important;
        background: #f5f5f5;
    }
</style> 
<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="master_content" role="tabpanel" aria-labelledby="master_tab">
                <div class="d-flex flex-wrap">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="card mb-3">
                            <div class="card-header text-uppercase">general detail</div>
                            <div class="card-body">
                                <div class="d-flex flex-wrap pt-2 form-group floating-form mb-0">
                                    <div class="col-12 col-sm-6 col-md-2 col-lg-1 floating-label">
                                        <input 
                                            type="hidden" 
                                            id="id" 
                                            name="id" 
                                            value="<?php echo $id; ?>"
                                        />
                                        <input 
                                            type="hidden" 
                                            id="om_uuid" 
                                            name="om_uuid" 
                                            value="<?php echo $uuid; ?>"
                                        />
                                        <input 
                                            type="hidden" 
                                            id="ot_id" 
                                            name="ot_id" 
                                            value="0"
                                        />
                                        <input 
                                            type="number" 
                                            class="form-control floating-input" 
                                            id="om_em_entry_no" 
                                            name="om_em_entry_no" 
                                            value="<?php echo empty($master_data) ? $om_em_entry_no : $master_data[0]['om_em_entry_no'] ?>" 
                                            placeholder=" "  
                                        />   
                                        <label class="text-uppercase">order no</label>
                                        <small class="form-text text-muted helper-text" id="om_em_entry_no_msg"></small>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-3 col-lg-2 floating-label">
                                        <input 
                                            type="date" 
                                            class="form-control floating-input" 
                                            id="om_em_entry_date" 
                                            name="om_em_entry_date" 
                                            value="<?php echo empty($master_data) ? date('Y-m-d') : date('Y-m-d', strtotime($master_data[0]['om_em_entry_date'])) ?>" 
                                            placeholder=" " 
                                            autocomplete="off"
                                            tabindex= "<?php echo $tabindex++; ?>"
                                        />   
                                        <label class="text-uppercase">order date</label>
                                        <small class="form-text text-muted helper-text" id="om_em_entry_date_msg"></small>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-3 col-lg-2 floating-label">
                                        <input 
                                            type="date" 
                                            class="form-control floating-input" 
                                            id="om_trial_date" 
                                            name="om_trial_date" 
                                            value="<?php echo empty($master_data) || empty($master_data[0]['om_trial_date']) ? '' : date('Y-m-d', strtotime($master_data[0]['om_trial_date'])) ?>" 
                                            placeholder=" " 
                                            autocomplete="off"
                                            tabindex= "<?php echo $tabindex++; ?>"
                                        />   
                                        <label class="text-uppercase">trial date</label>
                                        <small class="form-text text-muted helper-text" id="om_trial_date_msg"></small>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-3 col-lg-2 floating-label">
                                        <input 
                                            type="date" 
                                            class="form-control floating-input" 
                                            id="om_delivery_date" 
                                            name="om_delivery_date" 
                                            value="<?php echo empty($master_data) || empty($master_data[0]['om_delivery_date']) ? '' : date('Y-m-d', strtotime($master_data[0]['om_delivery_date'])) ?>" 
                                            placeholder=" " 
                                            autocomplete="off"
                                            tabindex= "<?php echo $tabindex++; ?>"
                                        />   
                                        <label class="text-uppercase">delivery date</label>
                                        <small class="form-text text-muted helper-text" id="om_delivery_date_msg"></small>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-3 floating-label">
                                        <p class="text-uppercase">client&nbsp;<span class="text-danger">*</span>
                                            <?php if(empty($master_data)): ?>
                                                <?php if(in_array('add', $customer_action)): ?>
                                                    <span>
                                                        <a
                                                            data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="ADD CLIENT"
                                                            style="cursor: pointer;"
                                                            onclick='customer_popup(<?php echo json_encode(["field" => "om_billing_id"]) ?>)'
                                                        ><i class="fa fa-plus"></i></a>
                                                    </span>
                                                <?php endif; ?>
                                            <?php endif; ?>

                                        </p>
                                        <select 
                                            class="form-control floating-select" 
                                            id="om_billing_id" 
                                            <?php echo (!empty($master_data) && $master_data[0]['isExist']) ? 'disabled' : 'name="om_billing_id"'; ?>
                                        >
                                            <?php if(!empty($master_data) && !empty($master_data[0]['om_billing_id'])): ?>
                                                <option value="<?php echo $master_data[0]['om_billing_id'] ?>" selected>
                                                    <?php echo $master_data[0]['customer_name']; ?> 
                                                </option>
                                            <?php endif; ?>
                                        </select>

                                        <small class="form-text text-muted helper-text" id="om_billing_id_msg"></small>

                                        <?php if(!empty($master_data) && $master_data[0]['isExist']): ?>
                                            <input 
                                                type="hidden" 
                                                name="om_billing_id" 
                                                value="<?php echo $master_data[0]['om_billing_id']; ?>" 
                                            />
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-3 floating-label d-none">
                                        <p class="text-uppercase">
                                            client&nbsp;<span class="text-danger">*</span>
                                        </p>

                                        <select 
                                            class="form-control floating-select" 
                                            id="om_customer_id"
                                            <?php echo (!empty($master_data) && $master_data[0]['isExist']) 
                                                ? 'disabled' 
                                                : 'name="om_customer_id"'; ?>
                                            tabindex="<?php echo $tabindex++; ?>"
                                            onchange="validate_dropdown(this)"
                                        >
                                            <?php if(!empty($master_data) && !empty($master_data[0]['om_customer_id'])): ?>
                                                <option value="<?php echo $master_data[0]['om_customer_id'] ?>" selected>
                                                    <?php echo $master_data[0]['customer_name']; ?> 
                                                </option>
                                            <?php endif; ?>
                                        </select>

                                        <small class="form-text text-muted helper-text" id="om_customer_id_msg"></small>

                                        <?php if(!empty($master_data) && $master_data[0]['isExist']): ?>
                                            <input 
                                                type="hidden" 
                                                name="om_customer_id" 
                                                value="<?php echo $master_data[0]['om_customer_id']; ?>" 
                                            />
                                        <?php endif; ?>
                                    </div>

                                     <div class="col-12 col-sm-12 col-md-6 col-lg-2 floating-label">
                                        <p class="text-uppercase">Salesman</p>
                                        <select 
                                            class="form-control floating-select" 
                                            id="om_salesman_id" 
                                            name="om_salesman_id" 
                                            placeholder=" " 
                                            tabindex= "<?php echo $tabindex++; ?>"
                                            onchange="validate_dropdown(this)" 
                                            <?php echo (!empty($master_data) && $master_data[0]['isExist']) ? 'disabled="disabled"' : ''; ?>
                                        >
                                            <?php if(!empty($master_data) && !empty($master_data[0]['om_salesman_id'])): ?>
                                                <option value="<?php echo $master_data[0]['om_salesman_id'] ?>" selected>
                                                    <?php echo $master_data[0]['salesman_name']; ?> 
                                                </option>
                                            <?php endif; ?>
                                        </select>
                                        <small class="form-text text-muted helper-text" id="om_salesman_id_msg"></small>
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
                                        id="add_itom_tabs"
                                        data-toggle="collapse" 
                                        data-target="#add_itom_tab" 
                                        aria-expanded="true" 
                                        aria-controls="add_itom_tab"
                                    >add item</a>
                                </h5>
                            </div>
                            <div id="add_itom_tab" class="collapse show" aria-labelledby="add_itom_tabs" data-parent="#accordion">
                                <div class="card-body mb-0">
                                    <div class="d-flex form-group pt-3 mb-0" style="overflow-y: auto;">
                                        <div class="col-12 col-sm-12 col-md-4 col-lg-2">
                                            <div class="d-flex floating-form">
                                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label">
                                                    <p class="text-uppercase">type&nbsp;<span class="text-danger">*</span></p> 
                                                    <select 
                                                        class="form-control floating-select select2" 
                                                        id="trans_type" 
                                                        name="trans_type" 
                                                        placeholder=" "
                                                        onchange="set_area()"  
                                                        tabindex= "<?php echo $tabindex++; ?>">
                                                        <option value="READYMADE">READYMADE</option>
                                                        <option value="CUSTOM">CUSTOM</option>
                                                    </select>
                                                    <small class="form-text text-muted helper-text" id="trans_type"></small>
                                                </div>
                                                
                                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label d-none _sku_area">
                                                    <p class="text-uppercase">sku&nbsp;</p> 
                                                    <select 
                                                        class="form-control floating-select" 
                                                        id="sku_id" 
                                                        name="sku_id" 
                                                        placeholder=" "
                                                        onchange="validate_dropdown(this)"  
                                                        tabindex= "<?php echo $tabindex++; ?>"
                                                    ></select>
                                                    <small class="form-text text-muted helper-text" id="sku_id_msg"></small>
                                                </div>
                                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label d-none _stitching_area">
                                                    <p class="text-uppercase">apparel&nbsp;</p> 
                                                    <select 
                                                        class="form-control floating-select" 
                                                        id="apparel_id" 
                                                        name="apparel_id" 
                                                        placeholder=" "
                                                        onchange="validate_dropdown(this)"  
                                                        tabindex= "<?php echo $tabindex++; ?>"
                                                    ></select>
                                                    <small class="form-text text-muted helper-text" id="apparel_id_msg"></small>
                                                </div>

                                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label d-none _readymade_fabric_area">
                                                    <p class="text-uppercase">barcode</p> 
                                                    <select 
                                                        class="form-control floating-select" 
                                                        id="brmm_id" 
                                                        name="brmm_id" 
                                                        placeholder=" "
                                                        onchange="validate_dropdown(this)"  
                                                        tabindex= "<?php echo $tabindex++; ?>"
                                                    ></select>
                                                    <small class="form-text text-muted helper-text" id="brmm_id_msg"></small>
                                                </div>

                                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label d-none">

                                                    <p class="text-uppercase">Size
                                                                <span>
                                                                    <a
                                                                        data-toggle="tooltip"
                                                                        data-placement="top"
                                                                        title="ADD SIZE"
                                                                        style="cursor: pointer;"
                                                                        onclick='popup(<?php echo json_encode(["sub_menu" => "size", "field" => "size_id"]) ?>)'
                                                                    ><i class="fa fa-plus"></i></a>
                                                                </span>
                                                    </p>

                                                    <div class="input-group">
                                                        <select 
                                                            class="form-control floating-select" 
                                                            id="size_id" 
                                                            name="size_id" 
                                                            placeholder=" "
                                                            onchange="validate_dropdown(this)"  
                                                            tabindex="<?php echo $tabindex++; ?>"
                                                        ></select>
                                                    </div>
                                                </div>

                                                <input 
                                                    type="hidden" 
                                                    class="form-control floating-input" 
                                                    id="size_id" 
                                                    name="size_id" 
                                                    value="0" 
                                                    placeholder="" 
                                                    autocomplete="off" 
                                                    tabindex= "<?php echo $tabindex++; ?>"
                                                    readonly
                                                />
                                                
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label" id="qty_area">
                                                    <p class="text-uppercase">qty&nbsp;<span class="text-danger">*</span></p> 
                                                    <input 
                                                        type="number" 
                                                        class="form-control floating-input" 
                                                        id="qty" 
                                                        name="qty" 
                                                        value="1" 
                                                        placeholder="" 
                                                        autocomplete="off" 
                                                        tabindex= "<?php echo $tabindex++; ?>"
                                                        readonly
                                                    />
                                                    <small class="form-text text-muted helper-text" id="qty_msg"></small>
                                                </div>
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                                                    <p class="text-uppercase">rate&nbsp;<span class="text-danger">*</span></p> 
                                                    <input 
                                                        type="number" 
                                                        class="form-control floating-input" 
                                                        id="rate" 
                                                        name="rate" 
                                                        value="0" 
                                                        placeholder="" 
                                                        autocomplete="off" 
                                                        onkeyup="calculate_transaction()" 
                                                        tabindex= "<?php echo $tabindex++; ?>"
                                                    />
                                                    <small class="form-text text-muted helper-text" id="rate_msg"></small>
                                                </div>
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                                                    <p class="text-uppercase">amt&nbsp;<span class="text-danger">*</span></p> 
                                                    <input 
                                                        type="number" 
                                                        class="form-control floating-input" 
                                                        id="amt"
                                                        name="amt"
                                                        value="0" 
                                                        placeholder="" 
                                                        autocomplete="off"
                                                        readonly
                                                    />
                                                    <small class="form-text text-muted helper-text" id="amt_msg"></small>
                                                </div>

                                                 <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                                                    <p class="text-uppercase">disc %</p> 
                                                    <input 
                                                        type="number" 
                                                        class="form-control floating-input" 
                                                        id="disc_per"
                                                        name="disc_per"
                                                        value="0"
                                                        placeholder="" 
                                                        autocomplete="off" 
                                                        onkeyup="calculate_transaction(true)" 
                                                        tabindex= "<?php echo $tabindex++; ?>"
                                                    />
                                                    <small class="form-text text-muted helper-text" id="disc_per_msg"></small>
                                                </div>
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                                                    <p class="text-uppercase">disc amt</p> 
                                                    <input 
                                                        type="number" 
                                                        class="form-control floating-input" 
                                                        id="disc_amt"
                                                        name="disc_amt"
                                                        value="0"
                                                        placeholder="" 
                                                        autocomplete="off" 
                                                        onkeyup="calculate_transaction()" 
                                                        tabindex= "<?php echo $tabindex++; ?>"
                                                    />
                                                    <small class="form-text text-muted helper-text" id="disc_amt_msg"></small>
                                                </div>

                                                <div class="col-12 col-sm-12 col-md-7 col-lg-7 floating-label">
                                                    <p class="text-uppercase">description</p> 
                                                    <input 
                                                        type="text" 
                                                        class="form-control floating-input" 
                                                        id="description"
                                                        name="description"
                                                        value=""
                                                        placeholder="" 
                                                        autocomplete="off" 
                                                        tabindex= "<?php echo $tabindex++; ?>"
                                                    />
                                                    <small class="form-text text-muted helper-text" id="description_msg"></small>
                                                </div>
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                                                    <p class="text-uppercase">total amt</p> 
                                                    <input 
                                                        type="number" 
                                                        class="form-control floating-input" 
                                                        id="total_amt"
                                                        name="total_amt"
                                                        value="0"
                                                        readonly
                                                    />
                                                    <small class="form-text text-muted helper-text" id="total_amt_msg"></small>
                                                </div>

                                                <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                                                <button 
                                                    type="button" 
                                                    class="btn btn-md btn-block btn-primary" 
                                                    id="add_row_btn"
                                                    data-toggle="tooltip" 
                                                    title="ADD ITEM" 
                                                    data-placement="top" 
                                                    tabindex= "<?php echo $tabindex++; ?>"
                                                    onclick="add_transaction()"   
                                                ><i class="text-success fa fa-plus"></i></button>
                                            </div>
                                        </div>

                                        </div>

                                        <!-- section d-none -->
                                        <div class="col-12 col-sm-12 col-md-4 col-lg-2 floating-form d-none">
                                            <div class="d-flex flex-wrap">
                                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label d-none">
                                                    <p class="text-uppercase">taxable amt</p> 
                                                    <input 
                                                        type="number" 
                                                        class="form-control floating-input" 
                                                        id="taxable_amt"
                                                        name="taxable_amt"
                                                        value="0"
                                                        readonly
                                                    />
                                                    <small class="form-text text-muted helper-text" id="taxable_amt_msg"></small>
                                                </div>
                                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label _display_qty d-none">
                                                    <p class="text-uppercase">available&nbsp;qty</p> 
                                                    <input 
                                                        type="number" 
                                                        class="form-control floating-input" 
                                                        id="available_qty"
                                                        name="available_qty"
                                                        value="0" 
                                                        placeholder="" 
                                                        autocomplete="off"
                                                        readonly
                                                    />
                                                    <small class="form-text text-muted helper-text" id="available_qty_msg"></small>
                                                </div>
                                            </div>
                                        </div>
                                        
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
                                id="added_itom_list_tabs"
                                data-toggle="collapse" 
                                data-target="#added_itom_list_tab" 
                                aria-expanded="true" 
                                aria-controls="added_itom_list_tab"
                            >added item list (<span id="transaction_count">0</span>)</a>
                        </h5>
                    </div>
                    <div id="added_itom_list_tab" class="collapse show" aria-labelledby="added_itom_list_tabs" data-parent="#accordion">
                        <div class="card-body p-0" style="max-width:100vw; max-height:50vh; overflow:auto;" id="div_wrapper">
                            <table class="table table-sm table-reponsive table-hover text-uppercase">
                                <tbody class="table-dark border-0">
                                    <tr style="font-weight:bold; font-size: 0.8rem;">
                                        <td class="border-bottom border-top-0" >#&nbsp;</td>
                                        <td class="border-bottom border-top-0" >type&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td class="border-bottom border-top-0" >sku&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td class="border-bottom border-top-0" >apparel&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td class="border-bottom border-top-0" >barcode&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td class="border-bottom border-top-0 d-none" >size&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td class="border-bottom border-top-0" >qty</td>
                                        <td class="border-bottom border-top-0" >rate</td>
                                        <td class="border-bottom border-top-0" >amt&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td class="border-bottom border-top-0" >disc&nbsp;%</td>
                                        <td class="border-bottom border-top-0" >disc&nbsp;amt&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td class="border-bottom border-top-0 d-none" >taxable&nbsp;amt&nbsp;</td>
                                        <td class="border-bottom border-top-0" >total&nbsp;amt&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td class="border-bottom border-top-0" >description&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
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
                <div class="card mb-3">
                    <div class="card-header text-uppercase d-flex justify-content-between">
                        <div>
                            <h5 class="mb-0">
                                <a 
                                    type="button" 
                                    class="btn btn-sm btn-secondary" 
                                    id="amount_details_tabs"
                                    data-toggle="collapse" 
                                    data-target="#amount_details_tab" 
                                    aria-expanded="true" 
                                    aria-controls="amount_details_tab"
                                >amount detail</a>
                            </h5>
                        </div>
                    </div>
                    <div id="amount_details_tab" class="collapse show" aria-labelledby="amount_details_tabs" data-parent="#accordion">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="card-body">
                                <div class="d-flex flex-wrap pt-2 form-group floating-form">
                                    <div class="col-12 col-sm-6 col-md-2 col-lg-2 floating-label">
                                        <input 
                                            type="number" 
                                            class="form-control floating-input" 
                                            id="om_total_qty" 
                                            name="om_total_qty" 
                                            value="<?php echo empty($master_data) ? 0 : $master_data[0]['om_total_qty'] ?>" 
                                            placeholder=" " 
                                            readonly="readonly" 
                                        />   
                                        <label class="text-uppercase">total qty</label>
                                        <small class="form-text text-muted helper-text" id="om_total_qty_msg"></small>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-2 col-lg-2 floating-label">
                                        <input 
                                            type="number" 
                                            class="form-control floating-input" 
                                            id="om_sub_amt" 
                                            name="om_sub_amt" 
                                            value="<?php echo empty($master_data) ? 0 : $master_data[0]['om_sub_amt'] ?>" 
                                            placeholder=" " 
                                            readonly="readonly" 
                                        />   
                                        <label class="text-uppercase">gross amt</label>
                                        <small class="form-text text-muted helper-text" id="om_sub_amt_msg"></small>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-2 col-lg-2 floating-label">
                                        <input 
                                            type="number" 
                                            class="form-control floating-input" 
                                            id="om_disc_amt" 
                                            name="om_disc_amt" 
                                            value="<?php echo empty($master_data) ? 0 : $master_data[0]['om_disc_amt'] ?>" 
                                            readonly
                                        />   
                                        <label class="text-uppercase">item disc amt</label>
                                        <small class="form-text text-muted helper-text" id="om_disc_amt_msg"></small>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-2 col-lg-2 floating-label d-none">
                                        <input 
                                            type="number" 
                                            class="form-control floating-input" 
                                            id="om_taxable_amt" 
                                            name="om_taxable_amt" 
                                            value="<?php echo empty($master_data) ? 0 : $master_data[0]['om_taxable_amt'] ?>" 
                                            readonly
                                        />   
                                        <label class="text-uppercase">taxable amt</label>
                                        <small class="form-text text-muted helper-text" id="om_taxable_amt_msg"></small>
                                    </div>

                                    <div class="col-12 col-sm-6 col-md-2 col-lg-2 floating-label">
                                        <input 
                                            type="number" 
                                            class="form-control floating-input" 
                                            id="om_bill_disc_per" 
                                            name="om_bill_disc_per" 
                                            value="<?php echo empty($master_data) ? 0 : $master_data[0]['om_bill_disc_per'] ?>" 
                                            onkeyup="calculate_master(true)"
                                            placeholder=" " 
                                            autocomplete="off"
                                            tabindex= "<?php echo $tabindex++; ?>"
                                        />   
                                        <label class="text-uppercase">bill disc %</label>
                                        <small class="form-text text-muted helper-text" id="om_bill_disc_per_msg"></small>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-2 col-lg-2 floating-label">
                                        <input 
                                            type="number" 
                                            class="form-control floating-input" 
                                            id="om_bill_disc_amt" 
                                            name="om_bill_disc_amt" 
                                            value="<?php echo empty($master_data) ? 0 : $master_data[0]['om_bill_disc_amt'] ?>" 
                                            onkeyup="calculate_master()"
                                            placeholder=" " 
                                            autocomplete="off"
                                            tabindex= "<?php echo $tabindex++; ?>"
                                        />   
                                        <label class="text-uppercase">bill disc amt</label>
                                        <small class="form-text text-muted helper-text" id="om_bill_disc_amt_msg"></small>
                                    </div>

                                    <div class="col-12 col-sm-6 col-md-2 col-lg-2 floating-label">
                                        <input 
                                            type="number" 
                                            class="form-control floating-input" 
                                            id="om_round_off" 
                                            name="om_round_off" 
                                            value="<?php echo empty($master_data) ? 0 : $master_data[0]['om_round_off'] ?>" 
                                            onkeyup="calculate_master()"
                                            placeholder=" " 
                                            autocomplete="off"
                                            tabindex= "<?php echo $tabindex++; ?>"
                                        />   
                                        <label class="text-uppercase">round off</label>
                                        <small class="form-text text-muted helper-text" id="om_round_off_msg"></small>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-2 col-lg-2 floating-label">
                                        <input 
                                            type="number" 
                                            class="form-control floating-input font-weight-bold" 
                                            id="om_total_amt" 
                                            name="om_total_amt" 
                                            value="<?php echo empty($master_data) ? 0 : $master_data[0]['om_total_amt'] ?>" 
                                            placeholder=" " 
                                            readonly
                                            style="font-size: 1.5rem;"
                                        />   
                                        <label class="text-uppercase">net amt</label>
                                        <small class="form-text text-muted helper-text" id="om_total_amt_msg"></small>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-2 col-lg-2 d-flex flex pl-0">
                                        <div class="floating-label" style="width: 100%;">
                                            <input 
                                                type="number" 
                                                class="form-control floating-input font-weight-bold" 
                                                id="om_advance_amt" 
                                                name="om_advance_amt" 
                                                value="<?php echo empty($master_data) ? 0 : $master_data[0]['om_advance_amt'] ?>" 
                                                readonly
                                            />   
                                            <label class="text-uppercase">advance amt</label>
                                            <small class="form-text text-muted helper-text" id="om_advance_amt_msg"></small>
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
                                    <div class="col-12 col-sm-6 col-md-2 col-lg-2 floating-label">
                                        <p class="text-uppercase">balance</p>
                                        <input 
                                            type="number" 
                                            class="form-control floating-input font-weight-bold" 
                                            id="om_balance_amt" 
                                            name="om_balance_amt" 
                                            value="<?php echo empty($master_data) ? 0 : $master_data[0]['om_balance_amt'] ?>" 
                                            placeholder=" " 
                                            readonly
                                            style="font-size: 30px;" 
                                        />   
                                        <small class="form-text text-muted helper-text" id="om_balance_amt_msg"></small>
                                    </div>
                                     <div class="col-12 col-sm-12 col-md-6 col-lg-5 floating-label">
                                        <textarea
                                            class="form-control floating-textarea"
                                            id="om_notes"
                                            name="om_notes"
                                            placeholder=" "
                                            autocomplete="off"
                                            tabindex= "<?php echo $tabindex++; ?>"
                                            style="min-height:85px;"
                                        ><?php echo empty($master_data) ? '' : $master_data[0]['om_notes']; ?></textarea>
                                        <label class="text-uppercase">notes</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>    
                </div>
            </div>
        </div>
    </div>
</div>
<div class="right-panel-wrapper" id="payment_mode_wrapper"><?php $this->load->view('pages/component/panel/_right'); ?></div>
<div class="top-panel-wrapper" id="measurement_wrapper"><?php $this->load->view('pages/component/panel/_top'); ?></div>