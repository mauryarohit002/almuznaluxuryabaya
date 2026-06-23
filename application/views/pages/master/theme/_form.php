<?php 
    $this->load->view('templates/header');
    $menu   		= 'master';
	$sub_menu   	= 'theme';
	$action_data 	= get_action_data($menu, $sub_menu);
    $action 	 = (isset($_GET['action'])) ? $_GET['action'] : "";
    $checked     = !empty($master_data) && $master_data[0]['theme_status'] == 0 ? '' : 'checked'; 
?>
<script>
    let link 	 = "<?php echo $menu; ?>";
    let sub_link = "<?php echo $sub_menu; ?>";
</script>
<section class="d-flex flex-column sticky_top">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item text-uppercase"><a href="<?php echo base_url($menu.'/'.$sub_menu.'?action=list'); ?>"><?php echo strtoupper($sub_menu); ?></a></li>
        <li class="breadcrumb-item active text-uppercase" aria-current="page"><?php echo empty($master_data) ? 'add' : 'edit'; ?></li>
        <?php if($action != 'view'): ?>
            <?php if(empty($master_data)): ?>
                <?php if(in_array('add', $action_data)): ?>
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
                <?php endif; ?>
            <?php else: ?>
                <?php if(in_array('edit', $action_data)): ?>
                    <li class="breadcrumb-item" aria-current="save-page">
                        <button 
                            type="button" 
                            class="btn btn-sm btn-primary master_block_btn" 
                            data-toggle="tooltip" 
                            data-placement="bottom" 
                            title="UPDATE" 
                            tabindex="99" 
                            onclick="add_update(<?php echo $master_data[0]['theme_id']; ?>)" 
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
</section>
<section class="container-fluid my-3">
    <form class="form-horizontal" id="theme_form">
        <div class="row">
           <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header text-uppercase">theme detail</div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap form-group floating-form">
                            <div class="col-12 col-sm-12 col-md-3 col-lg-3 floating-label">
                                <input 
                                    type="text" 
                                    class="form-control floating-input" 
                                    id="theme_name" 
                                    name="theme_name" 
                                    value="<?php echo empty($master_data) ? '' : $master_data[0]['theme_name']; ?>" 
                                    onkeyup="validate_textfield(this, true)" 
                                    placeholder=" " 
                                    autocomplete="off" 
                                    tabindex="1" 
                                />   
                                <label class="text-uppercase">name <span class="text-danger">*</span></label>
                                <small class="form-text text-muted helper-text" id="theme_name_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-3 col-lg-3 floating-label">
                                <input 
                                    type="checkbox" 
                                    id="theme_status" 
                                    name="theme_status" 
                                    data-toggle="toggle" 
                                    data-on="ACTIVE" 
                                    data-off="INACTIVE" 
                                    data-onstyle="primary" 
                                    data-offstyle="primary" 
                                    data-width="100" 
                                    data-size="normal" 
                                    tabindex="4" 
                                    <?php echo $checked ?>
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header text-uppercase">theme detail</div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap form-group floating-form">
                            <table class="table table-sm">
                                <tbody>
                                    <tr class="floating-form">
                                        <td class="floating-label" width="10%">
                                            <p class="text-uppercase">variable&nbsp;<span class="text-danger">*</span></p> 
                                            <input 
                                                type="text" 
                                                class="form-control floating-input" 
                                                id="tt_variable" 
                                                placeholder=" " 
                                                autocomplete="off"
                                                onkeyup="validate_textfield(this)"  
                                                tabindex="3" 
                                                style="text-transform:none"
                                            />
                                            <small class="form-text text-muted helper-text" id="tt_variable_msg"></small>
                                        </td>
                                        <td class="floating-label" width="10%">
                                            <p class="text-uppercase">value&nbsp;<span class="text-danger">*</span></p> 
                                            <input 
                                                type="text" 
                                                class="form-control floating-input text-lowercase" 
                                                id="tt_value" 
                                                placeholder=" " 
                                                autocomplete="off"
                                                onkeyup="validate_textfield(this)"  
                                                tabindex="4" 
                                                style="text-transform:none"
                                            />
                                            <small class="form-text text-muted helper-text" id="tt_value_msg"></small>
                                        </td>
                                        <td class="floating-label" width="2%">
                                            <p class="text-uppercase">status</p> 
                                            <input 
                                                type="checkbox" 
                                                id="tt_status" 
                                                name="tt_status" 
                                                data-toggle="toggle" 
                                                data-on="ACTIVE" 
                                                data-off="INACTIVE" 
                                                data-onstyle="primary" 
                                                data-offstyle="primary" 
                                                data-width="100" 
                                                data-size="normal" 
                                                tabindex="7"
                                                checked
                                            />
                                            <small class="form-text text-muted helper-text" id="tt_status_msg"></small>
                                        </td> 
                                        <td width="2%">
                                            <button 
                                                type="button" 
                                                class="btn btn-md btn-primary" 
                                                data-toggle="tooltip" 
                                                title="ADD VARIABLE" 
                                                data-placement="top" 
                                                tabindex="8" 
                                                onclick="<?php echo empty($master_data) ? 'add_transaction(0);' : "add_transaction(".$master_data[0]['theme_id'].");"  ?>"   
                                            ><i class="text-success fa fa-plus"></i></button>
                                        </td>                  
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-sm" id="theme_wrapper"></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>
<?php $this->load->view('templates/footer'); ?>
<script src="<?php echo assets('dist/js/master/theme.js?v=1')?>"></script>
<script type="text/javascript">get_transaction()</script>
</body>
</html>