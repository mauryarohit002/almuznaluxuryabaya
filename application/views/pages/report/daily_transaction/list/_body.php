<?php 
    $this->load->view('templates/header'); 
    $search_status  = !isset($_GET['search_status']);
?>
<script>
    let link        = "<?php echo $menu; ?>";
    let sub_link    = "<?php echo $sub_menu; ?>";
</script>
<section class="container-fluid sticky_top">
    <?php $this->load->view('pages/'.$menu.'/'.$sub_menu.'/list/_navbar'); ?>
</section>
<section class="container-fluid">
    <div class="row">
        <div class="col-12">
            <table class="table table-sm table-dark text-uppercase">
                <?php $this->load->view('pages/'.$menu.'/'.$sub_menu.'/list/_header'); ?>
            </table>
            <div class="row text-uppercase">

    <!-- RECEIPT -->
    <div class="col-12 col-md-6 col-lg-3 mb-3">
        <div class="card h-100">
            <div class="card-header text-center font-weight-bold">RECEIPT</div>
            <div class="card-body p-2">
                <?php if(!empty($data['receipt_payment'])): ?>
                    <?php foreach ($data['receipt_payment'] as $value): ?>
                        <div class="d-flex justify-content-between border-bottom py-1">
                            <span><?php echo $value['payment_mode_name'] ?></span>
                            <b><?php echo $value['payment_mode_amt'] ?></b>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center text-danger">No Data</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- ORDER -->
    <div class="col-12 col-md-6 col-lg-3 mb-3">
        <div class="card h-100">
            <div class="card-header text-center font-weight-bold">ORDER</div>
            <div class="card-body p-2">
                <?php if(!empty($data['order_payment'])): ?>
                    <?php foreach ($data['order_payment'] as $value): ?>
                        <div class="d-flex justify-content-between border-bottom py-1">
                            <span><?php echo $value['payment_mode_name'] ?></span>
                            <b><?php echo $value['payment_mode_amt'] ?></b>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center text-danger">No Data</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- PAYMENT -->
    <!-- <div class="col-12 col-md-6 col-lg-3 mb-3">
        <div class="card h-100">
            <div class="card-header text-center font-weight-bold">PAYMENT</div>
            <div class="card-body p-2">
                <?php if(!empty($data['payment_payment'])): ?>
                    <?php foreach ($data['payment_payment'] as $value): ?>
                        <div class="d-flex justify-content-between border-bottom py-1">
                            <span><?php echo $value['payment_mode_name'] ?></span>
                            <b><?php echo $value['payment_mode_amt'] ?></b>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center text-danger">No Data</div>
                <?php endif; ?>
            </div>
        </div>
    </div> -->

    <!-- GENERAL -->
    <div class="col-12 col-md-6 col-lg-3 mb-3">
        <div class="card h-100">
            <div class="card-header text-center font-weight-bold">GENERAL</div>
            <div class="card-body p-2">
                <?php if(!empty($data['general_payment'])): ?>
                    <?php foreach ($data['general_payment'] as $value): ?>
                        <div class="d-flex justify-content-between border-bottom py-1">
                            <span><?php echo $value['payment_mode_name'] ?></span>
                            <b><?php echo $value['payment_mode_amt'] ?></b>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center text-danger">No Data</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>
            <div class="list_wrapper">
                <table class="table text-uppercase" style="line-height: 1">
                    <thead>
                        <th width="5%" style="background-color: #1d5558;color:white;">ORDER&nbsp;DETAILS</th> 
                        <th width="10%" style="background-color: #858C8C">order&nbsp;no</th>
                        <th width="15%" style="background-color: #858C8C">order&nbsp;date</th>
                        <th width="15%" style="background-color: #858C8C">Customer&nbsp;name</th>
                        <th width="15%" style="background-color: #858C8C">Total&nbsp;Amt</th>
                        <th width="15%" style="background-color: #858C8C">Advance&nbsp;Amt</th>
                        <th width="20%" style="background-color: #3faaaa">Payment Mode</th>
                        <th width="15%" style="background-color: #858C8C">Receipt&nbsp;Amt</th>
                        <th width="10%" style="background-color: #858C8C">Balance&nbsp;Amt</th>
                    </thead>
                  
                    <tbody id="table_tbody1" class="font-weight-bold" style="font-size: 0.7rem;">
                        <?php 
                            $total_amt = 0;
                            $total_adv = 0;
                            $total_rec = 0;
                            $total_bal = 0;
                            // echo "<pre>";print_r($data);die;
                            if(!empty($data['order_data'])): 
                                foreach ($data['order_data'] as $key => $value):
                                    $total_amt += $value['om_total_amt'];
                                    $total_adv += $value['om_advance_amt'];
                                    $total_rec += $value['om_allocated_amt'];
                                    $total_bal += $value['om_balance_amt'];
                                    ?>
                                    <tr> 
                                        <td><?php echo $key+1; ?></td>
                                        <td><?php echo $value['om_entry_no']; ?></td>
                                        <td><?php echo $value['om_entry_date']; ?></td>
                                        <td><?php echo $value['customer_name']; ?></td>
                                        <td><?php echo $value['om_total_amt']; ?></td>
                                        <td><?php echo $value['om_advance_amt']; ?></td>
                                        <td><?php echo $value['payment_mode_name']; ?></td>
                                        <td><?php echo $value['om_allocated_amt']; ?></td>
                                        <td><?php echo $value['om_balance_amt']; ?></td>
                                       
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
                    <tr style="font-weight:bold; background:#eee;">
                        <td colspan="4" align="right">TOTAL</td>
                        <td><?php echo $total_amt; ?></td>
                        <td colspan="2"><?php echo $total_adv; ?></td>
                        <td><?php echo $total_rec; ?></td>
                        <td><?php echo $total_bal; ?></td>
                    </tr>
                </table> 

                <table class="table text-uppercase" style="line-height: 1">
                    <thead>
                        <th width="5%" style="background-color: #1d5558;color:white;">RECEIPT&nbsp;DETAILS</th> 
                        <th width="15%" style="background-color: #858C8C">receipt&nbsp;no</th>
                        <th width="15%" style="background-color: #858C8C">module</th>
                        <th width="15%" style="background-color: #858C8C">receipt&nbsp;date</th>
                        <th width="15%" style="background-color: #858C8C">Customer&nbsp;name</th>
                        <th width="15%" style="background-color: #858C8C">Amount</th>
                        <th width="15%" style="background-color: #3faaaa">Payment Mode</th>
                </thead>
               
                    <tbody id="table_tbody1" class="font-weight-bold" style="font-size: 0.7rem;">
                        <?php         
                            $receipt_total = 0; 
                            // echo "<pre>";print_r($data);die;
                            if(!empty($data['receipt_order_data'])):  
                            foreach ($data['receipt_order_data'] as $key => $value):
                            ?>
                                    <tr> 
                                        <td><?php echo $key+1; ?></td>
                                        <td><?php echo $value['entry_no']; ?></td>
                                        <td><?php echo $value['module_name']; ?></td>
                                        <td><?php echo $value['entry_date']; ?></td>
                                        <td><?php echo $value['customer_name']; ?></td>
                                        <td><?php echo $value['rot_adjust_amt']; ?></td>
                                        <td><?php echo $value['payment_mode_name']; ?></td>
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
                    <tr style="font-weight:bold; background:#eee;">
                        <td colspan="5" align="right">TOTAL</td>
                        <td><?php echo $receipt_total; ?></td>
                        <td></td>
                    </tr>
                </table> 

                <!-- <table class="table text-uppercase" style="line-height: 1">
                    <thead>
                        <th width="5%" style="background-color: #1d5558;color:white;">PAYMENT&nbsp;DETAILS</th> 
                        <th width="15%" style="background-color: #858C8C">entry&nbsp;no</th>
                        <th width="15%" style="background-color: #858C8C">entry&nbsp;date</th>
                        <th width="15%" style="background-color: #858C8C">name</th>
                        <th width="15%" style="background-color: #858C8C">Amount</th>
                        <th width="15%" style="background-color: #3faaaa">Payment Mode</th>
                    </thead>
               
                    <tbody id="table_tbody1" class="font-weight-bold" style="font-size: 0.7rem;">
                        <?php         
                            $payment_total = 0;
                            // echo "<pre>";print_r($data);die;
                            if(!empty($data['payment_data'])): 
                                foreach ($data['payment_data'] as $key => $value):
                                    $payment_total += $value['ppt_adjust_amt'];
                        ?>
                                    <tr> 
                                        <td><?php echo $key+1; ?></td>
                                        <td><?php echo $value['entry_no']; ?></td>
                                        <td><?php echo $value['entry_date']; ?></td>
                                        <td><?php echo $value['supplier_name']; ?></td>
                                        <td><?php echo $value['ppt_adjust_amt']; ?></td>
                                        <td><?php echo $value['payment_mode_name']; ?></td>
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
                    <tr style="font-weight:bold; background:#eee;">
                        <td colspan="4" align="right">TOTAL</td>
                        <td><?php echo $payment_total; ?></td>
                        <td></td>
                    </tr>
                </table>  -->

                <table class="table text-uppercase" style="line-height: 1">
                    <thead>
                        <th width="5%" style="background-color: #1d5558;color:white;">GENERAL&nbsp;DETAILS</th> 
                        <th width="15%" style="background-color: #858C8C">entry&nbsp;no</th>
                        <th width="15%" style="background-color: #858C8C">entry&nbsp;date</th>
                        <th width="15%" style="background-color: #858C8C">name</th>
                        <th width="15%" style="background-color: #858C8C">Amount</th>
                        <th width="15%" style="background-color: #3faaaa">Payment Mode</th>
                    </thead>
                    <tbody id="table_tbody1" class="font-weight-bold" style="font-size: 0.7rem;">
                        <?php        
                            $general_total = 0; 
                            // echo "<pre>";print_r($data);die;
                            if(!empty($data['general_data'])): 
                                foreach ($data['general_data'] as $key => $value):
                                    $general_total += $value['payment_general_amt'];
                        ?>
                                    <tr> 
                                        <td><?php echo $key+1; ?></td>
                                        <td><?php echo $value['entry_no']; ?></td>
                                        <td><?php echo $value['entry_date']; ?></td>
                                        <td><?php echo $value['general_name']; ?></td>
                                        <td><?php echo $value['payment_general_amt']; ?></td>
                                        <td><?php echo $value['payment_mode_name']; ?></td>
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
                    <tr style="font-weight:bold; background:#eee;">
                        <td colspan="4" align="right">TOTAL</td>
                        <td><?php echo $general_total; ?></td>
                        <td></td>
                    </tr>
                </table>    
            </div>
        </div>
    </div>
</section>
<div class="pagination_wrapper"><?= $this->pagination->create_links(); ?></div>
<?php $this->load->view('templates/footer'); ?>
<?php $this->load->view('pages/'.$menu.'/'.$sub_menu.'/_filter', ['filters' => isset($data['filter']) ? $data['filter'] : []]); ?>
<?php $this->load->view('pages/'.$menu.'/'.$sub_menu.'/list/_footer'); ?>
</body>
</html>