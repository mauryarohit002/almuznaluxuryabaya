<tbody id="table_tbody">
    <?php if(!empty($data)):  ?>
        <tr>
            <?php
                $cnt = 0; 
                $div = 3;
                foreach ($data as $key => $value):
                    $cnt++;
                    $id = encrypt_decrypt("encrypt", $value['sku_id'], SECRET_KEY);
                    $text_status = $value['sku_status'] == 0 ? 'border-danger' :'';
            ?>
                        <td width="25%" class="border-0">
                            <div class="d-flex flex-wrap justify-content-center mt-2" style="min-height: 10rem;">
                                <div class="card d-flex justify-content-between neu_flat_primary">
                                    <div class="d-flex flex-wrap justify-content-center">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-5 d-flex flex-wrap justify-content-center">
                                            <span class="d-flex justify-content-center mt-3" style="width: 14rem; height:14rem;">
                                                <img 
                                                    class="img-thumbnail pan master_loading border-0" 
                                                    onclick="zoom()" 
                                                    title="click to zoom in and zoom out" 
                                                    data-big="<?php echo $value['sku_image']; ?>" 
                                                    src="<?php echo assets(LAZYLOADING) ?>" 
                                                    data-src="<?php echo $value['sku_image']; ?>" 
                                                    onerror="this.onerror=null; this.src='<?php echo assets(NOIMAGE) ?>';"
                                                    style="max-width: 100%; max-height: 100%; aspect-ration: 3/2; object-fit: contain;"
                                                />
                                            </span>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-7">
                                            <div class="card-body d-flex flex-column text-uppercase <?php echo $text_status; ?>" style="font-size:0.7em;">
                                                <table class="table table-sm bg-primary text-uppercase">
                                                    <tbody>
                                                        <tr>
                                                            <th width="40%">apparel</th>
                                                            <td width="60%">: <?php echo $value['apparel_name']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th width="40%">sku</th>
                                                            <td width="60%">: <?php echo $value['sku_name']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th width="40%">mrp</th>
                                                            <td width="60%">: <?php echo $value['sku_mrp']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th width="40%">cp</th>
                                                            <td width="60%">: <?php echo $value['sku_cp']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th width="40%">pieces</th>
                                                            <td width="60%">: <?php echo $value['sku_piece']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th width="40%">status</th>
                                                            <td width="60%">: <?php echo $value['sku_status'] == 1 ? 'ACTIVE' : 'INACTIVE'; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th width="40%">
                                                                <?php if(in_array('edit', $action_data)): ?>
                                                                    <a 
                                                                        type="button" 
                                                                        class="btn btn-sm btn-primary" 
                                                                        data-toggle="tooltip" 
                                                                        data-placement="bottom" 
                                                                        title="EDIT"
                                                                        href="<?php echo base_url($menu.'/'.$sub_menu.'?action=edit&id='.$id); ?>"
                                                                    ><i class="text-success fa fa-edit"></i></a>
                                                                <?php endif; ?>
                                                            </th>
                                                            <td width="60%">
                                                                <?php if(in_array('delete', $action_data)): ?>
                                                                    <?php if($value['isExist']): ?>
                                                                        <button 
                                                                            type="button" 
                                                                            class="btn btn-sm"
                                                                            data-toggle="tooltip" 
                                                                            data-placement="bottom" 
                                                                            title="NOT ALLOWED"
                                                                        ><i class="text-danger fa fa-ban"></i></button>
                                                                    <?php else: ?>
                                                                        <a 
                                                                            type="button" 
                                                                            class="btn btn-sm btn-primary"
                                                                            data-toggle="tooltip" 
                                                                            data-placement="bottom" 
                                                                            title="DELETE" 
                                                                            onclick='sku_remove(<?php echo json_encode($value); ?>);'
                                                                        >
                                                                            <i class="text-danger fa fa-trash"></i>
                                                                        </a>
                                                                    <?php endif; ?>												                                        
                                                                <?php endif; ?>	
                                                                <?php if(in_array('read', $action_data)): ?>
                                                                    <a 
                                                                        type="button" 
                                                                        class="btn btn-sm btn-primary ml-4" 
                                                                        data-toggle="tooltip" 
                                                                        data-placement="bottom" 
                                                                        title="READ" 
                                                                        href="<?php echo base_url($menu.'/'.$sub_menu.'?action=read&id='.$id); ?>"
                                                                    ><i class="text-info fa fa-eye"></i></a>										
                                                                <?php endif; ?>											                                        
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                <?php if($cnt == $div): $cnt = 0;?>
                    </tr>              
                    <tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tr>
    <?php else: ?>
            <h5 class="text-danger font-weight-bold text-center">NO RECORD FOUND!!!</h5>
    <?php endif; ?>
</tbody>