<?
	
	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
	define("CMSCONSOLE_DEFAULT",	"");
	define("CMSCONSOLE_ERROR",		"error");
	define("CMSCONSOLE_WARNING",	"warning");
	define("CMSCONSOLE_NOTICE",		"notice");
	define("CMSCONSOLE_PLAIN",		"plain");
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function cmsConsole(				$msg, $pane = "", $print = false)	{	return cmsConsole_out($msg, $pane, "",				$print); }
	function cmsConsole_error(	$msg, $pane = "", $print = false)	{	return cmsConsole_out($msg, $pane, "error",		$print); }
	function cmsConsole_warning($msg, $pane = "", $print = false)	{	return cmsConsole_out($msg, $pane, "warning",	$print); }
	function cmsConsole_notice(	$msg, $pane = "", $print = false)	{	return cmsConsole_out($msg, $pane, "notice",	$print); }
	function cmsConsole_plain(	$msg, $pane = "", $print = false)	{	return cmsConsole_out($msg, $pane, "plain",		$print); }
	
	function cmsConsole_out($msg, $pane = "", $type = "", $print = false) {
		
		if ($type) $type = "_{$type}";
		
		if (!$msg) $msg = "&nbsp;";
		
		$html = "<script> cmsConsole{$type}('" . sql::check($msg) . "', '{$pane}'); </script>\n";
		
		if ($print) print $html; else return $html;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
?>