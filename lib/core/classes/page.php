<?

defined("CMS") or die("Restricted usage: " . basename(__FILE__));

class PAGE {
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	static $module = null;
	static $id = null;
	static $uid = null;
	static $parent = null;
	static $comments = null;
	static $cache = true;
	static $assets = array("js"	=> array(), "css" => array());
	static $path = array();
	static $pathRev = array();
	static $pathHTML = null;
	static $pathPlain = null;
	static $name = null;
	static $meta = array("title" => null, "desc" => null, "keys" => null);
	static $modVars = array();
	static $contents = "";
	static $content = "";
	
	static function init() {
		
		self::$content = &self::$contents;
		
	}
	
	static function pathReverse() {
		
		return array_reverse(self::$path);
		
	}
	
	static function getData() {
		
		cmsVar_export(get_class_vars(__CLASS__));
		
	}
	
	static function contents() {
		
		print self::$contents;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
}

page::init();

?>