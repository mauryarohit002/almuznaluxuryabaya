<tbody>
<?php
$total_hisab   = 0;
$total_payment = 0;
$final_closing = 0;

if(!empty($data)){ 
    foreach($data as $key=>$value){

        $total_hisab   += (float)$value['hisab_amt'];
        $total_payment += (float)$value['payment_amt'];
        $final_closing  = (float)$value['closing_amt']; // last row closing
?>
<tr>
    <td width="5%"><?= $key+1 ?></td>
    <td width="10%"><?= date('d-m-Y',strtotime($value['entry_date'])) ?></td>
    <td width="10%"><?= $value['entry_no'] ?></td>

    <td width="10%">
        <?php if($value['action']=='HISAB'){ ?>
            <span class="badge badge-danger">HISAB</span>
        <?php }else{ ?>
            <span class="badge badge-success">PAYMENT</span>
        <?php } ?>
    </td>

    <td width="15%"><?= $value['karigar_name'] ?></td>

    <td class="text-right" width="12%">
        <?= number_format($value['hisab_amt'],2) ?>
    </td>

    <td class="text-right" width="12%">
        <?= number_format($value['payment_amt'],2) ?>
    </td>

    <td class="text-right font-weight-bold" width="12%">
        <?= number_format($value['closing_amt'],2) ?>
    </td>

    <td width="8%">
        <?= ($value['label']=='TO PAY')
            ? '<span class="badge badge-warning">TO PAY</span>'
            : '<span class="badge badge-info">TO RECEIVE</span>'
        ?>
    </td>

    <td width="16%"><?= $value['remark'] ?></td>
</tr>

<?php } ?>

<tr style="background:#f8f9fa;font-weight:bold;">
    <td colspan="5" class="text-right">TOTAL</td>

    <td class="text-right">
        <?= number_format($total_hisab,2) ?>
    </td>

    <td class="text-right">
        <?= number_format($total_payment,2) ?>
    </td>

    <td class="text-right">
        <?= number_format($final_closing,2) ?>
    </td>

    <td colspan="2">
        <?= ($final_closing >= 0) ? 'TO PAY' : 'TO RECEIVE'; ?>
    </td>
</tr>

<?php }else{ ?>
<tr>
    <td class="text-danger font-weight-bold text-center" colspan="10">
        NO RECORD FOUND!!!
    </td>
</tr>
<?php } ?>
</tbody>