<?
	
	$type = $template['type'];
	
	if (!$_SERVER[modAuth][type][$type]) $type = MODAUTH_TYPE_DEFAULT;
	
	if ($type == MODAUTH_TYPE_ADMIN) return false;
	
	
	
	$form = new cmsForm_ajax("auth_register_{$type}", "/modules/auth/actions/register.php", "");
	$form->addFields(array(
		array(
			"type"		=> CMSFORM_TYPE_TEXT,
			"name"		=> "login",
			"value"		=> "",
			"options"	=> array("length" => 32),
		),
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
			"type"		=> CMSFORM_TYPE_TEXT,
			"name"		=> "name",
			"value"		=> "",
		),
		array(
			"type"		=> CMSFORM_TYPE_TEXT,
			"name"		=> "mail",
			"value"		=> "",
		),
		array(
			"type"		=> CMSFORM_TYPE_CODE,
		),
		array(
			"type"		=> CMSFORM_TYPE_SUBMIT,
			"name"		=> "submit",
			"value"		=> "Зарегистрироваться",
		),
		array(
			"type"		=> CMSFORM_TYPE_RESET,
			"name"		=> "reset",
			"value"		=> "Очистить",
		),
	));
	
	$form->addObligatory("login");
	$form->addObligatory("pass1");
	$form->addObligatory("pass2");
	$form->addObligatory("name");
	$form->addObligatory("mail");
	
	$form->addChecker("login",	CMSFORM_CHECK_LOGIN);
	$form->addChecker("mail",	CMSFORM_CHECK_MAIL);
	
	$form->addFormat("login",	CMSFORM_FORMAT_LOGIN);
	$form->addFormat("mail",	CMSFORM_FORMAT_MAIL);
	
	$fields = array(
		"custom"	=> import($_SERVER[TEMPLATES] . "/modules/auth/{$type}/_custom.php"),
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
	
	cmsTemplate_print("/modules/auth/{$type}/form_register.php", $tpl);
	
	$form->end();
	$form->destroy();
	
?>