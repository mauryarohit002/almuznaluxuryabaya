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
            <?php if(isset($filters['_category_name'])): ?><p class="text-uppercase">category</p><?php endif; ?>
            <select class="form-control floating-select" id="_category_name" name="_category_name">
                <?php if(isset($filters['_category_name']) && !empty($filters['_category_name'])): ?>
                    <option value="<?php echo $filters['_category_name']['value']; ?>" selected>
                        <?php echo $filters['_category_name']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
    </div>
</div>