<?
/*
 * Этот скрипт выдает список тегов подключающих к странице css файлы
 */
defined ( "CMS" ) or die ( "Restricted usage: " . basename ( __FILE__ ) );

// так как скрипт вставляется инклудом щзешщт определена, но во избежание предупреждений проверим и присвоим. Пусть не вы;лнится, но предупреждение будет только одно
if (! isset ( $options ))
	$options = array ();
	
$list = array ();

// jQuery and contributions
foreach ( $options [jquery] as $v )
	$list [] = "/lib/core/contrib/jquery/jquery." . trim ( $v ) . ".css";
foreach ( $options [contrib] as $v )
	$list [] = "/lib/core/contrib/{$v}/{$v}.css";
	
// CMS
$list [] = "/lib/core/css/style.css";
$list [] = "/lib/core/css/style_common.css";
if (! $_SERVER [project] [doctype] || $options [admin])
	$list [] = "/lib/core/css/common.css";
$list [] = "/lib/core/css/alert.css";
$list [] = "/lib/core/css/form.css";
if (! $_SERVER [project] [doctype] || $options [admin])
	$list [] = "/lib/core/css/form_style.css";
if ($_SERVER [project] [doctype] && ! $options [admin])
	$list [] = "/lib/core/css/form_standard.css";
$list [] = "/lib/core/css/node.css";
$list [] = "/lib/core/css/tables.css";
$list [] = "/lib/core/css/var.css";
$list [] = "/lib/core/css/layout.css";
$list [] = "/lib/core/css/calendar.css";
$list [] = "/lib/core/css/rounded.css";
$list [] = "/lib/core/css/mce.css";

if ($_SERVER [debug] [report])
	$list [] = "/lib/core/contrib/console/css/console.css";
if ($_SERVER [debug] [report])
	$list [] = "/lib/core/contrib/console/css/mysql.css";

if ($options [admin]) {
	
	$list [] = "/lib/core/css/calendar.css";
	$list [] = "/lib/core/css/form_admin.css";
	$list [] = "/lib/core/css/tabs.css";
	$list [] = "/lib/core/css/admin.css";

} else {
	
	$list [] = "/lib/core/css/node_map.css";
	
	if ($_SERVER [debug] [report])
		$list [] = "/lib/core/contrib/tabs/css/tabs.css";

}

// Modules autoexec
foreach ( $_SERVER [modules] as $mod => $name )
	$list [] = "/lib/modules/" . $mod . "/includes/style.css";
if ($options [admin])
	foreach ( $_SERVER [modules] as $mod => $name )
		$list [] = "/lib/modules/" . $mod . "/includes/style_admin.css";
	
// Contrib autoexec
foreach ( $_SERVER [contrib] as $mod => $name )
	$list [] = "/lib/core/contrib/" . $mod . "/css/" . $mod . ".css";


// Main CSS file
if (! $options [admin])
	$list [] = cmsFile_pathRel ( $_SERVER [TEMPLATES] ) . "/default.css";
if (! $options [admin])
	$list [] = cmsFile_pathRel ( $_SERVER [TEMPLATES] ) . "/style.css";
	
// Exclude
if (is_array ( $options [excss] ) && count ( $options [excss] ))
	foreach ( $options [excss] as $f ) {
		
		if (($key = array_search ( $f, $list )) !== false)
			unset ( $list [$key] );
	
	}
	
// Misc
foreach ( $options [css] as $v )
	$list [] = $v;
	
// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //


$list = array_unique ( $list );

foreach ( $list as $k => $v )
	if (empty ( $v ) || ! is_file ( cmsFile_path ( $v ) ))
		unset ( $list [$k] );
	
// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //


$return = array ();

if (! $options [raw])
	$return [] = "<style type='text/css' media='all'>\n" . $options [pad];

if ($_SERVER [debug] [noCache] [css]) {
	
	foreach ( $list as $l )
		$return [] = "	@import url({$l});\n" . $options [pad];

} else {
	
	$return [] = "	@import url(" . cmsCache::buildScript ( $list, "css", $options ) . ");\n" . $options [pad];

}

if (! $options [raw])
	$return [] = "</style>\n" . $options [pad];

return implode ( "", $return );

?>