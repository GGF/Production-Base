<?

	if (!is_callable("set")) REQUIRE $_SERVER[DOCUMENT_ROOT] . "/engine.php";
	
	$action		= checkString($_REQUEST[action], "id");
	
	if ($_REQUEST[redirect]) $_SESSION[modAuth][redirect] = cmsReferer();
	
	if ($action == "logout") {
		
		modAuth_killSession();
		
		// переместить юзера на страницу откуда он пришел
		if ($_SESSION[modAuth][redirect]) {
			
			$redirect = $_SESSION[modAuth][redirect];
			unset($_SESSION[modAuth][redirect]);
			
			cmsRedirect($redirect);
			
		}
		
	}
	
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
	/**/	page::$pathHTML			= page_pathHTML($refPage[id]) . modAuth_refPage($refPage);
	/**/	page::$name					= cmsLang("modAuth.title");
	/**/	page::$cache				= false;
	/**/	
	/**/	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/template.php";
	/**/	
	/*------------------------------------------------------------------------------------------------*/
	
	if ($_SESSION[auth]) {
		
		unset($_SESSION[modAuth][redirect]);
		cmsTemplate_print("/modules/auth/{$_SESSION[authUser][type]}/template_auth.php");
		
	} else {
		
		$form = new cmsForm_ajax("auth", "/modules/auth/actions/auth.php", "", array("autocomplete" => true));
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
		
		cmsTemplate_print("/modules/auth/common/form_auth.php", $tpl);
		
		$form->end();
		$form->destroy();
		
	}
	
	/*------------------------------------------------------------------------------------------------*/
	/**/	
	/**/	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/template.php";
	/**/	
	/*------------------------------------------------------------------------------------------------*/
	
?>