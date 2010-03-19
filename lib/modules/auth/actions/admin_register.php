<?

	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/admin/login.php";
	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/core/classes/ajax.php";
	
	$type = null;
	
	$form = new cmsForm_ajax("admin_auth_register");
	$form->initBackend();
	
	$form->request[login] = mb_strtolower($form->request[login]);
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	$error = false;
	
	if (sqlShared::result("SELECT count(*) FROM cms_auth WHERE mail='%mail%'", array("mail" => $form->request[mail]))) {
		
		$form->error(CMSFORM_ERROR_CUSTOM, "mail", "“акой e-mail уже зарегистрирован");
		
	}
	
	if (sqlShared::result("SELECT count(*) FROM cms_auth WHERE login='%login%'", array("login" => $form->request[login]))) {
		
		$form->error(CMSFORM_ERROR_CUSTOM, "login", "“акой логин уже зарегистрирован");
		
	}
	
	if (!$form->errors) {
		
		$confirmKey = cmsKey();
		
		sqlShared::insert("cms_auth", array(
			"login"				=> $form->request[login],
			"pass"				=> $form->request[pass],
			"mail"				=> $form->request[mail],
			"status"			=> $form->request[status],
			"blog"				=> 0,
			"confirm"			=> $form->request[xconfirm],
			"confirmKey"	=> $confirmKey,
			"type"				=> MODAUTH_TYPE_ADMIN,
			"date"				=> time(),
			"name"				=> $form->request[name],
			"json"				=> cmsJSON_encode($form->request[custom]),
			"jsonAdmin"		=> cmsJSON_encode($form->request[admin]),
		));
		
		$id = sqlShared::lastID();
		
		$usr = modAuth_getUser($id);
		
		cmsCall($_SERVER[modAuth][events][registerAdmin][callback], array($usr));
		
		$form->redirect = "/modules/auth/admin_edit.php?id={$id}";
		
	}// else $form->alert("–егистрационна€ форма должна быть заполнена правильно.");
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	$form->processed();
	
?>