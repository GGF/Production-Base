<?
include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php";
authorize();
showheader( "Склады");
// тут ссылк ана главное
// дальше собственно текст страницы


$menu = new Menu();

$menu->add("himiya","Ма&shy;те&shy;ри&shy;алы",false,"himiya.php");
$menu->add("materials","Тек&shy;сто&shy;лит",false,"materials.php");
$menu->add("himiya2","Ла&shy;бо&shy;ра&shy;то&shy;рия",false,"himiya2.php");
$menu->add("sverla","Све&shy;рла 3.0",false,"sverla.php");
$menu->add("halaty","Спец&shy;одеж&shy;да",false,"halaty.php");
$menu->add_newline();
$menu->add("instr","Осн. сред&shy;ства",false,"instr.php");
$menu->add("nepon","Све&shy;рла 3.175",false,"nepon.php");
$menu->add("maloc","Ма&shy;ло&shy;цен&shy;ка",false,"maloc.php");
$menu->add("stroy","Строй&shy;ма&shy;те&shy;ри&shy;алы",false,"stroy.php");
$menu->add("zap","Зап&shy;час&shy;ти",false,"zap.php");

//$menu->add("logout","Выход",false,'/logout.php');

$menu->show();


showfooter();
?>