<div class="row">
    <div class="d-flex flex-wrap justify-content-center floating-form">
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <?php if(isset($filters['_apparel_name'])): ?><p class="text-uppercase"><?php echo str_replace('_', ' ', $sub_menu); ?></p><?php endif; ?>
            <select class="form-control floating-select" id="_apparel_name" name="_apparel_name">
                <?php if(isset($filters['_apparel_name']) && !empty($filters['_apparel_name'])): ?>
                    <option value="<?php echo $filters['_apparel_name']['value']; ?>" selected>
                        <?php echo $filters['_apparel_name']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <?php if(isset($filters['_measurement_name'])): ?><p class="text-uppercase">measurement</p><?php endif; ?>
            <select class="form-control floating-select" id="_measurement_name" name="_measurement_name">
                <?php if(isset($filters['_measurement_name']) && !empty($filters['_measurement_name'])): ?>
                    <option value="<?php echo $filters['_measurement_name']['value']; ?>" selected>
                        <?php echo $filters['_measurement_name']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
    </div>
</div>