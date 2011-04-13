<?

require $_SERVER["DOCUMENT_ROOT"] . "/lib/config.php";
require $_SERVER["DOCUMENT_ROOT"] . "/lib/core.php";


// перекодируем полученые данные 
// (используютс€ функции из multibyte.php, потому 
// здесь, а не в encoding.php вызываем)
// TODO: ј нужно ли здесь? «апретил регистрацию глобальных,
//  а пост и гет тут всЄ равно регистрирую
foreach ($_GET as $key => $val) {
    ${$key} = cmsUTF_decode($val); 
    // она сама и массивы перекодирует и провер€ет на utf
}
foreach ($_POST as $key => $val) {
    ${$key} = cmsUTF_decode($val); 
    // она сама и массивы перекодирует и провер€ет на utf
}
// TODO: ѕосле такой сериализации нет необходимости в сериализации форм

define("MODAUTH_ADMIN", false); // не моЄ не знаю
// TODO: может вынести в autorize ?
session_start();  //starting session
setCookie(session_name(), session_id(), time() + 60 * 60 * 8, "/"); 
// 1 рабочий день
?>