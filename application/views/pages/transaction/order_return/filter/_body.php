<?php 
    $_entry_date_from 	= (isset($_GET['_entry_date_from'])) ? $_GET['_entry_date_from'] : "";
	$_entry_date_to 	= (isset($_GET['_entry_date_to'])) ? $_GET['_entry_date_to'] : "";
    $min_date           = $_SESSION['start_year'];
    $max_date           = $_SESSION['end_year']?>
<div class="row">
    <div class="d-flex flex-wrap floating-form">
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <?php if(isset($filters['_entry_no'])): ?><p class="text-uppercase">entry no</p><?php endif; ?>
            <select class="form-control floating-select" id="_entry_no" name="_entry_no">
                <?php if(isset($filters['_entry_no']) && !empty($filters['_entry_no'])): ?>
                    <option value="<?php echo $filters['_entry_no']['value']; ?>" selected>
                        <?php echo $filters['_entry_no']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
      
        <div class="d-flex flex-wrap col-12 col-sm-12 col-md-12 col-lg-12 mt-3">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                <input 
                    type="date" 
                    class="form-control floating-input" 
                    id="_entry_date_from" 
                    name="_entry_date_from" 
                    value="<?php echo $_entry_date_from ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                    onchange="trigger_search()"
                    min="<?php echo $min_date; ?>"
                    max="<?php echo $max_date; ?>"
                />   
                <label class="text-uppercase">entry date <small class="font-weight-bold">from</small></label>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                <input 
                    type="date" 
                    class="form-control floating-input" 
                    id="_entry_date_to" 
                    name="_entry_date_to" 
                    value="<?php echo $_entry_date_to ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                    onchange="trigger_search()"
                    min="<?php echo $min_date; ?>"
                    max="<?php echo $max_date; ?>"
                />   
                <label class="text-uppercase">entry date <small class="font-weight-bold">to</small></label>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <?php if(isset($filters['_customer_name'])): ?><p class="text-uppercase">customer</p><?php endif; ?>
            <select class="form-control floating-select" id="_customer_name" name="_customer_name">
                <?php if(isset($filters['_customer_name']) && !empty($filters['_customer_name'])): ?>
                    <option value="<?php echo $filters['_customer_name']['value']; ?>" selected>
                        <?php echo $filters['_customer_name']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
        
    </div>
</div>