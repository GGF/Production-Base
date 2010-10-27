<?

defined("CMS") or die("Restricted usage: " . basename(__FILE__));

class cmsAjax {
	
	public static $entityDecode = true;
	public static $result = array();
	public static $ajax = false;
	
	public static function init() {
		
		header("HTTP/1.0 200 OK");
		
		$json = cmsJson_decode($_REQUEST['json']);
		
		if ($json) {
			
			unset($_REQUEST[json]);
			
			$_REQUEST = array_merge($_REQUEST, $json);
			self::$ajax = true;
			
			//header("CONTENT-TYPE: TEXT/X-JSON; CHARSET={$_SERVER[cmsEncoding]}");
			header("CONTENT-TYPE: APPLICATION/JSON; CHARSET={$_SERVER[cmsEncoding]}");
			
		}
			self::$ajax = true;
		
	}
	
	public static function parseTokens($options = array()) {
		
		if (is_array(self::$result)) {
			
			foreach (self::$result as $k => $v) if (is_string($v)) self::$result[$k] = tokens::parse($v, $options);
			
		} else self::$result = tokens::parse(self::$result, $options);
		
		return true;
		
	}
	
	/**
	 *	Функция возвращает обработанный для фронтенда контент
	 *	@param	string	$content	Текущий контент (то, что вывелось на страницу тем или иным образом)
	 */
	public static function process($content) {
		
		/*
		*/
		
		return cmsJson_encode(array(
			"text" => trim(html_entity_decode($content)), // тут был tokens::parse
			"js"   => self::$result,
		), self::$entityDecode);
		
	}
	
	/**
	 *	Функция перекодирует переменную
	 */
	public static function decode(&$var, $key) {
		
		$var = cmsUTF_decode($var);
		
	}
	
	/**
	 *	Функция выключает разбор входящей переменной и буферизацию
	 */
	public static function raw() {
		
		array_walk_recursive($_REQUEST, "cmsAjax::decode");
		
		$content = ob_get_contents();
		ob_end_clean();
		
		print $content;
		
	}
	
}	

cmsAjax::init();

GLOBAL $_RESULT;
$_RESULT = &cmsAjax::$result;

ob_Start("cmsAjax::process");
	
?>