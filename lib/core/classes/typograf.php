<?

set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER['CORE'] . '/contrib/typograf');
require("Jare/Typograph.php");

class cmsTypograf {
	public static $typograf;
	
	function init() {
		
		//$jareTypo = new Jare_Typograph($text);
		//$jareTypo->getTof("etc")->disableBaseParam("paragraphs")->disableParsing(true);
		//$jareTypo->getTof("space")->disableBaseParam("autospace_after_pmarks")->disableParsing(true);
		
	}
	
	function parse($text) {
		
		$text = cmsUTF_encode($text);
		
		//$text = self::$typograf->parse(self::$typograf->getBaseTofsNames());
		$text = Jare_Typograph::quickParse($text);
		
		return cmsUTF_decode($text);
		
	}
	
}

?>