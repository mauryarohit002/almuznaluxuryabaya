<div class="row">
    <div class="d-flex flex-wrap floating-form">
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
    </div>
</div>