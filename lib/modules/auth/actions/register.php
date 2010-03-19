<?

	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/engine.php";
	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/core/classes/ajax.php";
	
	$type = null;
	
	foreach ($_SERVER[modAuth][type] as $k => $v) if ($_REQUEST["form_auth_register_{$k}"]) $type = $k;
	
	if (!$type) cmsExit("Потерян тип регистрации.");
	
	$form = new cmsForm_ajax("auth_register_{$type}");
	$form->initBackend();
	
	if ($type == MODAUTH_TYPE_ADMIN) $form->errorCritical("Операция запрещена");
	
	$form->request[login] = mb_strtolower($form->request[login]);
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	$reservedLogins = array(
		"admin",
		"administration",
	);
	
	$error = false;
	
	if (sqlShared::result("SELECT count(*) FROM cms_auth WHERE mail='%mail%'", array("mail" => $form->request[mail]))) {
		
		$form->error(CMSFORM_ERROR_CUSTOM, "mail", "Такой e-mail уже зарегистрирован");
		
	}
	
	if (sqlShared::result("SELECT count(*) FROM cms_auth WHERE login='%login%'", array("login" => $form->request[login])) || @in_array($form->request[login], $reservedLogins)) {
		
		$form->error(CMSFORM_ERROR_CUSTOM, "login", "Такой логин уже зарегистрирован");
		
	}
	
	if (mb_strlen($form->request[login]) < 3) {
		
		$form->error(CMSFORM_ERROR_CUSTOM, "login", "Логин слишком короткий");
		
	}
	
	if ($form->request[pass1] != $form->request[pass2]) {
		
		$form->error(CMSFORM_ERROR_CUSTOM, "pass1", "Пароли должны совпадать");
		$form->error(CMSFORM_ERROR_CUSTOM, "pass2", "Пароли должны совпадать");
		
	}
	
	if (!$form->errors) {
		
		$confirmKey = cmsKey();
		
		$form->request[pass] = $form->request[pass1];
		
		sqlShared::insert("cms_auth", array(
			"login"				=> $form->request[login],
			"pass"				=> $form->request[pass],
			"mail"				=> $form->request[mail],
			"status"			=> $_SERVER[modAuth][status],
			"confirm"			=> $_SERVER[modAuth][confirm],
			"confirmKey"	=> $confirmKey,
			"type"				=> $type,
			"date"				=> time(),
			"name"				=> $form->request[name],
			"json"				=> cmsJSON_encode($form->request[custom]),
		));
		
		$id = sqlShared::lastID();
		
		modAuth_authorize($form->request[login], $form->request[pass], MODAUTH_PASS_PLAIN, MODAUTH_NOSAVE);
		
		$user = modAuth_getUser($id);
		
		cmsCall($_SERVER[modAuth][events][register][callback], array($user));
		
		$mail = new cmsMail(false); // plain|html
		$mail->subject(cmsLang_var("modAuth.subject.register"));
		$mail->content(cmsTemplate("/modules/auth/{$type}/mail_register.php", $user));
		$mail->send($form->request[mail]);
		$mail->send($_SERVER[project][admin]);
		
		$form->redirect = "/account/register/{$type}?result=success";
		
	}// else $form->alert("Регистрационная форма должна быть заполнена правильно.");
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	$form->processed();
	
?>