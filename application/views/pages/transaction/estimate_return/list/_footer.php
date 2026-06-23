<script src="<?php echo assets('dist/js/transaction/purchase_readymade_return.js?v=2')?>"></script>
<script type="text/javascript">
    setTimeout(() => {
        $("#_entry_no").select2(
            select2_default({
                url: `<?php echo $menu; ?>/<?php echo $sub_menu; ?>/get_select2/_entry_no`,
                placeholder: "entry no",
            })
        ).on("change", () => trigger_search());
        $("#_bill_no").select2(
            select2_default({
                url: `<?php echo $menu; ?>/<?php echo $sub_menu; ?>/get_select2/_bill_no`,
                placeholder: "bill no",
            })
        ).on("change", () => trigger_search());
        $("#_supplier_name").select2(
            select2_default({
                url: `<?php echo $menu; ?>/<?php echo $sub_menu; ?>/get_select2/_supplier_name`,
                placeholder: "supplier",
            })
        ).on("change", () => trigger_search());
    }, RELOAD_TIME);
</script>