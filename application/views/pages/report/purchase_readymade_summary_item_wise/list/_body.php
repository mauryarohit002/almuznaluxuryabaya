<tbody id="tbody_wrapper" class="font-weight-bold" style="font-size: 0.7rem;">
    <?php 
        if(!empty($data)): 
            foreach ($data as $key => $value):
    ?>
                <tr>
                    <td width="3%">
                        <?php echo $value['cnt']+1; ?>
                        <span class="<?php echo ENV == DEV ? '' : 'd-none'; ?>">/<?php echo $value['page']; ?></span>
                    </td>
                    <td width="4%"><?php echo $value['bill_no']; ?></td>
                    <td width="5%"><?php echo $value['bill_date']; ?></td>
                    <td width="8%"><?php echo $value['supplier_name']; ?></td>
                    <td width="5%"><?php echo $value['product_name']; ?></td>
                    <td width="5%"><?php echo $value['design_name']; ?></td>
                    <td width="5%"><?php echo $value['color_name']; ?></td>
                    <td width="4%"><?php echo $value['size_name']; ?></td>
                    <td width="4%"><?php echo $value['qty']; ?></td>
                    <td width="4%"><?php echo $value['rate']; ?></td>
                    <td width="5%"><?php echo $value['sub_amt']; ?></td>
                    <td width="5%"><?php echo $value['disc_amt']; ?></td>
                    <td width="6%"><?php echo $value['taxable_amt']; ?></td>
                    <td width="5%"><?php echo $value['sgst_amt']; ?></td>
                    <td width="5%"><?php echo $value['cgst_amt']; ?></td>
                    <td width="5%"><?php echo $value['igst_amt']; ?></td>
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