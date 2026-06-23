<div class="row">
    <div class="d-flex flex-wrap floating-form">
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">ENTRY NO</p>
            <select class="form-control floating-select" id="_entry_no" name="_entry_no">
                <?php if(isset($filters['_entry_no']) && !empty($filters['_entry_no'])): ?>
                    <option value="<?php echo $filters['_entry_no']['value']; ?>" selected>
                        <?php echo $filters['_entry_no']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">ORDER NO</p>
            <select class="form-control floating-select" id="_order_no" name="_order_no">
                <?php if(isset($filters['_order_no']) && !empty($filters['_order_no'])): ?>
                    <option value="<?php echo $filters['_order_no']['value']; ?>" selected>
                        <?php echo $filters['_order_no']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">APPAREL</p>
            <select class="form-control floating-select" id="_apparel_name" name="_apparel_name">
                <?php if(isset($filters['_apparel_name']) && !empty($filters['_apparel_name'])): ?>
                    <option value="<?php echo $filters['_apparel_name']['value']; ?>" selected>
                        <?php echo $filters['_apparel_name']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">PROCESS</p>
            <select class="form-control floating-select" id="_process_name" name="_process_name">
                <?php if(isset($filters['_process_name']) && !empty($filters['_process_name'])): ?>
                    <option value="<?php echo $filters['_process_name']['value']; ?>" selected>
                        <?php echo $filters['_process_name']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">KARIGAR</p>
            <select class="form-control floating-select" id="_karigar" name="_karigar">
                <?php if(isset($filters['_karigar']) && !empty($filters['_karigar'])): ?>
                    <option value="<?php echo $filters['_karigar']['value']; ?>" selected>
                        <?php echo $filters['_karigar']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
    </div>
</div>