<?
include_once $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php"; // это нужно так как при notop не вызывается заголовк html
authorize(); // вызов авторизации


showheader("Управление");

$menu = new Menu();

$menu->add("todo","ToDo");
$menu->add("logs","Logs");
$menu->add("users","Users");
$menu->add("backup","Backup",false,'/lib/modules/backup/admin_dumper.php');
$menu->add("back","Назад",false,'/');

$menu->show();


showfooter();

?>