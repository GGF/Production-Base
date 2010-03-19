<?

	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/engine.php";
	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/core/classes/ajax.php";
	
	if (!$_SESSION[auth]) cmsExit("Вы не авторизованы.");
	
	$form = new cmsForm_ajax("auth_edit_pass");
	$form->initBackend();
	
	if ($_SESSION[authUser][type] == MODAUTH_TYPE_ADMIN) $form->errorCritical("Операция запрещена");
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	$oldUser =  $_SESSION[authUser];
	
	if ($form->request[pass1] != $form->request[pass2]) {
		
		$form->error(CMSFORM_ERROR_CUSTOM, "pass1", "Пароли должны совпадать");
		$form->error(CMSFORM_ERROR_CUSTOM, "pass2", "Пароли должны совпадать");
		
	}
	
	if (!$form->errors) {
		
		$form->request[pass] = $form->request[pass1];
		
		sqlShared::update("cms_auth", "id='%id%'", array("id" => $_SESSION[authUser][id]), array(
			"status"	=> $_SERVER[modAuth][status],
			"pass"		=> $form->request[pass],
		));
		
		modAuth_authorize($_SESSION[authUser][login], $form->request[pass], MODAUTH_PASS_PLAIN, $_COOKIE[modAuth_save], false);
		
		$user = modAuth_getUser($_SESSION[authUser][id]);
		
		cmsCall($_SERVER[modAuth][events][editPass][callback], array($user, $oldUser));
		
		$mail = new cmsMail(false); // plain|html
		$mail->subject(cmsLang_var("modAuth.subject.edit"));
		$mail->content(cmsTemplate("/modules/auth/{$_SESSION[authUser][type]}/mail_edit.php", $user));
		$mail->send($_SESSION[authUser][mail]);
		
		$form->redirect = "/account/edit?result=success";
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	$form->processed();
	
?>