<?php 
    $tabindex = 1;
    $id       = empty($master_data) ? 0 : $master_data[0]['jim_id'];
    $uuid     = empty($master_data) ? $_SESSION['user_id'].''.time() : $master_data[0]['jim_uuid'];
?>
<style>
    body{
        color: #000000 !important;
    }
</style>
<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
        <div class="d-flex flex-wrap">
            <div class="col-12 col-sm-12 col-md-4 col-lg-3">
                <div class="card">
                    <div class="card-header text-uppercase">filter</div>
                    <div class="card-body p-0">
                        <div class="d-flex flex-wrap pt-2 form-group floating-form" style="max-height: 60vh; overflow-x:auto;">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label mt-4">
                                <p class="text-uppercase">process</p>
                                <input 
                                    type="hidden" 
                                    id="id" 
                                    name="id" 
                                    value="<?php echo $id; ?>"
                                />
                                <input 
                                    type="hidden" 
                                    id="jim_uuid" 
                                    name="jim_uuid" 
                                    value="<?php echo $uuid; ?>"
                                />
                                <select 
                                    class="form-control floating-select" 
                                    id="jim_proces_id" 
                                    name="jim_proces_id" 
                                    placeholder=" " 
                                    tabindex= "<?php echo $tabindex++; ?>"
                                    <?php echo (!empty($master_data) && $master_data[0]['isExist']) ? 'disabled="disabled"' : ''; ?>
                                >
                                    <?php if(!empty($master_data) && !empty($master_data[0]['jim_proces_id'])): ?>
                                        <option value="<?php echo $master_data[0]['jim_proces_id'] ?>" selected>
                                            <?php echo $master_data[0]['proces_name']; ?> 
                                            <input type="hidden" name="jim_proces_id" value="<?php echo $master_data[0]['jim_proces_id']; ?>" />
                                        </option>
                                    <?php endif; ?>
                                </select>
                                <small class="form-text text-muted helper-text" id="jim_proces_id_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label">
                                <p class="text-uppercase">karigar</p>
                                <select 
                                    class="form-control floating-select" 
                                    id="jim_karigar_id" 
                                    name="jim_karigar_id" 
                                    placeholder=" " 
                                    tabindex= "<?php echo $tabindex++; ?>"
                                    <?php echo (!empty($master_data) && $master_data[0]['isExist']) ? 'disabled="disabled"' : ''; ?>
                                >
                                    <?php if(!empty($master_data) && !empty($master_data[0]['jim_karigar_id'])): ?>
                                        <option value="<?php echo $master_data[0]['jim_karigar_id'] ?>" selected>
                                            <?php echo $master_data[0]['karigar_name']; ?> 
                                            <input type="hidden" name="jim_karigar_id" value="<?php echo $master_data[0]['jim_karigar_id']; ?>" />
                                        </option>
                                    <?php endif; ?>
                                </select>
                                <small class="form-text text-muted helper-text" id="jim_karigar_id_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label">
                                <p class="text-uppercase">barcode</p>
                                <select 
                                    class="form-control floating-select" 
                                    id="obt_id" 
                                    name="obt_id" 
                                    placeholder=" " 
                                    tabindex= "<?php echo $tabindex++; ?>"
                                ></select>
                                <small class="form-text text-muted helper-text" id="obt_id_msg"></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-8 col-lg-9">
                <div class="card">
                    <div class="card-header text-uppercase">
                        <div>job issue detail (<span id="transaction_count">0</span>)</div>
                    </div>
                    <div class="card-body p-0" style="max-width:100vw; max-height:50vh; overflow:auto;" id="div_wrapper">
                        <table class="table table-sm text-uppercase">
                            <thead class="table-dark">
                                <tr>
                                    <th >barcode&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    <th >apparel&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    <th >client&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    <th >order&nbsp;no.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    <th >order&nbsp;date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    <th >remove&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody id="transaction_wrapper" style="font-weight:bold; font-size:0.8rem;"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>