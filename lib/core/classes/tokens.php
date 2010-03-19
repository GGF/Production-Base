<?

defined("CMS") or die("Restricted usage: " . basename(__FILE__));

define("CMSTOKENS_FILE_ABS",			1);
define("CMSTOKENS_FILE_TEMPLATE",	2);
define("CMSTOKENS_VARIABLE",			3);
define("CMSTOKENS_CONSTANT",			4);

defined("CMSTOKENS_MAX_RECURSION") or define("CMSTOKENS_MAX_RECURSION",	5);

class TOKENS {
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	static $array = array();
	static $vars = array();
	static $show = true;
	static $pass = 0;
	
	static function add($name, $content, $type = CMSTOKENS_FILE_TEMPLATE) {
		
		self::$array[$name] = array();
		self::$array[$name][content] = $content;
		self::$array[$name][type] = $type;
		
	}
	
	static function addVar($name, &$var, $type = CMSTOKENS_VARIABLE) {
		
		self::$vars[$name] = array(
			"type"		=> $type,
		);
		
		if ($type == CMSTOKENS_VARIABLE) self::$vars[$name][content] = &$var; else self::$vars[$name][content] = $var;
		
	}
	
	static function getData() {
		
		cmsVar_export(get_class_vars(__CLASS__));
		
	}
	
	static function get($token, $params = array()) {
		
		if (!isset(tokens::$array[$token]) && !isset(tokens::$vars[$token])) return false;
		
		if			(tokens::$array[$token][type]	== CMSTOKENS_FILE_ABS)			return cmsTemplate(tokens::$array[$token][content], $params, true);
		elseif	(tokens::$array[$token][type]	== CMSTOKENS_FILE_TEMPLATE)	return cmsTemplate(tokens::$array[$token][content], $params, false);
		//elseif	(tokens::$vars[$token][type]	== CMSTOKENS_VARIABLE)			return tokens::$vars[$token][content];
		else return tokens::$vars[$token][content];
		
	}
	
	static function parsePass($content, $options = array()) {
		
		$content = preg_split('/(\{[^{}]+\})/si', $content, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE); // was 
		
		for ($i = 0; $i < count($content); $i++) {
			
			if (preg_match('/(\{[^{\$}]+\})/si', $content[$i])) {
				
				$content[$i] = mb_substr($content[$i], 1, -1);
				$params = mb_stristr($content[$i], " ");
				
				if ($params) {
					
					$token = mb_substr($content[$i], 0, -mb_strlen($params));
					
					$params = str_replace("&quot;", '"', $params);
					//print_r($token . " — " . htmlspecialchars($params) . "<br>");
					
					$xml = new DOMDocument("1.0");
					$props = array();
					
					if (@$xml->loadHTML("<html><head><meta http-equiv='content-type' content='text/html; charset={$_SERVER[cmsEncoding]}'></head><body><div{$params} parsed='true'></div></body></html>")) {
						
						$node = $xml->getElementsByTagName("div")->item(0);
						if ($node->hasAttributes()) { foreach ($node->attributes as $attr) $props[$attr->name] = ($attr->value !== "") ? ($attr->value) : true; }
						if ($_SERVER[cmsEncoding] != "UTF-8") $params = cmsUTF_decode($props);
						
					}
					
				} else {
					
					$token = $content[$i];
					$params = array("parsed" => true);
					
				}
				
				//cmsVar($params);
				
				if (set(tokens::$array[$token]) && $params[parsed]) $content[$i] = self::get($token, $params); else $content[$i] = "{{$content[$i]}}";
				
			}
			
			if (preg_match('/(\{\$[^{}]+\})/si', $content[$i])) {
				
				$token = mb_substr($content[$i], 2, -1);
				
				if (set(tokens::$vars[$token])) $content[$i] = self::get($token);
				
			}
			
		}
		
		return implode($content);
		
	}
	
	static function parse($content, $options = array()) {
		
		$max = $options[multiPass] ? CMSTOKENS_MAX_RECURSION : 1;
		
		for ($i = 0; $i < $max; $i++) {
			
			self::$pass++;
			
			$oldContent = $content;
			$content = tokens::parsePass($content, $options);
			profiler::add("Токены", "Проход №{$i}");
			
			if ($oldContent == $content) break;
			
		}
		
		return $content;
		
	}
	
	static function parseTemplate($content, $template = array()) {
		
		$content = preg_split('/(\{\$template\.[^{}]+\})/si', $content, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
		
		for ($i = 0; $i < count($content); $i++) {
			
			if (preg_match('/\{\$template\.([^{}]+)\}/si', $content[$i], $reg)) { // braces are now inside
				
				$temp = $template;
				foreach (explode(".", $reg[1]) as $r) if ($temp[$r]) $temp = $temp[$r]; else { $temp = false; break; }
				
				$content[$i] = ($temp) ? $temp : ""; //"<!-- Non existent token: template.{$reg[1]} -->";
				
			}
			
		}
		
		return implode($content);
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
}

?>