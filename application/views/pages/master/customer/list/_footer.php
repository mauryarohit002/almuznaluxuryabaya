<script src="<?php echo assets('dist/js/'.$menu.'/'.$sub_menu.'.js?v=4')?>"></script>
<script type="text/javascript">
    setTimeout(() => {
        $("#_name").select2(
            select2_default({
                url: `<?php echo $menu; ?>/<?php echo $sub_menu; ?>/get_select2/_name`,
                placeholder: "name",
            })
        ).on("change", () => trigger_search());
        $("#_code").select2(
            select2_default({
                url: `<?php echo $menu; ?>/<?php echo $sub_menu; ?>/get_select2/_code`,
                placeholder: "code",
            })
        ).on("change", () => trigger_search());
        $("#_mobile").select2(
            select2_default({
                url: `<?php echo $menu; ?>/<?php echo $sub_menu; ?>/get_select2/_mobile`,
                placeholder: "mobile no",
            })
        ).on("change", () => trigger_search());
    }, RELOAD_TIME)
</script>