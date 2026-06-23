<div class="row">
    <div class="d-flex flex-wrap floating-form">
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">company</p>
            <select class="form-control floating-select select2" id="_company_name" name="_company_name">
                <option value="" <?php echo ((isset($filters['_company_name'])) && ($filters['_company_name']['value'] == '')) ? 'selected' : ''; ?>>ALL</option>
                <option value="stitching" <?php echo ((isset($filters['_company_name'])) && ($filters['_company_name']['value'] == 'stitching')) ? 'selected' : ''; ?>>MK CREATION</option>
                <option value="fabric" <?php echo ((isset($filters['_company_name'])) && ($filters['_company_name']['value'] == 'fabric')) ? 'selected' : ''; ?>>LR FASHION</option>
            </select>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">apparel</p>
            <select class="form-control floating-select" id="_apparel_name" name="_apparel_name">
                <?php if(isset($filters['_apparel_name']) && !empty($filters['_apparel_name'])): ?>
                    <option value="<?php echo $filters['_apparel_name']['value']; ?>" selected>
                        <?php echo $filters['_apparel_name']['text']; ?> 
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
                    id="_amt_from" 
                    name="_amt_from" 
                    value="<?php echo isset($filters['_amt_from']) ? $filters['_amt_from'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">amt <small class="font-weight-bold">from</small></label>
            </div>
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_amt_to" 
                    name="_amt_to" 
                    value="<?php echo isset($filters['_amt_to']) ? $filters['_amt_to'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">amt <small class="font-weight-bold">to</small></label>
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