<div class="row">
    <div class="d-flex flex-wrap floating-form">
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
            <p class="text-uppercase">fabric code</p>
            <select class="form-control floating-select" id="_design_name" name="_design_name">
                <?php if(isset($filters['_design_name']) && !empty($filters['_design_name'])): ?>
                    <option value="<?php echo $filters['_design_name']['value']; ?>" selected>
                        <?php echo $filters['_design_name']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">category</p>
            <select class="form-control floating-select" id="_category_name" name="_category_name">
                <?php if(isset($filters['_category_name']) && !empty($filters['_category_name'])): ?>
                    <option value="<?php echo $filters['_category_name']['value']; ?>" selected>
                        <?php echo $filters['_category_name']['text']; ?> 
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
            <p class="text-uppercase">width</p>
            <select class="form-control floating-select" id="_width_name" name="_width_name">
                <?php if(isset($filters['_width_name']) && !empty($filters['_width_name'])): ?>
                    <option value="<?php echo $filters['_width_name']['value']; ?>" selected>
                        <?php echo $filters['_width_name']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">description</p>
            <select class="form-control floating-select" id="_description" name="_description">
                <?php if(isset($filters['_description']) && !empty($filters['_description'])): ?>
                    <option value="<?php echo $filters['_description']['value']; ?>" selected>
                        <?php echo $filters['_description']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
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
                    id="_mrp_from" 
                    name="_mrp_from" 
                    value="<?php echo isset($filters['_mrp_from']) ? $filters['_mrp_from'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">mrp <small class="font-weight-bold">from</small></label>
            </div>
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_mrp_to" 
                    name="_mrp_to" 
                    value="<?php echo isset($filters['_mrp_to']) ? $filters['_mrp_to'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">mrp <small class="font-weight-bold">to</small></label>
            </div>
        </div>
        <div class="d-flex col-12 col-sm-12 col-md-6 col-lg-6 mt-3">
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_pt_mtr_from" 
                    name="_pt_mtr_from" 
                    value="<?php echo isset($filters['_pt_mtr_from']) ? $filters['_pt_mtr_from'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">pur mtr <small class="font-weight-bold">from</small></label>
            </div>
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_pt_mtr_to" 
                    name="_pt_mtr_to" 
                    value="<?php echo isset($filters['_pt_mtr_to']) ? $filters['_pt_mtr_to'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">pur mtr <small class="font-weight-bold">to</small></label>
            </div>
        </div>
        <div class="d-flex col-12 col-sm-12 col-md-6 col-lg-6 mt-3">
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_ot_mtr_from" 
                    name="_ot_mtr_from" 
                    value="<?php echo isset($filters['_ot_mtr_from']) ? $filters['_ot_mtr_from'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">order mtr <small class="font-weight-bold">from</small></label>
            </div>
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_ot_mtr_to" 
                    name="_ot_mtr_to" 
                    value="<?php echo isset($filters['_ot_mtr_to']) ? $filters['_ot_mtr_to'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">order mtr <small class="font-weight-bold">to</small></label>
            </div>
        </div>
        <div class="d-flex col-12 col-sm-12 col-md-6 col-lg-6 mt-3">
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_bal_mtr_from" 
                    name="_bal_mtr_from" 
                    value="<?php echo isset($filters['_bal_mtr_from']) ? $filters['_bal_mtr_from'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">bal mtr <small class="font-weight-bold">from</small></label>
            </div>
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_bal_mtr_to" 
                    name="_bal_mtr_to" 
                    value="<?php echo isset($filters['_bal_mtr_to']) ? $filters['_bal_mtr_to'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">bal mtr <small class="font-weight-bold">to</small></label>
            </div>
        </div>
        <div class="d-flex col-12 col-sm-12 col-md-6 col-lg-6 mt-3">
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_bal_amt_from" 
                    name="_bal_amt_from" 
                    value="<?php echo isset($filters['_bal_amt_from']) ? $filters['_bal_amt_from'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">bal amt <small class="font-weight-bold">from</small></label>
            </div>
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_bal_amt_to" 
                    name="_bal_amt_to" 
                    value="<?php echo isset($filters['_bal_amt_to']) ? $filters['_bal_amt_to'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">bal amt <small class="font-weight-bold">to</small></label>
            </div>
        </div>
    </div>
</div>