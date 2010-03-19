<?
	
	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function cmsPrint_frame($url=false) {
		
		if (!$url) {
			
			$d = (stristr($_SERVER[REQUEST_URI], "?")) ? "&" : "?";
			
			$url = $_SERVER[REQUEST_URI] . $d . "print=true"; //REDIRECT_URL
			
		}
		
		//print $url;
		
		print "<div><iframe name='printFrame' id='printFrame' src='" . $url . "' style='width: 100%; height: 1px; border: 0px' border='0' frameborder='0'></iframe></div><!-- style='display: none' -->";
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
?>