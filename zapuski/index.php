<?
include_once $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php"; // это нужно так как при notop не вызывается заголовк html
authorize(); // вызов авторизации


showheader("Управление");

$menu = new Menu();

$menu->add("nzap","Не запущенные");
$menu->add("zap","В производстве");
$menu->add("conductors","Кондук&shy;торы");
//$menu->add("mp","Мастер&shy;платы");
$menu->add("zd","Задел");
$menu->add("pt","Шабло&shy;ны");
$menu->add("plates","Платы",false);
$menu->add("back","Назад",false,'/');

$menu->show();


showfooter();

?>