<?
include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php";
authorize();
showheader( "Главное меню");
// тут ссылк ана главное
// дальше собственно текст страницы

$menu = new Menu();

$menu->add("lanch","Запуски",false,"/zapuski/");
$menu->add("storage","Склады",false,"/sklads/");
$menu->add("wiki","База знаний",false,"http://mppwiki");
$menu->add("docsearch","Доку&shy;менты",false,"http://igor");
$menu->add("logout","Выход",false,'/logout.php');

$menu->show();

showfooter();
?>