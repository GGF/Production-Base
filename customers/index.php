<?
require $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php"; // это нужно так как при notop не вызывается заголовк html
authorize(); // вызов авторизации


showheader("Управление");

$menu = new Menu();

$menu->add("customers","Заказчики");
$menu->add("orders","Заказы");
$menu->add("tz","ТЗ");
$menu->add("posintz","Позиции ТЗ");
$menu->add("blocks","Блоки",false);
$menu->add("boards","Платы",false);
$menu->add("back","Назад",false,'/');

$menu->show();


showfooter();

?>