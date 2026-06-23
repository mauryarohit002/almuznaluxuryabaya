<?php
    $checked    = !empty($master_data) && $master_data[0]['sku_status'] == 0 ? '' : 'checked'; 
    $id         = empty($master_data) ? 0 : $master_data[0]['sku_id'];
    $uuid       = empty($master_data) ? $sku_uuid : $master_data[0]['sku_uuid'];
    $tabindex   = 1;
?>
<div class="row">
    <div class="col-12 col-sm-12 col-md-4 col-lg-4">
        <div class="card">
            <div class="card-header text-uppercase">sku image</div>
            <div class="card-body p-0">
                <div class="form-group floating-form d-flex flex-wrap">
                    <div class="d-none">
                        <input 
                            type="hidden" 
                            id="sku_uuid" 
                            name="sku_uuid" 
                            value="<?php echo $uuid; ?>"
                        />
                        <input 
                            type="hidden" 
                            id="id" 
                            name="id" 
                            value="<?php echo $id; ?>"
                        />
                        <input 
                            type="hidden" 
                            id="sdt_id" 
                            name="sdt_id" 
                            value="0"
                        />
                        <input 
                            type="hidden" 
                            id="sku_pic" 
                            name="sku_pic" 
                            value="<?php echo empty($master_data) ? assets(NOIMAGE) : $master_data[0]['sku_image']; ?>"
                        />
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 d-flex flex-wrap">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                            <span class="d-flex justify-content-center mb-3" id="preview" style="width: 13rem; height:15rem;">
                                <img 
                                    class="img-thumbnail pan form_loading" 
                                    onclick="zoom(this)" 
                                    title="click to zoom in and zoom out" 
                                    src="<?php echo assets(LAZYLOADING) ?>" 
                                    data-src="<?php echo empty($master_data) ? assets(NOIMAGE) : $master_data[0]['sku_image']; ?>" 
                                    data-big="<?php echo empty($master_data) ? assets(NOIMAGE) : $master_data[0]['sku_image']; ?>" 
                                    style="max-width: 100%; max-height: 100%; object-fit: contain;"
                                />
                            </span>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                            <div class="d-flex flex-column align-items-center p-2">
                                <label class="text-uppercase"> <small class="text-danger font-weight-bold">(.jpg, .jpeg,) only</small></label>
                                <div class="d-flex flex-column">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                        <input 
                                            type="file"  
                                            id="sku_photo" 
                                            name="sku_photo" 
                                            class="form-control floating-input mb-3" 
                                            onchange="preview_image(this)"
                                            tabindex="<?php echo $tabindex++; ?>"
                                            accept="image/*"
                                        />
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                        <button 
                                            type="button" 
                                            class="btn btn-sm btn-block btn-primary mb-3" 
                                            onclick="remove_sku_image()"
                                            tabindex="<?php echo $tabindex++; ?>"
                                        >REMOVE <i class="text-danger fa fa-trash"></i></button>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                        <input 
                                            type="checkbox" 
                                            id="sku_status" 
                                            name="sku_status" 
                                            data-toggle="toggle" 
                                            data-on="ACTIVE" 
                                            data-off="INACTIVE" 
                                            data-onstyle="primary" 
                                            data-offstyle="primary" 
                                            data-width="120" 
                                            data-size="normal" 
                                            tabindex="<?php echo $tabindex++; ?>"
                                            <?php echo $checked ?>
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-12 col-md-8 col-lg-8">
        <div class="card">
            <div class="card-header text-uppercase">general detail</div>
            <div class="card-body p-0">
                <?php
                    $isExist = !empty($master_data[0]['isExist']) && $master_data[0]['isExist'] == 1;
                ?>
                <div class="form-group floating-form d-flex flex-wrap mt-4">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 floating-label">
                        <p class="text-uppercase">apparel <span class="text-danger">*</span></p>
                        <select 
                            class="form-control floating-select" 
                            id="sku_apparel_id" 
                            name="sku_apparel_id" 
                            placeholder=" " 
                            tabindex="<?php echo $tabindex++; ?>"
                            onkeyup="validate_dropdown(this, true)"
                            <?php echo $isExist ? 'disabled' : 'name="sku_apparel_id"'; ?>
                        >
                            <?php if(!empty($master_data) && !empty($master_data[0]['sku_apparel_id'])): ?>
                                <option value="<?php echo $master_data[0]['sku_apparel_id'] ?>" selected>
                                    <?php echo $master_data[0]['apparel_name']; ?> 
                                </option>
                            <?php endif; ?>
                            <?php if($isExist): ?>
                                <input type="hidden"
                                    name="sku_apparel_id"
                                    value="<?php echo $master_data[0]['sku_apparel_id']; ?>">
                            <?php endif; ?>
                        </select>
                        <small class="form-text text-muted helper-text" id="sku_apparel_id_msg"></small>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 floating-label">
                        <input 
                            type="text" 
                            class="form-control floating-input" 
                            id="sku_name" 
                            name="sku_name" 
                            value="<?php echo empty($master_data) ? '' : $master_data[0]['sku_name']; ?>" 
                            onkeyup="validate_textfield(this, true)"
                            placeholder=" " 
                            autocomplete="off" 
                            tabindex="<?php echo $tabindex++; ?>"
                        />   
                        <label class="text-uppercase">name <span class="text-danger">*</span></label>
                        <small class="form-text text-muted helper-text" id="sku_name_msg"></small>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 floating-label">
                        <p class="text-uppercase">supplier <span class="text-danger">*</span></p>
                        <select 
                            class="form-control floating-select" 
                            id="sku_supplier_id" 
                            name="sku_supplier_id" 
                            placeholder=" " 
                            tabindex="<?php echo $tabindex++; ?>"
                            onkeyup="validate_dropdown(this, true)"
                            <?php echo $isExist ? 'disabled' : 'name="sku_supplier_id"'; ?>
                        >
                            <?php if(!empty($master_data) && !empty($master_data[0]['sku_supplier_id'])): ?>
                                <option value="<?php echo $master_data[0]['sku_supplier_id'] ?>" selected>
                                    <?php echo $master_data[0]['supplier_name']; ?> 
                                </option>
                            <?php endif; ?>
                        </select>
                        <small class="form-text text-muted helper-text" id="sku_supplier_id_msg"></small>
                        <?php if($isExist): ?>
                            <input type="hidden"
                                name="sku_supplier_id"
                                value="<?php echo $master_data[0]['sku_supplier_id']; ?>">
                        <?php endif; ?>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 floating-label">
                        <input 
                            type="number" 
                            class="form-control floating-input" 
                            id="sku_fabric" 
                            name="sku_fabric" 
                            oninput="calculate_cp()"
                            value="<?php echo empty($master_data) ? '' : $master_data[0]['sku_fabric']; ?>" 
                            onkeyup="validate_textfield(this, true)"
                            placeholder=" " 
                            autocomplete="off" 
                            tabindex="<?php echo $tabindex++; ?>"
                        />   
                        <label class="text-uppercase">fabric</label>
                        <small class="form-text text-muted helper-text" id="sku_fabric_msg"></small>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 floating-label">
                        <input 
                            type="number" 
                            class="form-control floating-input" 
                            id="sku_cutting" 
                            name="sku_cutting" 
                            oninput="calculate_cp()"
                            value="<?php echo empty($master_data) ? '' : $master_data[0]['sku_cutting']; ?>" 
                            onkeyup="validate_textfield(this, true)"
                            placeholder=" " 
                            autocomplete="off" 
                            tabindex="<?php echo $tabindex++; ?>"
                        />   
                        <label class="text-uppercase">cutting</label>
                        <small class="form-text text-muted helper-text" id="sku_cutting_msg"></small>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 floating-label">
                        <input 
                            type="number" 
                            class="form-control floating-input" 
                            id="sku_silai" 
                            name="sku_silai" 
                            oninput="calculate_cp()"
                            value="<?php echo empty($master_data) ? '' : $master_data[0]['sku_silai']; ?>" 
                            onkeyup="validate_textfield(this, true)"
                            placeholder=" " 
                            autocomplete="off" 
                            tabindex="<?php echo $tabindex++; ?>"
                        />   
                        <label class="text-uppercase">stitch</label>
                        <small class="form-text text-muted helper-text" id="sku_silai_msg"></small>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 floating-label">
                        <input 
                            type="number" 
                            class="form-control floating-input" 
                            id="sku_stone" 
                            name="sku_stone" 
                            oninput="calculate_cp()"
                            value="<?php echo empty($master_data) ? '' : $master_data[0]['sku_stone']; ?>" 
                            onkeyup="validate_textfield(this, true)"
                            placeholder=" " 
                            autocomplete="off" 
                            tabindex="<?php echo $tabindex++; ?>"
                        />   
                        <label class="text-uppercase">stone</label>
                        <small class="form-text text-muted helper-text" id="sku_stone_msg"></small>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 floating-label">
                        <input 
                            type="number" 
                            class="form-control floating-input" 
                            id="sku_lagwayi" 
                            name="sku_lagwayi" 
                            oninput="calculate_cp()"
                            value="<?php echo empty($master_data) ? '' : $master_data[0]['sku_lagwayi']; ?>" 
                            onkeyup="validate_textfield(this, true)"
                            placeholder=" " 
                            autocomplete="off" 
                            tabindex="<?php echo $tabindex++; ?>"
                        />   
                        <label class="text-uppercase">lagwayi</label>
                        <small class="form-text text-muted helper-text" id="sku_lagwayi_msg"></small>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 floating-label">
                        <input 
                            type="number" 
                            class="form-control floating-input" 
                            id="sku_hand_work" 
                            name="sku_hand_work" 
                            oninput="calculate_cp()"
                            value="<?php echo empty($master_data) ? '' : $master_data[0]['sku_hand_work']; ?>" 
                            onkeyup="validate_textfield(this, true)"
                            placeholder=" " 
                            autocomplete="off" 
                            tabindex="<?php echo $tabindex++; ?>"
                        />   
                        <label class="text-uppercase">hand work</label>
                        <small class="form-text text-muted helper-text" id="sku_hand_work_msg"></small>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 floating-label">
                        <input 
                            type="number" 
                            class="form-control floating-input" 
                            id="sku_material" 
                            name="sku_material" 
                            oninput="calculate_cp()"
                            value="<?php echo empty($master_data) ? '' : $master_data[0]['sku_material']; ?>" 
                            onkeyup="validate_textfield(this, true)"
                            placeholder=" " 
                            autocomplete="off" 
                            tabindex="<?php echo $tabindex++; ?>"
                        />   
                        <label class="text-uppercase">material</label>
                        <small class="form-text text-muted helper-text" id="sku_material_msg"></small>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 floating-label">
                        <input 
                            type="number" 
                            class="form-control floating-input" 
                            id="sku_exp" 
                            name="sku_exp" 
                            oninput="calculate_cp()"
                            value="<?php echo empty($master_data) ? '' : $master_data[0]['sku_exp']; ?>" 
                            onkeyup="validate_textfield(this, true)"
                            placeholder=" " 
                            autocomplete="off" 
                            tabindex="<?php echo $tabindex++; ?>"
                        />   
                        <label class="text-uppercase">exp</label>
                        <small class="form-text text-muted helper-text" id="sku_exp_msg"></small>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 floating-label">
                        <input 
                            type="number" 
                            class="form-control floating-input" 
                            id="sku_cp" 
                            name="sku_cp" 
                            value="<?php echo empty($master_data) ? '' : $master_data[0]['sku_cp']; ?>" 
                            onkeyup="validate_textfield(this, true)"
                            placeholder=" " 
                            autocomplete="off" 
                            tabindex="<?php echo $tabindex++; ?>"
                        />   
                        <label class="text-uppercase">cp</label>
                        <small class="form-text text-muted helper-text" id="sku_cp_msg"></small>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 floating-label">
                        <input 
                            type="number" 
                            class="form-control floating-input" 
                            id="sku_mrp" 
                            name="sku_mrp" 
                            value="<?php echo empty($master_data) ? '' : $master_data[0]['sku_mrp']; ?>" 
                            onkeyup="validate_textfield(this, true)"
                            placeholder=" " 
                            autocomplete="off" 
                            tabindex="<?php echo $tabindex++; ?>"
                        />   
                        <label class="text-uppercase">mrp</label>
                        <small class="form-text text-muted helper-text" id="sku_mrp_msg"></small>
                    </div>  
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 floating-label">
                        <input 
                            type="number" 
                            class="form-control floating-input" 
                            id="sku_offer_price" 
                            name="sku_offer_price" 
                            value="<?php echo empty($master_data) || empty($master_data[0]['sku_offer_price']) ? '' : $master_data[0]['sku_offer_price']; ?>" 
                            onkeyup="validate_textfield(this, true)"
                            placeholder=" " 
                            autocomplete="off" 
                            tabindex="<?php echo $tabindex++; ?>"
                        />   
                        <label class="text-uppercase">offer price</label>
                    </div> 
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 floating-label">
                        <input 
                            type="number" 
                            class="form-control floating-input" 
                            id="sku_last_price" 
                            name="sku_last_price" 
                            value="<?php echo empty($master_data) || empty($master_data[0]['sku_last_price']) ? '' : $master_data[0]['sku_last_price']; ?>" 
                            onkeyup="validate_textfield(this, true)"
                            placeholder=" " 
                            autocomplete="off" 
                            tabindex="<?php echo $tabindex++; ?>"
                        />   
                        <label class="text-uppercase">last price</label>
                    </div> 
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 floating-label">
                        <input 
                            type="number" 
                            class="form-control floating-input" 
                            id="sku_piece" 
                            name="sku_piece" 
                            value="<?php echo empty($master_data) ? 1 : $master_data[0]['sku_piece']; ?>" 
                            onkeyup="validate_textfield(this, true)"
                            placeholder=" " 
                            autocomplete="off" 
                            tabindex="<?php echo $tabindex++; ?>"
                        />   
                        <label class="text-uppercase">no. of pieces <span class="text-danger">*</span></label>
                        <small class="form-text text-muted helper-text" id="sku_piece_msg"></small>
                    </div>          
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 floating-label">
                        <textarea
                            class="form-control floating-textarea"
                            id="sku_notes"
                            name="sku_notes"
                            placeholder=" "
                            autocomplete="off"
                            tabindex= "<?php echo $tabindex++; ?>"
                        ><?php echo empty($master_data) ? '' : $master_data[0]['sku_notes']; ?></textarea>
                        <label class="text-uppercase">notes</label>
                        <small class="form-text text-muted helper-text d-none" id="sku_notes_msg"></small>
                    </div> 
                </div> 
            </div>
        </div>
    </div>
</div>
