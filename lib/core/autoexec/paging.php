<? 
	
	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function cmsPaging($data) {
		
		$_REQUEST[page] = checkString($_REQUEST[page], "digits");
		
		$page		= $_REQUEST[page] ? $_REQUEST[page] : 1;
		
		$pages	= ceil($data[total] / $data[perPage]);
		$offset	= ($page-1) * $data[perPage];
		
		if ($data[perPage] > $data[total] - $offset) $data[perPage] = $data[total] - $offset;
		
		if ($pages > 1) {
			
			$html		= array();
			
			for ($i = 1; $i <= $pages; $i++) {
				
				$html[] = ($i == $page) ? "<span>{$i}</span>" : cmsTemplate_var($data[link], array("page" => $i));
				
			}
			
			$custom = $html;
			
			$html = cmsLang_var("paging.pages") . ": " . implode(", ", $html) . " — ";
			
		} else {
			
			$html		= "";
			$custom	= array();
			
		}
		
		$html = "<span class='cmsPaging'>{$html}" . cmsLang_var("paging.showed") . ": {$data[perPage]} " . cmsLang_var("paging.of") . " {$data[total]}.</span>";
		
		return array(
			"page"			=> $page,
			"pages"			=> $pages,
			"offset"		=> $offset,
			"perPage"		=> $data[perPage],
			"html"			=> $html,
			"custom"		=> $custom,
		);
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
?>