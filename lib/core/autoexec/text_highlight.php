<?
	
	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function highLight($search, $txt) {
		
		if (mb_strlen($search) > 2) {
			
		  if (mb_strlen($search) > 5) $search = mb_substr($search, 0, -2);
			
			$arr = preg_split('/(&[\\w#]+;)|(<.+?>)/si', $txt, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
			foreach ($arr as $k => $v) {
				
				if (!preg_match('/(&[\\w#]+;)|(<.+?>)/si', $arr[$k])) {
					
					$arr[$k] = preg_replace('/([\\w]*' . $search . '[\\w]*)/si', '<span class=\'highlight\'>$1</span>', $v);
					
				}
				
			}
			
		  return implode("", $arr);
			
		} else return $txt;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
?>