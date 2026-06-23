<tbody id="table_tbody" class="font-weight-bold" style="font-size: 0.7rem;">
    <?php 
        if(!empty($data)): 
            foreach (array_slice($data, 0, PER_PAGE) as $key => $value):
    ?>
                <tr>
                    <td width="3%"><?php echo $key+1; ?></td>
                    <td width="4%"><?php echo $value['entry_no']; ?></td>
                    <td width="5%"><?php echo $value['entry_date1']; ?></td>
                    <td width="10%"><?php echo $value['supplier_name']; ?></td>
                    <td width="8%"><?php echo $value['supplier_mobile']; ?></td>
                    <td width="5%"><?php echo $value['total_mtr']; ?></td>
                    <td width="5%"><?php echo $value['sub_amt']; ?></td>
                    <td width="5%"><?php echo $value['disc_amt']; ?></td>
                    <td width="6%"><?php echo $value['taxable_amt']; ?></td>
                    <td width="5%"><?php echo $value['sgst_amt']; ?></td>
                    <td width="5%"><?php echo $value['cgst_amt']; ?></td>
                    <td width="5%"><?php echo $value['igst_amt']; ?></td>
                    <td width="5%"><?php echo $value['bill_disc_amt']; ?></td>
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