<?

	REQUIRE $_SERVER['DOCUMENT_ROOT'] . "/admin/login.php"; 
	REQUIRE $_SERVER['DOCUMENT_ROOT'] . "/admin/blank.php"; 
	
	$action		= checkString($_REQUEST['action']);
	$_REQUEST	= checkString($_REQUEST);
	
	if (!$_REQUEST['name']) $_REQUEST['name'] = CMS_UNNAMED;
	
	// Включаем обвязку обработчика
	cmsPrint_init();
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	if ($action == "create") {
		
		cmsPrint("Создание объекта «{$_REQUEST['name']}».");
		
		sql::insert("cms_blank", array(
			"status"			=> $_REQUEST['status'],
			"createAdmin"	=> $_SESSION['user']['id'],
			"createDate"	=> time(),
			"editAdmin"		=> $_SESSION['user']['id'],
			"editDate"		=> time(),
			"name"				=> $_REQUEST['name'],
			"content"			=> $_REQUEST['content'],
		));
		$id = sql::lastID();
		
		if (!sql::$errors) {
			
			cmsPrint("Объекту присвоен идентификатор «{$id}».");
			cmsPrint();
			
			// Search index
			if (is_callable("modSearch_deleteIndex")) modSearch_deleteIndex("blank", $id);
			if (is_callable("modSearch_createIndex")) modSearch_createIndex("blank", $id);
			
			cmsPrint("Запрос прошел без ошибок.", "success");
			cmsPrint("Объект создан.", "success bold");
			
			// редирект на редактирование объекта
			//cmsRedirect_admin("admin_edit.php?id={$id}");
			
		}
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	if ($action == "update") {
		
		$f = modBlank_getObject($_REQUEST['id']);
		
		cmsPrint("Обновление объекта «{$f['name']}».");
		cmsPrint();
		
		sql::update("cms_blank", "id='%id%'", array("id" => $f['id']), array(
			"status"			=> $_REQUEST['status'],
			"editAdmin"		=> $_SESSION['user']['id'],
			"editDate"		=> time(),
			"name"				=> $_REQUEST['name'],
			"content"			=> $_REQUEST['content']
		));
		
		if (!sql::$errors) {
			
			// Search index
			if (is_callable("modSearch_deleteIndex")) modSearch_deleteIndex("blank", $f['id']);
			if (is_callable("modSearch_createIndex")) modSearch_createIndex("blank", $f['id']);
			
			cmsPrint("Запрос прошел без ошибок.", "success");
			cmsPrint("Объект обновлен.", "success bold");
			
			cmsPrint_actions(array(
				"admin.php" => "Перейти к структуре",
				"admin_edit.php" => "Создать объект",
			));
			
			cmsPrint_close();
			
		}
		
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	if ($action == "delete") {
		
		$f = modBlank_getObject($_REQUEST['id']);
		
		cmsPrint("Удаление объекта «{$f['name']}».");
		cmsPrint();
		
		sql::query("DELETE FROM cms_blank WHERE id='%id%' LIMIT 1", array(
			"id"	=> $f['id']
		));
		
		if (!sql::$errors) {
			
			cmsPrint("Товару присвоен идентификатор «{$id}».");
			cmsPrint();
			
			// Search index
			if (is_callable("modSearch_deleteIndex")) modSearch_deleteIndex("blank", $f['id']);
			if (is_callable("modSearch_createIndex")) modSearch_createIndex("blank", $f['id']);
			
			cmsPrint("Запрос прошел без ошибок.", "success");
			cmsPrint("Объект удален.", "success bold");
			
			// редирект на список
			cmsRedirect_admin("admin.php");
			
		}
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
?>