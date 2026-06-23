<div class="row">
    <div class="d-flex flex-wrap justify-content-center floating-form">
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <?php if(isset($filters['_fullname'])): ?><p class="text-uppercase">user</p><?php endif; ?>
            <select class="form-control floating-select" id="_fullname" name="_fullname">
                <?php if(isset($filters['_fullname']) && !empty($filters['_fullname'])): ?>
                    <option value="<?php echo $filters['_fullname']['value']; ?>" selected>
                        <?php echo $filters['_fullname']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <?php if(isset($filters['_role_name'])): ?><p class="text-uppercase">role</p><?php endif; ?>
            <select class="form-control floating-select" id="_role_name" name="_role_name">
                <?php if(isset($filters['_role_name']) && !empty($filters['_role_name'])): ?>
                    <option value="<?php echo $filters['_role_name']['value']; ?>" selected>
                        <?php echo $filters['_role_name']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <?php if(isset($filters['_branch_name'])): ?><p class="text-uppercase">branch</p><?php endif; ?>
            <select class="form-control floating-select" id="_branch_name" name="_branch_name">
                <?php if(isset($filters['_branch_name']) && !empty($filters['_branch_name'])): ?>
                    <option value="<?php echo $filters['_branch_name']['value']; ?>" selected>
                        <?php echo $filters['_branch_name']['text']; ?> 
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