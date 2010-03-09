<?
include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php"; // это нужно так как при notop не вызывается заголовк html
authorize(); // вызов авторизации


showheader("Управление");

$menu = new Menu();

$menu->add("customers","Заказ&shy;чики");
$menu->add("nzap","Не запу щенные");
$menu->add("zap","Запуски");
$menu->add("conductors","Кондук&shy;торы");
$menu->add("mp","Мастер&shy;платы");
$menu->add("zd","Задел");
$menu->add("pt","Шабло&shy;ны");
$menu->add("todo","ToDo");
$menu->add("logs","Logs");
$menu->add("users","Users");
$menu->add("logout","Выход",false,'/logout.php');

$menu->show();


showfooter();

?>