<script src="<?php echo assets('dist/js/'.$menu.'/general.js?v=1')?>"></script>
<script src="<?php echo assets('dist/js/'.$menu.'/common.js?v=2')?>"></script>
<script type="text/javascript">
    setTimeout(() => {
        $("#_general_name").select2(
            select2_default({
                url: `<?php echo $menu ?>/<?php echo $sub_menu; ?>/get_select2/_general_name`,
                placeholder: "general",
            })
        ).on("change", () => trigger_search());
    }, RELOAD_TIME)
</script>