<?php $action = (isset($_GET['action'])) ? $_GET['action'] : ""; ?>
<div class="d-flex justify-content-between">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item text-uppercase">
                <a href="<?php echo base_url($menu.'/'.$sub_menu.'?action=list'); ?>">
                    <?php echo str_replace('_', ' ', $sub_menu_name); ?>
                </a>
            </li>
            <li class="breadcrumb-item active text-uppercase" aria-current="page">
                <?php echo $_GET['action']; ?>
            </li>
        </ol>
    </nav>
    <?php echo isset($tab) ? $tab : ''; ?>
    <div class="d-flex align-items-center">
        <?php if($action != 'read'): ?>
            <?php if(empty($master_data)): ?>
                <?php if(in_array('add', $action_data)): ?>
                    <button 
                        type="button" 
                        class="btn btn-md btn-primary master_block_btn mx-2" 
                        data-toggle="tooltip" 
                        data-placement="bottom" 
                        title="SAVE" 
                        tabindex="99"
                        onclick="add_edit()" 
                        disabled
                    ><i class="text-success fa fa-save"></i></button>
                <?php endif; ?>
            <?php else: ?>
                <?php if(in_array('edit', $action_data)): ?>
                    <button 
                        type="button" 
                        class="btn btn-md btn-primary master_block_btn mx-2" 
                        data-toggle="tooltip" 
                        data-placement="bottom" 
                        title="UPDATE" 
                        tabindex="99"
                        onclick="add_edit()" 
                    ><i class="text-success fa fa-edit"></i></button>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
        <a 
            type="button" 
            class="btn btn-md btn-primary mx*2" 
            data-toggle="tooltip" 
            data-placement="bottom" 
            title="CANCEL" 
            tabindex="100"
            href="<?php echo base_url($menu.'/'.$sub_menu.'?action=list')?>" 
        ><i class="text-danger fa fa-close"></i></a>
    </div>
</div>