<?php
    $checked        = !empty($master_data) && $master_data[0]['menu_status'] == 0 ? '' : 'checked'; 
    $id             = empty($master_data) ? 0 : $master_data[0]['menu_id'];
    $tabindex       = 1;
?>
<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="card-header text-uppercase">menu detail</div>
            <div class="card-body">
                <div class="d-flex flex-wrap form-group floating-form">
                    <div class="col-12 col-sm-12 col-md-3 col-lg-3 floating-label">
                        <input 
                            type="hidden"
                            id="id"
                            name="id"
                            value="<?php echo $id; ?>"
                        />
                        <input 
                            type="text" 
                            class="form-control floating-input" 
                            id="menu_name" 
                            name="menu_name" 
                            value="<?php echo empty($master_data) ? '' : $master_data[0]['menu_name']; ?>" 
                            onkeyup="validate_textfield(this, true)" 
                            placeholder=" " 
                            autocomplete="off" 
                            tabindex="<?php echo $tabindex++; ?>"
                        />   
                        <label class="text-uppercase">name <span class="text-danger">*</span></label>
                        <small class="form-text text-muted helper-text" id="menu_name_msg"></small>
                    </div>
                    <div class="col-12 col-sm-12 col-md-3 col-lg-3 floating-label">
                        <input 
                            type="text" 
                            class="form-control floating-input text-lowercase" 
                            id="menu_js" 
                            name="menu_js" 
                            value="<?php echo empty($master_data) ? '' : $master_data[0]['menu_js']; ?>" 
                            onkeyup="validate_textfield(this, true)" 
                            placeholder=" " 
                            autocomplete="off" 
                            tabindex="<?php echo $tabindex++; ?>"
                        />   
                        <label class="text-uppercase">js <span class="text-danger">*</span></label>
                        <small class="form-text text-muted helper-text" id="menu_js_msg"></small>
                    </div>
                    <div class="col-12 col-sm-12 col-md-3 col-lg-3 floating-label">
                        <input 
                            type="checkbox" 
                            id="menu_status" 
                            name="menu_status" 
                            data-toggle="toggle" 
                            data-on="ACTIVE" 
                            data-off="INACTIVE" 
                            data-onstyle="primary" 
                            data-offstyle="primary" 
                            data-width="100" 
                            data-size="normal" 
                            tabindex="<?php echo $tabindex++; ?>"
                            <?php echo $checked ?>
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="card-header text-uppercase">sub menu detail</div>
            <div class="card-body">
                <div class="d-flex flex-wrap form-group floating-form">
                    <table class="table table-sm">
                        <tbody>
                            <tr class="floating-form">
                                <td class="floating-label" width="10%">
                                    <p class="text-uppercase">name&nbsp;<span class="text-danger">*</span></p> 
                                    <input 
                                        type="text" 
                                        class="form-control floating-input" 
                                        id="mt_name" 
                                        placeholder=" " 
                                        autocomplete="off"
                                        onkeyup="validate_textfield(this)"  
                                        tabindex="<?php echo $tabindex++; ?>"
                                    />
                                    <small class="form-text text-muted helper-text" id="mt_name_msg"></small>
                                </td>
                                <td class="floating-label" width="10%">
                                    <p class="text-uppercase">js&nbsp;<span class="text-danger">*</span></p> 
                                    <input 
                                        type="text" 
                                        class="form-control floating-input text-lowercase" 
                                        id="mt_js" 
                                        placeholder=" " 
                                        autocomplete="off"
                                        onkeyup="validate_textfield(this)"  
                                        tabindex="<?php echo $tabindex++; ?>"
                                    />
                                    <small class="form-text text-muted helper-text" id="mt_js_msg"></small>
                                </td>
                                <td class="floating-label" width="10%">
                                    <p class="text-uppercase">url&nbsp;<span class="text-danger">*</span></p> 
                                    <input 
                                        type="text" 
                                        class="form-control floating-input text-lowercase" 
                                        id="mt_url" 
                                        placeholder=" " 
                                        autocomplete="off"
                                        onkeyup="validate_textfield(this)"  
                                        tabindex="<?php echo $tabindex++; ?>"
                                    />
                                    <small class="form-text text-muted helper-text" id="mt_url_msg"></small>
                                </td>
                                <td class="floating-label" width="2%">
                                    <p class="text-uppercase">status</p> 
                                    <input 
                                        type="checkbox" 
                                        id="mt_status" 
                                        name="mt_status" 
                                        data-toggle="toggle" 
                                        data-on="ACTIVE" 
                                        data-off="INACTIVE" 
                                        data-onstyle="primary" 
                                        data-offstyle="primary" 
                                        data-width="100" 
                                        data-size="normal" 
                                        tabindex="<?php echo $tabindex++; ?>"
                                        checked
                                    />
                                    <small class="form-text text-muted helper-text" id="mt_status_msg"></small>
                                </td> 
                                <td width="2%">
                                    <button 
                                        type="button" 
                                        class="btn btn-md btn-primary" 
                                        data-toggle="tooltip" 
                                        title="ADD SUB MENU" 
                                        data-placement="top" 
                                        tabindex="<?php echo $tabindex++; ?>"
                                        onclick="add_sub_menu_row()"   
                                    ><i class="text-success fa fa-plus"></i></button>
                                </td>                  
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-sm" id="menu_wrapper"></table>
                </div>
            </div>
        </div>
    </div>
</div>