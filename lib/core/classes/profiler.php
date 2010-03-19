<?

defined("CMS") or die("Restricted usage: " . basename(__FILE__));

class PROFILER {
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	static $default = "Exec";
	static $time = array();
	static $memory = array();
	static $previous = 0;
	static $i = 0;
	static $full = 0;
	
	static function formatMemory($mem) {
		
		return round($mem / 1024 / 1024, 2);
		
	}
	
	static function add($type, $name = "") {
		
		if (!$name) {
			$name = $type;
			$type = self::$default;
		}
		
		$time = microtime(true);
		$last = $time - self::$previous;
		
		self::$i++;
		self::$full += $last;
		self::$time[$type][self::$i . ". {$name}"] = $last;
		self::$memory[self::$i . ". {$name}"] = self::formatMemory(memory_get_usage()) . " <small>(" . self::formatMemory(memory_get_usage(true)) . ")</small>";
		self::$previous = $time;
		
	}
	
	static function export() {
		
		$html = "<table class='frame cmsConsole_table'><thead><tr><th nowrap>Действие</th><th nowrap>Время, мс</th><th nowrap>Память, МБ</th><th width='100%'>&nbsp;</th></tr></thead><tbody>\n";
		
		foreach (self::$time as $type => $times) {
			
			//asort(self::$time[$type]);
			
			$html .= "	<tr><th colspan='4'>{$type}</th></tr>\n";
			
			foreach (self::$time[$type] as $name => $time) {
				
				$mem = self::$memory[$name];
				
				$html .= "	<tr><td nowrap>{$name}</td><td nowrap>" . round($time * 1000, 2) . "</td><td nowrap>{$mem}</td><td><div class='progress' style='width: " . (round($time * 1000 * 3) + 1) . "px'><div style='width: 100%'></div></div></td></tr>\n";
				
			}
			
			$html .= "	<tr><th nowrap>Время выполнения {$type}</th><th nowrap>" . round(array_sum(self::$time[$type]) * 1000, 2) . "</th><th colspan='2'>&nbsp;</th></tr>\n";
			
		}
		
		$html .= "	<tr><th colspan='4'>{$type}</th></tr>\n";
		$html .= "<tr><th nowrap><big>Полное время выполнения</big></th><th nowrap><big>" . round(self::$full * 1000, 2) . "</big></th><th nowrap><big>" . self::formatMemory(memory_get_peak_usage()) . "</big> <small>(" . self::formatMemory(memory_get_peak_usage(true)) . ")</small></th><th>&nbsp;</th></tr></tbody></table>";
		
		return cmsConsole_plain($html, "time");
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
}

profiler::$previous = microtime(true);

?>