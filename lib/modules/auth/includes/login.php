<?
	
	define("MODAUTH_ADMIN", false);
	
	session_start();  //starting session
	setCookie(session_name(), session_id(), time() + 60 * 60 * 24, "/"); // 1 день
	
?>