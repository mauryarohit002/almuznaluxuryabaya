<script src="<?php echo assets('dist/js/'.$menu.'/measurement_setting.js?v=1')?>"></script>
<script type="text/javascript">
    setTimeout(() => {
        $("#_apparel_name").select2(
            select2_default({
                url: `<?php echo $menu ?>/<?php echo $sub_menu; ?>/get_select2/_apparel_name`,
                placeholder: "apparel",
            })
        ).on("change", () => trigger_search());
        $("#_measurement_name").select2(
            select2_default({
                url: `<?php echo $menu ?>/<?php echo $sub_menu; ?>/get_select2/_measurement_name`,
                placeholder: "measurement",
            })
        ).on("change", () => trigger_search());
    }, RELOAD_TIME)
</script>