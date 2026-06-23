<tbody id="table_tbody">
    <?php 
        if(!empty($data)): 
            foreach ($data as $key => $value):
                $id = encrypt_decrypt("encrypt", $value['prrm_id'], SECRET_KEY);
        ?>

                <tr>
                    <td width="3%"><?php echo $key+1; ?></td>
                    <td width="5%"><?php echo $value['prrm_entry_no']; ?></td>
                    <td width="8%"><?php echo date('d-m-Y', strtotime($value['prrm_entry_date'])); ?></td>
                    <td width="10%"><?php echo $value['supplier_name']; ?></td>
                    <td width="8%"><?php echo $value['prrm_total_qty']; ?></td>
                    <td width="8%"><?php echo $value['prrm_total_amt']; ?></td>
                    <?php if(in_array('bill', $action_data)): ?>
                        <td width="3%">
                            <a 
                                type="button" 
                                class="btn btn-sm btn-primary" 
                                target="_blank" 
                                href="<?php echo base_url($menu.'/'.$sub_menu.'?action=print&id='.$id) ?>"
                            ><i class="text-info fa fa-print"></i></a>                                        
                        </td>
                    <?php endif;?>
                    <?php if(in_array('read', $action_data)): ?>
                        <td width="3%">
                            <a 
                                type="button" 
                                class="btn btn-sm btn-primary" 
                                href="<?php echo base_url($menu.'/'.$sub_menu.'?action=read&id='.$id); ?>"
                            ><i class="text-info fa fa-eye"></i></a>										
                        </td>
                    <?php endif;?>
                    <?php if(in_array('edit', $action_data)): ?>
                        <td width="3%">
                            <a 
                                type="button" 
                                class="btn btn-sm btn-primary" 
                                href="<?php echo base_url($menu.'/'.$sub_menu.'?action=edit&id='.$id); ?>"
                            ><i class="text-success fa fa-edit"></i></a>										
                        </td>
                    <?php endif;?>
                    <?php if(in_array('delete', $action_data)): ?>
                        <td width="3%">
                            <?php if($value['isExist']): ?>
                                <button 
                                    type="button" 
                                    class="btn btn-sm btn-primary"
                                ><i class="text-danger fa fa-ban"></i></button>
                            <?php else: ?>
                                <a 
                                    type="button" 
                                    class="btn btn-sm btn-primary" 
                                    onclick='record_remove(<?php echo json_encode($value); ?>);'
                                ><i class="text-danger fa fa-trash"></i></a>
                            <?php endif; ?>
                        </td>
                    <?php endif;?>
                </tr>
    <?php 
            endforeach;
        else: 
    ?>
        <tr>
            <td class="text-danger font-weight-bold text-center" colspan="10">NO RECORD FOUND!!!</td>
        </tr>
    <?php endif; ?>
</tbody>