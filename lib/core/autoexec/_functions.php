<? 
/*
 * Функции общего назначения CMS (c) Osmio
 */	
	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function cmsShell($cmd) {
		
		if (is_array($cmd)) $cmd = implode(" ", $cmd);
		
		$var = @exec($cmd);
		
		if (strlen($var) > 0) {
			
			return $var;
			
		} else return false;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function import($file) {
		
		if (is_file($file)) return require($file); else return false;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function cmsCall($name = null, $args = array()) {
		
		if ($name && is_callable($name)) return call_user_func_array($name, $args); else return null;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	/**
	 * Функция возвращает строку для получения скриптов или стилей
	 * 
	 * @return string
	 */
	
	function cmsHeader_get($type, $options = array()) {
		
		$options[jquery]		= $options[jquery]	? $options[jquery]	: array();
		$options[contrib]		= $options[contrib]	? $options[contrib]	: array();
		$options[js]				= $options[js]			? $options[js]			: array();
		$options[css]				= $options[css]			? $options[css]			: array();
		$options[excss]			= $options[excss]		? $options[excss]		: array();
		$options[exjs]			= $options[exjs]		? $options[exjs]		: array();
		
		$allowed = array("js", "css");
		if (!@in_array($type, $allowed)) $type = "js";
		
		return require($_SERVER[CORE] . "/{$type}/list.php");
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	/**
	 * Функция возвращает MD5 хэш от текущего времени в микросекундах.
	 * Используется для создания кода подтверждения в e-mail.
	 * 
	 * @return string
	 */
	
	function cmsKey() {
		
		return md5(cmsTime());
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	/**
	 * Функция возвращает текущее временя с микросекундами.
	 * 
	 * @return float
	 */
	
	function cmsTime() {
		
		return array_sum(explode(" ", microtime()));
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	/**
	 * Функция возвращает текущее временя в микросекундах.
	 * 
	 * @return float
	 */
	
	function cmsTime_format($time) {
		
		return number_format($time * 1000, 0, ".", " ") . " мс";
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	/**
	 * Возвращает флаг, является ли массив списком или ассоциативным
	 * 
	 * @return bool
	 */
	
	function is_list($array) {
		
		$isList = true;
		for ($i = 0, @reset($a), $m = count($a); $i < $m; $i++, @next($a)) {
			if (key($a) !== $i) { 
				$isList = false; 
				break; 
			}
		}
		
		return $isList;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	/**
	 * Возвращает текущий URL, отбрасывая QUERY_STRING
	 * 
	 * @return string
	 */
	
	function cmsURL_get() {
		
		$url = explode("?", $_SERVER[REQUEST_URI]);
		
		return $url[0];
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	/**
	 * Производит редирект на указанную страницу
	 * 
	 * @param string $url URL на который необходимо сделать редирект
	 * @param bool $save Надо ли сохранить в $_SESSION[redirect] текущий адрес
	 */
	
	function cmsRedirect($url, $save = false) {
		
		$url = preg_replace('/http:\/\/(.*?)\//', '', $url);
		$url = preg_replace('/\/?site\/(.*?)\//', '', $url);
		$url = preg_replace('/^\//', '', $url);
		
		$url = "http://{$_SERVER[HTTP_HOST]}/{$url}";
		
		if ($save) $_SESSION[redirect] = $_SERVER[REQUEST_URI];
		/*
		if (!headers_sent()) {
			
			header("HTTP/1.0 302 FOUND");
			header("Location: {$url}");
			
		} else {
		*/
			
			/*
			print "<script>";
			print "	document.title = 'Сейчас Вы будете перемещены';\n";
			print "</script>";
			
			print "<div style='position: absolute; top: 0px; left: 0px; width: 100%; height: 100%; z-index: 100'>";
			print "<table class='frame' bgcolor='#F6F6F6'><tr><td class='center' align='center'>";
			print "<div style='width: 400px; background: white'>";
			
			print cmsNotice("
				<h1 style='margin-top: 4px'>ПЕРЕАДРЕСАЦИЯ</h1>
				<h2>Сейчас Вы будете перемещены</h2>
				<p>Адрес: <a href='{$url}' id='redirectLink'><u>{$url}</u></a></p>
				<p>Щелкните по ссылке выше, если Ваш браузер<br>не поддерживает автоматической переадресации</p>
				<p><a href='javascript: history.back()'>Вернуться назад</a> | <a href='http://" . $_SERVER[HTTP_HOST] . "'>На главную страницу</a></p>
			");
			
			print "</div></td></tr></table></div>";
			
			print "<script>";
			print "	setTimeout(function() { window.open('{$url}'); }, 500);\n";
			print "</script>";
			*/
			
			echo "<script>window.location = '{$url}'</script>";
			exit;
			
			//cmsError("Невозможно осуществить переход на «{$url}» — заголовки уже отправлены.");
			/*
		}
		*/
		exit;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//////
	
	function cmsReferer($add = "") {
		
		if ($add) {
			
			$add = (mb_strpos($_SERVER[HTTP_REFERER], "?") ? "&" : "?") . $add;
			
		}
		
		return $_SERVER[HTTP_REFERER] . $add;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//////
	
	function checkString($subject, $type = 'quotes', $maxLength = 0) {
		
		if (@is_array($subject)) {
			
			foreach ($subject as $k=>$v) $subject[$k] = checkString($v, $type, $maxLength);
			
		} else {
			
			$subject = trim($subject);
			if ($maxLength) $subject = mb_substr($subject, 0, $maxLength);
			
			//if ($type=='quotes') $subject = str_replace("'", "\'", $subject);
			
			if ($type == 'digits') $subject = preg_replace('/[^\\d]+/', '', $subject);
			if ($type == 'numeric') $subject = preg_replace('/[^\\d]+/', '', $subject);
			if ($type == 'alpha')  $subject = preg_replace('/[^a-zA-Z]+/', '', $subject);
			if ($type == 'al-num') $subject = preg_replace('/[^\\w]+/', '', $subject);
			if ($type == 'mail')   $subject = mb_substr(preg_replace('/[^-@_.\\w]+/', '', $subject), 0, 100);
			if ($type == 'phone')  $subject = mb_substr(preg_replace('/[^-,.()\\w\\s]+/', '', $subject), 0, 100);
			if ($type == 'url')    $subject = mb_substr(preg_replace('/[^-_:\/\/.:\\w]+/', '', $subject), 0, 100);
			if ($type == 'file')   $subject = mb_strtolower(mb_substr(preg_replace('/[^-_.\\w]/', '', str_replace(" ", "_", $subject)), 0, 100));
			if ($type == 'id')     $subject = mb_substr(preg_replace('/[^-_a-zA-Z0-9]+/', '', str_replace(" ", "_", $subject)), 0, 32);
			
			if ($type=='file' && $subject == '') $subject = uniqueID();
			
		}
		
		return $subject;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//////

	function uniqueID($short=false) {
		
		$time = number_format(array_sum(explode(" ", microtime())), 3, "", "") - 1000000000000;
		
		$value = mb_strtoupper(base_convert($time,10,26));
		
		for ($i = 0; $i < mb_strlen($value); $i++) $array[] = mb_substr($value, $i, 1);
			
		foreach ($array as $letter) {
			
			$n = ord($letter);
			
			if ($n > 47 && $n < 58) $x = $n + 17;
			if ($n > 64 && $n < 91) $x = $n + 10;
			
			$letters[] = chr($x);
			
		}
		
		$value = mb_strtolower(implode("", $letters));
		
		if ($short) $value = mb_strtoupper(mb_substr($value, -5));
		
		return $value;
		
	}
	
	/* deprecated */
	
	function cmsUID($table='cms_content', $flag=true) {
		
		$next = sql::nextId($table);
		$id = ($flag) ? "id{$next}" : $next;
		
		if ($table == 'cms_content' || $table == 'cms_news') {
			
			if (sql::result("SELECT COUNT(id) FROM {$table} WHERE id='{$id}'")) $id = uniqueID();
			
		}
		
		return $id;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//////
	
?>