<?
	
	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function cmsTags_explode($subject, $url = false) {
		
		$result = explode(",", $subject);
		$output = array();
		
		foreach ($result as $v) {
			
			$v = trim($v);
			if ($v) $output[] = ($url) ? "<a href='{$url}?tag={$v}'><i>{$v}</i></a>" : "<u><i>{$v}</i></u>";
			
		}
		
		sort($output);
		
		return count($output) ? cmsLang_var("tags") . ": " . implode(", ", $output) . "." : "";
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
?>