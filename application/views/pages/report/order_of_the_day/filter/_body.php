<div class="row">
    <div class="d-flex flex-wrap floating-form">
        <div class="d-flex col-12 col-sm-12 col-md-6 col-lg-6 mt-3">
            <div class="floating-label">
                <input 
                    type="date" 
                    class="form-control floating-input" 
                    id="_entry_date_from" 
                    name="_entry_date_from" 
                    value="<?php echo isset($filters['_entry_date_from']) ? $filters['_entry_date_from'] : date('Y-m-d') ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">entry date <small class="font-weight-bold">from</small></label>
            </div>
            <div class="floating-label">
                <input 
                    type="date" 
                    class="form-control floating-input" 
                    id="_entry_date_to" 
                    name="_entry_date_to" 
                    value="<?php echo isset($filters['_entry_date_to']) ? $filters['_entry_date_to'] : date('Y-m-d') ?>" 
                    placeholder=" " 
                    autocomplete="off" 
                />   
                <label class="text-uppercase">entry date <small class="font-weight-bold">to</small></label>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label mt-3">
            <p class="text-uppercase">TYPE</p>
            <select class="form-control floating-select select2" id="_trans_type" name="_trans_type">
                <option value="" <?php echo ((isset($_GET['_trans_type'])) && ($_GET['_trans_type'] == '')) ? 'selected' : ''; ?>>ALL</option>
                <option value="FABRIC" <?php echo ((isset($_GET['_trans_type'])) && ($_GET['_trans_type'] == 'FABRIC')) ? 'selected' : ''; ?>>FABRIC</option>
                <option value="STITCHING" <?php echo ((isset($_GET['_trans_type'])) && ($_GET['_trans_type'] == 'STITCHING')) ? 'selected' : ''; ?>>STITCHING</option>
                <option value="PACKAGE" <?php echo ((isset($_GET['_trans_type'])) && ($_GET['_trans_type'] == 'PACKAGE')) ? 'selected' : ''; ?>>PACKAGE</option>
                <option value="OTHER" <?php echo ((isset($_GET['_trans_type'])) && ($_GET['_trans_type'] == 'OTHER')) ? 'selected' : ''; ?>>OTHER</option>
            </select>
        </div> 
        
    </div>
</div>