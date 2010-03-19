<?
	
	require($_SERVER[DOCUMENT_ROOT] . "/admin/login.php");
	
	$f = modAuth_getUser($_REQUEST[id]);
	
	$pageName = "Просмотр пользователя " . $f[login];
	require($_SERVER[DOCUMENT_ROOT] . "/admin/blank.php");
	
	print "<div style='padding: 10px'>";
	cmsTemplate_print("/modules/auth/{$f[type]}/admin_print.php", $f);
	print "<div><input type='button' class='submit' onclick='window.open(\"/modules/auth/admin_edit.php?id={$f[id]}\", \"_blank\"); window.close(); return false;' value='Редактировать'></div>";
	print "</div>";
	
?>