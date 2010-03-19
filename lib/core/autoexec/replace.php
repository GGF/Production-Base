<?
	
	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function cmsReplace($array, $text, $str = false) {
		
		$patterns = array();
		$replaces = array();
		
		foreach ($array as $k => $v) {
			
			$patterns[] = $k;
			$replaces[] = $v;
			
		}
		
		if ($str) return str_replace($patterns, $replaces, $text); else return preg_replace($patterns, $replaces, $text);
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
?>