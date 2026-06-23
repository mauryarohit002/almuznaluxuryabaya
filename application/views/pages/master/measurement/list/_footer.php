<script src="<?php echo assets('dist/js/'.$menu.'/measurement.js?v=1')?>"></script>
<script type="text/javascript">
    setTimeout(() => {
        $("#_name").select2(
            select2_default({
                url: `<?php echo $menu ?>/<?php echo $sub_menu; ?>/get_select2/_name`,
                placeholder: "measurement",
            })
        ).on("change", () => trigger_search());
        $("#_group").select2(
            select2_default({
                url: `<?php echo $menu ?>/<?php echo $sub_menu; ?>/get_select2/_group`,
                placeholder: "group",
            })
        ).on("change", () => trigger_search());
    }, RELOAD_TIME)
</script>