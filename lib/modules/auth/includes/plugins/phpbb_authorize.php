<?
	
	require($_SERVER['DOCUMENT_ROOT'] . "/config.php");
	
	// Подключаем API
	define('IN_PHPBB', true);
	$phpbb_root_path = $_SERVER[modAuth][plugin][forum] . "/";
	$phpEx = substr(strrchr(__FILE__, '.'), 1);
	
	require($phpbb_root_path . 'common.' . $phpEx);
	
	$l = $auth->login($_REQUEST[login], $_REQUEST[pass], false, false, $_REQUEST[admin]);
	
	print $l[status];
	
?>