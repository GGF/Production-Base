<?

	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/engine.php";
	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/core/classes/ajax.php";
	
	$formName = isset($_REQUEST[form_auth_site]) ? "auth_site" : "auth";
	
	$form = new cmsForm_ajax($formName);
	$form->initBackend();
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	if (!$form->errors) {
		
		$state = modAuth_authorize($form->request[login], $form->request[pass], MODAUTH_PASS_PLAIN, $form->request[save]);
		
		cmsCall($_SERVER[modAuth][events][auth][callback]);
		
		switch ($state) {
			case MODAUTH_STATE_AUTHORIZED:		$form->redirect = ($formName == "auth_site") ? cmsReferer() : "/account/"; break;
			case MODAUTH_STATE_NOTCONFIRMED:	$form->html(cmsLang("modAuth.error.auth.{$state}")); break;
			case MODAUTH_STATE_NOTACTIVE:			$form->html(cmsLang("modAuth.error.auth.{$state}")); break;
			case MODAUTH_STATE_ATTEMPTS:			$form->html(cmsLang("modAuth.error.auth.{$state}")); break;
			case MODAUTH_STATE_WRONGPASS:			$form->error(CMSFORM_ERROR_CUSTOM, "pass", cmsLang("modAuth.error.auth.{$state}")); break;
			case MODAUTH_STATE_NOTFOUND:			$form->error(CMSFORM_ERROR_CUSTOM, "login", cmsLang("modAuth.error.auth.{$state}")); break;
			default:													$form->html("Неизвестная ошибка: {$state}"); break;
		}
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	$form->processed();
	
?>