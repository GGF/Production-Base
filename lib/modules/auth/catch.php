<?

	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/admin/login.php"; 
	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/admin/blank.php"; 
	
	modAuth_checkPlugin();
	
	$action	= checkString($_REQUEST[action]);
	$result	= checkString($_REQUEST[result]);
	$id			= checkString($_REQUEST[id]);
	
	$_REQUEST = checkString($_REQUEST);
	
	cmsPrint_init();
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	if ($action == "save") {
		
		cmsPrint("Сохранение пользователя «{$_REQUEST[name]}» ({$_REQUEST[login]}).");
		cmsPrint();
		
		cmsPrint("Запрос прошел без ошибок.", "success");
		cmsPrint("Пользователь сохранен.", "success bold");
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	if ($action == "delete") {
		
		$f = sqlShared::fetch("SELECT * FROM cms_auth WHERE id='{$_REQUEST[id]}'");
		$f = modAuth_getUser($_REQUEST[id]);
		
		if ($f[login] != "admin") {
			
			cmsPrint("Удаление пользователя «{$f[name]}» ({$f[login]}, {$f[id]}).");
			cmsPrint();
			
			// DELETE FROM STRUCTURE
			sqlShared::query("DELETE FROM cms_auth WHERE id='{$f[id]}'");
			
			cmsCall($_SERVER[modAuth][events][delete][callback], array($f));
			
			cmsPrint("Запрос прошел без ошибок.", "success");
			cmsPrint("Пользователь удален.", "success bold");
			
			cmsRedirect_admin("admin.php");
			
		} else {
			
			cmsPrint("Вы пытались удалить администратора.", "error");
			cmsPrint("Пользователь не удален.", "error bold");
			
		}
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
?>