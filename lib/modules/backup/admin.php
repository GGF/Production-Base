<? 
	
	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/admin/login.php"; 
	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/admin/engine.php"; 

	cmsHeader("Восстановление");	
	$tabs[] = array("Восстановление", "#", 1);
	cmsContent($tabs);
	cmsCaption("/modules/backup/images/");

	?>
	
	<iframe src='admin_dumper.php' width='100%' height='100%' border='0' frameborder='0' style='border: 0px; height: 470px'></iframe>
	
	<?
	
	cmsFooter();
	
?>