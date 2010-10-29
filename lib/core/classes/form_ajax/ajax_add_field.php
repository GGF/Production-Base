<?
	REQUIRE $_SERVER["DOCUMENT_ROOT"] . "/engine.php";
	REQUIRE $_SERVER["DOCUMENT_ROOT"] . "/core/classes/ajax.php";
	
	$form = new cmsForm_ajax($_REQUEST[formID]);
	$form->initConfirm();
	
	$_REQUEST = checkString($_REQUEST);
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	$status = false;
	$html = "";
	
	if (!$form->fields[$_REQUEST[name]]) {
		
		$form->addFields(array(
							array(
								"type"		=> $_REQUEST[type],
								"name"		=> $_REQUEST[name],
								"value"		=> $_REQUEST[value],
								"values"	=> $_REQUEST[values],
								"label"		=> $_REQUEST[label],
								"options"	=> $_REQUEST[options],
							),
						)
			   );
		
		
		$form->sessionSet();
		
		$html = $form->add($_REQUEST[name]);
		
		$status = true;
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	$_RESULT = array(
		"html"		=> $html,
		"status"	=> $status,
	);
	
?>