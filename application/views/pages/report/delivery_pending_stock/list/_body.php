<tbody id="table_tbody" class="font-weight-bold" style="font-size: 0.7rem;">
    <?php 
        if(!empty($data)): 
            foreach (array_slice($data, 0, PER_PAGE) as $key => $value):
    ?>
                <tr> 
                    <td width="3%"><?php echo $key+1; ?></td>
                    <td width="5%"><?php echo $value['entry_no']; ?></td>
                    <td width="5%"><?php echo $value['entry_date1']; ?></td>
                    <td width="5%"><?php echo $value['om_em_entry_no']; ?></td>
                    <td width="5%"><?php echo $value['om_em_entry_date1']; ?></td>
                    <td width="8%"><?php echo $value['qrcode']; ?></td>
                    <td width="8%"><?php echo $value['apparel_name']; ?></td>
                    <td width="5%"><?php echo $value['branch_name']; ?></td>
                    <td width="5%"><?php echo $value['status']; ?></td>
                    <td width="8%"><?php echo $value['status_notes']; ?></td>
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