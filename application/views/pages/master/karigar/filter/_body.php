<div class="row">
    <div class="d-flex flex-wrap floating-form">
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <?php if(isset($filters['_name'])): ?><p class="text-uppercase">karigar</p><?php endif; ?>
            <select class="form-control floating-select" id="_name" name="_name">
                <?php if(isset($filters['_name']) && !empty($filters['_name'])): ?>
                    <option value="<?php echo $filters['_name']['value']; ?>" selected>
                        <?php echo $filters['_name']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <?php if(isset($filters['_status'])): ?><p class="text-uppercase">status</p><?php endif; ?>
            <select class="form-control floating-select" id="_status" name="_status">
                <?php if(isset($filters['_status']) && !empty($filters['_status'])): ?>
                    <option value="<?php echo $filters['_status']['value']; ?>" selected>
                        <?php echo $filters['_status']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
    </div>
</div>