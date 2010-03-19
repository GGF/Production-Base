<? 
	
	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	$_SERVER[templateDIR]	= $_SERVER[TEMPLATES];
	$_SERVER[templatePath] = $_SERVER[TEMPLATES];
	$_SERVER[templateURL]	= "/templates";
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	if ($_SERVER[debug][checkReverse]) {
		
		function cmsOut_referer()																									{ return cmsReferer(); }
		function cmsReferrer()																										{ return cmsReferer(); }
		
		function cmsOut_redir($url, $save=false)																	{ return cmsRedirect($url, $save); }
		function cmsOut_redirect($url, $save=false)																{ return cmsRedirect_admin($url, $save); }
		
		function cmsOut_status($f, $table)																				{ return cmsStatus($f, $table); }
		function cmsOut_statusAll()																								{ return cmsStatus_all(); }
		
		function cmsOut_mainMenu($link, $text, $icon, $target='_top', $show=true)	{ return cmsMenu($link, $text, $icon, $target, $show); }
		function cmsOut_mainMenuHR ()																							{ return cmsMenu_hr(); }
		
		function cmsOut_sendValue($k, $v, $type='value')													{ return cmsSendValue($k, $v, $type); }
		
		function cmsOut_caption($icon = 'default')																{ return cmsCaption($icon); }
		
		function cmsOut_infoTable($data, $flag=true, $stretch=false)							{ return cmsTable($data, $flag, $stretch); }
		
		function cmsOut_content($tabs, $width='100%')															{ return cmsContent($tabs, $width); }
		function cmsOut_contentEnd()																							{ return cmsContent_end(); }
		
		function cmsOut_print($text="&nbsp;", $class="")													{ return cmsPrint($text, $class); }
		function cmsOut_printActions($array)																			{ return cmsPrint_actions($array); }
		function cmsOut_printBegin()																							{ return cmsPrint_init(); }
		
		function cmsOut_checkID($oldid, $newid, $table, $change=true)							{ return cmsID_check($oldid, $newid, $table, $change); }
		
		function cmsOut_error($text)																							{ return cmsError($text); }
		function cmsOut_notice($text)																							{ return cmsNotice($text); }
		function cmsOut_warning($text)																						{ return cmsWarning($text); }
		
		function cmsOut_getURL()																									{ return cmsURL_get(); }
		function cmsOut_uid($table='cms_content', $flag=true)											{ return cmsUID($table, $flag); }
		
		function cmsOut_pageHeader($pageName = 'Главная')													{ return cmsHeader($pageName); }
		function cmsOut_pageFooter() 																							{ return cmsFooter(); }
		
		function cmsOut_checkPosParent($table, $parent)														{ return cmsPosition_checkParent($table, $parent); }
		function cmsOut_checkPosID($table, $id)																		{ return cmsPosition_checkID($table, $id); }
		function cmsOut_changePos($table, $id, $where='up')												{ return cmsPosition($table, $id, $where); }
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//////
	
?>