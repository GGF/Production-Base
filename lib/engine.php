<?

require  $_SERVER["DOCUMENT_ROOT"]."/lib/config.php";
require  $_SERVER["DOCUMENT_ROOT"]."/lib/core.php";


// перекодируем полученые данные (используются функции из multibyte.php, потому здесь а не в encoding.php вызываем)
// TODO: А нужно ли здесь? Запретил регистрацию глобальных а пост и гет тут всё равно регистрирую
foreach ($_GET as $key => $val) {
	//${$key}=cmsUTF_decode($val); // она сама и массивы перекодирует и проверяет на utf
	${$key}=$val; // просто глобализация
}
foreach ($_POST as $key => $val) {
	//${$key}=cmsUTF_decode($val); // она сама и массивы перекодирует и проверяет на utf
	${$key}=$val; // просто глобализация
}
// TODO: После такой сериализации нет необходимости в сериализации форм

define("MODAUTH_ADMIN", false);// не моё не знаю

// TODO: может вынести в autorize ?
session_start();  //starting session
setCookie(session_name(), session_id(), time() + 60 * 60 * 8, "/"); // 1 рабочий день

?>