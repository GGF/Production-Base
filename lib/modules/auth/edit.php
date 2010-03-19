<?

	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/engine.php";
	
	modAuth_checkPlugin();
	
	if (!$_SESSION[auth]) cmsRedirect("/account/");
	if ($_SESSION[authUser][type] == MODAUTH_TYPE_ADMIN) cmsDie("Операция запрещена");
	
	$result = checkString($_REQUEST[result]);
	
	/*------------------------------------------------------------------------------------------------*/
	/*--	T E M P L A T E   I N I T																																	--*/
	/*------------------------------------------------------------------------------------------------*/
	/**/	
	/**/	$refPage = page_alias("/account/");
	/**/	
	/**/	page::$module				= "auth";
	/**/	page::$id						= $refPage[id];
	/**/	page::$uid					= $refPage[uid];
	/**/	page::$parent				= $refPage[parent];
	/**/	page::$path					= page_path($refPage[id]);
	/**/	page::$pathHTML			= page_pathHTML($refPage[id]) . $_SERVER[delim] . "<a href='/account/'>" . cmsLang("modAuth.title") . "</a>" . $_SERVER[delim] . cmsLang("modAuth.edit");
	/**/	page::$name					= cmsLang("modAuth.edit");
	/**/	page::$cache				= false;
	/**/	
	/**/	page::$modVars[id]	= "edit";
	/**/	
	/**/	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/template.php";
	/**/	
	/*------------------------------------------------------------------------------------------------*/
	
	if ($result == "success") {
		
		cmsTemplate_print("/modules/auth/{$_SESSION[authUser][type]}/template_edit.php");
		
	} else {
		
		// Обычные данные
		
		$form = new cmsForm_ajax("auth_edit", "/modules/auth/actions/edit.php", "");
		$form->addFields(array(
			array(
				"type"		=> CMSFORM_TYPE_TEXT,
				"name"		=> "name",
				"value"		=> $_SESSION[authUser][name],
			),
			array(
				"type"		=> CMSFORM_TYPE_SUBMIT,
				"name"		=> "submit",
				"value"		=> "Внести изменения",
			),
			array(
				"type"		=> CMSFORM_TYPE_RESET,
				"name"		=> "reset",
				"value"		=> "Отмена",
			),
		));
		
		$form->addObligatory("name");
		
		$fields = array(
			"custom"	=> import($_SERVER[TEMPLATES] . "/modules/auth/{$_SESSION[authUser][type]}/_custom.php"),
		);
		
		$form->addFields($fields[custom][fields]);
		if (is_array($fields[custom][formats]))			foreach ($fields[custom][formats]			as $format) 		call_user_func_array(array(&$form, "addFormat"), $format);
		if (is_array($fields[custom][checkers]))		foreach ($fields[custom][checkers]		as $checker) 		call_user_func_array(array(&$form, "addChecker"), $checker);
		if (is_array($fields[custom][obligatory]))	foreach ($fields[custom][obligatory]	as $obligatory) call_user_func_array(array(&$form, "addObligatory"), $obligatory);
		
		$form->init();
		$form->form();
		
		$tpl = array(
			"formObject"	=> &$form,
		);
		
		cmsTemplate_print("/modules/auth/{$_SESSION[authUser][type]}/form_edit.php", $tpl);
		
		$form->end();
		$form->destroy();
		
		// Замена пароля
		
		$form = new cmsForm_ajax("auth_edit_pass", "/modules/auth/actions/edit_pass.php", "");
		$form->addFields(array(
			array(
				"type"		=> CMSFORM_TYPE_PASSWORD,
				"name"		=> "pass1",
				"value"		=> "",
				"options"	=> array("length" => 32),
			),
			array(
				"type"		=> CMSFORM_TYPE_PASSWORD,
				"name"		=> "pass2",
				"value"		=> "",
				"options"	=> array("length" => 32),
			),
			array(
				"type"		=> CMSFORM_TYPE_SUBMIT,
				"name"		=> "submit",
				"value"		=> "Изменить пароль",
			),
			array(
				"type"		=> CMSFORM_TYPE_RESET,
				"name"		=> "reset",
				"value"		=> "Отмена",
			),
		));
		
		$form->addObligatory("pass1");
		$form->addObligatory("pass2");
		
		$form->init();
		$form->form();
		
		$tpl = array(
			"formObject"	=> &$form,
		);
		
		cmsTemplate_print("/modules/auth/{$_SESSION[authUser][type]}/form_edit_pass.php", $tpl);
		
		$form->end();
		$form->destroy();
		
		// Замена e-mail
		
		$form = new cmsForm_ajax("auth_edit_mail", "/modules/auth/actions/edit_mail.php", "");
		$form->addFields(array(
			array(
				"type"		=> CMSFORM_TYPE_TEXT,
				"name"		=> "mail",
				"value"		=> $_SESSION[authUser][mail],
			),
			array(
				"type"		=> CMSFORM_TYPE_SUBMIT,
				"name"		=> "submit",
				"value"		=> "Изменить e-mail",
			),
			array(
				"type"		=> CMSFORM_TYPE_RESET,
				"name"		=> "reset",
				"value"		=> "Отмена",
			),
		));
		
		$form->addObligatory("mail");
		$form->addChecker("mail",	CMSFORM_CHECK_MAIL);
		$form->addFormat("mail",	CMSFORM_FORMAT_MAIL);
		
		$form->init();
		$form->form();
		
		$tpl = array(
			"formObject"	=> &$form,
		);
		
		cmsTemplate_print("/modules/auth/{$_SESSION[authUser][type]}/form_edit_mail.php", $tpl);
		
		$form->end();
		$form->destroy();
		
	}
	
	/*------------------------------------------------------------------------------------------------*/
	/**/	
	/**/	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/template.php";
	/**/	
	/*------------------------------------------------------------------------------------------------*/
	
?>