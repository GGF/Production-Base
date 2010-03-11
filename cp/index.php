<?
include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php"; // это нужно так как при notop не вызывается заголовк html
authorize(); // вызов авторизации


showheader("Управление");

$menu = new Menu();

$menu->add("todo","ToDo");
$menu->add("logs","Logs");
$menu->add("users","Users");
$menu->add("logout","Выход",false,'/logout.php');

$menu->show();


showfooter();

?>