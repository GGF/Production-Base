<?

	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/engine.php";
	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/core/classes/ajax.php";
	
	$form = new cmsForm_ajax("auth_recover");
	$form->initBackend();
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	if (!$form->errors) {
		
		$f = sqlShared::fetch("SELECT * FROM cms_auth WHERE mail='%mail%'", array("mail" => $form->request[mail]));
		if ($f) {
			
			$user = modAuth_getUser($f);
			
			if ($user[type] == MODAUTH_TYPE_ADMIN) $form->errorCritical("Операция запрещена");
			
			$mail = new cmsMail(false);
			$mail->subject(cmsLang_var("modAuth.subject.recover"));
			$mail->content(cmsTemplate("/modules/auth/" . MODAUTH_TYPE_DEFAULT . "/mail_register.php", $user));
			$mail->send($form->request[mail]);
			
			$form->redirect = "/account/recover?result=success";
			
		} else $form->error(CMSFORM_ERROR_CUSTOM, "mail", cmsLang("modAuth.error.mail"));
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	$form->processed();
	
?>