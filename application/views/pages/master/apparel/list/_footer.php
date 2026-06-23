<script src="<?php echo assets('dist/js/'.$menu.'/apparel.js?v=4')?>"></script>
<script src="<?php echo assets('dist/js/'.$menu.'/common.js?v=1')?>"></script>
<script type="text/javascript">
    setTimeout(() => {
        $("#_apparel_name").select2(
            select2_default({
                url: `<?php echo $menu ?>/<?php echo $sub_menu; ?>/get_select2/_apparel_name`,
                placeholder: "apparel",
            })
        ).on("change", () => trigger_search());
        $("#_category_name").select2(
            select2_default({
                url: `<?php echo $menu ?>/<?php echo $sub_menu; ?>/get_select2/_category_name`,
                placeholder: "category",
            })
        ).on("change", () => trigger_search());
    }, RELOAD_TIME)
</script>