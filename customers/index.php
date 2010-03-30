<?
include_once $_SERVER["DOCUMENT_ROOT"]."/lib/sql.php"; // это нужно так как при notop не вызывается заголовк html
authorize(); // вызов авторизации


showheader("Управление");

$menu = new Menu();

$menu->add("customers","Заказ&shy;чики");
$menu->add("orders","Заказы");
$menu->add("tz","ТЗ");
$menu->add("posintz","Позиции ТЗ");
$menu->add("blocks","Блоки",false);
$menu->add("boards","Платы",false);
$menu->add("back","Назад",false,'/');

$menu->show();


showfooter();

?>