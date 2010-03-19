<?
	
	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
	$list = array();
	
	// jQuery and contributions
	foreach ($options[jquery] as $v) $list[] = "/core/contrib/jquery/jquery." . trim($v) . ".css";
	foreach ($options[contrib] as $v) $list[] = "/core/contrib/{$v}/{$v}.css";
	
	// CMS
	$list[] = "/core/css/style.css";
	$list[] = "/core/css/style_common.css";
	if (!$_SERVER[project][doctype] || $options[admin])	$list[] = "/core/css/common.css";
	$list[] = "/core/css/alert.css";
	$list[] = "/core/css/form.css";
	if (!$_SERVER[project][doctype] || $options[admin])	$list[] = "/core/css/form_style.css";
	if ($_SERVER[project][doctype] && !$options[admin])	$list[] = "/core/css/form_standard.css";
	$list[] = "/core/css/node.css";
	$list[] = "/core/css/tables.css";
	$list[] = "/core/css/var.css";
	$list[] = "/core/css/layout.css";
	$list[] = "/core/css/calendar.css";
	$list[] = "/core/css/rounded.css";
	$list[] = "/core/css/mce.css";
	
	if ($_SERVER[debug][report]) $list[] = "/core/css/console.css";
	if ($_SERVER[debug][report]) $list[] = "/core/css/mysql.css";
	
	if ($options[admin]) {
		
		$list[] = "/core/css/calendar.css";
		$list[] = "/core/css/form_admin.css";
		$list[] = "/core/css/tabs.css";
		$list[] = "/core/css/admin.css";
		
	} else {
		
		$list[] = "/core/css/node_map.css";
		
		if ($_SERVER[debug][report]) $list[] = "/core/css/tabs.css";
		
	}
	
	// Modules autoexec
												foreach($_SERVER[modules] as $mod => $name) $list[] = "/modules/" . $mod . "/includes/style.css";
	if ($options[admin])	foreach($_SERVER[modules] as $mod => $name) $list[] = "/modules/" . $mod . "/includes/style_admin.css";
	
	// Main CSS file
	if (!$options[admin]) $list[] = cmsFile_pathRel($_SERVER[TEMPLATES]) . "/default.css";
	if (!$options[admin]) $list[] = cmsFile_pathRel($_SERVER[TEMPLATES]) . "/style.css";
	
	// Exclude
	if (is_array($options[excss]) && count($options[excss])) foreach ($options[excss] as $f) {
		
		if (($key = array_search($f, $list)) !== false) unset($list[$key]);
		
	}
	
	// Misc
	foreach ($options[css] as $v) $list[] = $v;
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	$list = array_unique($list);
	
	foreach ($list as $k => $v) if (empty($v) || !is_file(cmsFile_path($v))) unset($list[$k]);
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	$return = array();
	
	if (!$options[raw]) $return[] = "<style type='text/css' media='all'>\n" . $options[pad];
	
	if ($_SERVER[debug][noCache][css]) {
		
		foreach ($list as $l) $return[] = "	@import url({$l});\n" . $options[pad];
		
	} else {
		
		$return[] = "	@import url(" . cmsCache::buildScript($list, "css", $options) . ");\n" . $options[pad];
		
	}
	
	if (!$options[raw]) $return[] = "</style>\n" . $options[pad];
	
	return implode("", $return);
	
?>