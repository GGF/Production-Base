<?
	
	$id = $template[id];
	
	print modAuth_user($id, array("admin" => false, "full" => $template['full']));
	
?>