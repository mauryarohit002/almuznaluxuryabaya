<script src="<?php echo assets('dist/js/'.$menu.'/branch.js?v=4')?>"></script>
<script type="text/javascript">
    setTimeout(() => {
        $("#_branch_name").select2(
            select2_default({
                url: `<?php echo $menu ?>/<?php echo $sub_menu; ?>/get_select2/_branch_name`,
                placeholder: "branch",
            })
        ).on("change", () => trigger_search());
    }, RELOAD_TIME)
</script>