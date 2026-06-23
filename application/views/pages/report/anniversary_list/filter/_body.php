<div class="row">
    <div class="d-flex flex-wrap floating-form">
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
        <div class="d-flex col-12 col-sm-12 col-md-6 col-lg-6 mt-3">
            <div class="floating-label">
                <input 
                    type="date" 
                    class="form-control floating-input" 
                    id="_anniversary_date_from" 
                    name="_anniversary_date_from" 
                    value="<?php echo isset($filters['_anniversary_date_from']) ? $filters['_anniversary_date_from'] : date('Y-m-d') ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">anniversary date <small class="font-weight-bold">from</small></label>
            </div>
            <div class="floating-label">
                <input 
                    type="date" 
                    class="form-control floating-input" 
                    id="_anniversary_date_to" 
                    name="_anniversary_date_to" 
                    value="<?php echo isset($filters['_anniversary_date_to']) ? $filters['_anniversary_date_to'] : date('Y-m-d') ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">anniversary date <small class="font-weight-bold">to</small></label>
            </div>
        </div>
    </div>
</div>