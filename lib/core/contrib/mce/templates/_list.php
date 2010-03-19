<?
	
	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/engine.php";
	
	$array = "";
	
	$list = cmsFile_list(cmsFile_current(), false);
	
	foreach ($list as $f) {
		
		if (basename($f) != "_list.php" && basename($f) != "_blank.php" && substr($f, 0, -3) == "php") {
			
			ob_start();
			REQUIRE $_SERVER[DOCUMENT_ROOT] . $f;
			ob_end_clean();
			
			$array[] = array("Cтандартный: {$title}", $f, $desc);
			
		}
		
	}
	
	$list = cmsFile_list($_SERVER[TEMPLATES] . "/mce", false);
	
	foreach ($list as $f) {
		
		if (substr($f, 0, -3) == "php") {
			
			ob_start();
			REQUIRE $_SERVER[DOCUMENT_ROOT] . $f;
			$content = ob_get_contents();
			ob_end_clean();
			
			$array[] = array("Проектный: {$title}", $f, $desc);
		}
		
	}
	
	print "var tinyMCETemplateList = " . cmsJSON_encode($array) . ";";
	
?>