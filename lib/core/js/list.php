<?
	
	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
	$options[contrib][] = "json";
	$options[contrib][] = "md5";
	$options[contrib][] = "swfobject";
	
	if (!$options[admin]) $options[jquery][] = "png";
	$options[jquery][] = "maskedinput";
	
	$list = array(
		"/lib/core/js/browserdetect.js",
		"/lib/core/contrib/jquery/jquery.js",
	);
	
	// jQuery and contributions
	foreach ($options[contrib] as $v)	$list[] = "/lib/core/contrib/{$v}/{$v}.js";
	foreach ($options[jquery] as $v)	$list[] = "/lib/core/contrib/jquery/jquery." . trim($v) . ".js";
	
	// CMS
	$list[] = "/lib/core/js/autoexec.js";
	
	//$list[] = "/lib/core/classes/form/form.js";
	$list[] = "/lib/core/classes/form_ajax/form_ajax.js";
	
	$list[] = "/lib/core/js/ajax.js";
	$list[] = "/lib/core/js/alert.js";
	$list[] = "/lib/core/js/png.js";
	$list[] = "/lib/core/js/pos.js";
	$list[] = "/lib/core/js/print.js";
	$list[] = "/lib/core/js/calendar.js";

	$list[] = "/lib/core/js/console" . ($_SERVER[debug][report] ? "" : "Gag") . ".js";
	
	if ($options[admin]) {
		
		$list[] = "/lib/core/js/admin.js";
		
		$list[] = "/lib/core/js/calendar.js";
		$list[] = "/lib/core/js/save.js";
		$list[] = "/lib/core/js/node.js";
		$list[] = "/lib/core/js/position.js";
		$list[] = "/lib/core/js/status.js";
		$list[] = "/lib/core/js/tabs.js";
		$list[] = "/lib/core/js/tooltip.js";
		
	} else {
		
		if ($_SERVER[debug][report]) $list[] = "/lib/core/js/tabs.js";
		
	}
	
	// contrib autoexec
							foreach($_SERVER[contrib] as $mod => $name) $list[] = "/lib/core/contrib/" . $mod . "/".$mod.".js";
	//if ($options[admin])	foreach($_SERVER[modules] as $mod => $name) $list[] = "/modules/" . $mod . "/includes/scripts.js";
	// Modules autoexec
							foreach($_SERVER[modules] as $mod => $name) $list[] = "/lib/modules/" . $mod . "/includes/autoexec.js";
	if ($options[admin])	foreach($_SERVER[modules] as $mod => $name) $list[] = "/lib/modules/" . $mod . "/includes/scripts.js";
	
	// Exclude
	if (is_array($options[exjs]) && count($options[exjs])) foreach ($options[exjs] as $f) {
		
		if (($key = array_search($f, $list)) !== false) unset($list[$key]);
		
	}
	
	// Misc
	foreach ($options[js] as $v) $list[] = $v;
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	$list = array_unique($list);
	
	foreach ($list as $k => $v) if (empty($v) || !is_file(cmsFile_path($v))) unset($list[$k]);
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	$return = array();
	
	if ($_SERVER[debug][noCache][js]) {
		
		foreach ($list as $l) $return[] = "<script type='text/javascript' src='{$l}'></script>\n" . $options[pad];
		
	} else {
		
		$return[] = "<script type='text/javascript' src='" . cmsCache::buildScript($list, "js", $options) . "'></script>\n" . $options[pad];
		
	}
	
	return implode("", $return);
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
?>