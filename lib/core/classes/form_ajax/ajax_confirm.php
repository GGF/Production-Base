<? 

	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/engine.php";
	
	$form = new cmsForm_ajax($_REQUEST[formName]);
	
	$form->initConfirm();
	$form->confirmGenerate();
	$form->confirmImage();
	$form->sessionSet();
	
?>