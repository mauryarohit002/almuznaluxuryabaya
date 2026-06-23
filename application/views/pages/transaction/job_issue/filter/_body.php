<?php 
    $_entry_date_from 	= (isset($_GET['_entry_date_from'])) ? $_GET['_entry_date_from'] : "";
	$_entry_date_to 	= (isset($_GET['_entry_date_to'])) ? $_GET['_entry_date_to'] : "";
    $_order_date_from 	= (isset($_GET['_order_date_from'])) ? $_GET['_order_date_from'] : "";
	$_order_date_to 	= (isset($_GET['_order_date_to'])) ? $_GET['_order_date_to'] : "";
?>
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
       <!--  <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <?php if(isset($filters['_order_no'])): ?><p class="text-uppercase">order no</p><?php endif; ?>
            <select class="form-control floating-select" id="_order_no" name="_order_no">
                <?php if(isset($filters['_order_no']) && !empty($filters['_order_no'])): ?>
                    <option value="<?php echo $filters['_order_no']['value']; ?>" selected>
                        <?php echo $filters['_order_no']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div> -->
      <!--   <div class="d-flex col-12 col-sm-12 col-md-6 col-lg-6 mt-3">
            <div class="floating-label">
                <input type="text" class="form-control floating-input datepicker" id="_entry_date_from" name="_entry_date_from" value="<?php echo $_entry_date_from ?>" placeholder=" " autocomplete="off" onchange="trigger_search()"/>   
                <label class="text-uppercase">entry date <small class="font-weight-bold">from</small></label>
            </div>
            <div class="floating-label">
                <input type="text" class="form-control floating-input datepicker" id="_entry_date_to" name="_entry_date_to" value="<?php echo $_entry_date_to ?>" placeholder=" " autocomplete="off" onchange="trigger_search()"/>   
                <label class="text-uppercase">entry date <small class="font-weight-bold">to</small></label>
            </div>
        </div> -->
       <!--  <div class="d-flex col-12 col-sm-12 col-md-6 col-lg-6 mt-3">
            <div class="floating-label">
                <input type="text" class="form-control floating-input datepicker" id="_order_date_from" name="_order_date_from" value="<?php echo $_order_date_from ?>" placeholder=" " autocomplete="off" onchange="trigger_search()"/>   
                <label class="text-uppercase">order date <small class="font-weight-bold">from</small></label>
            </div>
            <div class="floating-label">
                <input type="text" class="form-control floating-input datepicker" id="_order_date_to" name="_order_date_to" value="<?php echo $_order_date_to ?>" placeholder=" " autocomplete="off" onchange="trigger_search()"/>   
                <label class="text-uppercase">order date <small class="font-weight-bold">to</small></label>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <?php if(isset($filters['_item_code'])): ?><p class="text-uppercase">barcode</p><?php endif; ?>
            <select class="form-control floating-select" id="_item_code" name="_item_code">
                <?php if(isset($filters['_item_code']) && !empty($filters['_item_code'])): ?>
                    <option value="<?php echo $filters['_item_code']['value']; ?>" selected>
                        <?php echo $filters['_item_code']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div> -->
       <!--  <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <?php if(isset($filters['_apparel_name'])): ?><p class="text-uppercase">apparel</p><?php endif; ?>
            <select class="form-control floating-select" id="_apparel_name" name="_apparel_name">
                <?php if(isset($filters['_apparel_name']) && !empty($filters['_apparel_name'])): ?>
                    <option value="<?php echo $filters['_apparel_name']['value']; ?>" selected>
                        <?php echo $filters['_apparel_name']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div> -->
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <?php if(isset($filters['_proces_name'])): ?><p class="text-uppercase">process</p><?php endif; ?>
            <select class="form-control floating-select" id="_proces_name" name="_proces_name">
                <?php if(isset($filters['_proces_name']) && !empty($filters['_proces_name'])): ?>
                    <option value="<?php echo $filters['_proces_name']['value']; ?>" selected>
                        <?php echo $filters['_proces_name']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <?php if(isset($filters['_karigar_name'])): ?><p class="text-uppercase">karigar</p><?php endif; ?>
            <select class="form-control floating-select" id="_karigar_name" name="_karigar_name">
                <?php if(isset($filters['_karigar_name']) && !empty($filters['_karigar_name'])): ?>
                    <option value="<?php echo $filters['_karigar_name']['value']; ?>" selected>
                        <?php echo $filters['_karigar_name']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div>
      <!--   <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <?php if(isset($filters['_customer_name'])): ?><p class="text-uppercase">customer</p><?php endif; ?>
            <select class="form-control floating-select" id="_customer_name" name="_customer_name">
                <?php if(isset($filters['_customer_name']) && !empty($filters['_customer_name'])): ?>
                    <option value="<?php echo $filters['_customer_name']['value']; ?>" selected>
                        <?php echo $filters['_customer_name']['text']; ?> 
                    </option>
                <?php endif; ?>
            </select>
        </div> -->
    </div>
</div>