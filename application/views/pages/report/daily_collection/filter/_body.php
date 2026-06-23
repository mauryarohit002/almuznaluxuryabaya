<div class="row">
    <div class="d-flex flex-wrap floating-form">
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">module</p>
            <select class="form-control floating-select" id="_module_name" name="_module_name">
                <?php if(isset($filters['_module_name']) && !empty($filters['_module_name'])): ?>
                    <option value="<?php echo $filters['_module_name']['value']; ?>" selected>
                        <?php echo $filters['_module_name']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">payment mode</p>
            <select class="form-control floating-select" id="_payment_mode_name" name="_payment_mode_name">
                <?php if(isset($filters['_payment_mode_name']) && !empty($filters['_payment_mode_name'])): ?>
                    <option value="<?php echo $filters['_payment_mode_name']['value']; ?>" selected>
                        <?php echo $filters['_payment_mode_name']['text']; ?> 
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
                    id="_payment_mode_amt_from" 
                    name="_payment_mode_amt_from" 
                    value="<?php echo isset($filters['_payment_mode_amt_from']) ? $filters['_payment_mode_amt_from'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">total mtr <small class="font-weight-bold">from</small></label>
            </div>
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_payment_mode_amt_to" 
                    name="_payment_mode_amt_to" 
                    value="<?php echo isset($filters['_payment_mode_amt_to']) ? $filters['_payment_mode_amt_to'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">total mtr <small class="font-weight-bold">to</small></label>
            </div>
        </div>
    </div>
</div>