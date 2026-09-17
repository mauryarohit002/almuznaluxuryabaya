<div class="row">
    <div class="d-flex flex-wrap floating-form">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
                <input 
                    type="date" 
                    class="form-control floating-input" 
                    id="_entry_date_from" 
                    name="_entry_date_from" 
                    value="<?php echo isset($filters['_entry_date_from']) ? $filters['_entry_date_from'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">date <small class="font-weight-bold">from</small></label>
            </div>
           <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
                <input 
                    type="date" 
                    class="form-control floating-input" 
                    id="_entry_date_to" 
                    name="_entry_date_to" 
                    value="<?php echo isset($filters['_entry_date_to']) ? $filters['_entry_date_to'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">date <small class="font-weight-bold">to</small></label>
            </div>
      
         <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3 <?php echo ($_SESSION['branch_default']==1) ? '': 'd-none'?>">
            <p class="text-uppercase">branch</p>
            <select class="form-control floating-select" id="_branch_name" name="_branch_name">
                <?php if(isset($filters['_branch_name']) && !empty($filters['_branch_name'])): ?>
                    <option value="<?php echo $filters['_branch_name']['value']; ?>" selected>
                        <?php echo $filters['_branch_name']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>

    </div>
</div>