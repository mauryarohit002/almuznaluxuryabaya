<div class="navigationn_wrapper">
    <div class="navigationn">
        <div class="menuToggle" id="menu_toggle_<?php echo $value['hm_id']; ?>" onclick="toggle_menuu(this)"></div>
        <div class="menuu">
            <ul>
                <?php if(in_array('read', $action_data)): ?>
                    <li>
                        <a 
                            type="button" 
                            class="btn btn-sm" 
                            data-toggle="tooltip" 
                            data-placement="bottom" 
                            title="VIEW"
                            href="<?php echo base_url($menu.'/'.$sub_menu.'?action=read&id='.$value['id']); ?>"
                        ><i class="text-info fa fa-eye"></i></a>										
                    </li>
                <?php endif;?>
                <?php if(in_array('edit', $action_data)): ?>
                    <li>
                        <a 
                            type="button" 
                            class="btn btn-sm" 
                            data-toggle="tooltip" 
                            data-placement="bottom" 
                            title="EDIT"
                            href="<?php echo base_url($menu.'/'.$sub_menu.'?action=edit&id='.$value['id']); ?>"
                        ><i class="text-success fa fa-edit"></i></a>										
                    </li>
                <?php endif;?>
                <?php if(in_array('delete', $action_data)): ?>
                    <li>
                        <?php if($value['isExist']): ?>
                                <span 
                                    type="button" 
                                    class="btn btn-sm"
                                    data-toggle="tooltip" 
                                    data-placement="bottom" 
                                    title="NOT ALLOWED"
                                ><i class="text-danger fa fa-ban"></i></span>
                        <?php else: ?>
                            <a 
                                type="button" 
                                class="btn btn-sm" 
                                data-toggle="tooltip" 
                                data-placement="bottom" 
                                title="DELETE"
                                onclick='record_remove(<?php echo json_encode($value); ?>);'
                            ><i class="text-danger fa fa-trash"></i></a>
                        <?php endif; ?>
                    </li>
                <?php endif;?>
            </ul>
        </div>
    </div>
</div>