<?
	
	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function cmsCache_get($var) {
		
		$file = $_SERVER[CACHE] . "/{$var}.php";
		
		if (cmsFile_touch($file)) {
			
			return require($file);
			
		} else return false;
		
	}
	
	function cmsCache_write(&$var, $file) { // Возможно ампресанд надо вернуть для $var
		
		$var[cached] = true;
		$var[date] = time();
		
		$export = "<?\n\n// Cached\nreturn " . var_export($var, true) . ";\n\n?>";
		
		//file_put_contents($_SERVER[CACHE] . "/{$file}.php", $export);
		
		$fp = fopen($_SERVER[CACHE] . "/{$file}.php", "w+");
		
		if (flock($fp, LOCK_EX)) { // do an exclusive lock
			
			ftruncate($fp, 0);  // truncate file
			fwrite($fp, $export);
			flock($fp, LOCK_UN); // release the lock
			
		} else {
			
			trigger_error("Система не может залочить файл «{$_SERVER[CACHE]}/{$file}.php»", E_USER_WARNING);
			
		}
		
		fclose($fp);
		
	}
	
	function cmsCache_delete($file) {
		
		@unlink($_SERVER[CACHE] . "/{$file}.php");
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//

?>