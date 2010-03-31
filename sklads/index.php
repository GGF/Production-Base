<?
include_once $_SERVER["DOCUMENT_ROOT"]."/lib/sql.php";
authorize();
showheader( "Склады");

$menu = new Menu();


$menuitems=array(
				array(
					type	=>	"himiya",
					text	=>	"Материалы",
					link	=>	"himiya.php",
					picture	=>	"him.gif",
				),
				array(
					type	=>	"materials",
					text	=>	"Текстолит",
					link	=>	"materials.php",
					picture	=>	"mater.gif",
				),
				array(
					type	=>	"himiya2",
					text	=>	"Лаборатория",
					link	=>	"himiya2.php",
					picture	=>	"him2.gif",
				),
				array(
					type	=>	"sverla",
					text	=>	"Сверла 3.0",
					link	=>	"sverla.php",
					picture	=>	"sver.gif",
				),
				array(
					type	=>	"halaty",
					text	=>	"Спецодежда",
					link	=>	"halaty.php",
					picture	=>	"halat.gif",
				),
				array(
					type	=>	"back",
					text	=>	"Назад",
					link	=>	"/",
					picture	=>	"backsclads.gif",
				),
				);
$menu->adds($menuitems);
$menu->add_newline();
$menuitems=array(
				array(
					type	=>	"instr",
					text	=>	"Осн. средства",
					link	=>	"instr.php",
					picture	=>	"instr.gif",
				),
				array(
					type	=>	"nepon",
					text	=>	"Сверла 3.175",
					link	=>	"nepon.php",
					picture	=>	"sver.gif",
				),
				array(
					type	=>	"maloc",
					text	=>	"Малоценка",
					link	=>	"maloc.php",
					picture	=>	"none.gif",
				),
				array(
					type	=>	"stroy",
					text	=>	"Стройматериалы",
					link	=>	"stroy.php",
					picture	=>	"stroy.gif",
				),
				array(
					type	=>	"zap",
					text	=>	"Запчасти",
					link	=>	"zap.php",
					picture	=>	"none.gif",
				),
				array(
					type	=>	"back",
					text	=>	"Назад",
					link	=>	"/",
					picture	=>	"backsclads.gif",
				),
				);
$menu->adds($menuitems);


$menu->show();


showfooter();
?>