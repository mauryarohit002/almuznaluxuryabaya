<div class="row">
    <div class="d-flex flex-wrap floating-form">
        
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">bill no</p>
            <select class="form-control floating-select" id="_bill_no" name="_bill_no">
                <?php if(isset($filters['_bill_no']) && !empty($filters['_bill_no'])): ?>
                    <option value="<?php echo $filters['_bill_no']['value']; ?>" selected>
                        <?php echo $filters['_bill_no']['text']; ?> 
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
            <p class="text-uppercase">mobile</p>
            <select class="form-control floating-select" id="_supplier_mobile" name="_supplier_mobile">
                <?php if(isset($filters['_supplier_mobile']) && !empty($filters['_supplier_mobile'])): ?>
                    <option value="<?php echo $filters['_supplier_mobile']['value']; ?>" selected>
                        <?php echo $filters['_supplier_mobile']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
        <div class="d-flex col-12 col-sm-12 col-md-6 col-lg-6 mt-3">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                <input 
                    type="date" 
                    class="form-control floating-input" 
                    id="_bill_date_from" 
                    name="_bill_date_from" 
                    value="<?php echo isset($filters['_bill_date_from']) ? $filters['_bill_date_from'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">bill date <small class="font-weight-bold">from</small></label>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                <input 
                    type="date" 
                    class="form-control floating-input" 
                    id="_bill_date_to" 
                    name="_bill_date_to" 
                    value="<?php echo isset($filters['_bill_date_to']) ? $filters['_bill_date_to'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">bill date <small class="font-weight-bold">to</small></label>
            </div>
        </div>
  
    </div>
</div>