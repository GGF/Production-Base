<?

	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/engine.php";
	
	modAuth_checkPlugin();
	
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
	/**/	page::$pathHTML			= page_pathHTML($refPage[id]) . modAuth_refPage($refPage, cmsLang("modAuth.recover"));
	/**/	page::$name					= cmsLang("modAuth.recover");
	/**/	page::$cache				= false;
	/**/	
	/**/	page::$modVars[id]	= "recover";
	/**/	
	/**/	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/template.php";
	/**/	
	/*------------------------------------------------------------------------------------------------*/
	
	if ($result == "success") {
		
		cmsTemplate_print("/modules/auth/common/template_recover.php");
		
	} else {
		
		$form = new cmsForm_ajax("auth_recover", "/modules/auth/actions/recover.php", "", array("autocomplete" => true));
		$form->addFields(array(
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
				"value"		=> "Выслать данные",
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
		
		cmsTemplate_print("/modules/auth/common/form_recover.php", $tpl);
		
		$form->end();
		$form->destroy();
		
	}
	
	/*------------------------------------------------------------------------------------------------*/
	/**/	
	/**/	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/template.php";
	/**/	
	/*------------------------------------------------------------------------------------------------*/
	
?>