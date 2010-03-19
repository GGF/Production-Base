<?
	
	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/engine.php";
	
	header("CONTENT-TYPE: TEXT/JAVASCRIPT; CHARSET={$_SERVER[cmsEncoding]}");
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function cmsMCE_getTemplate($path) {
		
		$title = $desc = $styles = "";
		$path = cmsFile_path($path);
		
		ob_start();
		REQUIRE $path;
		ob_end_clean();
		
		return array($title, $desc);
		
	}
	
	function cmsMCE_templateList($path) {
		
		$path = cmsFile_path($path);
		$tpls = array();
		
		foreach (cmsFile_list($path, false) as $f) {
			
			if (substr(basename($f), 0, 1) != "_") {
				
				list($title, $desc) = cmsMCE_getTemplate($f);
				
				$tpls[] = array(
					"title"				=> $title,
					"src"					=> $f,
					"description"	=> $desc,
				);
				
			}
			
		}
		
		return $tpls;
		
	}
	
	$templates = array();
	$templates[] = cmsMCE_templateList("/core/contrib/mce/templates");
	$templates[] = cmsMCE_templateList($_SERVER[TEMPLATES] . "/mce");
	$templates = cmsJSON_encode(array_merge($templates[0], $templates[1]));
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	// STYLES
	
	$styles = array(
		"styles"	=> array(
			"icon"				=> "Иконка в тексте",
			"imgLeft"			=> "Висит слева",
			"imgRight"		=> "Висит справа",
			"small"				=> "Маленький текст",
			"last"				=> "Абзац без отступа",
			"textBlock"		=> "Выделенный блок текста",
		),
		"table"	=> array(
			"defTable"		=> "Таблица",
			"borderless"	=> "Без границ с отступом снизу",
			"frame"				=> "Без границ и отступа",
		),
		"tableCell"	=> array(
			"nowrap"			=> "Без переносов",
			"fullWidth"		=> "Ширина 100%",
			"halfWidth"		=> "Ширина 50%",
			"autoWidth"		=> "Ширина авто",
			"fullHeight"	=> "Высота 100%",
			"autoHeight"	=> "Высота авто",
		),
		"tableRow"	=> array(
			"fullHeight"	=> "Высота 100%",
			"autoHeight"	=> "Высота авто",
		),
	);
	
	$stylesStr = array();
	$stylesAdd = require($_SERVER[TEMPLATES] . "/mce/_styles.php");
	
	foreach ($styles as $t => $s) if (is_array($stylesAdd[$t])) $styles[$t] = array_merge($styles[$t], $stylesAdd[$t]);
	
	foreach ($styles as $t => $s) {
		
		$stylesStr[$t] = array();
		foreach ($s as $k => $v) $stylesStr[$t][] = mysql_real_escape_string($v) . "=" . mysql_real_escape_string($k);
		$stylesStr[$t] = implode(";", $stylesStr[$t]);
		
	}
	
	$styles = $stylesStr;
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function cmsMCE_clean($content) {
		
		return trim(substr(trim($content), 8, -9));
		
	}
	
	ob_start("cmsMCE_clean");
	
?>