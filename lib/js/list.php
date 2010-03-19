<?
	
	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
	$options[contrib][] = "json";
	$options[contrib][] = "md5";
	$options[contrib][] = "swfobject";
	
	if (!$options[admin]) $options[jquery][] = "png";
	$options[jquery][] = "maskedinput";
	
	$list = array(
		"/core/js/browserdetect.js",
		"/core/contrib/jquery/jquery.js",
	);
	
	// jQuery and contributions
	foreach ($options[contrib] as $v)	$list[] = "/core/contrib/{$v}/{$v}.js";
	foreach ($options[jquery] as $v)	$list[] = "/core/contrib/jquery/jquery." . trim($v) . ".js";
	
	// CMS
	$list[] = "/core/js/autoexec.js";
	
	$list[] = "/core/classes/form/form.js";
	$list[] = "/core/classes/form_ajax/form_ajax.js";
	
	$list[] = "/core/js/ajax.js";
	$list[] = "/core/js/alert.js";
	$list[] = "/core/js/png.js";
	$list[] = "/core/js/pos.js";
	$list[] = "/core/js/print.js";
	$list[] = "/core/js/calendar.js";

	$list[] = "/core/js/console" . ($_SERVER[debug][report] ? "" : "Gag") . ".js";
	
	if ($options[admin]) {
		
		$list[] = "/core/js/admin.js";
		
		$list[] = "/core/js/calendar.js";
		$list[] = "/core/js/save.js";
		$list[] = "/core/js/node.js";
		$list[] = "/core/js/position.js";
		$list[] = "/core/js/status.js";
		$list[] = "/core/js/tabs.js";
		$list[] = "/core/js/tooltip.js";
		
	} else {
		
		if ($_SERVER[debug][report]) $list[] = "/core/js/tabs.js";
		
	}
	
	// Modules autoexec
												foreach($_SERVER[modules] as $mod => $name) $list[] = "/modules/" . $mod . "/includes/autoexec.js";
	if ($options[admin])	foreach($_SERVER[modules] as $mod => $name) $list[] = "/modules/" . $mod . "/includes/scripts.js";
	
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