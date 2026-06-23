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
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">QRCODE</p>
            <select class="form-control floating-select" id="_qrcode_no" name="_qrcode_no">
                <?php if(isset($filters['_qrcode_no']) && !empty($filters['_qrcode_no'])): ?>
                    <option value="<?php echo $filters['_qrcode_no']['value']; ?>" selected>
                        <?php echo $filters['_qrcode_no']['text']; ?> 
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
            <p class="text-uppercase">CUSTOMER</p>
            <select class="form-control floating-select" id="_customer" name="_customer">
                <?php if(isset($filters['_customer']) && !empty($filters['_customer'])): ?>
                    <option value="<?php echo $filters['_customer']['value']; ?>" selected>
                        <?php echo $filters['_customer']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
    </div>
</div>