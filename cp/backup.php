<?
// управление правами доступа

require $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php";
authorize(); // вызов авторизации
$processing_type=basename (__FILE__,".php");

include $_SERVER["DOCUMENT_ROOT"]."/lib/modules/backup/admin_dumper.php"

?>