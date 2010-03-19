<?

	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/engine.php";
	
	modAuth_checkPlugin();
	
	$result		= checkString($_REQUEST[result]);
	$type			= checkString($_REQUEST[type]);
	
	if (!$_SERVER[modAuth][type][$type]) $type = MODAUTH_TYPE_DEFAULT;
	
	if ($type == MODAUTH_TYPE_ADMIN) cmsDie("Операция запрещена");
	
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
	/**/	page::$pathHTML			= page_pathHTML($refPage[id]) . modAuth_refPage($refPage, cmsLang("modAuth.registration"));
	/**/	page::$name					= cmsLang("modAuth.registration");
	/**/	page::$cache				= false;
	/**/	
	/**/	page::$modVars[id]	= "register";
	/**/	
	/**/	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/template.php";
	/**/	
	/*------------------------------------------------------------------------------------------------*/
	
	if ($result == "success") {
		
		cmsTemplate_print("/modules/auth/{$type}/template_register.php");
		
	} else {
		
		print tokens::get("modAuth.register", array("type" => $type));
		
	}
	
	/*------------------------------------------------------------------------------------------------*/
	/**/	
	/**/	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/template.php";
	/**/	
	/*------------------------------------------------------------------------------------------------*/
	
?>