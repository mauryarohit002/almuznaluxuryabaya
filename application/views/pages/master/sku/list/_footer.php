<script src="<?php echo assets('dist/js/'.$menu.'/'.$sub_menu.'/list.js?v=1')?>">
<script>
    $(document).ready(function () {
        lazy_loading('master_loading');
            $("#_sku_name").select2(
                select2_default({
                    url: `<?php echo $menu; ?>/<?php echo $sub_menu; ?>/get_select2/_sku_name`,
                    placeholder: "sku",
                })
            ).on("change", () => trigger_search());
            $("#_apparel_name").select2(
                select2_default({
                    url: `<?php echo $menu; ?>/<?php echo $sub_menu; ?>/get_select2/_apparel_name`,
                    placeholder: "apparel",
                })
            ).on("change", () => trigger_search());

    });
</script>