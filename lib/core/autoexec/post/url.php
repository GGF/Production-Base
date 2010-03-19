<?
	
	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
	// переменная $pageContents
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	if ($_SERVER[project][oldAddr]) {
		
		$pageContents = cmsReplace(array(
			'/href=(\'|")([^\'"]+)(\'|")/'		=> 'href=$1/site/' . $_SERVER[lang] . '$2$3',
			'/action=(\'|")([^\'"]+)(\'|")/'	=> 'action=$1/site/' . $_SERVER[lang] . '$2$3',
		), $pageContents);
		
		$pageContents = cmsReplace(array(
			"/site/" . $_SERVER[lang] . "/favicon"		=> "/favicon",
			"/site/" . $_SERVER[lang] . "javascript"	=> "javascript",
			"/site/" . $_SERVER[lang] . "newWindow"	=> "newWindow",
			"/site/" . $_SERVER[lang] . "http"				=> "http",
			"/site/" . $_SERVER[lang] . "mailto"			=> "mailto",
			"/site/" . $_SERVER[lang] . "/files"			=> "/files",
			"/site/" . $_SERVER[lang] . "/pages"			=> "/pages",
			"/site/" . $_SERVER[lang] . "pages"			=> "pages",
			"/site/" . $_SERVER[lang] . "files"			=> "files",
			"/site/" . $_SERVER[lang] . "/site"			=> "/site",
			"/site/" . $_SERVER[lang] . "/admin"			=> "/admin",
		), $pageContents, true);
		
	}
	
	$pageContents = preg_replace('/(["\'])newWindow:(.*?)(["\'])/i', "\"javascript: newWindow('$2')\"", $pageContents);
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
?>