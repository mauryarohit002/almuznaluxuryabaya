<tbody id="table_tbody" class="font-weight-bold" style="font-size: 0.7rem;">
    <?php 
        if(!empty($data)): 
            foreach (array_slice($data, 0, PER_PAGE) as $key => $value):
    ?>
                <tr> 
                    <td width="3%"><?php echo $key+1; ?></td>
                    <td width="5%"><?php echo $value['month']; ?></td>
                    <td width="5%"><?php echo $value['open_qty']; ?></td>
                    <td width="5%"><?php echo $value['pur_qty']; ?></td>
                    <td width="5%"><?php echo $value['pur_return_qty']; ?></td>
                    <td width="5%"><?php echo $value['outward_qty']; ?></td>
                    <td width="5%"><?php echo $value['inward_qty']; ?></td>
                    <td width="5%"><?php echo $value['sale_qty']; ?></td>
                    <td width="5%"><?php echo $value['close_qty']; ?></td>
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