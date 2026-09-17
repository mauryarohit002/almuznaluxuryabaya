<?php
$this->load->view('templates/header');
$search_status = !isset($_GET['search_status']);
?>

<script>
    let link = "<?php echo $menu; ?>";
    let sub_link = "<?php echo $sub_menu; ?>";
</script>

<style>
    body{
        background:#f4f6f9;
    }
    .summary-card{
        background:#fff;
        border-radius:12px;
        overflow:hidden;
        box-shadow:0 2px 15px rgba(0,0,0,.08);
    }

    .summary-card .card-header{
        background:#fff;
        border-bottom:1px solid #e9ecef;
        padding:18px 20px;
    }

    .summary-card .card-header h5{
        margin:0;
        font-weight:700;
        color:#1b355d;
    }

    .table{
        margin-bottom:0;
    }

    .table thead th{
        background:#343a40;
        color:#fff;
        text-transform:uppercase;
        font-size:13px;
        letter-spacing:.5px;
        padding:15px;
        text-align:center;
        vertical-align:middle;
        position:sticky;
        top:0;
        z-index:5;
    }

    .table tbody td{
        vertical-align:middle;
        padding:15px 10px;
    }

    .table tbody tr{
        transition:.25s;
    }

    .table tbody tr:hover{
        background:#f8fbff;
    }

    .sr-no{
        text-align:center;
        font-weight:700;
        color:#6c757d;
        width:60px;
    }

    .karigar-name{
        font-size:15px;
        font-weight:700;
        color:#1b355d;
        white-space:nowrap;
    }

    .apparel-wrapper{
        display:flex;
        flex-wrap:wrap;
        gap:10px;
        justify-content:flex-start;
    }

    .apparel-card{
        min-width:100px;
        background:#fff;
        border:1px solid #dee2e6;
        border-radius:10px;
        padding:10px;
        text-align:center;
        box-shadow:0 2px 8px rgba(0,0,0,.05);
        transition:.25s;
    }

    .apparel-card:hover{
        transform:translateY(-2px);
        box-shadow:0 5px 15px rgba(0,0,0,.12);
    }

    .apparel-tag{
        display:inline-block;
        background:#0d6efd;
        color:#fff;
        padding:4px 10px;
        border-radius:20px;
        font-size:11px;
        font-weight:600;
        margin-bottom:8px;
    }

    .apparel-qty{
        font-size:22px;
        font-weight:700;
        color:#198754;
    }

    .total-cell{
        text-align:center;
    }

    .total-badge{
        display:inline-block;
        min-width:60px;
        padding:8px 15px;
        background:#198754;
        color:#fff;
        border-radius:25px;
        font-size:18px;
        font-weight:700;
    }

    @media(max-width:768px){

        .karigar-name{
            white-space:normal;
        }

        .apparel-wrapper{
            justify-content:center;
        }

        .apparel-card{
            min-width:85px;
        }

    }
</style>

<section class="container-fluid sticky_top">
    <?php $this->load->view('pages/'.$menu.'/'.$sub_menu.'/list/_navbar','add'==''); ?>
</section>

<section class="container-fluid mt-3">
    <div class="summary-card">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="20%">Karigar</th>
                        <th width="65%">Apparels</th>
                        <th width="10%">Total</th>
                    </tr>
                </thead>
                <tbody id="table_tbody">
                <?php if(!empty($data['data'])): ?>
                    <?php foreach($data['data'] as $key=>$value): ?>
                    <tr>
                        <td class="sr-no"><?= $key+1; ?></td>
                        <td class="karigar-name">
                            <i class="fa fa-user-circle text-primary"></i>
                            <?= $value['karigar_name']; ?>
                        </td>
                        <td>
                            <div class="apparel-wrapper">
                                <?php
                                if(!empty($value['apparel_data'])):
                                    foreach($value['apparel_data'] as $apparel=>$qty):
                                ?>
                                <div class="apparel-card">
                                    <div class="apparel-tag">
                                        <?= strtoupper($apparel); ?>
                                    </div>
                                    <div class="apparel-qty">
                                        <?= $qty; ?>
                                    </div>
                                </div>
                                <?php
                                    endforeach;
                                endif;
                                ?>
                            </div>
                        </td>
                        <td class="total-cell">
                            <span class="total-badge">
                                <?= $value['total_quantity']; ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">
                            <div class="text-center py-5">
                                <i class="fa fa-folder-open fa-3x text-secondary mb-3"></i>
                                <h5>No Records Found</h5>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<div class="pagination_wrapper">
    <?= $this->pagination->create_links(); ?>
</div>
<?php $this->load->view('templates/footer'); ?>
<?php
$this->load->view(
    'pages/'.$menu.'/'.$sub_menu.'/_filter',
    ['filters' => isset($data['filter']) ? $data['filter'] : []]
);
?>
<?php $this->load->view('pages/'.$menu.'/'.$sub_menu.'/list/_footer'); ?>
</body>
</html>