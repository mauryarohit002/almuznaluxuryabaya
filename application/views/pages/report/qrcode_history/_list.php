<?php 
	$this->load->view('templates/header'); 
	$search_status 	= !isset($_GET['search_status']);

?>
<script>
    let link 		= "<?php echo $menu; ?>";
    let sub_link 	= "<?php echo $sub_menu; ?>";
</script>
<section class="container-fluid sticky_top">
    <div class="d-flex justify-content-between">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item text-uppercase">
                    <a href="<?php echo base_url($menu.'/'.$sub_menu); ?>">
                        <?php echo str_replace('_', ' ', $menu_name); ?>
                    </a>
                </li>
                <li class="breadcrumb-item active text-uppercase" id="sub_menu_name" aria-current="page">
                    <?php echo str_replace('_', ' ', $sub_menu_name); ?>
                </li>
            </ol>
        </nav>
        <div class="d-flex align-items-center">
            <a 
                type="button" 
                class="btn btn-md btn-primary"
                data-toggle="tooltip" 
                data-placement="bottom" 
                title="REFRESH"
                href="<?php echo base_url($menu.'/'.$sub_menu); ?>"
            ><i class="text-info fa fa-undo"></i></a>
        </div>
    </div>
</section>
<section class="container-fluid">
	<div class="row mt-2">
        <div class="col-12 col-sm-12 col-md-6 col-lg-3" id="detail_wrapper"><?php echo $data['detail_data']; ?></div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-9" id="history_wrapper"><?php echo $data['history_data']; ?></div>
	</div>
</section>
<?php $this->load->view('templates/footer'); ?>
<script type="text/javascript">
    setTimeout(() => {item_code()}, RELOAD_TIME);
    const item_code = () => {
        $("#_item_code").select2(
            select2_default({
                url: `${link}/${sub_link}/get_select2/_item_code`,
                placeholder: "scan barcode",
                // maximumInputLength:12,
                // minimumInputLength:12,
                param:()=>$('#type').is(':checked'),
                barcode: "_item_code",
            })
        ).on("change", (event) => get_record(event.target.value));
    }
    const get_record = (_item_code) => {
        let url = $("#_item_code").val() == null ? '' : `?_item_code=${$("#_item_code :selected").text()}&type=${$("#type").is(':checked')}`
        window.location.href=`${base_url}/${link}/${sub_link}${url}`;
        const path = `${link}/${sub_link}/handler`;
        const form_data = { func: "get_record", sub_func: 'get_record', _item_code };
        ajaxCall(
            "POST",
            path,
            form_data,
            "JSON",
            (resp) => {
            if (handle_response(resp)) {
                const { data, msg } = resp;
                const {detail_data, history_data} = data;
                $('#detail_wrapper').html(detail_data)
                $('#history_wrapper').html(history_data)
                item_code();
            }
            },
            (errmsg) => {}
        );
    }
    const update_mrp = (id) => {
        event.preventDefault();
        let mrp = $(`#brmm_mrp`).val();
        if (mrp == "" || mrp <= 0) {
            toastr.error("Invalid MRP", '', { closeButton: true, progressBar: true });
            return false;
        }
        const path = `${link}/${sub_link}/handler`;
        const form_data = {func: 'update_mrp', id, mrp}
        ajaxCall(
            "POST",
            path,
            form_data,
            "JSON",
            (resp) => {
                if (handle_response(resp)) {
                    const { data, msg } = resp;
                    toastr.success("", msg, { closeButton: true, progressBar: true });
                    setTimeout(() => {
                        window.open(
                            `${base_url}/transaction/purchase_readymade?action=qrcode&clause=brmm.brmm_id&id=${data}`,
                            "_blank",
                            "width=1024, height=768"
                        );
                    }, RELOAD_TIME);
                }
            },
            (errmsg) => {}
        );
    };
</script>
</body>
</html>