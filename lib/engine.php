<?

require  $_SERVER[DOCUMENT_ROOT]."/lib/config.php";
require  $_SERVER[DOCUMENT_ROOT]."/lib/core.php";


// запускается - не функция
// перекодируем полученые данные
foreach ($_GET as $key => $val) {
	${$key}=$val;
	if (!is_array($val)) {
		if (mb_detect_encoding($val)=="UTF-8") 
			${$key}=mb_convert_encoding($val,"cp1251","UTF-8");
	}
	//echo mb_detect_encoding($val)."=".$key."=>".$val."==>".${$key};
}
foreach ($_POST as $key => $val) {
	${$key}=$val;
	if (!is_array($val)) {
		if (mb_detect_encoding($val)=="UTF-8") 
			${$key}=mb_convert_encoding($val,"cp1251","UTF-8");
	}
	//echo mb_detect_encoding($val)."=".$key."=>".$val."==>".${$key};
}

define("MODAUTH_ADMIN", false);

session_start();  //starting session
setCookie(session_name(), session_id(), time() + 60 * 60 * 8, "/"); // 1 рабочий день

?>