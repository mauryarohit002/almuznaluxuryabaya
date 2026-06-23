<?php
    $checked                        = !empty($master_data) && $master_data[0]['customer_status'] == 0 ? '' : 'checked'; 
    $id                             = empty($master_data) ? 0 : $master_data[0]['customer_id'];
    $uuid                           = empty($master_data) ? $uuid : $master_data[0]['customer_uuid'];
    $tabindex                       = 1;

    $customer_same_as_mobile        = !empty($master_data) && $master_data[0]['customer_same_as_mobile'] == 0 ? '' : 'checked'; 
    $customer_mobile_length         = !empty($master_data)? (10 - strlen($master_data[0]['customer_mobile'])) : 10; 
    $customer_whatsapp_length       = !empty($master_data)? (10 - strlen($master_data[0]['customer_whatsapp'])) : 10; 
    $customer_refer_type            = !empty($master_data)? $master_data[0]['customer_refer_type'] : 'OTHER'; 
    $customer_type                  = !empty($master_data)? $master_data[0]['customer_type'] : ''; 
    $customer_sms_service           = !empty($master_data) && $master_data[0]['customer_sms_service'] == 1 ? 'checked' : ''; 
    $customer_whatsapp_service      = !empty($master_data) && $master_data[0]['customer_whatsapp_service'] == 1 ? 'checked' : ''; 
    $customer_email_service         = !empty($master_data) && $master_data[0]['customer_email_service'] == 1 ? 'checked' : ''; 
    $customer_dnd_service           = !empty($master_data) && $master_data[0]['customer_dnd_service'] == 1 ? 'checked' : ''; 
