<tbody id="table_tbody" class="font-weight-bold" style="font-size: 0.7rem;">
    <?php 
        if(!empty($data)): 
            foreach (array_slice($data, 0, PER_PAGE) as $key => $value):
    ?>
                <tr> 
                   <!--  <td width="5%"><?php echo $value['module_name']; ?></td> -->
                    <td width="5%"><?php echo $value['type']; ?></td>
                    <td width="5%"><?php echo $value['apparel_name']; ?></td>
                    <td width="5%"><?php echo $value['qty']; ?></td>
                    <td width="5%"><?php echo $value['rate']; ?></td>
                    <td width="5%"><?php echo $value['amt']; ?></td>
                    <td width="5%"><?php echo $value['disc_amt']; ?></td>
                    <td width="5%"><?php echo $value['taxable_amt']; ?></td>
                    <td width="5%"><?php echo $value['gst_amt']; ?></td>
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