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
            <p class="text-uppercase">product</p>
            <select class="form-control floating-select" id="_product_name" name="_product_name">
                <?php if(isset($filters['_product_name']) && !empty($filters['_product_name'])): ?>
                    <option value="<?php echo $filters['_product_name']['value']; ?>" selected>
                        <?php echo $filters['_product_name']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
         <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">design</p>
            <select class="form-control floating-select" id="_design_name" name="_design_name">
                <?php if(isset($filters['_design_name']) && !empty($filters['_design_name'])): ?>
                    <option value="<?php echo $filters['_design_name']['value']; ?>" selected>
                        <?php echo $filters['_design_name']['text']; ?> 
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
            <p class="text-uppercase">size</p>
            <select class="form-control floating-select" id="_size_name" name="_size_name">
                <?php if(isset($filters['_size_name']) && !empty($filters['_size_name'])): ?>
                    <option value="<?php echo $filters['_size_name']['value']; ?>" selected>
                        <?php echo $filters['_size_name']['text']; ?> 
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
        <div class="d-flex col-12 col-sm-12 col-md-6 col-lg-6 mt-3">
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_nod_from" 
                    name="_nod_from" 
                    value="<?php echo isset($filters['_nod_from']) ? $filters['_nod_from'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">nod <small class="font-weight-bold">from</small></label>
            </div>
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_nod_to" 
                    name="_nod_to" 
                    value="<?php echo isset($filters['_nod_to']) ? $filters['_nod_to'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">nod <small class="font-weight-bold">to</small></label>
            </div>
        </div>
        <div class="d-flex col-12 col-sm-12 col-md-6 col-lg-6 mt-3">
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_rate_from" 
                    name="_rate_from" 
                    value="<?php echo isset($filters['_rate_from']) ? $filters['_rate_from'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">rate <small class="font-weight-bold">from</small></label>
            </div>
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_rate_to" 
                    name="_rate_to" 
                    value="<?php echo isset($filters['_rate_to']) ? $filters['_rate_to'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">rate <small class="font-weight-bold">to</small></label>
            </div>
        </div>
        <div class="d-flex col-12 col-sm-12 col-md-6 col-lg-6 mt-3">
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_mrp_from" 
                    name="_mrp_from" 
                    value="<?php echo isset($filters['_mrp_from']) ? $filters['_mrp_from'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">mrp <small class="font-weight-bold">from</small></label>
            </div>
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_mrp_to" 
                    name="_mrp_to" 
                    value="<?php echo isset($filters['_mrp_to']) ? $filters['_mrp_to'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">mrp <small class="font-weight-bold">to</small></label>
            </div>
        </div>
        <div class="d-flex col-12 col-sm-12 col-md-6 col-lg-6 mt-3">
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_prmt_qty_from" 
                    name="_prmt_qty_from" 
                    value="<?php echo isset($filters['_prmt_qty_from']) ? $filters['_prmt_qty_from'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">pur qty <small class="font-weight-bold">from</small></label>
            </div>
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_prmt_qty_to" 
                    name="_prmt_qty_to" 
                    value="<?php echo isset($filters['_prmt_qty_to']) ? $filters['_prmt_qty_to'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">pur qty <small class="font-weight-bold">to</small></label>
            </div>
        </div>
        <div class="d-flex col-12 col-sm-12 col-md-6 col-lg-6 mt-3">
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_ot_qty_from" 
                    name="_ot_qty_from" 
                    value="<?php echo isset($filters['_ot_qty_from']) ? $filters['_ot_qty_from'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">order qty <small class="font-weight-bold">from</small></label>
            </div>
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_ot_qty_to" 
                    name="_ot_qty_to" 
                    value="<?php echo isset($filters['_ot_qty_to']) ? $filters['_ot_qty_to'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">order qty <small class="font-weight-bold">to</small></label>
            </div>
        </div>
        <div class="d-flex col-12 col-sm-12 col-md-6 col-lg-6 mt-3">
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_bal_qty_from" 
                    name="_bal_qty_from" 
                    value="<?php echo isset($filters['_bal_qty_from']) ? $filters['_bal_qty_from'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">bal qty <small class="font-weight-bold">from</small></label>
            </div>
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_bal_qty_to" 
                    name="_bal_qty_to" 
                    value="<?php echo isset($filters['_bal_qty_to']) ? $filters['_bal_qty_to'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">bal qty <small class="font-weight-bold">to</small></label>
            </div>
        </div>
        <div class="d-flex col-12 col-sm-12 col-md-6 col-lg-6 mt-3">
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_bal_amt_from" 
                    name="_bal_amt_from" 
                    value="<?php echo isset($filters['_bal_amt_from']) ? $filters['_bal_amt_from'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">bal amt <small class="font-weight-bold">from</small></label>
            </div>
            <div class="floating-label">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_bal_amt_to" 
                    name="_bal_amt_to" 
                    value="<?php echo isset($filters['_bal_amt_to']) ? $filters['_bal_amt_to'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">bal amt <small class="font-weight-bold">to</small></label>
            </div>
        </div>
    </div>
</div>