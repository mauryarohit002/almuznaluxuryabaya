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
                <label class="text-uppercase">issue date <small class="font-weight-bold">from</small></label>
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
                <label class="text-uppercase">issue date <small class="font-weight-bold">to</small></label>
            </div>
      
       
       <!--  <div class="d-flex col-12 col-sm-12 col-md-6 col-lg-6 mt-3">
            <div class="floating-label">
                <input 
                    type="date" 
                    class="form-control floating-input" 
                    id="_order_date_from" 
                    name="_order_date_from" 
                    value="<?php echo isset($filters['_order_date_from']) ? $filters['_order_date_from'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">order date <small class="font-weight-bold">from</small></label>
            </div>
            <div class="floating-label">
                <input 
                    type="date" 
                    class="form-control floating-input" 
                    id="_order_date_to" 
                    name="_order_date_to" 
                    value="<?php echo isset($filters['_order_date_to']) ? $filters['_order_date_to'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">order date <small class="font-weight-bold">to</small></label>
            </div>
        </div> -->
         <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">order no</p>
            <select class="form-control floating-select" id="_order_no" name="_order_no">
                <?php if(isset($filters['_order_no']) && !empty($filters['_order_no'])): ?>
                    <option value="<?php echo $filters['_order_no']['value']; ?>" selected>
                        <?php echo $filters['_order_no']['text']; ?> 
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
            <p class="text-uppercase">process</p>
            <select class="form-control floating-select" id="_proces_name" name="_proces_name">
                <?php if(isset($filters['_proces_name']) && !empty($filters['_proces_name'])): ?>
                    <option value="<?php echo $filters['_proces_name']['value']; ?>" selected>
                        <?php echo $filters['_proces_name']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
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
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">Barcode</p>
            <select class="form-control floating-select" id="_bm_item_code" name="_bm_item_code">
                <?php if(isset($filters['_bm_item_code']) && !empty($filters['_bm_item_code'])): ?>
                    <option value="<?php echo $filters['_bm_item_code']['value']; ?>" selected>
                        <?php echo $filters['_bm_item_code']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">status</p>
            <select class="form-control floating-select select2" id="_job_status" name="_job_status">
                    <option value="" <?php echo (isset($filters['_job_status']['value']) && $filters['_job_status']['value'] == '') ? 'selected' : ''; ?>>ALL</option>
                    <option value="PENDING" <?php echo (isset($filters['_job_status']['value']) && $filters['_job_status']['value'] == 'PENDING') ? 'selected' : ''; ?>>PENDING</option>
                    <option value="COMPLETED" <?php echo (isset($filters['_job_status']['value']) && $filters['_job_status']['value'] == 'COMPLETED') ? 'selected' : ''; ?>>COMPLETED</option>
            </select>
        </div>
    </div>
</div>