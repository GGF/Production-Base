<?
include_once $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php";
authorize();
showheader( "������");

$menu = new Menu();


$menuitems=array(
				array(
					type	=>	"himiya",
					text	=>	"���������",
					link	=>	"himiya.php",
					picture	=>	"him.gif",
					right =>	true,
				),
				array(
					type	=>	"materials",
					text	=>	"���������",
					link	=>	"materials.php",
					picture	=>	"mater.gif",
					right =>	true,
				),
				array(
					type	=>	"himiya2",
					text	=>	"�����������",
					link	=>	"himiya2.php",
					picture	=>	"him2.gif",
					right =>	true,
				),
				array(
					type	=>	"sverla",
					text	=>	"������ 3.0",
					link	=>	"sverla.php",
					picture	=>	"sver.gif",
					right =>	true,
				),
				array(
					type	=>	"halaty",
					text	=>	"����������",
					link	=>	"halaty.php",
					picture	=>	"halat.gif",
					right =>	true,
				),
				array(
					type	=>	"back",
					text	=>	"�����",
					link	=>	"/",
					picture	=>	"backsclads.gif",
				),
				);
$menu->adds($menuitems);
$menu->add_newline();
$menuitems=array(
				array(
					type	=>	"instr",
					text	=>	"���. ��������",
					link	=>	"instr.php",
					picture	=>	"instr.gif",
					right =>	true,
				),
				array(
					type	=>	"nepon",
					text	=>	"������ 3.175",
					link	=>	"nepon.php",
					picture	=>	"sver.gif",
					right =>	true,
				),
				array(
					type	=>	"maloc",
					text	=>	"���������",
					link	=>	"maloc.php",
					picture	=>	"none.gif",
					right =>	true,
				),
				array(
					type	=>	"stroy",
					text	=>	"��������������",
					link	=>	"stroy.php",
					picture	=>	"stroy.gif",
					right =>	true,
				),
				array(
					type	=>	"skzap",
					text	=>	"��������",
					link	=>	"zap.php",
					picture	=>	"none.gif",
					right =>	true,
				),
				array(
					type	=>	"back",
					text	=>	"�����",
					link	=>	"/",
					picture	=>	"backsclads.gif",
				),
				);
$menu->adds($menuitems);


$menu->show();


showfooter();
?>