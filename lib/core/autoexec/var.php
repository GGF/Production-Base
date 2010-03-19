<?
	
	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	define("CMSVAR_RETURN",	true);
	define("CMSVAR_PRINT",	false);
	
	/**
	 *	DEPRECATED!!! Функция выводит или возвращает внутренности переменной
	 *	@param	mixed		$var			Переменная
	 *	@param	string	$varName	Что вывести вместо стандартного «variable»
	 *	@param	bool		$return		Возвращать ли результат
	 *	@return	void|string
	 */
	function cmsVar_dump($var, $varName = "variable", $return = CMSVAR_PRINT) {
		
		$export = stripslashes(var_export($var, true));
		$export = "<span class='key'>{$varName}</span> =&gt; " . htmlSpecialChars(str_replace("\r", "", $export)) . ",\n"; //
		
		// array nl's
		$export = preg_replace('/=&gt;[\s]+([\w]+) \(/si', '=&gt; <span class=\'$1\'>$1</span> (', $export);
		
		// keys (string, numeric)
		$export = preg_replace('/(\'[\w]+\' =&gt;)/si', '<span class=\'key\'>$1</span>', $export);
		$export = preg_replace('/([\d]+ =&gt;)/si', '<span class=\'key\'>$1</span>', $export);
		
		// strings, nulls, bool, int, float
		$export = preg_replace('%(=&gt;</span>) \'(.*?)\',\r?\n%si', "\$1 <span class='string'>\"\$2\"</span>,\r\n", $export);
		$export = preg_replace('/(NULL),\r?\n/si', "<span class='null'>\$1</span>,\r\n", $export);
		$export = preg_replace('/(true|false),\r?\n/si', "<span class='boolean'>\$1</span>,\r\n", $export);
		$export = preg_replace('%(=&gt;</span>) ([\d]+),\r?\n%si', "\$1 <span class='integer'>\$2</span>,\r\n", $export);
		$export = preg_replace('%(=&gt;</span>) ([\d.,]+),\r?\n%si', "\$1 <span class='float'>\$2</span>,\r\n", $export);
		
		// class
		$export = preg_replace('%(=&gt;</span>)[\s]+([\w]+)::__set_state\(array\(%si', '$1 <span class=\'object\'>Class $2</span> (', $export);
		$export = str_replace(")),\n", "),\n", $export);
		
		$export = "\n<pre class='cmsVar'>" . trim($export) . "</pre>\n";
		
		if ($return) return $export; else print $export;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function cvar(  $var, $varName = 'variable', $return = CMSVAR_PRINT) { return cmsVar_export($var, $varName, $return); }
	function cmsVar($var, $varName = 'variable', $return = CMSVAR_PRINT) { return cmsVar_export($var, $varName, $return); }
	
	/**
	 *	Функция для разбора конкретной переменной, вызывается из-под cmsVar_export
	 *	@param	mixed		$var		Переменная
	 *	@param	int			$level	Глубина рекурсии
	 *	@return	string
	 */
	function cmsVar_parse($var, $level = 0) {
		
		if ($level > 20) return "<span class='unknown'>Too much recursion: " . ($level) . "</span>";
		
		$pad = "\t";
		
		if			(is_bool($var))			return "<span class='boolean'>" . ($var === true ? "true" : "false") . "</span>";
		elseif	(is_integer($var))	return "<span class='integer'>" . strval($var) . "</span>";
		elseif	(is_float($var))		return "<span class='float'>" . strval($var) . "</span>";
		elseif	(is_null($var))			return "<span class='null'>null</span>";
		elseif	(is_resource($var))	return "<span class='resource'>" . strval($var) . " of type " . get_resource_type($var) . "</span>";
		elseif	(is_object($var) || is_array($var)) {
			
			$r = ((is_array($var)) ? "<span class='array'>Array(" . count($var) . ")" : "<span class='object'>Object of class " . get_class($var)) . "</span> (\n";
			
			foreach ($var as $k => $v) $r .= str_repeat($pad, $level + 1) . "<span class='key'>{$k}</span> = " . cmsVar_parse($v, $level + 1) . "\n";
			
			$r .= str_repeat($pad, $level) . ")";
			
			return $r;
			
		} elseif (is_string($var)) {
			
			$var = htmlspecialchars($var);
			$var = str_replace(" ", "<span class='system system-s'> </span>", $var);
			$var = str_replace(" ", "<span class='system system-s'> </span>", $var);
			$var = str_replace("\r", "<span class='system system-r'></span>", $var);
			$var = str_replace("\t", "<span class='system system-t'>\t</span>", $var);
			$var = str_replace("\n", "<span class='system system-n'></span>\n", $var);// . str_repeat($pad, $level + 1)
			
			return "<span class='string'>\"{$var}\"</span>";
			
		} else return "<span class='unknown'>Unknown type — strval() returned \"" . strval($var) . "\"</span>";
		
	}
	
	/**
	 *	Функция выводит или возвращает внутренности переменной
	 *	@param	mixed		$var			Переменная
	 *	@param	string	$varName	Что вывести вместо стандартного «variable»
	 *	@param	bool		$return		Возвращать ли результат
	 *	@return	void|string
	 */
	function cmsVar_export($var, $varName = 'variable', $return = CMSVAR_PRINT) {
		
		$export = "<pre class='cmsVar'><span class='key'><b>{$varName}</b></span> = " . cmsVar_parse($var) . "</pre>";
		
		if ($return) return $export; else print $export;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
?>