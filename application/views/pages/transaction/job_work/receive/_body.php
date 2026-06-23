<?php $tabindex = 1;?>
<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
        <div class="d-flex flex-wrap">
            <div class="col-12 col-sm-12 col-md-4 col-lg-3">
                <div class="card">
                    <div class="card-header text-uppercase">filter</div>
                    <div class="card-body p-0">
                        <div class="d-flex flex-wrap pt-2 form-group floating-form" style="max-height: 60vh; overflow-x:auto;">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label mt-4">
                                <p class="text-uppercase">client</p>
                                <select 
                                    class="form-control floating-select" 
                                    id="_customer_name" 
                                    name="_customer_name" 
                                    placeholder=" " 
                                    tabindex= "<?php echo $tabindex++; ?>"
                                >
                                    <?php if(isset($_GET['_customer_name']) && !empty($_GET['_customer_name'])): ?>
                                        <option value="<?php echo $_GET['_customer_name']; ?>"><?php echo $_GET['_customer_name']; ?></option>
                                    <?php endif; ?>
                                </select>
                                <small class="form-text text-muted helper-text" id="_customer_name_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label">
                                <p class="text-uppercase">apparel</p>
                                <select 
                                    class="form-control floating-select" 
                                    id="_apparel_name" 
                                    name="_apparel_name" 
                                    placeholder=" " 
                                    tabindex= "<?php echo $tabindex++; ?>"
                                >
                                    <?php if(isset($_GET['_apparel_name']) && !empty($_GET['_apparel_name'])): ?>
                                        <option value="<?php echo $_GET['_apparel_name']; ?>"><?php echo $_GET['_apparel_name']; ?></option>
                                    <?php endif; ?>
                                </select>
                                <small class="form-text text-muted helper-text" id="_apparel_name_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label">
                                <p class="text-uppercase">process</p>
                                <select 
                                    class="form-control floating-select" 
                                    id="_proces_name" 
                                    name="_proces_name" 
                                    placeholder=" " 
                                    tabindex= "<?php echo $tabindex++; ?>"
                                >
                                    <?php if(isset($_GET['_proces_name']) && !empty($_GET['_proces_name'])): ?>
                                        <option value="<?php echo $_GET['_proces_name']; ?>"><?php echo $_GET['_proces_name']; ?></option>
                                    <?php endif; ?>
                                </select>
                                <small class="form-text text-muted helper-text" id="_proces_name_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label">
                                <p class="text-uppercase">karigar</p>
                                <select 
                                    class="form-control floating-select" 
                                    id="_karigar_name" 
                                    name="_karigar_name" 
                                    placeholder=" " 
                                    tabindex= "<?php echo $tabindex++; ?>"
                                >
                                    <?php if(isset($_GET['_karigar_name']) && !empty($_GET['_karigar_name'])): ?>
                                        <option value="<?php echo $_GET['_karigar_name']; ?>"><?php echo $_GET['_karigar_name']; ?></option>
                                    <?php endif; ?>
                                </select>
                                <small class="form-text text-muted helper-text" id="_karigar_name_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label">
                                <p class="text-uppercase">order no</p>
                                <select 
                                    class="form-control floating-select" 
                                    id="_entry_no" 
                                    name="_entry_no" 
                                    placeholder=" " 
                                    tabindex= "<?php echo $tabindex++; ?>"
                                >
                                    <?php if(isset($_GET['_entry_no']) && !empty($_GET['_entry_no'])): ?>
                                        <option value="<?php echo $_GET['_entry_no']; ?>"><?php echo $_GET['_entry_no']; ?></option>
                                    <?php endif; ?>
                                </select>
                                <small class="form-text text-muted helper-text" id="_entry_no_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                                <input 
                                    type="date" 
                                    class="form-control floating-input" 
                                    id="_entry_date_from" 
                                    name="_entry_date_from" 
                                    value="<?php echo (isset($_GET['_entry_date_from']) && !empty($_GET['_entry_date_from'])) ? $_GET['_entry_date_from'] : ''; ?>" 
                                    placeholder=" " 
                                    autocomplete="off"
                                    tabindex= "<?php echo $tabindex++; ?>"
                                />   
                                <label class="text-uppercase">entry date from</label>
                                <small class="form-text text-muted helper-text" id="_entry_date_from_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                                <input 
                                    type="date" 
                                    class="form-control floating-input" 
                                    id="_entry_date_to" 
                                    name="_entry_date_to" 
                                    value="<?php echo (isset($_GET['_entry_date_to']) && !empty($_GET['_entry_date_to'])) ? $_GET['_entry_date_to'] : ''; ?>" 
                                    placeholder=" " 
                                    autocomplete="off"
                                    tabindex= "<?php echo $tabindex++; ?>"
                                />   
                                <label class="text-uppercase">entry date to</label>
                                <small class="form-text text-muted helper-text" id="_entry_date_to_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                                <input 
                                    type="date" 
                                    class="form-control floating-input" 
                                    id="_trial_date_from" 
                                    name="_trial_date_from" 
                                    value="<?php echo (isset($_GET['_trial_date_from']) && !empty($_GET['_trial_date_from'])) ? $_GET['_trial_date_from'] : ''; ?>" 
                                    placeholder=" " 
                                    autocomplete="off"
                                    tabindex= "<?php echo $tabindex++; ?>"
                                />   
                                <label class="text-uppercase">trial date from</label>
                                <small class="form-text text-muted helper-text" id="_trial_date_from_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                                <input 
                                    type="date" 
                                    class="form-control floating-input" 
                                    id="_trial_date_to" 
                                    name="_trial_date_to" 
                                    value="<?php echo (isset($_GET['_trial_date_to']) && !empty($_GET['_trial_date_to'])) ? $_GET['_trial_date_to'] : ''; ?>" 
                                    placeholder=" " 
                                    autocomplete="off"
                                    tabindex= "<?php echo $tabindex++; ?>"
                                />   
                                <label class="text-uppercase">trial date to</label>
                                <small class="form-text text-muted helper-text" id="_trial_date_to_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                                <input 
                                    type="date" 
                                    class="form-control floating-input" 
                                    id="_delivery_date_from" 
                                    name="_delivery_date_from" 
                                    value="<?php echo (isset($_GET['_delivery_date_from']) && !empty($_GET['_delivery_date_from'])) ? $_GET['_delivery_date_from'] : ''; ?>" 
                                    placeholder=" " 
                                    autocomplete="off"
                                    tabindex= "<?php echo $tabindex++; ?>"
                                />   
                                <label class="text-uppercase">delivery date from</label>
                                <small class="form-text text-muted helper-text" id="_delivery_date_from_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                                <input 
                                    type="date" 
                                    class="form-control floating-input" 
                                    id="_delivery_date_to" 
                                    name="_delivery_date_to" 
                                    value="<?php echo (isset($_GET['_delivery_date_to']) && !empty($_GET['_delivery_date_to'])) ? $_GET['_delivery_date_to'] : ''; ?>" 
                                    placeholder=" " 
                                    autocomplete="off"
                                    tabindex= "<?php echo $tabindex++; ?>"
                                />   
                                <label class="text-uppercase">delivery date to</label>
                                <small class="form-text text-muted helper-text" id="_delivery_date_from_msg"></small>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center p-2">
                            <button
                                type="button"
                                class="btn btn-sm btn-block btn-secondary text-uppercase mx-1"
                                onclick="get_search_data()"
                            >search</button>
                            <button
                                type="button"
                                class="btn btn-sm btn-block btn-secondary text-uppercase mx-1 mt-0"
                                onclick="get_reset_data()"
                            >reset</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-8 col-lg-9">
                <div class="card">
                    <div class="card-header text-uppercase">
                        <div>job issue detail</div>
                    </div>
                    <div class="card-body p-0" style="max-width:100vw; max-height:50vh; overflow:auto;" id="div_wrapper">
                        <table class="table table-sm text-uppercase">
                            <thead class="table-dark">
                                <tr>
                                    <th >#&nbsp;</th>
                                    <th >client&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    <th >order&nbsp;no.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    <th >order&nbsp;date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    <th >trial&nbsp;date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    <th >delivery&nbsp;date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    <th >apparel&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    <th >process&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    <th >karigar&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    <th >save&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    <th >revert&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody id="transaction_wrapper" style="font-weight:bold; font-size:0.8rem;">
                                <tr>
                                    <td class="text-center text-info font-weight-bold" colspan="11">fetching record ....</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>