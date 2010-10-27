<?
include_once $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php";
authorize();
showheader( "Склады");

$menu = new Menu();


$menuitems=array(
				array(
					type	=>	"himiya",
					text	=>	"Материалы",
					link	=>	"himiya.php",
					picture	=>	"him.gif",
					right =>	true,
				),
				array(
					type	=>	"materials",
					text	=>	"Текстолит",
					link	=>	"materials.php",
					picture	=>	"mater.gif",
					right =>	true,
				),
				array(
					type	=>	"himiya2",
					text	=>	"Лаборатория",
					link	=>	"himiya2.php",
					picture	=>	"him2.gif",
					right =>	true,
				),
				array(
					type	=>	"sverla",
					text	=>	"Сверла 3.0",
					link	=>	"sverla.php",
					picture	=>	"sver.gif",
					right =>	true,
				),
				array(
					type	=>	"halaty",
					text	=>	"Спецодежда",
					link	=>	"halaty.php",
					picture	=>	"halat.gif",
					right =>	true,
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
					right =>	true,
				),
				array(
					type	=>	"nepon",
					text	=>	"Сверла 3.175",
					link	=>	"nepon.php",
					picture	=>	"sver.gif",
					right =>	true,
				),
				array(
					type	=>	"maloc",
					text	=>	"Малоценка",
					link	=>	"maloc.php",
					picture	=>	"none.gif",
					right =>	true,
				),
				array(
					type	=>	"stroy",
					text	=>	"Стройматериалы",
					link	=>	"stroy.php",
					picture	=>	"stroy.gif",
					right =>	true,
				),
				array(
					type	=>	"skzap",
					text	=>	"Запчасти",
					link	=>	"zap.php",
					picture	=>	"none.gif",
					right =>	true,
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