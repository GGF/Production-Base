<?
	
	// не даем логину проверить залогиненность, чтобы избежать вечного цикла
	define("MODAUTH_ADMIN_NOCHECK", true);
	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/admin/login.php";
	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/core/classes/ajax.php";
	
	$form = new cmsForm_ajax("admin_auth");
	$form->initBackend();
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	if (!$form->errors) {
		
		$state = modAuth_authorize($form->request[login], $form->request[pass], MODAUTH_PASS_PLAIN, $form->request[save]);
		
		cmsCall($_SERVER[modAuth][events][authAdmin][callback]);
		
		$uri = $form->request[uri] ? $form->request[uri] : "/admin/";
		
		switch ($state) {
			case MODAUTH_STATE_AUTHORIZED:		$form->redirect = $uri; break;
			case MODAUTH_STATE_NOTCONFIRMED:	$form->error(CMSFORM_ERROR_CUSTOM, "login",	"E-mail не подтвержден"); break;
			case MODAUTH_STATE_NOTACTIVE:			$form->error(CMSFORM_ERROR_CUSTOM, "login",	"Аккаунт заблокирован"); break;
			case MODAUTH_STATE_ATTEMPTS:			$form->error(CMSFORM_ERROR_CUSTOM, "login",	"Слишком много попыток логина"); break;
			case MODAUTH_STATE_WRONGPASS:			$form->error(CMSFORM_ERROR_CUSTOM, "pass",	"Неверный пароль"); break;
			case MODAUTH_STATE_NOTFOUND:			$form->error(CMSFORM_ERROR_CUSTOM, "login",	"Пользователь не найден"); break;
			default:													$form->error(CMSFORM_ERROR_CUSTOM, "login",	"Неизвестная ошибка: " . $state); break;
		}
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	$form->processed();
	
?>