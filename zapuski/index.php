<?
include_once $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php"; // это нужно так как при notop не вызывается заголовк html
authorize(); // вызов авторизации


showheader("Управление");

$menu = new Menu();

$menu->add("nzap","Не запу щенные");
$menu->add("zap","Запуски");
$menu->add("conductors","Кондук&shy;торы");
$menu->add("mp","Мастер&shy;платы");
$menu->add("zd","Задел");
$menu->add("pt","Шабло&shy;ны");
$menu->add("back","Назад",false,'/');

$menu->show();


showfooter();

?>