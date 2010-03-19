<?

	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/engine.php";
	
	modAuth_checkPlugin();
	
	$code		= checkString($_REQUEST[code]);
	$result	= checkString($_REQUEST[result]);
	
	if ($code) {
		
		$f = sqlShared::fetch("SELECT * FROM cms_auth WHERE confirmKey='%code%'", array("code" => $code));
		
		if ($f) {
			
			if ($f[type] == MODAUTH_TYPE_ADMIN) cmsDie("Операция запрещена");
			
			sqlShared::update("cms_auth", "id='%id%'", array("id" => $f[id]), array("confirm" => 1));
			modAuth_authorize($f[login], $f[pass], MODAUTH_PASS_PLAIN, MODAUTH_NOSAVE);
			
			cmsRedirect("/account/confirm?result=success"); 
			
		} else cmsRedirect("/account/confirm?result=error");
		
	} else {
		
		/*------------------------------------------------------------------------------------------------*/
		/*--	T E M P L A T E   I N I T																																	--*/
		/*------------------------------------------------------------------------------------------------*/
		/**/	
		/**/	$refPage = page_alias('/account/');
		/**/	
		/**/	page::$module				= "auth";
		/**/	page::$id						= $refPage[id];
		/**/	page::$uid					= $refPage[uid];
		/**/	page::$parent				= $refPage[parent];
		/**/	page::$path					= page_path($refPage[id]);
		/**/	page::$pathHTML			= page_pathHTML($refPage[id]) . $_SERVER[delim] . "<a href='/account/'>" . cmsLang("modAuth.title") . "</a>" . $_SERVER[delim] . cmsLang("modAuth.confirm");
		/**/	page::$name					= cmsLang("modAuth.confirm");
		/**/	page::$cache				= false;
		/**/	
		/**/	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/template.php";
		/**/	
		/*------------------------------------------------------------------------------------------------*/
		
		if ($result == 'success') {
			
			cmsTemplate_print("/modules/auth/common/template_confirm.php");			
			
		} else {
			
			cmsTemplate_print("/modules/auth/common/template_confirm_error.php");			
			
		}
		
		/*------------------------------------------------------------------------------------------------*/
		/**/	
		/**/	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/template.php";
		/**/	
		/*------------------------------------------------------------------------------------------------*/
		
	}
	
?>