<? 
	
	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	tokens::add("cmsHead",						"/lib/core/tokens/cmsHead.php",						CMSTOKENS_FILE_ABS);
	tokens::add("cmsHead.belated",		"/lib/core/tokens/cmsHead.belated.php",		CMSTOKENS_FILE_ABS);
	tokens::add("cmsVideo",						"/lib/core/tokens/cmsVideo.php",					CMSTOKENS_FILE_ABS);
	tokens::add("cmsFlash",						"/lib/core/tokens/cmsFlash.php",					CMSTOKENS_FILE_ABS);
	tokens::add("cmsAdmin",						"/lib/core/tokens/cmsAdmin.php",					CMSTOKENS_FILE_ABS);
	tokens::add("cmsPopup",						"/lib/core/tokens/cmsPopup.php",					CMSTOKENS_FILE_ABS);
	tokens::add("cmsLayout",					"/lib/core/tokens/cmsLayout.php",					CMSTOKENS_FILE_ABS);
	tokens::add("cmsGMap",						"/lib/core/tokens/cmsGMap.php",						CMSTOKENS_FILE_ABS);
	tokens::add("cmsRounded",					"/lib/core/tokens/cmsRounded.php",				CMSTOKENS_FILE_ABS);
	tokens::add("cmsDoctype",					"/lib/core/tokens/cmsDoctype.php",				CMSTOKENS_FILE_ABS);
	
	tokens::add("template.head",			"/lib/core/tokens/cmsHead.php",						CMSTOKENS_FILE_ABS); // DEPRECATED
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	tokens::addVar("cmsPage.id",					page::$id);
	tokens::addVar("cmsPage.uid",					page::$uid);
	tokens::addVar("cmsPage.name",				page::$name);
	tokens::addVar("cmsPage.pathHTML",		page::$pathHTML);
	tokens::addVar("cmsPage.pathPlain",		page::$pathPlain);
	
	tokens::addVar("cmsPage.title",				page::$meta[title]);
	tokens::addVar("cmsPage.desc",				page::$meta[desc]);
	tokens::addVar("cmsPage.keys",				page::$meta[keys]);
	
	tokens::addVar("cmsPage.contents",		page::$contents);
	tokens::addVar("cmsPage.content",			page::$contents); // Flexibility
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	tokens::addVar("cmsName",							$_SERVER[cmsName], CMSTOKENS_CONSTANT);
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	if (@file_exists($_SERVER[TEMPLATES] . "/tokens.php")) REQUIRE $_SERVER[TEMPLATES] . "/tokens.php"; /* deprecated */
	if (@file_exists($_SERVER[TEMPLATES] . "/_tokens.php")) REQUIRE $_SERVER[TEMPLATES] . "/_tokens.php"; 
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
?>