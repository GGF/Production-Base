<?
	
	// Проверяем, чтобы это не был системный модуль типа error
	// А также не был установлен флаг запрета кеширования
	if (array_key_exists(page::$module, $_SERVER['modules']) && page::$cache && $_SERVER['modCache']['pages']['cache']) {
		
		$cache = sql::result("SELECT id, status, date FROM cms_cache WHERE uri='%uri%' LIMIT 1", array("uri" => $_SERVER['REQUEST_URI']));
		
		$check = modCache_check($cache, true);
		
		// Если кеша нет, или он есть и статус включен
		if ($check) {
			
			if ($cache) modCache_delete($cache['id']);
			
			$uri = $_SERVER['REQUEST_URI'];
			
			sql::insertUpdate("cms_cache", array(array(
				"uri"			=> $uri,
				"date"		=> time(),
				"status"	=> ($cache ? $cache['status'] : 1),
				"title"		=> page::$meta['title'],
				"name"		=> page::$name,
				"assets"	=> cmsJSON_encode(page::$assets),
			)), array(
				"uri"
			));
			
			cmsFile_write(modCache_file($uri), $pageContents);
			
			//$pageContents = "<h1>Updated: {$check}</h1>" . $pageContents;
			
		}
		
	}

?>