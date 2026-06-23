<div class="row">
    <div class="d-flex flex-wrap floating-form">
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">CUSTOMER</p>
            <select class="form-control floating-select" id="_debit_name" name="_debit_name">
                <?php if(isset($filters['_debit_name']) && !empty($filters['_debit_name'])): ?>
                    <option value="<?php echo $filters['_debit_name']['value']; ?>" selected>
                        <?php echo $filters['_debit_name']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
    </div>
</div>