<script src="<?php echo assets('dist/js/'.$menu.'/'.$sub_menu.'.js?v=2')?>"></script>
<script type="text/javascript">
    setTimeout(() => {
        $("#_name").select2(
            select2_default({
                url: `<?php echo $menu; ?>/<?php echo $sub_menu; ?>/get_select2/_name`,
                placeholder: "name",
            })
        ).on("change", () => trigger_search());
        $("#_person").select2(
            select2_default({
                url: `<?php echo $menu; ?>/<?php echo $sub_menu; ?>/get_select2/_person`,
                placeholder: "contact person",
            })
        ).on("change", () => trigger_search());
        $("#_mobile").select2(
            select2_default({
                url: `<?php echo $menu; ?>/<?php echo $sub_menu; ?>/get_select2/_mobile`,
                placeholder: "mobile no",
            })
        ).on("change", () => trigger_search());
    }, RELOAD_TIME);
</script>