?>
<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="general_content" role="tabpanel" aria-labelledby="general_tab">
                <div class="d-flex flex-wrap">
                    <div class="col-12 col-sm-12 col-md-8 col-lg-8">            
                        <div class="card">
                            <div class="card-header text-uppercase">basic detail</div>
                            <div class="card-body d-flex flex-wrap pt-4">
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-group floating-form d-flex flex-wrap">
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                                            <input
                                                type="hidden"
                                                id="id"
                                                name="id"
                                                value="<?php echo $id; ?>"
                                            />
                                            <input
                                                type="hidden"
                                                id="customer_uuid"
                                                name="customer_uuid"
                                                value="<?php echo $uuid; ?>"
                                            />
                                            <input 
                                                type="text" 
                                                class="form-control floating-input" 
                                                id="customer_no" 
                                                name="customer_no" 
                                                value="<?php echo empty($master_data) ? $customer_no : $master_data[0]['customer_id']; ?>" 
                                                readonly 
                                            />   
                                            <label class="text-uppercase">no.</label>
                                            <small class="form-text text-muted helper-text" id="customer_no_msg"></small>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                                            <input 
                                                type="text" 
                                                class="form-control floating-input" 
                                                id="customer_name" 
                                                name="customer_name" 
                                                value="<?php echo empty($master_data) ? '' : $master_data[0]['customer_name']; ?>" 
                                                onkeyup="validate_textfield(this, true)" 
                                                placeholder=" " 
                                                autocomplete="off" 
                                                tabindex="<?php echo $tabindex++; ?>" 
                                            />   
                                            <label class="text-uppercase">name <span class="text-danger">*</span></label>
                                            <small class="form-text text-muted helper-text" id="customer_name_msg"></small>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                                            <input 
                                                type="number" 
                                                class="form-control floating-input" 
                                                id="customer_mobile" 
                                                name="customer_mobile" 
                                                value="<?php echo empty($master_data) ? '' : $master_data[0]['customer_mobile']; ?>" 
                                                placeholder=" " 
                                                onkeyup="set_mobile_no(this)" 
                                                onfocusout="validate_mobile_no(this)" 
                                                autocomplete="off" 
                                                tabindex="<?php echo $tabindex++; ?>"
                                            />   
                                            <label class="text-uppercase">mobile no <span id="customer_mobile_length">(<?php echo $customer_mobile_length; ?>)</span> <span class="text-danger">*</span></label>
                                            <small class="form-text text-muted helper-text" id="customer_mobile_msg"></small>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                                            <p class="text-uppercase">
                                                <input 
                                                    type="checkbox" 
                                                    id="customer_same_as_mobile"
                                                    name="customer_same_as_mobile"
                                                    onchange="set_whatsapp_no()"
                                                    <?php echo $customer_same_as_mobile; ?>
                                                />
                                                whatsapp no 
                                                <span id="customer_whatsapp_length">
                                                    (<?php echo $customer_whatsapp_length; ?>)
                                                </span>
                                            </p>
                                            <input 
                                                type="number" 
                                                class="form-control floating-input" 
                                                id="customer_whatsapp" 
                                                name="customer_whatsapp" 
                                                value="<?php echo empty($master_data) ? '' : $master_data[0]['customer_whatsapp']; ?>" 
                                                placeholder="" 
                                                onkeyup="set_mobile_no(this)" 
                                                onfocusout="validate_mobile_no(this)" 
                                                autocomplete="off" 
                                                tabindex="<?php echo $tabindex++; ?>"
                                            />   
                                            <small class="form-text text-muted helper-text" id="customer_whatsapp_msg"></small>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                                            <input 
                                                type="number" 
                                                class="form-control floating-input" 
                                                id="customer_phone1" 
                                                name="customer_phone1" 
                                                value="<?php echo empty($master_data) ? '' : $master_data[0]['customer_phone1']; ?>" 
                                                placeholder=" " 
                                                autocomplete="off" 
                                                tabindex="<?php echo $tabindex++; ?>"
                                            />   
                                            <label class="text-uppercase">std code / telephone 1</label>
                                            <small class="form-text text-muted helper-text" id="customer_phone1_msg"></small>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                                            <input 
                                                type="number" 
                                                class="form-control floating-input" 
                                                id="customer_phone2" 
                                                name="customer_phone2" 
                                                value="<?php echo empty($master_data) ? '' : $master_data[0]['customer_phone2']; ?>" 
                                                placeholder=" " 
                                                autocomplete="off" 
                                                tabindex="<?php echo $tabindex++; ?>"
                                            />   
                                            <label class="text-uppercase">std code / telephone 2</label>
                                            <small class="form-text text-muted helper-text" id="customer_phone2_msg"></small>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                                            <input 
                                                type="email" 
                                                class="form-control floating-input text-lowercase" 
                                                id="customer_email" 
                                                name="customer_email" 
                                                value="<?php echo empty($master_data) ? '' : $master_data[0]['customer_email']; ?>" 
                                                onkeyup="validate_email(this)" 
                                                placeholder=" " 
                                                autocomplete="off" 
                                                tabindex="<?php echo $tabindex++; ?>"
                                            />   
                                            <label class="text-uppercase">email</label>
                                            <small class="form-text text-muted helper-text" id="customer_email_msg"></small>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                                            <input 
                                                type="date" 
                                                class="form-control floating-input" 
                                                id="customer_birth_date" 
                                                name="customer_birth_date" 
                                                value="<?php echo empty($master_data) ? '' : $master_data[0]['customer_birth_date']; ?>" 
                                                max="<?php echo date("Y-m-d"); ?>"
                                                placeholder=" " 
                                                autocomplete="off"
                                                tabindex="<?php echo $tabindex++; ?>"
                                            />   
                                            <label class="text-uppercase">birth date</label>
                                            <small class="form-text text-muted helper-text" id="customer_birth_date_msg"></small>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                                            <input 
                                                type="date" 
                                                class="form-control floating-input" 
                                                id="customer_anniversary_date" 
                                                name="customer_anniversary_date" 
                                                value="<?php echo empty($master_data) ? '' : $master_data[0]['customer_anniversary_date']; ?>" 
                                                max="<?php echo date("Y-m-d"); ?>"
                                                placeholder=" " 
                                                autocomplete="off"
                                                tabindex="<?php echo $tabindex++; ?>"
                                            />   
                                            <label class="text-uppercase">anniversary date</label>
                                            <small class="form-text text-muted helper-text" id="customer_anniversary_date_msg"></small>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                                            <input 
                                                type="text" 
                                                class="form-control floating-input" 
                                                id="customer_gst_no" 
                                                name="customer_gst_no" 
                                                value="<?php echo empty($master_data) ? '' : $master_data[0]['customer_gst_no']; ?>" 
                                                placeholder=" " 
                                                autocomplete="off"
                                                tabindex="<?php echo $tabindex++; ?>"
                                            />   
                                            <label class="text-uppercase">gst no</label>
                                            <small class="form-text text-muted helper-text" id="customer_gst_no_msg"></small>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                                            <p class="text-uppercase">refer type&nbsp;</p>
                                            <select 
                                                class="form-control floating-select select2" 
                                                id="customer_refer_type" 
                                                name="customer_refer_type" 
                                                onchange="set_refer_area()"
                                                placeholder=" " 
                                                tabindex="<?php echo $tabindex++; ?>""
                                            >
                                                <option value="OTHER" <?php echo $customer_refer_type == 'OTHER' ? 'selected' : ''; ?>>OTHER</option>
                                                <option value="CUSTOMER" <?php echo $customer_refer_type == 'CUSTOMER' ? 'selected' : ''; ?>>CUSTOMER</option>
                                                <option value="SUPPLIER" <?php echo $customer_refer_type == 'SUPPLIER' ? 'selected' : ''; ?>>SUPPLIER</option>
                                            </select>
                                            <small class="form-text text-muted helper-text" id="customer_type_msg"></small>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label other_area <?php echo $customer_refer_type != 'OTHER' ? 'd-none' : '' ?>">
                                            <input 
                                                type="text" 
                                                class="form-control floating-input" 
                                                id="customer_refer_by" 
                                                name="customer_refer_by" 
                                                value="<?php echo empty($master_data) ? '' : $master_data[0]['customer_refer_by']; ?>" 
                                                placeholder=" " 
                                                autocomplete="off"
                                                tabindex="<?php echo $tabindex++; ?>""
                                            />   
                                            <label class="text-uppercase">refer by</label>
                                            <small class="form-text text-muted helper-text" id="customer_refer_by_msg"></small>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label refer_area <?php echo $customer_refer_type == 'OTHER' ? 'd-none' : '' ?>">
                                            <p class="text-uppercase">refer by&nbsp;</p>
                                            <select 
                                                class="form-control floating-select" 
                                                id="customer_refer_id" 
                                                name="customer_refer_id" 
                                                placeholder=" " 
                                                tabindex="<?php echo $tabindex++; ?>""
                                            >
                                                <?php if(!empty($master_data) && !empty($master_data[0]['customer_refer_id'])): ?>
                                                    <option value="<?php echo $master_data[0]['customer_refer_id'] ?>" selected>
                                                        <?php echo $master_data[0]['refer_name']; ?> 
                                                    </option>
                                                <?php endif; ?>
                                            </select>
                                            <small class="form-text text-muted helper-text" id="customer_refer_id_msg"></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-group floating-form d-flex flex-wrap">
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                                            <input 
                                                type="number" 
                                                class="form-control floating-input" 
                                                id="customer_disc_per" 
                                                name="customer_disc_per" 
                                                value="<?php echo empty($master_data) ? '' : $master_data[0]['customer_disc_per']; ?>" 
                                                placeholder=" " 
                                                autocomplete="off" 
                                                onkeyup="validate_textfield(this)"
                                                tabindex="<?php echo $tabindex++; ?>""
                                            />   
                                            <label class="text-uppercase">discount %</label>
                                            <small class="form-text text-muted helper-text" id="customer_disc_per_msg"></small>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                                            <input 
                                                type="number" 
                                                class="form-control floating-input" 
                                                id="customer_credit_amt" 
                                                name="customer_credit_amt" 
                                                value="<?php echo empty($master_data) ? '' : $master_data[0]['customer_credit_amt']; ?>" 
                                                placeholder=" " 
                                                autocomplete="off" 
                                                onkeyup="validate_textfield(this)"
                                                tabindex="<?php echo $tabindex++; ?>""
                                            />   
                                            <label class="text-uppercase">credit limit</label>
                                            <small class="form-text text-muted helper-text" id="customer_credit_amt_msg"></small>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                                            <input 
                                                type="number" 
                                                class="form-control floating-input" 
                                                id="customer_credit_day" 
                                                name="customer_credit_day" 
                                                value="<?php echo empty($master_data) ? '' : $master_data[0]['customer_credit_day']; ?>" 
                                                placeholder=" " 
                                                autocomplete="off" 
                                                onkeyup="validate_textfield(this)"
                                                tabindex="<?php echo $tabindex++; ?>""
                                            />   
                                            <label class="text-uppercase">credit day</label>
                                            <small class="form-text text-muted helper-text" id="customer_credit_day_msg"></small>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                                            <input 
                                                type="number" 
                                                class="form-control floating-input" 
                                                id="customer_opening_amt" 
                                                name="customer_opening_amt" 
                                                value="<?php echo empty($master_data) ? '' : $master_data[0]['customer_opening_amt']; ?>" 
                                                placeholder=" " 
                                                autocomplete="off" 
                                                onkeyup="validate_textfield(this)"
                                                tabindex="<?php echo $tabindex++; ?>""
                                            />   
                                            <label class="text-uppercase">opening balance</label>
                                            <small class="form-text text-muted helper-text" id="customer_opening_amt_msg"></small>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                                            <p class="text-uppercase">grade&nbsp;</p>
                                            <select 
                                                class="form-control floating-select select2" 
                                                id="customer_type" 
                                                name="customer_type" 
                                                placeholder=" " 
                                                tabindex="<?php echo $tabindex++; ?>""
                                            >
                                                <option value="" <?php echo $customer_type == '' ? 'selected' : ''; ?>>SELECT</option>
                                                <option value="A" <?php echo $customer_type == 'A' ? 'selected' : ''; ?>>A</option>
                                                <option value="B" <?php echo $customer_type == 'B' ? 'selected' : ''; ?>>B</option>
                                                <option value="C" <?php echo $customer_type == 'C' ? 'selected' : ''; ?>>C</option>
                                                <option value="D" <?php echo $customer_type == 'D' ? 'selected' : ''; ?>>D</option>
                                                <option value="E" <?php echo $customer_type == 'E' ? 'selected' : ''; ?>>E</option>
                                            </select>
                                            <small class="form-text text-muted helper-text" id="customer_type_msg"></small>
                                        </div>
                                        
                                         <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                                            <p class="text-uppercase">branch</p>
                                            <select 
                                                class="form-control floating-select" 
                                                id="customer_branch_id" 
                                                name="customer_branch_id" 
                                                placeholder=" " 
                                                tabindex="<?php echo $tabindex++; ?>"
                                            >
                                                <?php if(!empty($master_data) && !empty($master_data[0]['customer_branch_id'])): ?>
                                                    <option value="<?php echo $master_data[0]['customer_branch_id'] ?>" selected>
                                                        <?php echo $master_data[0]['branch_name']; ?> 
                                                    </option>
                                                <?php endif; ?>
                                            </select>
                                            <small class="form-text text-muted helper-text" id="customer_branch_id_msg"></small>
                                        </div>
                                        
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                                            <input 
                                                type="checkbox" 
                                                id="customer_status" 
                                                name="customer_status" 
                                                data-toggle="toggle" 
                                                data-on="ACTIVE" 
                                                data-off="INACTIVE" 
                                                data-onstyle="primary" 
                                                data-offstyle="primary" 
                                                data-width="100" 
                                                data-size="normal" 
                                                tabindex="<?php echo $tabindex++; ?>"" 
                                                <?php echo $checked; ?>
                                            />
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label mt-4">
                                            <input 
                                                type="file" 
                                                name="customer_attachment[]" 
                                                id="customer_attachment" 
                                                class="form-control floating-input" 
                                                onchange="upload_customer_document(this, ['image/gif', 'image/jpeg', 'image/png', 'application/pdf'])" 
                                                multiple="multiple" 
                                                tabindex="<?php echo $tabindex++; ?>""
                                            >
                                            <label class="text-uppercase">attachment <small class="text-danger font-weight-bold">(.png, .jpg, .jpeg, .gif, .pdf) only</small></label>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 d-flex flex-wrap preview">
                                            <?php 
                                                if(!empty($attachment_data)):
                                                    foreach ($attachment_data as $key => $value):
                                                        $is_pdf = strpos($value['cat_path'], '.pdf') !== false;
                                            ?>
                                                        <div class="d-flex flex-column p-2" id="preview_<?php echo $value['cat_id'] ?>">
                                                            <span class="d-flex justify-content-center" style="width: 9rem; height:15rem;">
                                                                <?php if($is_pdf): ?>
                                                                    <object 
                                                                        class="img-thumbnail pan form_loading" 
                                                                        type="application/pdf" 
                                                                        data="<?php echo $value['cat_path'] ?>"
                                                                        style="max-width: 100%; max-height: 100%; object-fit: contain;"
                                                                    ></object>
                                                                <?php else: ?>
                                                                    <img 
                                                                        class="img-thumbnail pan form_loading" 
                                                                        onclick="zoom(this)" 
                                                                        title="click to zoom in and zoom out" 
                                                                        src="<?php echo assets(LAZYLOADING) ?>" 
                                                                        data-src="<?php echo $value['cat_path'] ?>" 
                                                                        data-big="<?php echo $value['cat_path'] ?>" 
                                                                        style="max-width: 100%; max-height: 100%; object-fit: contain;"
                                                                    />
                                                                <?php endif; ?>
                                                            </span>
                                                            <button 
                                                                type="button" 
                                                                class="btn btn-sm btn-primary mt-2" 
                                                                onclick="remove_preview_image('preview_<?php echo $value['cat_id'] ?>')"
                                                            >REMOVE <i class="text-danger fa fa-trash"></i></button>
                                                            <a 
                                                                type="button" 
                                                                class="btn btn-sm btn-primary mt-2"
                                                                href="<?php echo $value['cat_path']; ?>"
                                                                download
                                                            >DOWNLOAD <i class="text-info fa fa-download"></i></a>
                                                            <input type="hidden" id="cat_id_<?php echo $value['cat_id'] ?>" name="cat_id[]" value="<?php echo $value['cat_id'] ?>">
                                                            <input type="hidden" id="cat_customer_id_<?php echo $value['cat_id'] ?>" name="cat_customer_id[]" value="<?php echo $value['cat_customer_id'] ?>">
                                                            <input type="hidden" id="cat_path_<?php echo $value['cat_id'] ?>" name="cat_path[]" value="<?php echo $value['cat_path'] ?>">
                                                        </div>
                                            <?php 
                                                    endforeach;
                                                endif; 
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4">            
                        <div class="card mb-2">
                            <div class="card-header text-uppercase">address detail</div>
                            <div class="card-body d-flex flex-wrap pt-4">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label">
                                    <textarea 
                                        name="customer_address" 
                                        class="form-control floating-textarea" 
                                        id="customer_address" 
                                        placeholder=" " 
                                        autocomplete="off" 
                                        tabindex="<?php echo $tabindex++; ?>""
                                        rows="3"
                                    ><?php echo empty($master_data) ? '' : $master_data[0]['customer_address']; ?></textarea>
                                    <label class="text-uppercase">address</label>
                                    <small class="form-text text-muted helper-text" id="customer_address_msg"></small>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-4 floating-label">
                                    <input 
                                        type="text" 
                                        class="form-control floating-input" 
                                        id="customer_pincode" 
                                        name="customer_pincode" 
                                        value="<?php echo empty($master_data) ? '' : $master_data[0]['customer_pincode']; ?>" 
                                        placeholder=" " 
                                        autocomplete="off" 
                                        tabindex="<?php echo $tabindex++; ?>""
                                    />   
                                    <label class="text-uppercase">pincode</label>
                                    <small class="form-text text-muted helper-text" id="customer_pincode_msg"></small>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-4 floating-label">
                                    <p class="text-uppercase">city<span>
                                            <a 
                                                data-toggle="tooltip" 
                                                data-placement="top" 
                                                title="ADD CITY"
                                                style="cursor: pointer;" 
                                                onclick='popup(<?php echo json_encode(["sub_menu" => "city", "field" => "customer_city_id"]); ?>)'
                                            ><i class="fa fa-plus"></i></a>
                                        </span>
                                    </p>
                                    <select 
                                        class="form-control floating-select" 
                                        id="customer_city_id" 
                                        name="customer_city_id" 
                                        placeholder=" " 
                                        tabindex="<?php echo $tabindex++; ?>""
                                    >
                                        <?php if(!empty($master_data) && !empty($master_data[0]['customer_city_id'])): ?>
                                            <option value="<?php echo $master_data[0]['customer_city_id'] ?>" selected>
                                                <?php echo $master_data[0]['city_name']; ?> 
                                            </option>
                                        <?php endif; ?>
                                    </select>
                                    <small class="form-text text-muted helper-text" id="customer_state_id_msg"></small>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-4 floating-label">
                                    <p class="text-uppercase">state<span>
                                            <a 
                                                data-toggle="tooltip" 
                                                data-placement="top" 
                                                title="ADD STATE"
                                                style="cursor: pointer;" 
                                                onclick='popup(<?php echo json_encode(["sub_menu" => "state", "field" => "customer_state_id"]); ?>)'
                                            ><i class="fa fa-plus"></i></a>
                                        </span>
                                    </p>
                                    <select 
                                        class="form-control floating-select" 
                                        id="customer_state_id" 
                                        name="customer_state_id" 
                                        placeholder=" " 
                                        tabindex="<?php echo $tabindex++; ?>""
                                    >
                                        <?php if(!empty($master_data) && !empty($master_data[0]['customer_state_id'])): ?>
                                            <option value="<?php echo $master_data[0]['customer_state_id'] ?>" selected>
                                                <?php echo $master_data[0]['state_name']; ?> 
                                            </option>
                                        <?php endif; ?>
                                    </select>
                                    <small class="form-text text-muted helper-text" id="customer_state_id_msg"></small>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-4 floating-label">
                                    <p class="text-uppercase">country<span>
                                            <a 
                                                data-toggle="tooltip" 
                                                data-placement="top" 
                                                title="ADD COUNTRY"
                                                style="cursor: pointer;" 
                                                onclick='popup(<?php echo json_encode(["sub_menu" => "country", "field" => "customer_country_id"]); ?>)'
                                            ><i class="fa fa-plus"></i></a>
                                        </span>
                                    </p>
                                    <select 
                                        class="form-control floating-select" 
                                        id="customer_country_id" 
                                        name="customer_country_id" 
                                        placeholder=" " 
                                        tabindex="<?php echo $tabindex++; ?>""
                                    >
                                        <?php if(!empty($master_data) && !empty($master_data[0]['customer_country_id'])): ?>
                                            <option value="<?php echo $master_data[0]['customer_country_id'] ?>" selected>
                                                <?php echo $master_data[0]['country_name']; ?> 
                                            </option>
                                        <?php endif; ?>
                                    </select>
                                    <small class="form-text text-muted helper-text" id="customer_country_id_msg"></small>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header text-uppercase">services</div>
                            <div class="card-body d-flex flex-wrap justify-content-around">
                                <label class="custom-control material-checkbox">
                                    <input 
                                        type="checkbox" 
                                        class="material-control-input" 
                                        id="customer_sms_service" 
                                        name="customer_sms_service" 
                                        onclick="disable_dnd()"
                                        tabindex="<?php echo $tabindex++; ?>""
                                        <?php echo $customer_sms_service; ?>
                                    />
                                    <span class="material-control-indicator"></span>
                                    <span class="material-control-description text-uppercase">sms</span>
                                </label>
        
                                <label class="custom-control material-checkbox">
                                    <input 
                                        type="checkbox" 
                                        class="material-control-input" 
                                        id="customer_whatsapp_service" 
                                        name="customer_whatsapp_service" 
                                        onclick="disable_dnd()"
                                        tabindex="<?php echo $tabindex++; ?>""
                                        <?php echo $customer_whatsapp_service; ?>
                                    />
                                    <span class="material-control-indicator"></span>
                                    <span class="material-control-description text-uppercase">whatsapp</span>
                                </label>
        
                                <label class="custom-control material-checkbox">
                                    <input 
                                        type="checkbox" 
                                        class="material-control-input" 
                                        id="customer_email_service" 
                                        name="customer_email_service" 
                                        onclick="disable_dnd()"
                                        tabindex="<?php echo $tabindex++; ?>""
                                        <?php echo $customer_email_service; ?>
                                    />
                                    <span class="material-control-indicator"></span>
                                    <span class="material-control-description text-uppercase">email</span>
                                </label>
        
                                <label class="custom-control material-checkbox">
                                    <input 
                                        type="checkbox" 
                                        class="material-control-input" 
                                        id="customer_dnd_service" 
                                        name="customer_dnd_service" 
                                        onclick="disable_service()"
                                        tabindex="<?php echo $tabindex++; ?>""
                                        <?php echo $customer_dnd_service; ?>
                                    />
                                    <span class="material-control-indicator"></span>
                                    <span class="material-control-description text-uppercase">dnd</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="measurement_content" role="tabpanel" aria-labelledby="measurement_tab">
                <div class="d-flex flex-wrap"> 
                    <div class="col-12 col-sm-12 col-md-4 col-lg-3">      
                        <div class="card">
                            <div class="card-header text-uppercase">
                                <table>
                                    <thead>
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="50%" class="text-center">apparel</th>
                                            <th width="10%" class="text-right">
                                                <a 
                                                    type="button" 
                                                    class="btn btn-sm"
                                                    target="_blank"
                                                    data-toggle="tooltip" 
                                                    data-placement="bottom" 
                                                    title="ALL MEASUREMENT"
                                                    href="<?php echo base_url($menu.'/'.$sub_menu.'/measurement_print/'.$id) ?>"
                                                ><i class="text-info fa fa-print"></i></a>                                        
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="card-body" style="max-height: 70vh; overflow-x: auto;">
                                <?php if(!empty($apparel_data) ): ?>
                                    <table class="table table-sm table-hover">
                                        <tbody>
                                            <?php foreach ($apparel_data as $key => $value): ?>
                                                <tr 
                                                    class="apparel_tr"
                                                    id="apparel_tr_<?php echo $value['apparel_id']; ?>" 
                                                    onclick="get_measurement(<?php echo $value['apparel_id']; ?>)"
                                                    style="cursor: pointer;"
                                                >
                                                    <td class="border-0" width="5%"><?php echo $key+1; ?></td>
                                                    <td class="border-0" width="50%">
                                                        <span id="_apparel_name_<?php echo $value['apparel_id'] ?>"><?php echo $value['apparel_name']; ?></span>
                                                    </td>
                                                    <td class="border-0 text-right" width="10%">
                                                        <a 
                                                            type="button" 
                                                            class="btn btn-sm"
                                                            target="_blank"
                                                            data-toggle="tooltip" 
                                                            data-placement="LEFT" 
                                                            title="MEASUREMENT"
                                                            href="<?php echo base_url($menu.'/'.$sub_menu.'/measurement_print/'.$id.'/'.$value['apparel_id']) ?>"
                                                        ><i class="text-info fa fa-print"></i></a>  
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php else:?>
                                    <div class="font-weight-bold text-center text-danger text-uppercase">no record found !!!</div>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-4 col-lg-5">      
                        <div class="card">
                            <div class="card-header text-uppercase text-center d-flex flex-wrap justify-content-between">
                                <p class="_apparel_name"></p>
                                <p id="measurement_bill_no"></p>
                            </div>
                            <div class="card-body d-flex flex-wrap p-0">
                                <table class="table table-sm table-dark text-uppercase">
                                    <thead>
                                        <tr style="font-size: 14px;">
                                            <th width="5%">#</th>
                                            <th width="25%">measurement</th>
                                            <th width="10%">value</th>
                                            <!-- <th width="10%">value 2</th> -->
                                        </tr>
                                    </thead>
                                </table>
                                <div style="width:100%; max-height: 55vh; overflow-x: auto; font-size: 1.5rem;">
                                    <table class="table table-sm table-hover text-uppercase font-weight-bold">
                                        <tbody id="measurement_wrapper">
                                            <tr>
                                                <td class="text-danger text-center font-weight-bold">no record found !!!</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>