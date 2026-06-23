<?php 
    $_entry_date_from= (isset($_GET['_entry_date_from'])) ? $_GET['_entry_date_from'] : "";
	$_entry_date_to	 = (isset($_GET['_entry_date_to'])) ? $_GET['_entry_date_to'] : "";
	$_total_amt_from = (isset($_GET['_total_amt_from'])) ? $_GET['_total_amt_from'] : "";
	$_total_amt_to	 = (isset($_GET['_total_amt_to'])) ? $_GET['_total_amt_to'] : "";
	$status	 		 = (isset($_GET['status'])) ? $_GET['status'] : 0;
?>
<div class="row">
    <div class="d-flex flex-wrap floating-form">
        <div class="col-6 col-sm-6 col-md-6 col-lg-6 floating-label mt-3">
            <?php if(isset($filters['_entry_no'])): ?><p class="text-uppercase">entry no</p><?php endif; ?>
            <select class="form-control floating-select" id="_entry_no" name="_entry_no">
                <?php if(isset($filters['_entry_no']) && !empty($filters['_entry_no'])): ?>
                    <option value="<?php echo $filters['_entry_no']['value']; ?>" selected>
                        <?php echo $filters['_entry_no']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
        <div class="d-flex col-12 col-sm-12 col-md-12 col-lg-12">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
                <input 
                    type="date" 
                    class="form-control floating-input" 
                    id="_entry_date_from" 
                    name="_entry_date_from" 
                    value="<?php echo $_entry_date_from ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                    onchange="trigger_search()"
                />   
                <label class="text-uppercase">entry date <small class="font-weight-bold">from</small></label>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
                <input 
                    type="date" 
                    class="form-control floating-input" 
                    id="_entry_date_to" 
                    name="_entry_date_to" 
                    value="<?php echo $_entry_date_to ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                    onchange="trigger_search()"
                />   
                <label class="text-uppercase">entry date <small class="font-weight-bold">to</small></label>
            </div>
        </div>
        <div class="d-flex col-12 col-sm-12 col-md-12 col-lg-12">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_total_amt_from" 
                    name="_total_amt_from" 
                    value="<?php echo $_total_amt_from ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                    onchange="trigger_search()"
                />   
                <label class="text-uppercase">payment amt <small class="font-weight-bold">from</small></label>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
                <input 
                    type="number" 
                    class="form-control floating-input" 
                    id="_total_amt_to" 
                    name="_total_amt_to" 
                    value="<?php echo $_total_amt_to ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                    onchange="trigger_search()"
                />   
                <label class="text-uppercase">payment amt <small class="font-weight-bold">to</small></label>
            </div>
        </div>
    </div>
</div>