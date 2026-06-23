<?php
$this->load->view('templates/header');

$summary = $data['summary'];
?>

<link rel="stylesheet" href="<?php echo assets('dist/css/report/daily_profit.css')?>">

<script>
    let link        = "<?php echo $menu; ?>";
    let sub_link    = "<?php echo $sub_menu; ?>";
</script>

<section class="container-fluid sticky_top">
    <?php $this->load->view('pages/'.$menu.'/'.$sub_menu.'/list/_navbar'); ?>
</section>

<section class="container-fluid mt-3">

    <!-- SUMMARY CARDS -->

    <div class="row">

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="dashboard-card bg-sales">
                <div class="card-body">
                    <div class="title">Total Sales</div>
                    <div class="amount">
                        ₹ <?= number_format($summary['total_sales'],2) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="dashboard-card bg-cost">
                <div class="card-body">
                    <div class="title">Total Cost</div>
                    <div class="amount">
                        ₹ <?= number_format($summary['total_cost'],2) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="dashboard-card bg-profit">
                <div class="card-body">
                    <div class="title">Gross Profit</div>
                    <div class="amount">
                        ₹ <?= number_format($summary['gross_profit'],2) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="dashboard-card bg-balance">
                <div class="card-body">
                    <div class="title">Pending Balance</div>
                    <div class="amount">
                        ₹ <?= number_format($summary['pending_balance'],2) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-3">
            <div class="dashboard-card bg-custom">
                <div class="card-body">
                    <div class="title">Custom Profit</div>
                    <div class="amount">
                        ₹ <?= number_format($summary['custom_profit'],2) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-3">
            <div class="dashboard-card bg-readymade">
                <div class="card-body">
                    <div class="title">Readymade Profit</div>
                    <div class="amount">
                        ₹ <?= number_format($summary['readymade_profit'],2) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-12 mb-3">
            <div class="dashboard-card bg-net">
                <div class="card-body">
                    <div class="title">Net Profit</div>
                    <div class="amount">
                        ₹ <?= number_format($summary['net_profit'],2) ?>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- MAIN PROFIT TABLE -->

    <div class="section-title mt-4">
        Daily Profit Report
    </div>

    <div class="report-table report-scroll mb-4">

        <table class="table table-hover table-bordered">

            <thead>
                <tr>

                    <th>#</th>
                    <th>Order No</th>
                    <th>Order Date</th>
                    <th>Trial</th>
                    <th>Delivery</th>
                    <th>Customer</th>
                    <th>Sku</th>
                    <th>Apparel</th>
                    <th>Branch</th>
                    <th>Type</th>
                    <th>Qty</th>
                    <th>SP</th>
                    <th>CP</th>
                    <th>Profit</th>
                    <th>Profit %</th>
                    <th>Advance</th>
                    <th>Receipt</th>
                    <th>Balance</th>

                </tr>
            </thead>

            <tbody>

            <?php if(!empty($data['profit_data'])): ?>

                <?php foreach($data['profit_data'] as $key => $value): ?>

                    <tr>

                        <td><?= $key + 1 ?></td>

                        <td>

                            <a 
                                href="<?= base_url('transaction/estimate?action=list&_entry_no='.$value['entry_no']) ?>"
                                target="_blank"
                                class="order-link-btn"
                            >

                                <span>
                                    <?= $value['entry_no'] ?>
                                </span>

                                <i class="fa fa-external-link"></i>

                            </a>

                        </td>

                        <td><?= $value['entry_date'] ?></td>

                        <td><?= $value['trial_date'] ?></td>

                        <td><?= $value['delivery_date'] ?></td>

                        <td>
                            <?= strtoupper($value['customer_name']) ?>
                        </td>

                        <td>
                            <?= !empty($value['sku_name'])
                                ? $value['sku_name']
                                : '-' ?>
                        </td>

                        <td>
                            <?= !empty($value['apparel_name'])
                                ? $value['apparel_name']
                                : '-' ?>
                        </td>

                        <td><?= $value['branch_name'] ?></td>

                        <td>

                            <?php if($value['order_type'] == 'CUSTOM'): ?>

                                <span class="badge-custom">
                                    CUSTOM
                                </span>

                            <?php else: ?>

                                <span class="badge-readymade">
                                    READYMADE
                                </span>

                            <?php endif; ?>

                        </td>

                        <td>
                            <?= $value['total_qty'] ?>
                        </td>

                        <td>
                            ₹ <?= number_format($value['selling_price'],2) ?>
                        </td>

                        <td>
                            ₹ <?= number_format($value['cost_price'],2) ?>
                        </td>

                        <td class="<?= ($value['profit_amt'] >= 0) ? 'profit-positive' : 'profit-negative' ?>">

                            ₹ <?= number_format($value['profit_amt'],2) ?>

                        </td>

                        <td>

                            <?= number_format($value['profit_percent'],2) ?> %

                        </td>

                        <td>
                            ₹ <?= number_format($value['om_advance_amt'],2) ?>
                        </td>

                        <td>
                            ₹ <?= number_format($value['om_allocated_amt'],2) ?>
                        </td>

                        <td class="text-danger font-weight-bold">
                            ₹ <?= number_format($value['balance_amt'],2) ?>
                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="15" class="text-center text-danger font-weight-bold">
                        NO RECORD FOUND
                    </td>
                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

    <!-- CUSTOM ORDER BLOCK -->

    <div class="section-title">
        Custom Orders
    </div>

    <div class="report-table report-scroll-sm mb-4">

        <table class="table table-hover table-bordered">

            <thead>
                <tr>

                    <th>#</th>
                    <th>Order No</th>
                    <th>Customer</th>
                    <th>Sku</th>
                    <th>Apparel</th>
                    <th>Qty</th>
                    <th>SP</th>
                    <th>CP</th>
                    <th>Profit</th>

                </tr>
            </thead>

            <tbody>

            <?php if(!empty($data['custom_data'])): ?>

                <?php foreach($data['custom_data'] as $key => $value): ?>

                    <tr>

                        <td><?= $key + 1 ?></td>

                        <td>

                            <a 
                                href="<?= base_url('transaction/estimate?action=list&_entry_no='.$value['entry_no']) ?>"
                                target="_blank"
                                class="order-link-btn"
                            >

                                <span>
                                    <?= $value['entry_no'] ?>
                                </span>

                                <i class="fa fa-external-link"></i>

                            </a>

                        </td>

                        <td><?= strtoupper($value['customer_name']) ?></td>

                        <td>
                            <?= !empty($value['sku_name'])
                                ? $value['sku_name']
                                : '-' ?>
                        </td>

                        <td>
                            <?= !empty($value['apparel_name'])
                                ? $value['apparel_name']
                                : '-' ?>
                        </td>

                        <td><?= $value['total_qty'] ?></td>

                        <td>
                            ₹ <?= number_format($value['selling_price'],2) ?>
                        </td>

                        <td>
                            ₹ <?= number_format($value['cost_price'],2) ?>
                        </td>

                        <td class="profit-positive">
                            ₹ <?= number_format($value['profit_amt'],2) ?>
                        </td>

                    </tr>

                <?php endforeach; ?>

                <?php else: ?>

                <tr>
                    <td colspan="9" class="text-center text-danger font-weight-bold">
                        NO RECORD FOUND
                    </td>
                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

    <!-- READYMADE BLOCK -->

    <div class="section-title">
        Readymade Orders
    </div>

    <div class="report-table report-scroll-sm mb-4">

        <table class="table table-hover table-bordered">

            <thead>
                <tr>

                    <th>#</th>
                    <th>Order No</th>
                    <th>Customer</th>
                    <th>Sku</th>
                    <th>Apparel</th>
                    <th>Qty</th>
                    <th>SP</th>
                    <th>CP</th>
                    <th>Profit</th>

                </tr>
            </thead>

            <tbody>

            <?php if(!empty($data['readymade_data'])): ?>

                <?php foreach($data['readymade_data'] as $key => $value): ?>

                    <tr>

                        <td><?= $key + 1 ?></td>

                        <td>

                            <a 
                                href="<?= base_url('transaction/estimate?action=list&_entry_no='.$value['entry_no']) ?>"
                                target="_blank"
                                class="order-link-btn"
                            >

                                <span>
                                    <?= $value['entry_no'] ?>
                                </span>

                                <i class="fa fa-external-link"></i>

                            </a>

                        </td>

                        <td><?= strtoupper($value['customer_name']) ?></td>

                        <td>
                            <?= !empty($value['sku_name'])
                                ? $value['sku_name']
                                : '-' ?>
                        </td>

                        <td>
                            <?= !empty($value['apparel_name'])
                                ? $value['apparel_name']
                                : '-' ?>
                        </td>

                        <td><?= $value['total_qty'] ?></td>

                        <td>
                            ₹ <?= number_format($value['selling_price'],2) ?>
                        </td>

                        <td>
                            ₹ <?= number_format($value['cost_price'],2) ?>
                        </td>

                        <td class="profit-positive">
                            ₹ <?= number_format($value['profit_amt'],2) ?>
                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="9" class="text-center text-danger font-weight-bold">
                        NO RECORD FOUND
                    </td>
                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

    <!-- PAYMENT SUMMARY -->

    <div class="section-title">
        Payment Summary
    </div>

    <div class="payment-box mb-5">

        <?php if(!empty($data['payment_summary'])): ?>

            <?php foreach($data['payment_summary'] as $value): ?>

                <div class="payment-item">

                    <div>
                        <?= $value['payment_mode_name'] ?>
                    </div>

                    <div>
                        ₹ <?= number_format($value['payment_mode_amt'],2) ?>
                    </div>

                </div>

            <?php endforeach; ?>

        <?php endif; ?>

    </div>

</section>

<?php $this->load->view('templates/footer'); ?>
<?php $this->load->view('pages/'.$menu.'/'.$sub_menu.'/_filter', ['filters' => isset($data['filter']) ? $data['filter'] : []]); ?>
<?php $this->load->view('pages/'.$menu.'/'.$sub_menu.'/list/_footer'); ?>

</body>
</html>