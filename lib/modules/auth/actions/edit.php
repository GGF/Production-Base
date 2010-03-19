<?

	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/engine.php";
	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/core/classes/ajax.php";
	
	if (!$_SESSION[auth]) cmsExit("Вы не авторизованы.");
	
	$form = new cmsForm_ajax("auth_edit");
	$form->initBackend();
	
	if ($_SESSION[authUser][type] == MODAUTH_TYPE_ADMIN) $form->errorCritical("Операция запрещена");
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	$error = false;
	
	$oldUser =  $_SESSION[authUser];
	
	if (!$form->errors) {
		
		sqlShared::update("cms_auth", "id='%id%'", array("id" => $_SESSION[authUser][id]), array(
			"status"	=> $_SERVER[modAuth][status],
			"json"		=> cmsJSON_encode($form->request[custom]),
			"name"		=> $form->request[name],
		));
		
		modAuth_authorize($_SESSION[authUser][login], $_SESSION[authUser][pass], MODAUTH_PASS_PLAIN, $_COOKIE[modAuth_save]);
		
		$user = modAuth_getUser($_SESSION[authUser][id]);
		
		cmsCall($_SERVER[modAuth][events][edit][callback], array($user, $oldUser));
		
		$mail = new cmsMail(false); // plain|html
		$mail->subject(cmsLang_var("modAuth.subject.edit"));
		$mail->content(cmsTemplate("/modules/auth/{$_SESSION[authUser][type]}/mail_edit.php", $user));
		$mail->send($_SESSION[authUser][mail]);
		
		$form->redirect = "/account/edit?result=success";
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	$form->processed();
	
?>