<?

	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/admin/login.php"; 
	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/admin/engine.php"; 

	cmsHeader($_SERVER[modules][backup]);
	$tabs[] = array("БД: Создать точку", "admin.php", 1);
	$tabs[] = array("БД: Откатить", "admin_restore.php", 0);
	cmsContent($tabs);
	cmsCaption("/modules/backup/images/");
	
	
	$path = "D:/WWW/usr/local/mysql5/bin/";
	$file = $_SERVER[BACKUPS] . "/file.sql.gz";
	
	// dump
	
	$tmp = tempnam("/tmp", "sql");
	
	$cmd = array(
		"{$path}mysqldump",
		"--opt",
		"--lock-tables",
		"--host={$_SERVER[mysql][lang][host]}",
		"--user={$_SERVER[mysql][lang][name]}",
		"--password={$_SERVER[mysql][lang][pass]}",
		"{$_SERVER[mysql][lang][base]}",
		"--result-file={$tmp}",
	);
	
	cmsVar($cmd);
	cmsVar(cmsShell($cmd));
	
	file_put_contents($file, gzencode(file_get_contents($tmp), 9));
	
	unlink($tmp);
	
	
	// restore
	
	
	$tmp = tempnam("/tmp", "sql");
	ob_start();
	readgzfile($file);
	$data = ob_get_clean();
	file_put_contents($tmp, $data);
	
	$cmd = array(
		"{$path}mysql",
		"--host={$_SERVER[mysql][lang][host]}",
		"--user={$_SERVER[mysql][lang][name]}",
		"--password={$_SERVER[mysql][lang][pass]}",
		"{$_SERVER[mysql][lang][base]}",
		"< {$tmp}",
	);

	cmsVar($cmd);
	cmsVar(cmsShell($cmd));
	
	unlink($tmp);
	
	cmsFooter();

?>