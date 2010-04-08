<?
require $_SERVER[DOCUMENT_ROOT]."/lib/engine.php";

authorize();
showheader( "Главное меню");

$menu = new Menu();

$menu->add("lanch","Запуски",false,"/zapuski/");
$menu->add("orders","Заказы",false,"/customers/");
$menu->add("storage","Склады",false,"/sklads/");
$menu->add("cp","Управление",true,"/cp/");
$menu->add("wiki","База знаний",false,"http://mppwiki");
$menu->add("docsearch","Документы",false,"http://igor");
$menu->add("help","Помощь",false,"/help/");
$menu->add("logout","Выход",false,'/logout.php');

$menu->show();

showfooter();
?>