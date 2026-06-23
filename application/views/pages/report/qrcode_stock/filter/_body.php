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
            <p class="text-uppercase">item code</p>
            <select class="form-control floating-select" id="_item_code" name="_item_code">
                <?php if(isset($filters['_item_code']) && !empty($filters['_item_code'])): ?>
                    <option value="<?php echo $filters['_item_code']['value']; ?>" selected>
                        <?php echo $filters['_item_code']['text']; ?> 
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
            <p class="text-uppercase">fabric</p>
            <select class="form-control floating-select" id="_fabric_name" name="_fabric_name">
                <?php if(isset($filters['_fabric_name']) && !empty($filters['_fabric_name'])): ?>
                    <option value="<?php echo $filters['_fabric_name']['value']; ?>" selected>
                        <?php echo $filters['_fabric_name']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">fabric code</p>
            <select class="form-control floating-select" id="_design_name" name="_design_name">
                <?php if(isset($filters['_design_name']) && !empty($filters['_design_name'])): ?>
                    <option value="<?php echo $filters['_design_name']['value']; ?>" selected>
                        <?php echo $filters['_design_name']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">category</p>
            <select class="form-control floating-select" id="_category_name" name="_category_name">
                <?php if(isset($filters['_category_name']) && !empty($filters['_category_name'])): ?>
                    <option value="<?php echo $filters['_category_name']['value']; ?>" selected>
                        <?php echo $filters['_category_name']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">color</p>
            <select class="form-control floating-select" id="_color_name" name="_color_name">
                <?php if(isset($filters['_color_name']) && !empty($filters['_color_name'])): ?>
                    <option value="<?php echo $filters['_color_name']['value']; ?>" selected>
                        <?php echo $filters['_color_name']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">width</p>
            <select class="form-control floating-select" id="_width_name" name="_width_name">
                <?php if(isset($filters['_width_name']) && !empty($filters['_width_name'])): ?>
                    <option value="<?php echo $filters['_width_name']['value']; ?>" selected>
                        <?php echo $filters['_width_name']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">description</p>
            <select class="form-control floating-select" id="_description" name="_description">
                <?php if(isset($filters['_description']) && !empty($filters['_description'])): ?>
                    <option value="<?php echo $filters['_description']['value']; ?>" selected>
                        <?php echo $filters['_description']['text']; ?> 
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
      
    </div>
</div>