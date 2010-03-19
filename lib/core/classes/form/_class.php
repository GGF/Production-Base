<?

defined("CMS") or die("Restricted usage: " . basename(__FILE__));

class cmsForm {
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	//                                                                                                                                                                 //
	//   CONSTRUCTOR : VARIABLE INITIALIZATION                                                                                                                         //
	//                                                                                                                                                                 //
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	var $request = array();
	var $files = array();
	var $elements = array();
	var $obligatory = false;
	var $checkers = false;
	
	var $id = 0;
	var $uid = 0;
	var $action = false;
	var $tag = false;
	var $errors = false;
	var $confirm = false;
	var $confirm_code = false;
	
	var $debug = false;
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	//                                                                                                                                                                 //
	//   CONSTRUCTOR : CLASS INITIALIZATION                                                                                                                            //
	//                                                                                                                                                                 //
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function cmsForm($id, $action = "/", $class = 'basic', $target = '_top', $events = '', $handler = false) {
		
		$_SERVER[uid]++;
		
		$this->id = $id;
		$this->uid = $_SERVER[uid];
		$this->action = $action;
		$this->array = array();
		$this->errors = false;
		$this->isSubmitted = false;
		
		// яваскрипт-тэг (присоединяется один раз, так что надо смотреть этот момент)
		
		// тэг для формы
		$this->tag = "\n<form name='form_" . $this->id . "' id='form_" . $this->uid . "' action='" . $this->action . "' method='post' target='" . $target . "' enctype='multipart/form-data'" . $events . " class='" . $class . "' onsubmit='return(cmsForm_submit(\"" . $this->uid . "\"));'>\n	";
		
		// выносим из сессии флаг отправки формы, чтобы он не просочился в массив с финальными данными
		unset($_SESSION[cmsForm][$this->id][form_submit]);
		unset($_SESSION[cmsForm][$this->id][confirm_code]);
		unset($_SESSION[cmsForm][$this->id][confirm]);
		
		// [ - ШАГ 1 - ] всегда кладем в массив с реквестом сессию
		$this->request = $_SESSION[cmsForm][$this->id];
		
		if (is_array($_FILES["form_" . $this->id])) {
			
			foreach($_FILES["form_" . $this->id] as $type => $arr) {
				foreach ($arr as $name => $value) {
					
					$this->files[$name][$type] = $value;
					
				}
			}
			
		}
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function init($handler=false) { // является ли обработчиком
		
		// [ - ШАГ 2+3 - ] - кладем данные из входного массива в сессию и в реквест
		
		$req = $_REQUEST['form_' . $this->id];
		
		if (isset($_REQUEST['form_' . $this->id])) { // проверяем, нет ли в реквесте чего нового
			
			// пихаем в сессию слитые вместе массивы сессии и реквеста
			if (is_array($req) && is_array($_SESSION[cmsForm][$this->id])) $_SESSION[cmsForm][$this->id] = array_merge($_SESSION[cmsForm][$this->id], $req);
			$this->request = $req;
			
		}
		
		// проверяем целостность
		if (is_array($this->checkers)) foreach ($this->checkers as $name=>$type) { $this->request[$name] = $this->checkField($this->request[$name], $type); }
		// всегда проводим проверку по-умолчанию
		$this->request = $this->checkField($this->request);
		
		// ------------------------------------ //
		// ПРОВЕРКА ПОЛЕЙ ЕСЛИ ФОРМА ОТПРАВЛЕНА //
		// ------------------------------------ //
		if ($this->request[form_submit]) {
			
			// несовпадение реферрала и текущего хоста - данные шлются неизвестно откуда
			if (!stristr($_SERVER[HTTP_REFERER], $_SERVER[HTTP_HOST])) {
				
				cmsError("Форма отправлена с другого домена: " . $_SERVER[HTTP_REFERER]);
				exit();
				
			}
			
			// есть массив обязательных полей
			if (is_array($this->obligatory)) { 
				
				foreach ($this->obligatory as $name => $id) {
					
					if (empty($this->request[$name])) $this->errors[] = "Не заполнено поле «{$name}»";
					
				}
				
				if ($this->errors) {
					
					// ситуация возможна только в случае кривого заполнения (!) не из формы а из левого места...
					print cmsError("Форма " . $this->id . " заполнена не полностью" . cmsVar($this->errors, "Ошибки", true));
					exit;
					
				}
				
			}
			
			if ($this->request[confirm]) {
				
				$this->request[confirm] = $this->confirmReplace($this->request[confirm]);
				
				if (md5($this->request[confirm]) != $this->request[confirm_code]) { $this->errors[] = "code_mismatch"; }
				
			}
			
			unset($this->request[form_submit]);
			$this->isSubmitted = true;
			
		} else $this->errors[] = "not_submitted";
		
		// [ - ШАГ 4 - ] - сливаем все в сессию
		if (!is_array($_SESSION[cmsForm][$this->id])) $_SESSION[cmsForm][$this->id] = array();
		if (is_array($_SESSION[cmsForm][$this->id]) && is_array($this->request))	$_SESSION[cmsForm][$this->id] = array_merge($_SESSION[cmsForm][$this->id], $this->request);
		
		// убиваем  ненужный элемент
		unset($_SESSION[cmsForm][$this->id][0]);
		
		if (@in_array("code_mismatch", $this->errors)) cmsRedirect(cmsReferer() . "?wrongCode");
		
		if ($_REQUEST[clear] == $this->id) { $this->sessionEnd(); cmsRedirect(cmsURL_get()); }
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	//                                                                                                                                                                 //
	//   M E T H O D S                                                                                                                                                 //
	//                                                                                                                                                                 //
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function printErrors() {
		
		cmsVar($this->errors, "Ошибки формы " . $this->id);
		
		return is_array($this->errors);
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function addForm() {
		
		print $this->tag;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function hidden($name, $value) { $this->addHidden($name, $value); }
	
	function addHidden($name, $value) {
		
		print "\n	<input type='hidden' name='" . $name . "' id='" . $name . "' value='" . $value . "'>";
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function confirm() {
		
		$s = "";
		
		for ($i = 0; $i < 6; $i++) { 
			
			$n = mt_rand(0, 1);
			$n = ($n) ? mt_rand(48, 57) : mt_rand(65, 90); // digit or char
			$s .= chr($n);
			
		}
		
		$code = $this->confirmReplace(mb_strtoupper($s));
		$md5	= md5($code);
		
		$_SESSION[cmsForm][code][$md5] = $code;
		
		return $md5;
		
	}
	
	function confirmRecover($md5) {
		
		if ($_SESSION[cmsForm][code][$md5]) {
			
			$code = $_SESSION[cmsForm][code][$md5];
			
			//unset($_SESSION[cmsForm][code][$md5]);
			
			return $code;
			
		} else {
			
			print "Signature not found, use «Reload» link to reload CAPTCHA image.";
			exit;
			
		}
		
	}
	
	function confirmReplace($var) {
		
		$replaces = array(
			"0" => "O",
			"I" => "1",
			"5" => "S",
			"8" => "B",
			"3" => "9",
			"G" => "6",
		);
		
		return cmsReplace($replaces, $var, true);
		
	}
	
	function confirmImage($md5, $width, $height) {
		
		$code = $this->confirmRecover($md5);
		
		$im = imageCreateTrueColor($width, $height);
		
		$w = imageColorAllocate($im, 255, 255, 255);
		$b = imageColorAllocate($im, 000, 000, 000);
		
		imageFill($im, 1, 1, $w);
		$w = imageColorTransparent($im, $w);
		
		$arr = preg_split('//', $code, -1, PREG_SPLIT_NO_EMPTY);
		for ($i = 0; $i < count($arr); $i++) {
			
			$x = (floor($width / 6)) * $i + 1; // разрядка
			
			imageTTFText($im, 14, 0, $x, 18, $b, $_SERVER[DOCUMENT_ROOT] . "/admin/ui/consolas.ttf", $arr[$i]); //
			
		}
		
		$im2 = imageCreateTrueColor($width, $height);
		$w = imageColorAllocate($im2, 255, 255, 255);
		imageFill($im2, 1, 1, $w);
		$w = imageColorTransparent($im2, $w);
		
		$phase = rand(-M_PI, M_PI);
		$frq = M_PI / $height;
		$amp = 2;
		
		for ($i = 0; $i < $height; $i++) {
			
			$dstx = $amp / 2 + sin($i * $frq + $phase) * $amp;
			
			imagecopy($im2, $im, $dstx, $i, 0, $i, $width, 1);
			
		}
		
		imageTrueColorToPalette($im2, true, 256);
		
		header("CONTENT-TYPE: IMAGE/PNG");
		imagePNG($im2);
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function element($type, $name='', $value='', $values=false, $options=false, $events=false) { $this->addElement($type, $name, $value, $values, $options, $events); }
	
	function addElement($type, $name='', $value='', $values=false, $options=false, $events=false) {
		
		if (mb_substr($type, 0, 6) == "fixed:") $type = mb_substr($type, 6);
		
		$origValue = $value;
		
		$options_arr  = explode(";", $options);
		$options = array();
		foreach ($options_arr as $opt) {
			
			list($k, $v) = explode("=", $opt);
			if (!$v) $v = true;
			$options[$k] = $v;
			
		}
		
		$events   = ($events) ? " " . $events : "";
		
		$width		= ($options[width]) ? " style='width:" . $options[width] . "'" : "";
		$disabled = ($options[disabled]) ? " disabled" : "";
		$readonly = ($options[readonly]) ? " readonly" : "";
		$length   = ($options[length]) ? $options[length] : 1000; // умолчание
		$nobr     = ($options[nobr]) ? true : false;
		$length   = " maxlength='" . $length . "'";
		
		$defBegin	= ""; // чтобы не рвать тэг <P> тэгами <DIV>
		$defEnd		= "<br />";
		$blockBegin	= "";
		$blockEnd		= "";
		$lineBegin = "";
		$lineEnd	 = "";
		
		if ($nobr) {
			
			$blockBegin	= $defBegin;
			$blockEnd		= $defEnd;
			
		} else {
			
			$lineBegin = $defBegin;
			$lineEnd	 = $defEnd;
			
		}
		
		if ($type != 'hidden') {
			$value = ($this->request[$name]) ? $this->request[$name] : $value;
			$value = stripslashes($value);
		}
		
		// -- TEXT ---------------------------------------------//
		
		if ($type == 'code') {
			
			$md5 = $this->confirm();
			
			print "\n	<table class='frame autoWidth'><tr><td class='middle'>";
			$this->addElement("hidden", "confirm_code", $md5);
			$this->addElement("text", "confirm", "", false, "length=6");
			print "\n	</td><td class='middle' style='padding: 0px 0px 0px 7px'>";
			print "\n	<img src='/core/classes/form/ajax_confirm.php?code={$md5}&width=120&height=21' width='120' height='21' id='form_{$this->uid}_captcha' title='" . cmsLang_var("cmsForm.captcha.title") . "'>";
			print "\n	</td><td class='middle' style='padding: 0px 0px 0px 7px'><a href='javascript: cmsForm_reloadCaptcha(\"{$this->uid}\")'>" . cmsLang_var("cmsForm.captcha.reload") . "</a></tr></table>";
			
			$this->addObligatory("confirm");
			$this->addChecker("confirm", "code");
			
		}
		
		if ($type == 'text' || $type == 'password') {
			
			$cls   = ($name == 'confirm') ? " confirm" : "";
			$value = ($name == 'confirm') ? "" : $value;
			
			$value = str_replace("\'", "&#39;", $value);
			$value = str_replace("'", "&#39;", $value);
			print "\n	<input type='" . $type . "' class='text" . $cls . "' name='form_" . $this->id . "[" . $name . "]' id='form_" . $this->uid . "_" . $name . "' value='" . $value . "'" . $disabled . $readonly . $events . $length . $width . ">";
			
		}
		
		// -- HIDDEN -------------------------------------------//
		
		if ($type == 'hidden') {
			
			print "\n	<input type='hidden' name='form_" . $this->id . "[" . $name . "]' id='form_" . $this->uid . "_" . $name . "' value='" . $origValue . "'>";
			
		}
		
		// -- IMAGE --------------------------------------------//
		
		if ($type == 'image') {
			
			$path = cmsFile_path($value);
			$info = getImageSize($path);
			
			//print "\n	<input type='hidden' name='form_" . $this->id . "[" . $name . "]' id='form_" . $this->uid . "_" . $name . "' value='" . $origValue . "'>";
			print "\n	<input type='submit' class='image' id='form_" . $this->uid . "_" . $name . "' value='&nbsp;'" . $disabled . $readonly . $events . " style='width: " . $info[0] . "px; height: " . $info[1] . "px; background: transparent url(" . $value . ") no-repeat'>";
			
		}
		
		// -- FILE ---------------------------------------------//
		
		if ($type == 'file') {
			
			print "\n	<input type='file' class='file' name='form_" . $this->id . "[" . $name . "]' id='form_" . $this->uid . "_" . $name . "' value=''" . $disabled . $readonly . $events . ">";
			
		}
		
		// -- TEXTAREA -----------------------------------------//
		
		if ($type == 'textarea') {
			
			$options[rows] = ($options[rows]) ? $options[rows] : 4;
			
			$width		= ($options[width]) ? "; width:" . $options[width] : "";
			$height	= " style='height: " . ($options[rows] * 16 + 4) . "px{$width}'";
			print "\n	<textarea name='form_" . $this->id . "[" . $name . "]' id='form_" . $this->uid . "_" . $name . "' " . $disabled . $readonly . $events . $length . $width . $height . ">" . $value . "</textarea>";
			
		}
		
		// -- BUTTON -------------------------------------------//
		
		if ($type == 'submit' || $type == 'button') {
			
			print "\n	<input type='" . $type . "' class='submit' id='form_" . $this->uid . "_" . $name . "' value='" . $origValue . "'" . $disabled . $readonly . $width . $events . ">";
			
		}
		
		if ($type == 'reset') {
			
			print "\n	<input type='reset' class='submit' id='form_" . $this->uid . "_reset' value='Очистить' onclick=\"document.location='" . cmsURL_get() . "?clear=" . $this->id . "'\">";
			
		}
		
		// -- SELECT -------------------------------------------//
		
		if ($type == 'select') {
			
			$placeGroups = false;
			
			print "\n	<select class='select' name='form_" . $this->id . "[" . $name . "]' id='form_" . $this->uid . "_" . $name . "'" . $disabled . $readonly . $width . $events . ">";
			foreach ($values as $k=>$v) {
				
				$s = ($value == $k) ? " selected" : "";
				
				if (mb_substr($v, 0, 1) == "_" || mb_substr($v, 0, 1) == "+" && $placeGroups) print "\n		</optgroup>";
				if (mb_substr($v, 0, 1) == "_" || mb_substr($v, 0, 1) == "+") {
					$m = mb_substr($v, 0, 1);
					$v = mb_substr($v, 1);
					$placeGroups = true;
					print "\n		<optgroup label='" . $v . "'>";
					if ($m == "+") print "\n		<option value='" . $k . "'" . $s . ">" . $v . "</option>";
					
				} else {
					print "\n		<option value='" . $k . "'" . $s . ">" . $v . "</option>";
				}
				
			}
			
			if ($placeGroups == true) print "\n		</optgroup>";
			
			print "\n	</select>";
			
		}
		
		// -- RADIO --------------------------------------------//
		
		if ($type == 'radio') {
			
			$i = 0;
			
			print $blockBegin;
			print "\n	<input type='hidden' name='form_" . $this->id . "[" . $name . "]' value='false'>";
			foreach ($values as $k=>$v) {
				
				$i++;
				$s = ($value == $k) ? " checked" : "        ";
				
				print $lineBegin;
				//print "<input type='radio' class='radio' name='form_{$this->id}[{$name}]' id='form_{$this->uid}_{$name}_{$i}' value='{$k}'{$s}{$disabled}{$readonly}{$events}><a class='label' href='javascript: cmsForm_check(\"form_{$this->uid}_{$name}_{$i}\");'{$disabled}>{$v}</a> ";
				print "<label for='form_{$this->uid}_{$name}_{$i}' class='label'{$disabled}><input type='radio' class='radio' name='form_{$this->id}[{$name}]' id='form_{$this->uid}_{$name}_{$i}' value='{$k}'{$s}{$disabled}{$readonly}{$events}>{$v}</label> ";
				print $lineEnd;
				
			}
			print $blockEnd;
			
		}
		
		// -- CHECKBOX -----------------------------------------//
		
		if ($type == 'checkbox') {
			
			$i = 0;
			
			foreach ($values as $k=>$v) { // потому что нужны ключи массива, а вообще - множественная запись не предусмотрена
				
				$i++;
				$s = ($value == $k) ? " checked" : "        ";
				
				print $lineBegin;
				print "<input type='hidden' name='form_{$this->id}[{$name}]' value='false'>";
				print "<label for='form_{$this->uid}_{$name}' class='checkboxLabel'{$disabled}><input type='checkbox' class='checkbox' name='form_{$this->id}[{$name}]' id='form_{$this->uid}_{$name}' value='{$k}'{$s}{$disabled}{$readonly}{$events}>{$v}</label> "; //
				//print "<input type='checkbox' class='checkbox' name='form_{$this->id}[{$name}]' id='form_{$this->uid}_{$name}' value='{$k}'{$s}{$disabled}{$readonly}{$events}><a class='label checkboxLabel' href='javascript: cmsForm_check(\"form_{$this->uid}_{$name}\");'{$disabled}>{$v}</a> ";
				print $lineEnd;
				
			}
			
		}
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function addArray($elements) {
		
		if (is_array($elements)) foreach ($elements as $k=>$elm) {
			
			$this->array[$elm[1]] = array($elm[0], $elm[1], $elm[2], $elm[3], $elm[4], $elm[5]);
			
		}
		
	}
	
	function addToArray($elm) {
		
		$this->array[$elm[1]] = array($elm[0], $elm[1], $elm[2], $elm[3], $elm[4], $elm[5]);
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function add($name, $value = false) { $this->addArrayElement($name, $value); } // just a shortcut
	
	function addArrayElement($name, $value=false) { // уже на страницу из массива
		
		$elm = $this->array[$name];
		$value = ($value !== false) ? $value : $elm[2];
		
		// IF SOMETHING IS IN SESSION!!!
		if ($this->request[$name]) $value = $this->request[$name];
		
		$this->addElement($elm[0], $elm[1], $value, $elm[3], $elm[4], $elm[5]);
		
		if (!$this->request[$name]) $this->request[$name] = $value;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function addChecker($name, $type) {
		
		if (mb_substr($type, 0, 6) == "fixed:") $type = mb_substr($type, 6);
		
		$this->checkers[$name] = $type;
		
	}
	
	function addCheckerArray($array) {
		
		if (is_array($array)) foreach($array as $name=>$type) {
			if (mb_substr($type, 0, 6) == "fixed:") $type = mb_substr($type, 6);
			$this->addChecker($name, $type);
		}
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function sessionEnd() {
		
		unset($_SESSION[cmsForm][$this->id]);
		$this->request = false;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function value($name, $ret = true) {
		
		if ($this->array[$name][0] == 'checkbox' || $this->array[$name][0] == 'radio' || $this->array[$name][0] == 'select') {
			
			$return = $this->array[$name][3][$this->request[$name]];
			
		} else {
			
			$ret = ($ret) ? "нет" : false;
			$return = $this->request[$name] ? $this->request[$name] : $ret;
			
		}
		
		return ($return) ? $return : "";
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function checkField($value, $type=false) {
		
		if (@is_array($value)) {
			
			foreach ($value as $k=>$v) $value[$k] = $this->checkField($v, $type);
			
		} else {
			
			$value = (string) $value;
			
			if ($type=='numeric') $value = preg_replace('/[^\d.,]/', ''	,							$value);
			if ($type=='login') 	$value = preg_replace('/[^_\da-zA-Z]/', ''	,				$value);
			if ($type=='code') 		$value = preg_replace('/[^\da-zA-Z]/', ''	,					mb_strtoupper($value));
			if ($type=='phone') 	$value = preg_replace('/[^-+() \d]/', ''	,					$value);
			if ($type=='mail')		$value = preg_replace('/[^-_@.\da-zA-Z]/', '',			$value);
			if ($type=='url')			$value = preg_replace('/[^-\\_.:\/\da-zA-Z]/', ''	, $value);
			
			//$value = stripslashes($value);
			//$value = mysql_escape_string($value);
			
			$value = str_replace("\\r", "", $value);
			$value = str_replace("\\n", "\n", $value);
			
			$value = trim($value);
			
		}
		
		return $value;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function addObligatory($name) {
		
		$this->obligatory[$name] = "'form_" . $this->uid . "_" . $name . "'";
		
	}
	
	function addObligatoryArray($array) {
		
		if (is_array($array)) foreach($array as $name) $this->addObligatory($name);
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function end() {
		
		if ($this->debug) cmsVar($this->obligatory, '$this->obligatory');
		if ($this->debug) cmsVar($this->checkers, '$this->checkers');
		if ($this->debug) cmsVar($this->errors, '$this->errors');
		
		if ($this->obligatory) {
			
			print "\n	<script>";
			print "\n		window.obligatory_" . $this->uid . " = new Array(" . implode(", ", $this->obligatory) . ");";
			print "\n	</script>\n";
			
		}
		
		if ($this->checkers) {
			
			print "\n	<script>";
			
			foreach ($this->checkers as $name=>$type) {
				
				print "\n		$('#form_" . $this->uid . "_" . $name . "').bind('keyup', function(e) { cmsForm_checkField('form_" . $this->uid . "_" . $name . "', '" . $type . "', e); });";
				
			}
			
			print "\n	</script>";
			
		}
		
		$this->addElement("hidden", "form_submit", "true");
		
		print "\n	<input type='hidden' name='submit' value='true'>";
		print "\n</form>";
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
}

?>