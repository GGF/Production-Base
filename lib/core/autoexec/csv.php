<? 
	
	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function cmsCSV_header($name) {
		
		header("Content-type: application/vnd.ms-excel");
		header('Content-Disposition: attachment; filename="' . addSlashes($name) . '"');
		
	}
	
	function cmsCSV($array) {
		
		$return = array();
		
		for ($i = 0; $i < count($array); $i++) {
			
			for ($j = 0; $j < count($array[$i]); $j++) {
				
																					$array[$i][$j] = str_replace('"', '""', $array[$i][$j]);
				if (is_numeric($array[$i][$j]))		$array[$i][$j] = str_replace('.', ',', $array[$i][$j]);
				if (stristr($array[$i][$j], ";"))	$array[$i][$j] = '"' . $array[$i][$j] . '"';
				
			}
			
			$return[] = implode(";", $array[$i]);
			
		}
		
		return implode("\r\n", $return);
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
?>