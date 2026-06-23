<div class="row">
    <div class="d-flex flex-wrap floating-form">
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <?php if(isset($filters['_sku_name'])): ?><p class="text-uppercase"><?php echo str_replace('_', ' ', $sub_menu); ?></p><?php endif; ?>
            <select class="form-control floating-select" id="_sku_name" name="_sku_name">
                <?php if(isset($filters['_sku_name']) && !empty($filters['_sku_name'])): ?>
                    <option value="<?php echo $filters['_sku_name']['value']; ?>" selected>
                        <?php echo $filters['_sku_name']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <?php if(isset($filters['_apparel_name'])): ?><p class="text-uppercase">apparel</p><?php endif; ?>
            <select class="form-control floating-select" id="_apparel_name" name="_apparel_name">
                <?php if(isset($filters['_apparel_name']) && !empty($filters['_apparel_name'])): ?>
                    <option value="<?php echo $filters['_apparel_name']['value']; ?>" selected>
                        <?php echo $filters['_apparel_name']['text']; ?> 
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