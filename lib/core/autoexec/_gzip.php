<? 
	
	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	if (!isset($_SERVER[cmsGZIP][enabled])) {
		
		if(isset($_SERVER['HTTP_ACCEPT_ENCODING'])) $acceptEnc = $_SERVER[HTTP_ACCEPT_ENCODING]; else $acceptEnc = $_SERVER[HTTP_TE];
		
		$_SERVER[cmsGZIP] = array(
			"enabled"		=> (stristr($acceptEnc, 'gzip') || stristr($acceptEnc, 'deflate')) ? true : false,
			"algorythm"	=> (stristr($acceptEnc, 'deflate')) ? "deflate" : "gzip",
		);
		
		if (stristr($_SERVER['HTTP_USER_AGENT'], "MSIE 6")) $_SERVER[cmsGZIP][enabled] = false;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
?>