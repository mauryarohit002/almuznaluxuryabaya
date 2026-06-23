<tbody id="table_tbody">
    <?php 
        if(!empty($data)): 
            foreach ($data as $key => $value):
                $id = encrypt_decrypt("encrypt", $value['user_id'], SECRET_KEY);
                $isConstant 	= in_array('constant', $action_data) ? 1 : 0;
	            $auth 			= ['isConstant' => $isConstant];
    ?>

                <tr class="<?php echo $value['user_status'] == 0 ? 'text-danger' : '' ?>">
                    <td width="3%"><?php echo $key+1; ?></td>
                    <td width="10%"><?php echo $value['user_fullname']; ?></td>
                    <td width="10%"><?php echo $value['role_name']; ?></td>
                    <td width="10%"><?php echo $value['branch_name']; ?></td>
                    <td width="10%"><?php echo $value['user_mobile']; ?></td>
                    <td width="10%" class="text-lowercase"><?php echo $value['user_email']; ?></td>
                    <td width="10%"><?php echo $value['user_status'] == 1 ? 'active' : 'inactive'; ?></td>
                    <?php if(in_array('read', $action_data)): ?>
                        <td width="3%">
                            <a 
                                type="button" 
                                class="btn btn-sm btn-primary" 
                                onclick='user_popup(<?php echo json_encode(["action" => "read", "id" => $value["user_id"], "auth" => $auth]); ?>)'
                            ><i class="text-info fa fa-eye"></i></a>										
                        </td>
                    <?php endif; ?>
                    <?php if(in_array('edit', $action_data)): ?>
                        <td width="3%">
                            <a 
                                type="button" 
                                class="btn btn-sm btn-primary" 
                                onclick='user_popup(<?php echo json_encode(["action" => "edit", "id" => $value["user_id"], "auth" => $auth]); ?>)'
                            ><i class="text-success fa fa-edit"></i></a>										
                        </td>
                    <?php endif; ?>
                    <?php if(in_array('delete', $action_data)): ?>
                        <td width="3%">
                            <?php if($value['isExist']): ?>
                                <button type="button" class="btn btn-sm btn-primary"><i class="text-danger fa fa-ban"></i></button>
                            <?php else: ?>
                                <a 
                                    type="button" 
                                    class="btn btn-sm btn-primary" 
                                    onclick='user_remove(<?php echo json_encode($value); ?>);'
                                ><i class="text-danger fa fa-trash"></i></a>
                            <?php endif; ?>												                                        
                        </td>
                    <?php endif; ?>
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