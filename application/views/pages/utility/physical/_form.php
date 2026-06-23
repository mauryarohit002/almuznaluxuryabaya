<?php 
    $this->load->view('templates/header');    
    $menu 				= 'utility';
	$sub_menu 			= 'physical';
	$search_status 		= !isset($_GET['search_status']);
	$action 			= (isset($_GET['action'])) ? $_GET['action'] : "";
?>
<script>
    let link 	= '<?php echo $menu ?>';
    let sub_link= '<?php echo $sub_menu ?>';
</script>
<style type="text/css">
    tbody {
        display:block;
        height:250px;
        overflow:auto;
    }
    thead, tbody tr {
        display:table;
        width:100%;
        table-layout:fixed;
    }
</style>
<section class="sticky_top">
    <div class="row d-flex flex-wrap">
        <div class="col-12 col-sm-12 col-md-5 col-lg-5">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item text-uppercase">
                        <a href="<?php echo base_url($menu.'/'.$sub_menu.'?action=list'); ?>"><?php echo str_replace('_', ' ', $sub_menu); ?></a>
                    </li>
                    <li class="breadcrumb-item active text-uppercase" aria-current="page"><?php echo $action; ?></li>
                    <?php if($action != 'view'): ?>
                        <?php if(empty($master_data)): ?>
                            <li class="breadcrumb-item" aria-current="save-page">
                                <button 
                                    type="button" 
                                    class="btn btn-sm btn-primary master_block_btn" 
                                    data-toggle="tooltip" 
                                    data-placement="bottom" 
                                    title="SAVE" 
                                    tabindex="99" 
                                    onclick="add_update(0)" 
                                    disabled
                                ><i class="text-success fa fa-save"></i></button>
                            </li>
                        <?php else: ?>
                            <?php if(!$master_data[0]['isExist']): ?>
                                <li class="breadcrumb-item" aria-current="save-page">
                                    <button 
                                        type="button" 
                                        class="btn btn-sm btn-primary master_block_btn" 
                                        data-toggle="tooltip" 
                                        data-placement="bottom" 
                                        title="UPDATE" 
                                        tabindex="99" 
                                        onclick="add_update(<?php echo $master_data[0]['psm_id']; ?>)" 
                                    ><i class="text-success fa fa-edit"></i></button>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                    <li class="breadcrumb-item" aria-current="cancel-page">
                        <a 
                            type="button" 
                            class="btn btn-sm btn-primary" 
                            data-toggle="tooltip" 
                            data-placement="bottom" 
                            title="CANCEL" 
                            tabindex="100"
                            href="<?php echo base_url($menu.'/'.$sub_menu.'?action=list')?>" 
                        ><i class="text-danger fa fa-close"></i></a>
                    </li>
                </ol>
            </nav>
        </div>
        <div class="col-12 col-sm-12 col-md-7 col-lg-7"></div>
    </div>
</section>
<section class="container-fluid my-3">
    <form class="form-horizontal" id="<?php echo $sub_menu; ?>_form">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-4 col-lg-3">
                <div class="card mb-3">
                    <div class="card-header">GENERAL DETAIL</div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap mt-2 form-group floating-form">
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                                <input type="hidden" id="psm_id" value="<?php echo empty($master_data) ? 0 : $master_data[0]['psm_id'] ?>" />   
                                <input type="number" class="form-control floating-input" id="psm_entry_no" name="psm_entry_no" value="<?php echo empty($master_data) ? $psm_entry_no : $master_data[0]['psm_entry_no'] ?>" placeholder=" " readonly="readonly" />   
                                <label for="inputEmail3">ENTRY NO&nbsp;<span class="text-danger">*</span></label>
                                <small class="form-text text-muted helper-text" id="psm_entry_no_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                                <input type="text" class="form-control floating-input" id="psm_entry_date" name="psm_entry_date" value="<?php echo empty($master_data) ? date('d-m-Y') : date('d-m-Y', strtotime($master_data[0]['psm_entry_date'])) ?>" placeholder=" " readonly="readonly" />   
                                <label for="inputEmail3">ENTRY DATE&nbsp;<span class="text-danger">*</span></label>
                                <small class="form-text text-muted helper-text" id="psm_entry_date_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label">
                                <textarea class="form-control floating-textarea" id="psm_notes" name="psm_notes" placeholder=" " autocomplete="off" rows="3" tabindex="1"><?php echo (empty($master_data)?'':$master_data[0]['psm_notes'])?></textarea>
                                <label for="inputEmail3">NOTES</label>
                                <small class="form-text text-muted helper-text" id="psm_notes_msg"></small>
                            </div>
                            <?php if(empty($master_data) || (!empty($master_data) && !$master_data[0]['isExist'])): ?>
                                <div class="col-sm-12 col-md-12 col-lg-12 floating-label">
                                    <p for="inputEmail3" class="text-success">AVAILABLE BARCODE</p>
                                    <select class="form-control floating-select select2" id="bm_id" placeholder="" tabindex="2"></select>
                                    <small class="form-text text-muted helper-text" id="bm_id_msg"></small>
                                </div>
                            <?php endif;?>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label">
                                <input type="number" class="form-control floating-input font-weight-bold" id="psm_scan_qty" name="psm_scan_qty" value="<?php echo empty($master_data) ? 0 : $master_data[0]['psm_scan_qty'] ?>" placeholder=" " readonly="readonly"/>   
                                <label for="inputEmail3">SCAN QTY</label>
                                <small class="form-text text-muted helper-text" id="psm_scan_qty_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label">
                                <input type="number" class="form-control floating-input font-weight-bold" id="psm_unscan_qty" name="psm_unscan_qty" value="<?php echo empty($master_data) ? 0 : $master_data[0]['psm_unscan_qty'] ?>" placeholder=" " readonly="readonly"/>   
                                <label for="inputEmail3">MISSING QTY</label>
                                <small class="form-text text-muted helper-text" id="psm_unscan_qty_msg"></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-8 col-lg-9">
                <div class="card mb-2">
                    <div class="card-header">SCAN BARCODE LIST</div>
                    <div class="card-body p-0" style="font-size:0.8rem;">
                        <table class="table table-hover table-sm table-dark">
                            <thead>
                                <tr>
                                    <th width="15%">BARCODE</th>
                                    <th width="20%">DESCRIPTION</th>
                                    <th width="10%">SKU</th>
                                    <th width="10%">APPAREL</th>
                                    <th width="10%">SUPPLIER</th>
                                    <th width="5%">RATE</th>
                                    <th width="5%">QTY</th>
                                    <th width="5%">REMOVE</th>
                                </tr>
                            </thead>
                        </table>
                        <div style="min-height: 50vh; overflow-x: auto;">
                            <table class="table table-hover table-sm table-hover font-weight-bold">
                                <tbody id="scan_barcode_wrapper"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>
<?php $this->load->view('templates/footer'); ?>
<script src="<?php echo assets('dist/js/utility/physical.js')?>"></script>
</body>
</html>