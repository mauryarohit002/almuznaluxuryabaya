<div class="navigationn_wrapper">
    <div class="navigationn">
        <div class="menuToggle" id="menu_toggle_<?php echo $value['om_id']; ?>" onclick="toggle_menuu(this)"></div>
        <div class="menuu">
            <ul>
                <li>
                    <a 
                        type="button" 
                        class="btn btn-sm"
                        target="_blank"
                        data-toggle="tooltip" 
                        data-placement="bottom" 
                        title="QRCODE"
                        href="<?php echo base_url($menu.'/'.$sub_menu.'?action=qrcode&clause=om.om_id&id='.$value['id']) ?>"
                    ><i class="text-info fa fa-qrcode"></i></a>                                        
                </li>
                <li>
                    <a 
                        type="button" 
                        class="btn btn-sm"
                        target="_blank"
                        data-toggle="tooltip" 
                        data-placement="bottom" 
                        title="BILL"
                        href="<?php echo base_url($menu.'/'.$sub_menu.'?action=print&id='.$value['id']) ?>"
                    ><i class="text-info fa fa-print"></i></a>                                        
                </li>
                <li>
                    <a 
                        type="button" 
                        class="btn btn-sm"
                        target="_blank"
                        data-toggle="tooltip" 
                        data-placement="bottom" 
                        title="MEASUREMENT"
                        href="<?php echo base_url($menu.'/'.$sub_menu.'/measurement_print/'.$value['om_id']) ?>"
                    ><i class="text-info fa fa-print"></i></a>
                </li>
                <?php
                    $editable = true;
                    if(($_SESSION['user_role_id']==SALESMAN) && ($value['om_em_entry_date'] != date('Y-m-d'))){
                        $editable=false;
                    }

                ?>
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
                <?php if(in_array('edit', $action_data) && $editable):
                    if($value['om_status']==0):
                 ?>
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
                <?php endif;
                    endif;
                ?>
                <?php if(in_array('delete', $action_data)): ?>
                    <li>
                        <?php if($value['isExist'] || $value['om_status']>0): ?>
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