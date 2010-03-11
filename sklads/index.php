<?
include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php";
authorize();
showheader( "Склады");
// тут ссылк ана главное
// дальше собственно текст страницы


$menu = new Menu();

$menu->add("himiya","Ма&shy;те&shy;ри&shy;алы",false,"himiya.php");
$menu->add("materials","Тек&shy;сто&shy;лит",false,"materials/");
$menu->add("himiya2","Ла&shy;бо&shy;ра&shy;то&shy;рия",false,"himiya2/");
$menu->add("sverla","Све&shy;рла 3.0",false,"sverla/");
$menu->add("halaty","Спец&shy;одеж&shy;да",false,"halaty/");
$menu->add_newline();
$menu->add("instr","Осн. сред&shy;ства",false,"instr/");
$menu->add("nepon","Све&shy;рла 3.175",false,"nepon/");
$menu->add("maloc","Ма&shy;ло&shy;цен&shy;ка",false,"maloc/");
$menu->add("stroy","Строй&shy;ма&shy;те&shy;ри&shy;алы",false,"stroy/");
$menu->add("zap","Зап&shy;час&shy;ти ин&shy;стру&shy;мен&shy;ты",false,"zap/");

//$menu->add("logout","Выход",false,'/logout.php');

$menu->show();


showfooter();
?>