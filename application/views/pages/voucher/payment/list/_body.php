<tbody id="table_tbody">
    <?php 
        if(!empty($data)): 
            foreach ($data as $key => $value):
                $id = encrypt_decrypt("encrypt", $value['payment_id'], SECRET_KEY);
    ?>
                <tr>
                    <td width="3%"><?php echo $key+1; ?></td>
                    <td width="7%"><?php echo $value['payment_entry_no']; ?></td>
                    <td width="10%"><?php echo $value['entry_date']; ?></td>
                    <td width="15%"><?php echo $value['supplier_name']; ?></td>
                    <td width="10%"><?php echo $value['payment_amt']; ?></td>
                    <td width="10%"><?php echo $value['payment_purchase_readymade_amt']; ?></td>
                    <td width="15%"><?php echo $value['payment_notes']; ?></td>
                    <td width="10%"><?php echo $value['payment_adjust_status'] == 1 ? 'ADJUSTED' : 'PENDING'; ?></td>
                    <?php if(in_array('view', $action_data)): ?>
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
                                <button type="button" class="btn btn-sm btn-primary"><i class="text-danger fa fa-ban"></i></button>
                            <?php else: ?>
                                <a 
                                    type="button" 
                                    class="btn btn-sm btn-primary" 
                                    onclick='remove_record(<?php echo json_encode($value); ?>);'
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