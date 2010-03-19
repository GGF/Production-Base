<? 

	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/engine.php";
	
	$form = new cmsForm("_send", "/pages/send.php", "basic");
	
	if ($_REQUEST[json]) {
		
		REQUIRE $_SERVER[DOCUMENT_ROOT] . "/core/classes/ajax.php";
		$_RESULT = $form->confirm();
		
	} else {
		
		$form->confirmImage($_REQUEST[code], $_REQUEST[width], $_REQUEST[height]);
		
	}
	
?>