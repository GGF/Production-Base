<?
	
	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
  function previewText($text, $action='preview') {
		
    if ($action=='preview') {
			
      $pos=mb_strpos($text, $_SERVER[splitter]);
			
      if ($pos !== false) { $text = mb_substr($text, 0, $pos); $isCut=true; }
			
      if ($isCut) {
				
        if (mb_strtolower(mb_substr($text,-4))=='</p>') $text=mb_substr($text,0,-4) . "…</p>";
        else $text.="…";
				
      }  
			
    } else {
			
      $text=str_replace($_SERVER[splitter], "", $text);
			
    }
		
    return $text;
		
  }
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function cmsCut($text) {
		
		$arr = preg_split('%(<p>)?[\s]*' . $_SERVER[splitter] . '[\s]*(</p>)?%si', $text, -1, PREG_SPLIT_NO_EMPTY);
		
		$cut[content] = $arr;
		
		unset($cut[content][0]);
		
		$cut[preview] = $arr[0];
		$cut[content] = implode($_SERVER[splitter], $cut[content]);
		
		$cut[fullContent] = $cut[preview] . $cut[content];
		
		return $cut;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function cmsSplit($text) {
		
		return preg_split('%(<p>)?[\s]*' . $_SERVER[splitter] . '[\s]*(</p>)?%si', $text, -1, PREG_SPLIT_NO_EMPTY);
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function cmsCut_substr($str, $length = 60, $max = 5, $postfix = "…") {
    
		$str = strip_tags($str);
		
		if (mb_strlen($str) > $length + $max) {
			
			$arr = explode(" ", $str);
			$out = "";
			
			foreach ($arr as $word) {
				
				$out .= " {$word}"; 
				
				if (mb_strlen($word) + mb_strlen($out) > $length + $max + 1) {
					
					$out = mb_substr($out, 0, $length + $max);
					break;
					
				}
				
			}
			
			return mb_substr($out, 1) . $postfix;
			
		} else return $str;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
?>