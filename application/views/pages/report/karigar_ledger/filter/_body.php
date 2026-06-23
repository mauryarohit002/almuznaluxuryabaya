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
    </div>
</div>