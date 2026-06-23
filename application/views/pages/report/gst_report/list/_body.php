<tbody id="table_tbody" class="font-weight-bold" style="font-size: 0.7rem;">
    <?php 
        if(!empty($data)):   
            foreach (array_slice($data, 0, PER_PAGE) as $key => $value):
    ?>
                <tr>  
                    <td width="10%"><?php echo $value['apparel_name']; ?></td>
                    <td width="4%"><?php echo $value['hsn_name']; ?></td>
                    <td width="5%"><?php echo $value['total_mtr']; ?></td>
                    <td width="5%"><?php echo $value['amt']; ?></td>
                    <td width="5%"><?php echo $value['sgst_amt']; ?></td>
                    <td width="5%"><?php echo $value['cgst_amt']; ?></td>
                    <td width="5%"><?php echo $value['igst_amt']; ?></td>
                    <td width="5%"><?php echo $value['tax_amt']; ?></td>
                    <td width="5%"><?php echo $value['total_amt']; ?></td>
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