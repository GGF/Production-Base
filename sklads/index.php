<?
include_once $_SERVER["DOCUMENT_ROOT"]."/lib/sql.php";
authorize();
showheader( "Склады");

$menu = new Menu();

$menu->add("himiya","Материалы",false,"himiya.php");
$menu->add("materials","Текстолит",false,"materials.php");
$menu->add("himiya2","Лаборатория",false,"himiya2.php");
$menu->add("sverla","Сверла 3.0",false,"sverla.php");
$menu->add("halaty","Спецодежда",false,"halaty.php");
$menu->add("back","Назад",false,'/');
$menu->add_newline();
$menu->add("instr","Осн. средства",false,"instr.php");
$menu->add("nepon","Сверла 3.175",false,"nepon.php");
$menu->add("maloc","Малоценка",false,"maloc.php");
$menu->add("stroy","Стройматериалы",false,"stroy.php");
$menu->add("zap","Запчасти",false,"zap.php");
$menu->add("back","Назад",false,'/');

$menu->show();


showfooter();
?>