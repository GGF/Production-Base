<?

	REQUIRE $_SERVER['DOCUMENT_ROOT'] . "/admin/login.php"; 
	REQUIRE $_SERVER['DOCUMENT_ROOT'] . "/admin/blank.php"; 
	
	$action		= checkString($_REQUEST['action']);
	$_REQUEST	= checkString($_REQUEST);
	
	if (!$_REQUEST['name']) $_REQUEST['name'] = CMS_UNNAMED;
	
	// �������� ������� �����������
	cmsPrint_init();
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	if ($action == "create") {
		
		cmsPrint("�������� ������� �{$_REQUEST['name']}�.");
		
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
			
			cmsPrint("������� �������� ������������� �{$id}�.");
			cmsPrint();
			
			// Search index
			if (is_callable("modSearch_deleteIndex")) modSearch_deleteIndex("blank", $id);
			if (is_callable("modSearch_createIndex")) modSearch_createIndex("blank", $id);
			
			cmsPrint("������ ������ ��� ������.", "success");
			cmsPrint("������ ������.", "success bold");
			
			// �������� �� �������������� �������
			//cmsRedirect_admin("admin_edit.php?id={$id}");
			
		}
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	if ($action == "update") {
		
		$f = modBlank_getObject($_REQUEST['id']);
		
		cmsPrint("���������� ������� �{$f['name']}�.");
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
			
			cmsPrint("������ ������ ��� ������.", "success");
			cmsPrint("������ ��������.", "success bold");
			
			cmsPrint_actions(array(
				"admin.php" => "������� � ���������",
				"admin_edit.php" => "������� ������",
			));
			
			cmsPrint_close();
			
		}
		
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	if ($action == "delete") {
		
		$f = modBlank_getObject($_REQUEST['id']);
		
		cmsPrint("�������� ������� �{$f['name']}�.");
		cmsPrint();
		
		sql::query("DELETE FROM cms_blank WHERE id='%id%' LIMIT 1", array(
			"id"	=> $f['id']
		));
		
		if (!sql::$errors) {
			
			cmsPrint("������ �������� ������������� �{$id}�.");
			cmsPrint();
			
			// Search index
			if (is_callable("modSearch_deleteIndex")) modSearch_deleteIndex("blank", $f['id']);
			if (is_callable("modSearch_createIndex")) modSearch_createIndex("blank", $f['id']);
			
			cmsPrint("������ ������ ��� ������.", "success");
			cmsPrint("������ ������.", "success bold");
			
			// �������� �� ������
			cmsRedirect_admin("admin.php");
			
		}
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
?>