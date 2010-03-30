<?
	
	if (!$_SERVER['modCache']['pages']['lifetime']) $_SERVER['modCache']['pages']['lifetime'] = 60 * 10;
	
	function modCache_file($uri) {
		
		return $_SERVER[SYSCACHE] . "/" . str_replace("/", "__", $uri) . ".html";
		
	}
	
	function modCache_delete($id) {
		
		sql::query("DELETE FROM cms_cache WHERE id = '%id%' LIMIT 1", array("id" => (int)$id));
		return sql::affected();
		
	}
	
	function modCache_check($f, $update = false) {
		
		if ($update) {
			
			if (!$f) return true;
			if (!$f['status']) return false;
			if ($f['date'] + $_SERVER['modCache']['pages']['lifetime'] < time()) return true;
			
		} else {
			
			if (!$f) return false;
			if (!$f['status']) return false;
			if ($f['date'] + $_SERVER['modCache']['pages']['lifetime'] < time()) return false;
			
			return file_exists(modCache_file($f['uri']));
			
		}
		
		return true;
		
	}
	
	// Если мы не в админке
	if ((!defined("MODAUTH_ADMIN") || !MODAUTH_ADMIN) && $_SERVER['modCache']['pages']['cache']) {
		
		
		$cache = sql::fetch("SELECT * FROM cms_cache WHERE uri = '%uri%' AND status = '1' LIMIT 1", array("uri" => $_SERVER['REQUEST_URI']));
		
		if (modCache_check($cache)) {
			
			$assets = cmsJSON_decode($cache['assets']);
			foreach ($assets as $type => $list) cmsCache::buildScript($list, $type);
			
			//print "<h1>Cached!!!</h1>";
			require(modCache_file($cache['uri']));
			
			if ($_SERVER[debug][report]) {
				
				profiler::add("Завершение", "Вывод кешированной страницы");
				
				$console = "";
				
				$console .= profiler::export();
				
				foreach (sql::$lang->logOut(		CMSSQL_REPORT_ARRAY) as $line) $console .= cmsConsole_out($line[0], "mysql", $line[1]);
				
				$console .= cmsConsole_out("Сжатие <b>отключено</b>.", "", "notice");
				$console .= cmsConsole_out("<b>Полное время выполнения: <u>" . floor(profiler::$full * 1000) . " мс</u>.</b>", "", "notice");
				$console .= cmsConsole_out("");
				
				print $console;
				
			}
			
			exit();
			
		}
		
	}

?>