<div class="row">
    <div class="d-flex flex-wrap floating-form">
        
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">payment mode</p>
            <select class="form-control floating-select" id="_payment_mode_name" name="_payment_mode_name">
                <?php if(isset($_GET['_payment_mode_name']) && !empty($_GET['_payment_mode_name'])): ?>
                    <option value="<?php echo $_GET['_payment_mode_name']['value']; ?>" selected>
                        <?php echo $_GET['_payment_mode_name']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
        <div class="d-flex col-12 col-sm-12 col-md-6 col-lg-6 mt-3">
            <div class="floating-label">
                <input 
                    type="date" 
                    class="form-control floating-input" 
                    id="_date_from" 
                    name="_date_from" 
                    value="<?php echo isset($_GET['_date_from']) ? $_GET['_date_from'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">entry date <small class="font-weight-bold">from</small></label>
            </div>
            <div class="floating-label">
                <input 
                    type="date" 
                    class="form-control floating-input" 
                    id="_date_to" 
                    name="_date_to" 
                    value="<?php echo isset($_GET['_date_to']) ? $_GET['_date_to'] : '' ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">entry date <small class="font-weight-bold">to</small></label>
            </div>
        </div>
  
    </div>
</div>