<?
include_once $_SERVER["DOCUMENT_ROOT"]."/lib/sql.php";
authorize();
showheader( "Главное меню");

$menu = new Menu();

$menu->add("lanch","Запуски",false,"/zapuski/");
$menu->add("lanch","Заказы",false,"/customers/");
$menu->add("storage","Склады",false,"/sklads/");
$menu->add("cp","Admin",true,"/cp/");
$menu->add("wiki","База знаний",false,"http://mppwiki");
$menu->add("docsearch","Доку&shy;менты",false,"http://igor");
$menu->add("logout","Выход",false,'/logout.php');

$menu->show();

showfooter();
?>