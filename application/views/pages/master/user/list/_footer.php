<script src="<?php echo assets('dist/js/'.$menu.'/'.$sub_menu.'.js?v=3')?>"></script>
<script type="text/javascript">
	setTimeout(() => {
		$("#_fullname").select2(
			select2_default({
			url: `<?php echo $menu; ?>/<?php echo $sub_menu; ?>/get_select2/_fullname`,
			placeholder: "USER",
			})
		);
		$("#_role_name").select2(
			select2_default({
			url: `<?php echo $menu; ?>/<?php echo $sub_menu; ?>/get_select2/_role_name`,
			placeholder: "ROLE",
			})
		);
		$("#_branch_name").select2(
			select2_default({
			url: `<?php echo $menu; ?>/<?php echo $sub_menu; ?>/get_select2/_branch_name`,
			placeholder: "BRANCH",
			})
		);
	}, RELOAD_TIME);
</script>