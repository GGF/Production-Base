<?
	
	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function zeroFill($var, $n = 5) {
		
		return sprintf("%0" . $n . "s", $var);
		
	}
	
	function preg_match_count($needle, $haystack) {
		
		if (preg_match_all($needle, $haystack, $n, PREG_PATTERN_ORDER)) {
			
			return count($n[0]);
			
		} else return 0;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function textHTML($text, $flag='html') {
		
		$text = str_replace("\r", "", $text);
		
		$text = $flag=='html' ? str_replace("\n", "<br>", $text) : str_replace("<br>", "\n", $text);
		
		if ($flag == 'title') {
			$text = str_replace("<br>", "&#x0A", $text);
			$text = str_replace("\n", "&#x0A", $text);
		}
		
		return $text;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	/**
	 *	ѕодготовить HTML текст (ссылки и адреса картинок) дл€ публикации на внешнем сервере
	 *	@param	string	$html			HTML текст
	 *	@param	array		$options	ќпции (trim Ч сжать все в одну строку)
	 *	@return	string
	 */
	function cmsHTML_external($html, $options = array()) {
		
		$html = preg_replace('%((?:src|href)=[\'"])/%sim', '$1http://' . $_SERVER[HTTP_HOST] . '/', $html);
		
		if ($options[trim]) {
			
			$html = explode("\n", $html);
			for ($i = 0, $max = count($html); $i < $max; $i++) $html[$i] = trim($html[$i]);
			$html = implode(" ", $html);
			
		}
		
		if ($options[cutScripts]) {
			
			$html = preg_replace('%<script[^>]*?>.*?</script>%si', '', $html);
			
		}
		
		return $html;

	}
	
	/**
	 *	¬ырезать из HTML лишние символы
	 *	@param	string	$html			HTML текст
	 *	@param	array		$options	ќпции (light Ч м€гкий режим с тэгами xml, all Ч вырезать все, exclude|add Ч удалить или добавить разрешенных тегов)
	 *	@return	string
	 */
	function cmsHTML_stripTags($html, $options = array()) {
		
		$html = str_replace("\r", "", $html);
		$html = str_replace("<p></p>", "", $html);
		//$html = str_replace("<p>&nbsp;</p>", "", $html);
		
		$html = "<p>{$html}</p>";
		$html = str_replace("<p><p>", "<p>", $html);
		$html = str_replace("</p></p>", "</p>", $html);
		
		if ($options[stripAll] || $options[all]) {
			
			$html = strip_tags($html);
			
		} else {
			
			$allowed = ($options[light]) ? array(
				"a",
				"abbr",
				"acronym",
				"address",
				"b",
				"bdo",
				"big",
				"blockquote",
				"br",
				"center",
				"cite",
				"code",
				"dd",
				"del",
				"dfn",
				"dl",
				"dt",
				"em",
				"font",
				"h1",
				"h2",
				"h3",
				"h4",
				"h5",
				"h6",
				"h7",
				"h8",
				//"hr",
				"i",
				"img",
				"ins",
				"kbd",
				"li",
				"nobr",
				"ol",
				"p",
				"pre",
				"q",
				"samp",
				"small",
				"span",
				"strike",
				"strong",
				"sub",
				"sup",
				"tt",
				"ul",
				"var",
				"wbr",
				"xmp"
			) : array(
				"a",
				"b",
				"big",
				"blockquote",
				"br",
				"code",
				"del",
				"em",
				"h1",
				"h2",
				"h3",
				"h4",
				"h5",
				"h6",
				"h7",
				"h8",
				"i",
				"img",
				"li",
				"ol",
				"p",
				"pre",
				"q",
				"small",
				"strike",
				"strong",
				"sub",
				"sup",
				"ul",
			);
			
			if (is_array($options[exclude]) && count($options[exclude])) foreach ($options[exclude] as $f) {
				
				if (($key = array_search($f, $allowed)) !== false) unset($allowed[$key]);
				
			}
			
			if (is_array($options[add])) $allowed = array_merge($allowed, $options[add]);
			
			$html = strip_tags($html, "<" . implode("><", $allowed) . ">");
			
		}
		
		$html = str_replace("\n", "<br>", $html);
		
		return $html;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
?>