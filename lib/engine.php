<?

require  $_SERVER[DOCUMENT_ROOT]."/lib/config.php";
require  $_SERVER[DOCUMENT_ROOT]."/lib/core.php";


// запускается - не функция
// перекодируем полученые данные
foreach ($_GET as $key => $val) {
	${$key}=$val;
	if (!is_array($val)) {
		if (is_utf($val)) 
			${$key}=cmsUTF_decode($val);
	}
}
foreach ($_POST as $key => $val) {
	${$key}=$val;
	if (!is_array($val)) {
		if (is_utf($val)) 
			${$key}=cmsUTF_decode($val);
	}
}

define("MODAUTH_ADMIN", false);

session_start();  //starting session
setCookie(session_name(), session_id(), time() + 60 * 60 * 8, "/"); // 1 рабочий день

?>