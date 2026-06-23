<div class="row">
    <div class="d-flex flex-wrap floating-form">
        <div class="d-flex col-12 col-sm-12 col-md-6 col-lg-6 mt-3">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label">
                <input 
                    type="date" 
                    class="form-control floating-input" 
                    id="_date_from" 
                    name="_date_from" 
                    value="<?php echo isset($filters['_date_from']) ? $filters['_date_from'] : date('Y-m-d') ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">delivery date <small class="font-weight-bold">from</small></label>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label">
                <input 
                    type="date" 
                    class="form-control floating-input" 
                    id="_date_to" 
                    name="_date_to" 
                    value="<?php echo isset($filters['_date_to']) ? $filters['_date_to'] : date('Y-m-d') ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">delivery date <small class="font-weight-bold">to</small></label>
            </div>
        </div>
    </div>
</div>