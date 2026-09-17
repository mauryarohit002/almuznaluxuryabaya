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
                <label class="text-uppercase">entry date <small class="font-weight-bold">from</small></label>
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
                <label class="text-uppercase">entry date <small class="font-weight-bold">to</small></label>
            </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">Entry NO</p>
            <select class="form-control floating-select" id="_entry_no" name="_entry_no[]" multiple>
                <?php if(isset($filters['_entry_no']['text']) && !empty($filters['_entry_no']['text'])): ?>
                   <?php foreach ($filters['_entry_no']['text'] as $key=>$entryNo): ?>
                    ?> 
                    <option value="<?php echo $entryNo; ?>" selected>
                        <?php echo $entryNo ?> 
                    </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">order NO</p>
            <select class="form-control floating-select" id="_om_em_entry_no" name="_om_em_entry_no[]" multiple>
                <?php if(isset($filters['_om_em_entry_no']['text']) && !empty($filters['_om_em_entry_no']['text'])): ?>
                   <?php foreach ($filters['_om_em_entry_no']['text'] as $key=>$omEntryNo): ?>
                    ?> 
                    <option value="<?php echo $omEntryNo; ?>" selected>
                        <?php echo $omEntryNo ?> 
                    </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <input 
                type="date" 
                class="form-control floating-input" 
                id="_om_entry_date_from" 
                name="_om_entry_date_from" 
                value="<?php echo isset($filters['_om_entry_date_from']) ? $filters['_om_entry_date_from'] : '' ?>" 
                placeholder=" " 
                autocomplete="off" 
            />   
            <label class="text-uppercase">order date <small class="font-weight-bold">from</small></label>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <input 
                type="date" 
                class="form-control floating-input" 
                id="_om_entry_date_to" 
                name="_om_entry_date_to" 
                value="<?php echo isset($filters['_om_entry_date_to']) ? $filters['_om_entry_date_to'] : '' ?>" 
                placeholder=" " 
                autocomplete="off" 
            />   
            <label class="text-uppercase">order date <small class="font-weight-bold">to</small></label>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">qrcode</p>
            <select class="form-control floating-select" id="_qrcode" name="_qrcode">
                <?php if(isset($filters['_qrcode']) && !empty($filters['_qrcode'])): ?>
                    <option value="<?php echo $filters['_qrcode']['value']; ?>" selected>
                        <?php echo $filters['_qrcode']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>

        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3 <?php echo ($_SESSION['branch_default']==1) ? '': 'd-none'?>">
            <p class="text-uppercase">branch</p>
            <select class="form-control floating-select" id="_branch" name="_branch">
                <?php if(isset($filters['_branch']) && !empty($filters['_branch'])): ?>
                    <option value="<?php echo $filters['_branch']['value']; ?>" selected>
                        <?php echo $filters['_branch']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>

    </div>
</div>