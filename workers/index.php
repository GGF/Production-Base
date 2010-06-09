<?
include_once $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php"; // это нужно так как при notop не вызывается заголовк html
authorize(); // вызов авторизации


showheader("Управление");

$menu = new Menu();

$menu->add("workers","Работники",false);

$menu->add("back","Назад",false,'/');

$menu->show();


showfooter();

?>