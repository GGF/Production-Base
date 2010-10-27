<? 

	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/lib/engine.php";
	
	$form = new cmsForm_ajax($_REQUEST[formName]);
	
	$form->initConfirm();
	$form->confirmGenerate();
	$form->confirmImage();
	$form->sessionSet();
	
?>