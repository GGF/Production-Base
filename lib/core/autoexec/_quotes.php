<?
	
	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function stripSlashesArray($a) {
		
		if (is_array($a)) {
			
			foreach ($a as $k => $v) $a[stripSlashesArray($k)] = stripSlashesArray($v);
			
		} else {
			
			$a = stripSlashes($a);
			
		}
		
		return $a;
		
	}
	
	if (get_magic_quotes_gpc()) {
		
		$_GET			= stripSlashesArray($_GET);
		$_POST		= stripSlashesArray($_POST);
		$_COOKIE	= stripSlashesArray($_COOKIE);
		$_REQUEST	= stripSlashesArray($_REQUEST);
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
?>