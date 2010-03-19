<?

defined("CMS") or die("Restricted usage: " . basename(__FILE__));

class cmsFeed {
	
	public static $feeds = array();
	
	public static function register($url, $name) {
		
		self::$feeds[$url] = $name;
		
	}
	
	public static function html($pad) {
		
		$html = "";
		
		foreach (self::$feeds as $url => $name) {
			
			$html .= "<link rel='alternate' href='http://{$_SERVER[HTTP_HOST]}/rss{$url}' title='{$_SERVER[project][name]}{$_SERVER[delim]}{$name}' type='application/rss+xml' />\n{$pad}";
			
		}
		
		return $html;
		
	}
	
}	

?>