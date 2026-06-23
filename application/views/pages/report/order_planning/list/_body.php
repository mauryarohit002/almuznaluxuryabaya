<tbody id="table_tbody" class="font-weight-bold" style="font-size: 0.7rem;">
    <?php 
        if(!empty($data)): 
            foreach ($data as $key => $value):
    ?>
                <tr>
                    <?php foreach ($value as $k => $v): ?>
                        <td width="5%"><?php echo $v; ?></td>
                    <?php endforeach; ?>
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