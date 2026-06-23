<?php 
    $supplier_action= get_action_data('master', 'supplier');
    $id             = empty($master_data) ? 0 : $master_data[0]['prmm_id'];
    $uuid           = empty($master_data) ? $prmm_uuid : $master_data[0]['prmm_uuid'];
    $tabindex       = 1;
?>
<style>  
   .floating-label { 
       margin-bottom:8px !important;
  }
</style> 
<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
        <div class="d-flex flex-wrap mb-2">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                <div class="card mb-3">
                    <div class="card-header text-uppercase">general detail</div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap pt-2 form-group floating-form">
                            <div class="col-12 col-sm-12 col-md-6 col-lg-3 floating-label">
                                <input 
                                    type="hidden" 
                                    id="id" 
                                    name="id" 
                                    value="<?php echo $id; ?>"
                                />
                                <input 
                                    type="hidden" 
                                    id="prmm_uuid" 
                                    name="prmm_uuid" 
                                    value="<?php echo $uuid; ?>"
                                />
                                <input 
                                    type="hidden" 
                                    id="prmt_id" 
                                    name="prmt_id" 
                                    value="0"
                                />
                                <input 
                                    type="hidden" 
                                    id="prmm_bill_no" 
                                    name="prmm_bill_no" 
                                    value="<?php echo empty($master_data) ? $prmm_entry_no : $master_data[0]['prmm_bill_no'] ?>" 
                                />   
                                <input 
                                    type="hidden"  
                                    id="prmm_bill_date" 
                                    name="prmm_bill_date" 
                                    value="<?php echo empty($master_data) ? date('Y-m-d') : date('Y-m-d', strtotime($master_data[0]['prmm_bill_date'])) ?>" 
                                />   
                                <input 
                                    type="number" 
                                    class="form-control floating-input" 
                                    id="prmm_entry_no" 
                                    name="prmm_entry_no" 
                                    value="<?php echo empty($master_data) ? $prmm_entry_no : $master_data[0]['prmm_entry_no'] ?>" 
                                    placeholder=" " 
                                    readonly="readonly" 
                                />   
                                <label class="text-uppercase">entry no</label>
                                <small class="form-text text-muted helper-text" id="prmm_entry_no_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-3 floating-label">
                                <input 
                                    type="date" 
                                    class="form-control floating-input" 
                                    id="prmm_entry_date" 
                                    name="prmm_entry_date" 
                                    value="<?php echo empty($master_data) ? date('Y-m-d') : date('Y-m-d', strtotime($master_data[0]['prmm_entry_date'])) ?>" 
                                    placeholder=" " 
                                    readonly="readonly" 
                                />   
                                <label class="text-uppercase">entry date</label>
                                <small class="form-text text-muted helper-text" id="prmm_entry_date_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                                <p class="text-uppercase">supplier&nbsp;<span class="text-danger">*</span>
                                    <?php if(empty($master_data)): ?>
                                        <?php if(in_array('add', $supplier_action)): ?>
                                            <span>
                                                <a
                                                    data-toggle="tooltip"
                                                    data-placement="top"
                                                    title="ADD SUPPLIER"
                                                    style="cursor: pointer;"
                                                    onclick='supplier_popup(<?php echo json_encode([]) ?>)'
                                                ><i class="fa fa-plus"></i></a>
                                            </span>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                </p>
                                <select 
                                    class="form-control floating-select" 
                                    id="prmm_supplier_id" 
                                    name="prmm_supplier_id" 
                                    placeholder=" " 
                                    tabindex= "<?php echo $tabindex++; ?>"
                                    onchange="validate_dropdown(this)" 
                                    <?php echo (!empty($master_data) && $master_data[0]['isExist']) ? 'disabled="disabled"' : ''; ?>
                                >
                                    <?php if(!empty($master_data) && !empty($master_data[0]['prmm_supplier_id'])): ?>
                                        <option value="<?php echo $master_data[0]['prmm_supplier_id'] ?>" selected>
                                            <?php echo $master_data[0]['supplier_name']; ?> 
                                            <input type="hidden" name="prmm_supplier_id" value="<?php echo $master_data[0]['prmm_supplier_id']; ?>" />
                                        </option>
                                    <?php endif; ?>
                                </select>
                                <small class="form-text text-muted helper-text" id="prmm_supplier_id_msg"></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                <div class="card mb-3">
                    <div class="card-header text-uppercase">amt detail</div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap pt-2 mb-0 form-group floating-form">
                            <div class="col-12 col-sm-12 col-md-3 col-lg-2 floating-label">
                                <input 
                                    type="number" 
                                    class="form-control floating-input" 
                                    id="prmm_total_qty" 
                                    name="prmm_total_qty" 
                                    value="<?php echo empty($master_data) ? 0 : $master_data[0]['prmm_total_qty'] ?>" 
                                    placeholder=" " 
                                    readonly="readonly" 
                                />   
                                <label class="text-uppercase">total qty</label>
                                <small class="form-text text-muted helper-text" id="prmm_total_qty_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-3 col-lg-2 floating-label">
                                <input 
                                    type="number" 
                                    class="form-control floating-input" 
                                    id="prmm_sub_amt" 
                                    name="prmm_sub_amt" 
                                    value="<?php echo empty($master_data) ? 0 : $master_data[0]['prmm_sub_amt'] ?>" 
                                    placeholder=" " 
                                    readonly="readonly" 
                                />   
                                <label class="text-uppercase">gross amt</label>
                                <small class="form-text text-muted helper-text" id="prmm_sub_amt_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-3 col-lg-2 floating-label d-none">
                                <input 
                                    type="number" 
                                    class="form-control floating-input" 
                                    id="prmm_taxable_amt" 
                                    name="prmm_taxable_amt" 
                                    value="<?php echo empty($master_data) ? 0 : $master_data[0]['prmm_taxable_amt'] ?>" 
                                    readonly
                                />   
                                <label class="text-uppercase">taxable amt</label>
                                <small class="form-text text-muted helper-text" id="prmm_taxable_amt_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-3 col-lg-2 floating-label">
                                <input 
                                    type="number" 
                                    class="form-control floating-input" 
                                    id="prmm_extra_amt" 
                                    name="prmm_extra_amt" 
                                    value="<?php echo empty($master_data) ? 0 : $master_data[0]['prmm_extra_amt'] ?>" 
                                    readonly
                                />   
                                <label class="text-uppercase">Extra amt</label>
                                <small class="form-text text-muted helper-text" id="prmm_extra_amt_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-3 col-lg-3 floating-label">
                                <input 
                                    type="number" 
                                    class="form-control floating-input font-weight-bold" 
                                    id="prmm_total_amt" 
                                    name="prmm_total_amt" 
                                    value="<?php echo empty($master_data) ? 0 : $master_data[0]['prmm_total_amt'] ?>" 
                                    placeholder=" " 
                                    readonly
                                    style="font-size: 1.5rem;"
                                />   
                                <label class="text-uppercase">net amt</label>
                                <small class="form-text text-muted helper-text" id="prmm_total_amt_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-3 floating-label">
                                <textarea
                                    class="form-control floating-textarea"
                                    id="prmm_notes"
                                    name="prmm_notes"
                                    placeholder=" "
                                    autocomplete="off"
                                    tabindex= "<?php echo $tabindex++; ?>"
                                ><?php echo empty($master_data) ? '' : $master_data[0]['prmm_notes']; ?></textarea>
                                <label class="text-uppercase">notes</label>
                                <small class="form-text text-muted helper-text" id="prmm_notes_msg"></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header text-uppercase">
                        <h5 class="mb-0">
                            <a 
                                type="button" 
                                class="btn btn-sm btn-secondary" 
                                id="add_product_tabs"
                                data-toggle="collapse" 
                                data-target="#add_product_tab" 
                                aria-expanded="true" 
                                aria-controls="add_product_tab"
                            >add product</a>
                        </h5>
                    </div>
                    <div id="add_product_tab" class="collapse show" aria-labelledby="add_product_tabs" data-parent="#accordion">
                        <div class="card-body">
                            <div class="d-flex form-group pt-3" style="overflow-y: auto;">
                                    <div class="col-12 col-sm-12 col-md-4 col-lg-3 d-flex floating-form">
                                         <input 
                                                type="hidden" 
                                                id="cost_char" 
                                                name="cost_char" 
                                                value=" "
                                            />
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">

                                             <p class="text-uppercase">Sku&nbsp;<span class="text-danger">*</span>
                                                        <!--<span>-->
                                                        <!--    <a-->
                                                        <!--        data-toggle="tooltip"-->
                                                        <!--        data-placement="top"-->
                                                        <!--        title="ADD NEW SKU (Alt + S)"-->
                                                        <!--        style="cursor: pointer;"-->
                                                        <!--        onclick="openSkuModal()"-->
                                                        <!--    ><i class="fa fa-plus"></i></a>-->
                                                        <!--</span>-->
                                            </p>

                                            <div class="input-group">
                                                <select 
                                                    class="form-control floating-select" 
                                                    id="sku_id" 
                                                    name="sku_id" 
                                                    placeholder=" "
                                                    onchange="validate_dropdown(this)"  
                                                    tabindex="<?php echo $tabindex++; ?>"
                                                ></select>
                                            </div>

                                            <small class="form-text text-muted helper-text" id="sku_id_msg"></small>
                                        </div>

                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">

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


                                        <div class="col-12 col-sm-12 col-md-3 col-lg-3 floating-label">
                                            <p class="text-uppercase">qty&nbsp;<span class="text-danger">*</span></p> 
                                            <input 
                                                type="number" 
                                                class="form-control floating-input" 
                                                id="qty" 
                                                name="qty" 
                                                value="1" 
                                                onkeyup="calculate_transaction()" 
                                                placeholder="" 
                                                autocomplete="off"
                                                tabindex= "<?php echo $tabindex++; ?>"
                                                min="0" 
                                                oninput="this.value = Math.abs(this.value)"  
                                            />
                                            <small class="form-text text-muted helper-text" id="qty_msg"></small>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-5 col-lg-5 floating-label">
                                            <p class="text-uppercase">MRP&nbsp;</p> 
                                            <input 
                                                type="number" 
                                                class="form-control floating-input" 
                                                id="mrp" 
                                                name="mrp" 
                                                value="0" 
                                                placeholder="" 
                                                autocomplete="off"
                                                tabindex= "<?php echo $tabindex++; ?>"
                                            />
                                            <small class="form-text text-muted helper-text" id="mrp_msg"></small>
                                        </div>

                                        <div class="col-12 col-sm-12 col-md-5 col-lg-5 floating-label">
                                            <p class="text-uppercase">pur.&nbsp;price<span class="text-danger">*</span></p> 
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
                                        <div class="col-12 col-sm-12 col-md-5 col-lg-5 floating-label">
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
                                
                                    <div class="col-12 col-sm-12 col-md-5 col-lg-5 floating-label d-none">
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

                                    <div class="col-12 col-sm-12 col-md-5 col-lg-5 floating-label">
                                        <p class="text-uppercase">extra amt</p> 
                                        <input  
                                            type="number" 
                                            class="form-control floating-input" 
                                            id="extra_amt"
                                            name="extra_amt"
                                            onkeyup="calculate_transaction()"
                                            value="0"
                                        />
                                        <small class="form-text text-muted helper-text" id="extra_amt_msg"></small>

                                         <input  
                                            type="hidden" 
                                            class="form-control floating-input" 
                                            id="actual_taxable_amt"
                                            name="actual_taxable_amt"
                                            value="0"
                                        />
                                    </div>
                                    
                                    <div class="col-12 col-sm-12 col-md-5 col-lg-5 floating-label">
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
                                    <div class="col-12 col-sm-12 col-md-5 col-lg-5 floating-label">
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
                                    <div class="col-12 col-sm-12 col-md-4 col-lg-4" >
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
                                        <?php 
                                            if(!empty($cost_char)): 
                                                foreach ([1, 2, 3, 4, 5, 6, 7, 8, 9, 0] as $key => $value):
                                        ?>
                                                    <input
                                                        type="hidden"
                                                        id="cost_char_<?php echo $value; ?>"
                                                        value="<?php echo $cost_char[0]['cost_char_'.$value]; ?>"
                                                    />
                                        <?php 
                                                endforeach;
                                            endif; 
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex flex-wrap">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 p-0">
                <div class="card">
                    <div class="card-header text-uppercase">
                        <h5 class="mb-0">
                            <a 
                                type="button" 
                                class="btn btn-sm btn-secondary" 
                                id="added_product_tabs"
                                data-toggle="collapse" 
                                data-target="#added_product_tab" 
                                aria-expanded="true" 
                                aria-controls="added_product_tab"
                            >added product list (<span id="transaction_count">0</span>)</a>
                        </h5>
                    </div>
                    <div id="added_product_tab" class="collapse show" aria-labelledby="added_product_tabs" data-parent="#accordion">
                        <div class="card-body p-0" style="max-width:100vw; max-height:50vh; overflow:auto;" id="div_wrapper">
                            <table class="table table-sm text-uppercase">
                                <tbody class="table-dark border-0">
                                    <tr style="font-weight:bold; font-size: 0.8rem;">
                                        <td class="border-bottom border-top-0" >sku&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td class="border-bottom border-top-0" >size&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td class="border-bottom border-top-0" >MRP&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td class="border-bottom border-top-0" >qty&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td class="border-bottom border-top-0" >purchase price&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td class="border-bottom border-top-0" >amt&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td class="border-bottom border-top-0 d-none" >taxable&nbsp;amt&nbsp;</td>
                                        <td class="border-bottom border-top-0" >extra&nbsp;amt&nbsp;</td>
                                        <td class="border-bottom border-top-0" >total&nbsp;amt&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td class="border-bottom border-top-0" >description&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td class="border-bottom border-top-0" >actions</td>
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