<tbody id="table_tbody" class="font-weight-bold" style="font-size: 0.7rem;">
    <?php 
        if(!empty($data)): 
            foreach (array_slice($data, 0, PER_PAGE) as $key => $value):
    ?>
                <tr>
                    <td width="3%"><?php echo $key+1; ?></td>
                    <td width="10%">
                        <a 
                            type="button" 
                            data-toggle="tooltip" 
                            data-placement="bottom" 
                            title="LEDGER"
                            target="_blank"
                            href="<?php echo base_url($menu.'/karigar_ledger?_karigar_name='.$value['karigar_name']); ?>"
                        ><i class="text-success fa fa-external-link"></i></a>
                        <?php echo $value['karigar_name']; ?>
                    </td>
                    <td width="10%"><?php echo $value['opening_amt']; ?></td>
                    <td width="10%"><?php echo $value['hisab_amt']; ?></td>
                    <td width="10%"><?php echo $value['payment_amt']; ?></td>
                    <td width="10%"><?php echo $value['closing_amt']; ?> <?php echo $value['label']; ?></td>
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