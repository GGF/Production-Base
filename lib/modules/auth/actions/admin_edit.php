<?
	
	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/admin/login.php";
	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/core/classes/ajax.php";
	
	$form = new cmsForm_ajax("admin_auth_edit");
	$form->initBackend();
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	if (!$form->errors) {
		
		$oldUser = modAuth_getUser($form->request[id]);
		
		if ($form->request[login] == "admin") {
			
			$form->request[status]				= 1;
			$form->request[xconfirm]			= 1;
			$form->request[confirmKey]		= "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";
			$form->request[blog]					= 1;
			
			foreach ($_SERVER[modules] as $m => $x) $form->request[admin][rights][$m] = 1;
			
		}
		
		if (count($form->request[admin][rights])) foreach ($form->request[admin][rights] as $k => $v) if (!$v) unset($form->request[admin][rights][$k]);
		
		sqlShared::update("cms_auth", "id='%id%'", array("id" => $form->request[id]), array(
			"status"			=> $form->request[status],
			"confirm"			=> $form->request[xconfirm],
			"confirmKey"	=> $form->request[confirmKey],
			"name"				=> $form->request[name],
			"mail"				=> $form->request[mail],
			"pass"				=> $form->request[pass],
			"json"				=> cmsJSON_encode($form->request[custom]),
			"jsonAdmin"		=> cmsJSON_encode($form->request[admin]),
		));
		
		$usr = modAuth_getUser($form->request[id]);
		
		cmsCall($_SERVER[modAuth][events][editAdmin][callback], array($usr, $oldUser));
		
		$_RESULT += array(
			"id"			=> $form->request[id],
			"login"		=> $form->request[login],
			"name"		=> $form->request[name],
			"result"	=> "success",
		);
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	$form->processed();
	
?>