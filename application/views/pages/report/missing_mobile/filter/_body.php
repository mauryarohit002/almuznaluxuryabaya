<div class="row">
    <div class="d-flex flex-wrap floating-form">
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">customer</p>
            <select class="form-control floating-select" id="_customer_name" name="_customer_name">
                <?php if(isset($filters['_customer_name']) && !empty($filters['_customer_name'])): ?>
                    <option value="<?php echo $filters['_customer_name']['value']; ?>" selected>
                        <?php echo $filters['_customer_name']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">contact person</p>
            <select class="form-control floating-select" id="_contact_person" name="_contact_person">
                <?php if(isset($filters['_contact_person']) && !empty($filters['_contact_person'])): ?>
                    <option value="<?php echo $filters['_contact_person']['value']; ?>" selected>
                        <?php echo $filters['_contact_person']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
    </div>
</div>