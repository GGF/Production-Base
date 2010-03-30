<? 

	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/admin/login.php";
	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/core/classes/ajax.php";
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	$_RESULT = array(
		"status"	=> modCache_delete($_REQUEST['id']),
	);
	
?>