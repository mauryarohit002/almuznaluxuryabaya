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
            <p class="text-uppercase">customer</p>
            <select class="form-control floating-select" id="_customer_name" name="_customer_name">
                <?php if(isset($filters['_customer_name']) && !empty($filters['_customer_name'])): ?>
                    <option value="<?php echo $filters['_customer_name']['value']; ?>" selected>
                        <?php echo $filters['_customer_name']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">mobile</p>
            <select class="form-control floating-select" id="_customer_mobile" mobile="_customer_mobile">
                <?php if(isset($filters['_customer_mobile']) && !empty($filters['_customer_mobile'])): ?>
                    <option value="<?php echo $filters['_customer_mobile']['value']; ?>" selected>
                        <?php echo $filters['_customer_mobile']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">delivery group</p>
            <select class="form-control floating-select" id="_apparel_group" mobile="_apparel_group">
                <?php if(isset($filters['_apparel_group']) && !empty($filters['_apparel_group'])): ?>
                    <option value="<?php echo $filters['_apparel_group']['value']; ?>" selected>
                        <?php echo $filters['_apparel_group']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label mt-3">
            <p class="text-uppercase">apparel</p>
            <select class="form-control floating-select" id="_apparel_name" name="_apparel_name[]" multiple="multiple">
                <?php if(isset($filters['_apparel_name']) && !empty($filters['_apparel_name'])): ?>
                    <?php foreach ($filters['_apparel_name'] as $key => $value): ?>
                        <option value="<?php echo $value; ?>" selected><?php echo $value; ?> </option>
                    <?php endforeach;?>
                <?php endif; ?>
            </select>
        </div>
        <div class="d-flex mt-3">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                <input 
                    type="date" 
                    class="form-control floating-input" 
                    id="_trial_date_from" 
                    name="_trial_date_from" 
                    value="<?php echo isset($filters['_trial_date_from']) ? $filters['_trial_date_from'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">trial date <small class="font-weight-bold">from</small></label>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                <input 
                    type="date" 
                    class="form-control floating-input" 
                    id="_trial_date_to" 
                    name="_trial_date_to" 
                    value="<?php echo isset($filters['_trial_date_to']) ? $filters['_trial_date_to'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">trial date <small class="font-weight-bold">to</small></label>
            </div>
        </div>
        <div class="d-flex mt-3">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                <input 
                    type="date" 
                    class="form-control floating-input" 
                    id="_delivery_date_from" 
                    name="_delivery_date_from" 
                    value="<?php echo isset($filters['_delivery_date_from']) ? $filters['_delivery_date_from'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">delivery date <small class="font-weight-bold">from</small></label>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                <input 
                    type="date" 
                    class="form-control floating-input" 
                    id="_delivery_date_to" 
                    name="_delivery_date_to" 
                    value="<?php echo isset($filters['_delivery_date_to']) ? $filters['_delivery_date_to'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">delivery date <small class="font-weight-bold">to</small></label>
            </div>
        </div>
    </div>
</div>