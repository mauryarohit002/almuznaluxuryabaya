<?php
    $checked                    = !empty($master_data) && $master_data[0]['karigar_status'] == 0 ? '' : 'checked'; 
    $karigar_mobile_length      = !empty($master_data)? (10 - strlen($master_data[0]['karigar_mobile'])) : 10; 
    
    $id                         = empty($master_data) ? 0 : $master_data[0]['karigar_id'];
    $tabindex                   = 1;
?>
<style>
    body{
        color: #000000 !important;
    }
</style>
<div class="row">
    <div class="col-12 col-sm-12 col-md-8 col-lg-8">
        <div class="card">
            <div class="card-header text-uppercase">general detail</div>
            <div class="card-body p-0">
                <div class="form-group floating-form d-flex flex-wrap">
                    <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                        <div class="d-flex flex-column align-items-center p-2">
                            <span class="d-flex justify-content-center mb-3" id="preview" style="width: 9rem; height:15rem;">
                                <img 
                                    class="pan form_loading" 
                                    onclick="zoom(this)" 
                                    title="click to zoom in and zoom out" 
                                    src="<?php echo assets(LAZYLOADING) ?>" 
                                    data-src="<?php echo empty($master_data) ? assets(USERIMAGE) : $master_data[0]['karigar_image']; ?>" 
                                    data-big="<?php echo empty($master_data) ? assets(USERIMAGE) : $master_data[0]['karigar_image']; ?>" 
                                    style="max-width: 100%; max-height: 100%; object-fit: contain;"
                                />
                            </span>
                            <label class="text-uppercase"> <small class="text-danger font-weight-bold">(.jpg, .jpeg,) only</small></label>
                            <input 
                                type="file"  
                                id="karigar_image" 
                                name="karigar_image" 
                                class="form-control floating-input mb-3" 
                                onchange="preview_image(this)"
                                tabindex="<?php echo $tabindex++; ?>"
                                accept="image/*"
                            />
                            <button 
                                type="button" 
                                class="btn btn-sm btn-block btn-primary" 
                                onclick="remove_karigar_image()"
                                tabindex="<?php echo $tabindex++; ?>"
                            >REMOVE <i class="text-danger fa fa-trash"></i></button>
                            <input 
                                type="hidden" 
                                id="karigar_pic" 
                                name="karigar_pic" 
                                value="<?php echo empty($master_data) ? assets(USERIMAGE) : $master_data[0]['karigar_image']; ?>"
                            />
                            <input 
                                type="hidden" 
                                id="id" 
                                name="id" 
                                value="<?php echo $id; ?>"
                            />
                        </div>

                        <br><br><br>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                            <input 
                                type="checkbox" 
                                id="karigar_status" 
                                name="karigar_status" 
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
                    <div class="col-12 col-sm-12 col-md-9 col-lg-9 pt-4 d-flex flex-wrap">
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                            <input 
                                type="text" 
                                class="form-control floating-input" 
                                id="karigar_code" 
                                name="karigar_code" 
                                value="<?php echo empty($master_data) ? '' : $master_data[0]['karigar_code']; ?>" 
                                onkeyup="validate_textfield(this, true)"
                                placeholder=" " 
                                autocomplete="off" 
                                tabindex="<?php echo $tabindex++; ?>"
                            />   
                            <label class="text-uppercase">code <span class="text-danger">*</span></label>
                            <small class="form-text text-muted helper-text" id="karigar_code_msg"></small>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                            <input 
                                type="text" 
                                class="form-control floating-input" 
                                id="karigar_name" 
                                name="karigar_name" 
                                value="<?php echo empty($master_data) ? '' : $master_data[0]['karigar_name']; ?>" 
                                onkeyup="validate_textfield(this, true)"
                                placeholder=" " 
                                autocomplete="off" 
                                tabindex="<?php echo $tabindex++; ?>"
                            />   
                            <label class="text-uppercase">name <span class="text-danger">*</span></label>
                            <small class="form-text text-muted helper-text" id="karigar_name_msg"></small>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                            <p class="text-uppercase">Karigar Type</p>
                            <select 
                                class="form-control floating-select" 
                                id="karigar_type_id" 
                                name="karigar_type_id" 
                                placeholder=" " 
                                tabindex="<?php echo $tabindex++; ?>">
                                <?php
                                    $selected_value = isset($master_data[0]['karigar_type_id']) ? $master_data[0]['karigar_type_id'] : 0; // default to 0 (Other)
                                ?>
                                <option value="0" <?php echo ($selected_value == 0) ? 'selected' : ''; ?>>Other</option>
                                <option value="1" <?php echo ($selected_value == 1) ? 'selected' : ''; ?>>Manager</option>
                            </select>
                            <small class="form-text text-muted helper-text" id="karigar_type_id_msg"></small>
                        </div> 
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                            <input 
                                type="number" 
                                class="form-control floating-input" 
                                id="karigar_mobile" 
                                name="karigar_mobile" 
                                value="<?php echo empty($master_data) ? '' : $master_data[0]['karigar_mobile']; ?>" 
                                onkeyup="set_mobile_no(this)" 
                                onfocusout="validate_mobile_no(this)"
                                placeholder=" " 
                                autocomplete="off"
                                tabindex="<?php echo $tabindex++; ?>"
                            />   
                            <label class="text-uppercase">mobile no. <span id="karigar_mobile_length">(<?php echo $karigar_mobile_length; ?>)</span></label>
                            <small class="form-text text-muted helper-text" id="karigar_mobile_msg"></small>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                            <input 
                                type="text" 
                                class="form-control floating-input" 
                                id="karigar_password" 
                                name="karigar_password" 
                                value="" 
                                placeholder=" " 
                                autocomplete="off"
                                tabindex="<?php echo $tabindex++; ?>"
                            />   
                            <label class="text-uppercase">Password</label>
                            <small class="form-text text-muted helper-text" id="karigar_password_msg"></small>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                            <input 
                                type="email" 
                                class="form-control floating-input" 
                                id="karigar_email" 
                                name="karigar_email" 
                                value="<?php echo empty($master_data) ? '' : $master_data[0]['karigar_email']; ?>" 
                                onfocusout="validate_email(this)" 
                                placeholder=" " 
                                autocomplete="off"
                                tabindex="<?php echo $tabindex++; ?>"
                                style="text-transform: lowercase;"
                            />   
                            <label class="text-uppercase">email</label>
                            <small class="form-text text-muted helper-text" id="karigar_email_msg"></small>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                            <input 
                                type="text" 
                                class="form-control floating-input" 
                                id="karigar_refer_by" 
                                name="karigar_refer_by" 
                                value="<?php echo empty($master_data) ? '' : $master_data[0]['karigar_refer_by']; ?>" 
                                placeholder=" " 
                                autocomplete="off"
                                tabindex="<?php echo $tabindex++; ?>"
                            />   
                            <label class="text-uppercase">refer by</label>
                            <small class="form-text text-muted helper-text" id="karigar_refer_by_msg"></small>
                        </div>
                        
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                            <input 
                                type="text"
                                class="form-control floating-input"
                                id="karigar_aadhaar"
                                name="karigar_aadhaar"
                                value="<?php echo empty($master_data) ? '' : $master_data[0]['karigar_aadhaar']; ?>"
                                maxlength="12"
                                onkeyup="this.value=this.value.replace(/[^0-9]/g,'')"
                                placeholder=" "
                                tabindex="<?php echo $tabindex++; ?>"
                            />
                            <label class="text-uppercase">aadhaar card</label>
                            <small class="form-text text-muted helper-text" id="karigar_aadhaar_msg"></small>
                        </div>

                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                            <input 
                                type="text"
                                class="form-control floating-input"
                                id="karigar_pan"
                                name="karigar_pan"
                                value="<?php echo empty($master_data) ? '' : $master_data[0]['karigar_pan']; ?>"
                                maxlength="10"
                                style="text-transform: uppercase;"
                                placeholder=" "
                                tabindex="<?php echo $tabindex++; ?>"
                            />
                            <label class="text-uppercase">pan card</label>
                            <small class="form-text text-muted helper-text" id="karigar_pan_msg"></small>
                        </div>

                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                            <textarea 
                                name="karigar_address" 
                                class="form-control floating-textarea" 
                                id="karigar_address" 
                                placeholder=" " 
                                autocomplete="off" 
                                tabindex="<?php echo $tabindex++; ?>"
                                rows="6"
                            ><?php echo empty($master_data) ? '' : $master_data[0]['karigar_address']; ?></textarea>
                            <label class="text-uppercase">address</label>
                            <small class="form-text text-muted helper-text" id="karigar_address_msg"></small>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label">
                                <input 
                                    type="text" 
                                    class="form-control floating-input" 
                                    id="karigar_pincode" 
                                    name="karigar_pincode" 
                                    value="<?php echo empty($master_data) ? '' : $master_data[0]['karigar_pincode']; ?>" 
                                    placeholder=" " 
                                    autocomplete="off"
                                    tabindex="<?php echo $tabindex++; ?>"
                                />   
                                <label class="text-uppercase">pincode</label>
                                <small class="form-text text-muted helper-text" id="karigar_pincode_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label">
                                <p class="text-uppercase">
                                    city
                                    <span>
                                        <a 
                                            data-toggle="tooltip" 
                                            data-placement="top" 
                                            title="ADD CITY"
                                            style="cursor: pointer;" 
                                            onclick='popup(<?php echo json_encode(["sub_menu" => "city", "field" => "karigar_city_id"]) ?>)' 
                                        ><i class="fa fa-plus"></i></a>
                                    </span>
                                </p>
                                <select 
                                    class="form-control floating-select" 
                                    id="karigar_city_id" 
                                    name="karigar_city_id" 
                                    placeholder=" " 
                                    tabindex="<?php echo $tabindex++; ?>"
                                >
                                    <?php if(!empty($master_data) && !empty($master_data[0]['karigar_city_id'])): ?>
                                        <option value="<?php echo $master_data[0]['karigar_city_id'] ?>" selected>
                                            <?php echo $master_data[0]['city_name']; ?> 
                                        </option>
                                    <?php endif; ?>
                                </select>
                                <small class="form-text text-muted helper-text" id="karigar_city_id_msg"></small>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                            <p class="text-uppercase">
                                state
                                <span>
                                    <a 
                                        data-toggle="tooltip" 
                                        data-placement="top" 
                                        title="ADD STATE"
                                        style="cursor: pointer;" 
                                        onclick='popup(<?php echo json_encode(["sub_menu" => "state", "field" => "karigar_state_id"]) ?>)' 
                                    ><i class="fa fa-plus"></i></a>
                                </span>
                            </p>
                            <select 
                                class="form-control floating-select" 
                                id="karigar_state_id" 
                                name="karigar_state_id" 
                                placeholder=" " 
                                tabindex="<?php echo $tabindex++; ?>"
                            >
                                <?php if(!empty($master_data) && !empty($master_data[0]['karigar_state_id'])): ?>
                                    <option value="<?php echo $master_data[0]['karigar_state_id'] ?>" selected>
                                        <?php echo $master_data[0]['state_name']; ?> 
                                    </option>
                                <?php endif; ?>
                            </select>
                            <small class="form-text text-muted helper-text" id="karigar_state_id_msg"></small>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                            <p class="text-uppercase">
                                country
                                <span>
                                    <a 
                                        data-toggle="tooltip" 
                                        data-placement="top" 
                                        title="ADD COUTRY"
                                        style="cursor: pointer;" 
                                        onclick='popup(<?php echo json_encode(["sub_menu" => "country", "field" => "karigar_country_id"]) ?>)' 
                                    ><i class="fa fa-plus"></i></a>
                                </span>
                            </p>
                            <select 
                                class="form-control floating-select" 
                                id="karigar_country_id" 
                                name="karigar_country_id" 
                                placeholder=" " 
                                tabindex="<?php echo $tabindex++; ?>"
                            >
                                <?php if(!empty($master_data) && !empty($master_data[0]['karigar_country_id'])): ?>
                                    <option value="<?php echo $master_data[0]['karigar_country_id'] ?>" selected>
                                        <?php echo $master_data[0]['country_name']; ?> 
                                    </option>
                                <?php endif; ?>
                            </select>
                            <small class="form-text text-muted helper-text" id="karigar_country_id_msg"></small>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                            <p class="text-uppercase">copy process</p>
                            <select 
                                class="form-control floating-select" 
                                id="_proces_id" 
                                placeholder=" " 
                                tabindex="<?php echo $tabindex++; ?>"
                            ></select>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                            <p class="text-uppercase">copy apparel</p>
                            <select 
                                class="form-control floating-select" 
                                id="_apparel_id" 
                                placeholder=" " 
                                tabindex="<?php echo $tabindex++; ?>"
                            ></select>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-12 col-md-4 col-lg-4">
        <div class="card">
            <div class="card-header text-uppercase">attachment detail</div>
            <div class="card-body pt-4">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label">
                    <p class="text-uppercase"><small class="text-danger font-weight-bold">(.png, .jpg, .jpeg, .gif, .pdf) only</small></p>
                    <input 
                        type="file" 
                        class="form-control floating-input mt-3" 
                        id="karigar_attachment" 
                        name="karigar_attachment[]" 
                        onchange="upload_document(this, ['image/gif', 'image/jpeg', 'image/png', 'application/pdf'])" 
                        multiple="multiple" 
                        tabindex="<?php echo $tabindex++; ?>"
                        accept="image/*,.pdf"
                    >
                </div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 d-flex flex-wrap preview"></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-12 col-md-12 col-lg-12 mt-2">
        <div class="card">
            <div class="card-header text-uppercase">bank details</div>
            <div class="card-body d-flex flex-wrap p-4">

                <div class="col-12 col-sm-12 col-md-6 col-lg-4 pb-4 floating-label">
                    <input type="text" class="form-control floating-input"
                        id="karigar_bank_name" name="karigar_bank_name"
                        value="<?php echo empty($master_data) ? '' : $master_data[0]['karigar_bank_name']; ?>"
                        placeholder=" " tabindex="<?php echo $tabindex++; ?>">
                    <label class="text-uppercase">bank name</label>
                </div>

                <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                    <input type="text" class="form-control floating-input"
                        id="karigar_account_no" name="karigar_account_no"
                        value="<?php echo empty($master_data) ? '' : $master_data[0]['karigar_account_no']; ?>"
                        onkeyup="this.value=this.value.replace(/[^0-9]/g,'')"
                        placeholder=" " tabindex="<?php echo $tabindex++; ?>">
                    <label class="text-uppercase">account no</label>
                </div>

                <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                    <input type="text" class="form-control floating-input"
                        id="karigar_account_name" name="karigar_account_name"
                        value="<?php echo empty($master_data) ? '' : $master_data[0]['karigar_account_name']; ?>"
                        placeholder=" " tabindex="<?php echo $tabindex++; ?>">
                    <label class="text-uppercase">account name</label>
                </div>

                <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                    <input type="text" class="form-control floating-input"
                        id="karigar_branch_name" name="karigar_branch_name"
                        value="<?php echo empty($master_data) ? '' : $master_data[0]['karigar_branch_name']; ?>"
                        placeholder=" " tabindex="<?php echo $tabindex++; ?>">
                    <label class="text-uppercase">branch name</label>
                </div>

                <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                    <input type="text" class="form-control floating-input"
                        id="karigar_ifsc_code" name="karigar_ifsc_code"
                        value="<?php echo empty($master_data) ? '' : $master_data[0]['karigar_ifsc_code']; ?>"
                        maxlength="11"
                        style="text-transform: uppercase;"
                        placeholder=" " tabindex="<?php echo $tabindex++; ?>">
                    <label class="text-uppercase">ifsc code</label>
                </div>

            </div>
        </div>
    </div>

    <div class="col-12 col-sm-12 col-md-12 col-lg-12 mt-2">
        <div class="card">
            <div class="card-header text-uppercase d-flex justify-content-between align-items-center">
                family details
                <button 
                    type="button" 
                    class="btn btn-sm btn-primary"
                    onclick="add_family_row()"
                >
                    ADD <i class="fa fa-plus"></i>
                </button>
            </div>

            <div class="card-body p-3">

                <!-- Header -->
                <div class="row text-uppercase font-weight-bold mb-2" style="font-size:0.75rem;">
                    <div class="col-12 col-md-3">relation</div>
                    <div class="col-12 col-md-4">name</div>
                    <div class="col-12 col-md-3">mobile no</div>
                    <div class="col-12 col-md-2 text-center">remove</div>
                </div>

                <!-- Wrapper -->
                <div id="family_wrapper">
                    <?php if(!empty($family_trans)): ?>
                        <?php foreach($family_trans as $row): ?>
                            <div class="row mb-2 family-row">
                                <input type="hidden" name="family_id[]" value="<?php echo $row['kft_id']; ?>">

                                <div class="col-md-3">
                                    <select name="family_relation[]" class="form-control">
                                        <option value="">Select</option>
                                        <?php
                                            $relations = ['Father','Mother','Wife','Husband','Son','Daughter','Brother','Sister','Other'];
                                            foreach($relations as $rel){
                                                $sel = ($row['kft_relation'] == $rel) ? 'selected' : '';
                                                echo "<option value='{$rel}' {$sel}>{$rel}</option>";
                                            }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <input type="text" name="family_name[]" class="form-control"
                                        value="<?php echo $row['kft_name']; ?>">
                                </div>

                                <div class="col-md-3">
                                    <input type="text" name="family_mobile[]" class="form-control"
                                        maxlength="10"
                                        onkeyup="this.value=this.value.replace(/[^0-9]/g,'')"
                                        value="<?php echo $row['kft_mobile']; ?>">
                                </div>

                                <div class="col-md-2 text-center">
                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="remove_family_row(this)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- No record message -->
                        <div class="row">
                            <div class="col-12 text-center text-muted py-3" style="font-size:0.85rem;">
                                No family details found. Click <b>ADD +</b> to add family member.
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>

    <div class="col-12 col-sm-12 col-md-6 col-lg-6 mt-2">
        <div class="card">
            <div class="card-header text-uppercase">define process</div>
            <div class="card-body pt-4 d-flex flex-wrap">
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 mt-4 floating-label">
                    <p class="text-uppercase">process
                        <span>
                            <a 
                            data-toggle="tooltip" 
                            data-placement="top" 
                            title="ADD PROCESS"
                            style="cursor: pointer;" 
                            onclick='popup(<?php echo json_encode(["sub_menu" => "proces", "field" => "proces_id"]); ?>)'
                            ><i class="fa fa-plus"></i></a>
                        </span>
                        </p>
                        <select 
                        class="form-control floating-select" 
                        id="proces_id" 
                        name="proces_id" 
                        placeholder=" "
                        ></select>
                        <small class="form-text text-muted helper-text" id="proces_id_msg"></small>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 d-flex text-uppercase font-weight-bold" style="font-size:0.7rem;">
                        <div class="mr-1" style="width: 70%;">process</div>
                        <div class="ml-1" style="width: 30%;">remove</div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12" id="proces_wrapper"></div>
                </div>
            </div>
        </div>
    </div>
    <!--<div class="col-12 col-sm-12 col-md-6 col-lg-6 mt-2">-->
    <!--    <div class="card">-->
    <!--        <div class="card-header text-uppercase">define apparel</div>-->
    <!--        <div class="card-body pt-4 d-flex flex-wrap">-->
    <!--            <div class="col-12 col-sm-12 col-md-6 col-lg-4 mt-4 floating-label">-->
    <!--                <p class="text-uppercase">apparel</p>-->
    <!--                <select -->
    <!--                    class="form-control floating-select" -->
    <!--                    id="apparel_id" -->
    <!--                    name="apparel_id" -->
    <!--                    placeholder=" "-->
    <!--                ></select>-->
    <!--                <small class="form-text text-muted helper-text" id="apparel_id_msg"></small>-->
    <!--            </div>-->
    <!--            <div class="col-12 col-sm-12 col-md-6 col-lg-8 floating-label">-->
    <!--                <div class="col-12 col-sm-12 col-md-12 col-lg-12 d-flex text-uppercase font-weight-bold" style="font-size:0.7rem;">-->
    <!--                    <div class="mx-1" style="width: 40%;">apparel</div>-->
    <!--                    <div class="mx-1" style="width: 40%;">prod. qty</div>-->
    <!--                    <div class="mx-1" style="width: 30%;">rate</div>-->
    <!--                    <div class="mx-1" style="width: 20%;">remove</div>-->
    <!--                </div>-->
    <!--                <div class="col-12 col-sm-12 col-md-12 col-lg-12" id="apparel_wrapper"></div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div>-->
</div>