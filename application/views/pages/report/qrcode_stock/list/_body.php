<tbody id="table_tbody" class="font-weight-bold" style="font-size: 0.7rem;">
    <?php 
        if(!empty($data)): 
            foreach (array_slice($data, 0, PER_PAGE) as $key => $value):
    ?>
                <tr>
                    <td width="3%"><?php echo $key+1; ?></td>
                    <td width="3%"><?php echo $value['entry_no']; ?></td>
                    <td width="5%"><?php echo $value['entry_date']; ?></td>
                    <td width="3%"><?php echo $value['nod']; ?></td>
                    <td width="5%"><?php echo $value['item_code']; ?></td>
                    <td width="10%"><?php echo $value['supplier_name']; ?></td>
                    <td width="5%"><?php echo $value['fabric_name']; ?></td>
                    <td width="5%"><?php echo $value['design_name']; ?></td>
                    <td width="5%"><?php echo $value['category_name']; ?></td>
                    <td width="5%"><?php echo $value['color_name']; ?></td>
                    <td width="5%"><?php echo $value['width_name']; ?></td>
                    <td width="10%"><?php echo $value['description']; ?></td>
                    <td width="5%"><?php echo $value['rate']; ?></td>
                    <td width="5%"><?php echo $value['mrp']; ?></td>
                    <td width="5%"><?php echo $value['pt_mtr']; ?></td>
                    <td width="5%"><?php echo $value['ot_mtr']; ?></td>
                    <td width="5%"><?php echo $value['bal_mtr']; ?></td>
                    <td width="5%"><?php echo $value['bal_amt']; ?></td>
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