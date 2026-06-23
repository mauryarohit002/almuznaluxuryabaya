<script src="<?php echo assets('dist/js/master/common.js?v=1')?>"></script>
<script type="text/javascript">
    setTimeout(() => {
        $("#_name").select2(
            select2_default({
            url: "master/<?php echo $sub_menu; ?>/get_select2/_name",
            placeholder: "<?php echo $sub_menu; ?>",
        }));
    }, RELOAD_TIME)
</script>