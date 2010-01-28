<?
include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php";

if ($action!='add') {
	showheader("Фотошаблоны запуск");
}
	include "inc.php";
if ($action!='add') {
	showfooter();
}
?>