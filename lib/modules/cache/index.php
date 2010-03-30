<?

	REQUIRE $_SERVER['DOCUMENT_ROOT'] . "/engine.php";
	
	$refPage = page_alias("/blank/");
	
	if ($_REQUEST[id]) {
		
		$f = modBlank_getObject((int)$_REQUEST['id'], array(
			"status"	=> 1,
		));
		
		if (!$f) cmsDie(); // 404
		
		$pathHTML = $_SERVER['delim'] . $f['name'];
		
	} else {
		
		$pathHTML = "";
		$f = array();
		
	}
	
	/*------------------------------------------------------------------------------------------------*/
	/*--	T E M P L A T E   I N I T																																	--*/
	/*------------------------------------------------------------------------------------------------*/
	/**/	
	/**/	page::$module				= "blank";
	/**/	page::$id						= $refPage['id'];
	/**/	page::$uid					= $refPage['uid'];
	/**/	page::$comments			= false;
	/**/	page::$parent				= $refPage['parent'];
	/**/	page::$path					= page_path($refPage['id']);
	/**/	page::$pathHTML			= page_pathHTML($refPage['id']) . $pathHTML;
	/**/	page::$name					= $f['name'];
	/**/	
	/**/	page::$meta['title']	= $refPage['meta_title'];
	/**/	page::$meta['desc']		= $refPage['meta_desc'];
	/**/	page::$meta['keys']		= $refPage['meta_keys'];
	/**/	
	/**/	page::$modVars = array(
	/**/		"id"		=> $f['id'],
	/**/		"name"	=> $f['name'],
	/**/	);
	/**/	
	/**/	REQUIRE $_SERVER['DOCUMENT_ROOT'] . "/template.php";
	/**/	
	/*------------------------------------------------------------------------------------------------*/
	
	if (!count($f)) {
		
		$tpl = array(
			"items"	=> modBlank_getObjects(array("status"	=> true)),
		);
		
		cmsTemplate_print("/modules/blank/list.php", $tpl);
		
	} else {
		
		cmsTemplate_print("/modules/blank/object.php", $f);
		
	}
	
	/*------------------------------------------------------------------------------------------------*/
	/**/	
	/**/	REQUIRE $_SERVER['DOCUMENT_ROOT'] . "/template.php";
	/**/	
	/*------------------------------------------------------------------------------------------------*/
	
?>