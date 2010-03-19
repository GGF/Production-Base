<?
	
	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
	// переменная $pageContents
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	$pageContents = preg_split('/(["\']popup:.*?["\'])/i', $pageContents, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
	
	if (count($pageContents)) foreach($pageContents as $i => $c) {
		
		if (preg_match('/(["\']popup:.*?["\'])/i', $c)) {
			
			$pageContents[$i] = tokens::get("cmsPopup", array("src" => substr($c, 7, -1), "quote" => substr($c, 0, 1)));
			
		}
		
	}
	
	$pageContents = implode($pageContents);
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
?>