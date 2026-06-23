<div class="row">
    <div class="d-flex flex-wrap floating-form">
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">karigar</p>
            <select class="form-control floating-select" id="_karigar_name" name="_karigar_name">
                <?php if(isset($filters['_karigar_name']) && !empty($filters['_karigar_name'])): ?>
                    <option value="<?php echo $filters['_karigar_name']['value']; ?>" selected>
                        <?php echo $filters['_karigar_name']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
        <div class="d-flex flex-wrap col-12 col-sm-12 col-md-6 col-lg-6 mt-3">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_close_amt_from" 
                    name="_close_amt_from" 
                    value="<?php echo isset($filters['_close_amt_from']) ? $filters['_close_amt_from'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">closing amt <small class="font-weight-bold">from</small></label>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_close_amt_to" 
                    name="_close_amt_to" 
                    value="<?php echo isset($filters['_close_amt_to']) ? $filters['_close_amt_to'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">closing amt <small class="font-weight-bold">to</small></label>
            </div>
        </div>
    </div>
</div>