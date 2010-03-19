<? 
	
	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	tokens::add("cmsHead",						"/core/tokens/cmsHead.php",						CMSTOKENS_FILE_ABS);
	tokens::add("cmsHead.belated",		"/core/tokens/cmsHead.belated.php",		CMSTOKENS_FILE_ABS);
	tokens::add("cmsVideo",						"/core/tokens/cmsVideo.php",					CMSTOKENS_FILE_ABS);
	tokens::add("cmsFlash",						"/core/tokens/cmsFlash.php",					CMSTOKENS_FILE_ABS);
	tokens::add("cmsAdmin",						"/core/tokens/cmsAdmin.php",					CMSTOKENS_FILE_ABS);
	tokens::add("cmsPopup",						"/core/tokens/cmsPopup.php",					CMSTOKENS_FILE_ABS);
	tokens::add("cmsLayout",					"/core/tokens/cmsLayout.php",					CMSTOKENS_FILE_ABS);
	tokens::add("cmsGMap",						"/core/tokens/cmsGMap.php",						CMSTOKENS_FILE_ABS);
	tokens::add("cmsRounded",					"/core/tokens/cmsRounded.php",				CMSTOKENS_FILE_ABS);
	tokens::add("cmsDoctype",					"/core/tokens/cmsDoctype.php",				CMSTOKENS_FILE_ABS);
	
	tokens::add("template.head",			"/core/tokens/cmsHead.php",						CMSTOKENS_FILE_ABS); // DEPRECATED
	
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