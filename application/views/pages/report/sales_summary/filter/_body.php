<div class="row">
    <div class="d-flex flex-wrap floating-form">
        <!-- <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">module</p>
            <select class="form-control floating-select" id="_module_name" name="_module_name">
                <?php if(isset($filters['_module_name']) && !empty($filters['_module_name'])): ?>
                    <option value="<?php echo $filters['_module_name']['value']; ?>" selected>
                        <?php echo $filters['_module_name']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div> -->
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">entry no</p>
            <select class="form-control floating-select" id="_entry_no" name="_entry_no">
                <?php if(isset($filters['_entry_no']) && !empty($filters['_entry_no'])): ?>
                    <option value="<?php echo $filters['_entry_no']['value']; ?>" selected>
                        <?php echo $filters['_entry_no']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">billing</p>
            <select class="form-control floating-select" id="_billing_name" name="_billing_name">
                <?php if(isset($filters['_billing_name']) && !empty($filters['_billing_name'])): ?>
                    <option value="<?php echo $filters['_billing_name']['value']; ?>" selected>
                        <?php echo $filters['_billing_name']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
         <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">sales man</p>
            <select class="form-control floating-select" id="_user_name" name="_user_name">
                <?php if(isset($filters['_user_name']) && !empty($filters['_user_name'])): ?>
                    <option value="<?php echo $filters['_user_name']['value']; ?>" selected>
                        <?php echo $filters['_user_name']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">mobile</p>
            <select class="form-control floating-select" id="_billing_mobile" name="_billing_mobile">
                <?php if(isset($filters['_billing_mobile']) && !empty($filters['_billing_mobile'])): ?>
                    <option value="<?php echo $filters['_billing_mobile']['value']; ?>" selected>
                        <?php echo $filters['_billing_mobile']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
        <div class="d-flex col-12 col-sm-12 col-md-6 col-lg-6 mt-3">
            <div class="floating-label">
                <input 
                    type="date" 
                    class="form-control floating-input" 
                    id="_entry_date_from" 
                    name="_entry_date_from" 
                    value="<?php echo isset($filters['_entry_date_from']) ? $filters['_entry_date_from'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">entry date <small class="font-weight-bold">from</small></label>
            </div>
            <div class="floating-label">
                <input 
                    type="date" 
                    class="form-control floating-input" 
                    id="_entry_date_to" 
                    name="_entry_date_to" 
                    value="<?php echo isset($filters['_entry_date_to']) ? $filters['_entry_date_to'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">entry date <small class="font-weight-bold">to</small></label>
            </div>
        </div>
        <!-- <div class="d-flex col-12 col-sm-12 col-md-6 col-lg-6 mt-3">
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_total_mtr_from" 
                    name="_total_mtr_from" 
                    value="<?php echo isset($filters['_total_mtr_from']) ? $filters['_total_mtr_from'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">total mtr <small class="font-weight-bold">from</small></label>
            </div>
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_total_mtr_to" 
                    name="_total_mtr_to" 
                    value="<?php echo isset($filters['_total_mtr_to']) ? $filters['_total_mtr_to'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">total mtr <small class="font-weight-bold">to</small></label>
            </div>
        </div> -->
        <div class="d-flex col-12 col-sm-12 col-md-6 col-lg-6 mt-3">
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_sub_amt_from" 
                    name="_sub_amt_from" 
                    value="<?php echo isset($filters['_sub_amt_from']) ? $filters['_sub_amt_from'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">sub amt <small class="font-weight-bold">from</small></label>
            </div>
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_sub_amt_to" 
                    name="_sub_amt_to" 
                    value="<?php echo isset($filters['_sub_amt_to']) ? $filters['_sub_amt_to'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">sub amt <small class="font-weight-bold">to</small></label>
            </div>
        </div>
        <div class="d-flex col-12 col-sm-12 col-md-6 col-lg-6 mt-3">
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_disc_amt_from" 
                    name="_disc_amt_from" 
                    value="<?php echo isset($filters['_disc_amt_from']) ? $filters['_disc_amt_from'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">disc amt <small class="font-weight-bold">from</small></label>
            </div>
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_disc_amt_to" 
                    name="_disc_amt_to" 
                    value="<?php echo isset($filters['_disc_amt_to']) ? $filters['_disc_amt_to'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">disc amt <small class="font-weight-bold">to</small></label>
            </div>
        </div>
        <div class="d-flex col-12 col-sm-12 col-md-6 col-lg-6 mt-3">
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_taxable_amt_from" 
                    name="_taxable_amt_from" 
                    value="<?php echo isset($filters['_taxable_amt_from']) ? $filters['_taxable_amt_from'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">taxable amt <small class="font-weight-bold">from</small></label>
            </div>
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_taxable_amt_to" 
                    name="_taxable_amt_to" 
                    value="<?php echo isset($filters['_taxable_amt_to']) ? $filters['_taxable_amt_to'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">taxable amt <small class="font-weight-bold">to</small></label>
            </div>
        </div>
        <div class="d-flex col-12 col-sm-12 col-md-6 col-lg-6 mt-3">
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_sgst_amt_from" 
                    name="_sgst_amt_from" 
                    value="<?php echo isset($filters['_sgst_amt_from']) ? $filters['_sgst_amt_from'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">sgst amt <small class="font-weight-bold">from</small></label>
            </div>
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_sgst_amt_to" 
                    name="_sgst_amt_to" 
                    value="<?php echo isset($filters['_sgst_amt_to']) ? $filters['_sgst_amt_to'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">sgst amt <small class="font-weight-bold">to</small></label>
            </div>
        </div>
        <div class="d-flex col-12 col-sm-12 col-md-6 col-lg-6 mt-3">
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_cgst_amt_from" 
                    name="_cgst_amt_from" 
                    value="<?php echo isset($filters['_cgst_amt_from']) ? $filters['_cgst_amt_from'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">cgst amt <small class="font-weight-bold">from</small></label>
            </div>
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_cgst_amt_to" 
                    name="_cgst_amt_to" 
                    value="<?php echo isset($filters['_cgst_amt_to']) ? $filters['_cgst_amt_to'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">cgst amt <small class="font-weight-bold">to</small></label>
            </div>
        </div>
        <div class="d-flex col-12 col-sm-12 col-md-6 col-lg-6 mt-3">
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_igst_amt_from" 
                    name="_igst_amt_from" 
                    value="<?php echo isset($filters['_igst_amt_from']) ? $filters['_igst_amt_from'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">igst amt <small class="font-weight-bold">from</small></label>
            </div>
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_igst_amt_to" 
                    name="_igst_amt_to" 
                    value="<?php echo isset($filters['_igst_amt_to']) ? $filters['_igst_amt_to'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">igst amt <small class="font-weight-bold">to</small></label>
            </div>
        </div>
        <div class="d-flex col-12 col-sm-12 col-md-6 col-lg-6 mt-3">
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_bill_disc_from" 
                    name="_bill_disc_from" 
                    value="<?php echo isset($filters['_bill_disc_from']) ? $filters['_bill_disc_from'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">bill disc <small class="font-weight-bold">from</small></label>
            </div>
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_bill_disc_to" 
                    name="_bill_disc_to" 
                    value="<?php echo isset($filters['_bill_disc_to']) ? $filters['_bill_disc_to'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">bill disc <small class="font-weight-bold">to</small></label>
            </div>
        </div>
        <div class="d-flex col-12 col-sm-12 col-md-6 col-lg-6 mt-3">
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_total_amt_from" 
                    name="_total_amt_from" 
                    value="<?php echo isset($filters['_total_amt_from']) ? $filters['_total_amt_from'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">total amt <small class="font-weight-bold">from</small></label>
            </div>
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_total_amt_to" 
                    name="_total_amt_to" 
                    value="<?php echo isset($filters['_total_amt_to']) ? $filters['_total_amt_to'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">total amt <small class="font-weight-bold">to</small></label>
            </div>
        </div>
        <div class="d-flex col-12 col-sm-12 col-md-6 col-lg-6 mt-3">
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_advance_amt_from" 
                    name="_advance_amt_from" 
                    value="<?php echo isset($filters['_advance_amt_from']) ? $filters['_advance_amt_from'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">advance amt <small class="font-weight-bold">from</small></label>
            </div>
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_advance_amt_to" 
                    name="_advance_amt_to" 
                    value="<?php echo isset($filters['_advance_amt_to']) ? $filters['_advance_amt_to'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">advance amt <small class="font-weight-bold">to</small></label>
            </div>
        </div>
        <div class="d-flex col-12 col-sm-12 col-md-6 col-lg-6 mt-3">
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_balance_amt_from" 
                    name="_balance_amt_from" 
                    value="<?php echo isset($filters['_balance_amt_from']) ? $filters['_balance_amt_from'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">balance amt <small class="font-weight-bold">from</small></label>
            </div>
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_balance_amt_to" 
                    name="_balance_amt_to" 
                    value="<?php echo isset($filters['_balance_amt_to']) ? $filters['_balance_amt_to'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">balance amt <small class="font-weight-bold">to</small></label>
            </div>
        </div>
    </div>
</div>