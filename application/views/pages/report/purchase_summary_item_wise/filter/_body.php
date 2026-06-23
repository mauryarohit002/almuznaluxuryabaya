<div class="row">
    <div class="d-flex flex-wrap floating-form">
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
            <p class="text-uppercase">supplier</p>
            <select class="form-control floating-select" id="_supplier_name" name="_supplier_name">
                <?php if(isset($filters['_supplier_name']) && !empty($filters['_supplier_name'])): ?>
                    <option value="<?php echo $filters['_supplier_name']['value']; ?>" selected>
                        <?php echo $filters['_supplier_name']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">fabric</p>
            <select class="form-control floating-select" id="_fabric_name" name="_fabric_name">
                <?php if(isset($filters['_fabric_name']) && !empty($filters['_fabric_name'])): ?>
                    <option value="<?php echo $filters['_fabric_name']['value']; ?>" selected>
                        <?php echo $filters['_fabric_name']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">design</p>
            <select class="form-control floating-select" id="_design_name" name="_design_name">
                <?php if(isset($filters['_design_name']) && !empty($filters['_design_name'])): ?>
                    <option value="<?php echo $filters['_design_name']['value']; ?>" selected>
                        <?php echo $filters['_design_name']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">color</p>
            <select class="form-control floating-select" id="_color_name" name="_color_name">
                <?php if(isset($filters['_color_name']) && !empty($filters['_color_name'])): ?>
                    <option value="<?php echo $filters['_color_name']['value']; ?>" selected>
                        <?php echo $filters['_color_name']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
       
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">hsn</p>
            <select class="form-control floating-select" id="_hsn_name" name="_hsn_name">
                <?php if(isset($filters['_hsn_name']) && !empty($filters['_hsn_name'])): ?>
                    <option value="<?php echo $filters['_hsn_name']['value']; ?>" selected>
                        <?php echo $filters['_hsn_name']['text']; ?> 
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
        <div class="d-flex col-12 col-sm-12 col-md-6 col-lg-6 mt-3">
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_qty_from" 
                    name="_qty_from" 
                    value="<?php echo isset($filters['_qty_from']) ? $filters['_qty_from'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">qty <small class="font-weight-bold">from</small></label>
            </div>
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_qty_to" 
                    name="_qty_to" 
                    value="<?php echo isset($filters['_qty_to']) ? $filters['_qty_to'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">qty <small class="font-weight-bold">to</small></label>
            </div>
        </div>
        <div class="d-flex col-12 col-sm-12 col-md-6 col-lg-6 mt-3">
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_mtr_from" 
                    name="_mtr_from" 
                    value="<?php echo isset($filters['_mtr_from']) ? $filters['_mtr_from'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">mtr <small class="font-weight-bold">from</small></label>
            </div>
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_mtr_to" 
                    name="_mtr_to" 
                    value="<?php echo isset($filters['_mtr_to']) ? $filters['_mtr_to'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">mtr <small class="font-weight-bold">to</small></label>
            </div>
        </div>
        <div class="d-flex col-12 col-sm-12 col-md-6 col-lg-6 mt-3">
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
        </div>
        <div class="d-flex col-12 col-sm-12 col-md-6 col-lg-6 mt-3">
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_rate_from" 
                    name="_rate_from" 
                    value="<?php echo isset($filters['_rate_from']) ? $filters['_rate_from'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">rate <small class="font-weight-bold">from</small></label>
            </div>
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_rate_to" 
                    name="_rate_to" 
                    value="<?php echo isset($filters['_rate_to']) ? $filters['_rate_to'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">rate <small class="font-weight-bold">to</small></label>
            </div>
        </div>
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
    </div>
</div>