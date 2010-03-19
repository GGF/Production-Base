<?

	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/engine.php";
	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/core/classes/ajax.php";
	
	if (!$_SESSION[auth]) cmsExit("Вы не авторизованы.");
	
	$form = new cmsForm_ajax("auth_edit_mail");
	$form->initBackend();
	
	if ($_SESSION[authUser][type] == MODAUTH_TYPE_ADMIN) $form->errorCritical("Операция запрещена");
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	$oldUser =  $_SESSION[authUser];
	
	if ($form->request[mail] == $_SESSION[authUser][mail]) {
		
		$form->error(CMSFORM_ERROR_CUSTOM, "mail", "Е-mail должен отличатся от текущего.");
		
	} else {
		
		if (sqlShared::result("SELECT count(*) FROM cms_auth WHERE mail='%mail%'", array("mail" => $form->request[mail]))) {
			
			$form->error(CMSFORM_ERROR_CUSTOM, "mail", "Такой e-mail уже зарегистрирован");
			
		}
		
	}
	
	if (!$form->errors) {
		
		$confirmKey = cmsKey();
		
		sqlShared::update("cms_auth", "id='%id%'", array("id" => $_SESSION[authUser][id]), array(
			"status"			=> $_SERVER[modAuth][status],
			"confirm"			=> $_SERVER[modAuth][confirm],
			"confirmKey"	=> $confirmKey,
			"mail"				=> $form->request[mail],
		));
		
		modAuth_authorize($_SESSION[authUser][login], $_SESSION[authUser][pass], MODAUTH_PASS_PLAIN, $_COOKIE[modAuth_save]);
		
		$user = modAuth_getUser($_SESSION[authUser][id]);
		
		cmsCall($_SERVER[modAuth][events][editMail][callback], array($user, $oldUser));
		
		$mail = new cmsMail(false); // plain|html
		$mail->subject(cmsLang_var("modAuth.subject.edit"));
		$mail->content(cmsTemplate("/modules/auth/{$_SESSION[authUser][type]}/mail_edit.php", $user));
		$mail->send($form->request[mail]);
		
		$form->redirect = "/account/edit?result=success";
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	$form->processed();
	
?>