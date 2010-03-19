<?
	
	$type = ($template[type]) ? $template[type] : MODAUTH_TYPE_DEFAULT;

	if ($_SESSION[auth]) {
		
		cmsTemplate_print("/modules/auth/{$_SESSION[authUser][type]}/template_site.php");
		
	} else {
		
		
		$form = new cmsForm_ajax("auth_site", "/modules/auth/actions/auth.php", "", array("autocomplete" => true));
		$form->addFields(array(
			array(
				"type"		=> CMSFORM_TYPE_TEXT,
				"name"		=> "login",
				"value"		=> "",
				"options"	=> array("length" => 32),
			),
			array(
				"type"		=> CMSFORM_TYPE_PASSWORD,
				"name"		=> "pass",
				"value"		=> "",
				"options"	=> array("length" => 32),
			),
			array(
				"type"		=> CMSFORM_TYPE_CHECKBOX,
				"name"		=> "save",
				"value"		=> $_COOKIE[userinfo_save],
				"label"		=> cmsLang_var("modAuth.save"),
			),
			array(
				"type"		=> CMSFORM_TYPE_SUBMIT,
				"name"		=> "submit",
				"value"		=> cmsLang_var("submit"),
			),
		));
		$form->addObligatory("login");
		$form->addObligatory("pass");
		$form->addChecker("login",	CMSFORM_CHECK_LOGIN);
		
		$form->init();
		$form->form();
		
		$tpl = array(
			"formObject"	=> &$form,
		);
		
		cmsTemplate_print("/modules/auth/common/form_site.php", $tpl);
		
		$form->end();
		$form->destroy();
		
	}
	
?>