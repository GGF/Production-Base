<?
	
	define("MODAUTH_ADMIN", true);
	
  session_name("osmio");
	session_start();  //starting session
	setCookie(session_name(), session_id(), time() + 60 * 60 * 24, "/"); // 1 день
	
  REQUIRE $_SERVER[DOCUMENT_ROOT] . "/config.php";
  REQUIRE $_SERVER[DOCUMENT_ROOT] . "/core/core.php";
  REQUIRE $_SERVER[DOCUMENT_ROOT] . "/admin/ui.php";
	
	if (!defined("MODAUTH_ADMIN_NOCHECK")) {
		
		// Авторизация
		
		$authState = false;
		$authState = modAuth_authorize($_SESSION[user][login], $_SESSION[user][pass], MODAUTH_PASS_PLAIN, $_SESSION[user][save]);
		
		if ($authState != MODAUTH_STATE_AUTHORIZED || $_SESSION[user][type] != MODAUTH_TYPE_ADMIN) {		
			
			//cmsError("Not authorized");
			//cmsVar($_SESSION);
			//cmsVar($authState);
			
			modAuth_killSession();
			cmsRedirect("/admin/auth.php?uri=" . rawUrlEncode($_SERVER[REQUEST_URI]));
			exit;
			
		} else {
			
			if ($_SESSION[user][login] == "admin") $_SESSION[user][admin][rights] = $_SERVER[modules];
			
			if (substr($_SERVER[REQUEST_URI], 0, 7) != "/admin/") {
				
				$module = explode("/", $_SERVER[REQUEST_URI]);
				$module = $module[2];
				
				if (!$_SESSION[user][admin][rights][$module]) {
					
					//REQUIRE $_SERVER[DOCUMENT_ROOT] . "/admin/engine.php";
					
					cmsDie("Недостаточно прав", "<p>Ваш уровень доступа не позволяет Вам использовать этот модуль.</p>");
					exit;
					
				}
				
			}
			
		}
		
	}
	
?>
