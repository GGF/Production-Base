<?
include_once $_SERVER["DOCUMENT_ROOT"]."/lib/sql.php";
authorize();
showheader( "������");

$menu = new Menu();


$menuitems=array(
				array(
					type	=>	"himiya",
					text	=>	"���������",
					link	=>	"himiya.php",
					picture	=>	"him.gif",
				),
				array(
					type	=>	"materials",
					text	=>	"���������",
					link	=>	"materials.php",
					picture	=>	"mater.gif",
				),
				array(
					type	=>	"himiya2",
					text	=>	"�����������",
					link	=>	"himiya2.php",
					picture	=>	"him2.gif",
				),
				array(
					type	=>	"sverla",
					text	=>	"������ 3.0",
					link	=>	"sverla.php",
					picture	=>	"sver.gif",
				),
				array(
					type	=>	"halaty",
					text	=>	"����������",
					link	=>	"halaty.php",
					picture	=>	"halat.gif",
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
				),
				array(
					type	=>	"nepon",
					text	=>	"������ 3.175",
					link	=>	"nepon.php",
					picture	=>	"sver.gif",
				),
				array(
					type	=>	"maloc",
					text	=>	"���������",
					link	=>	"maloc.php",
					picture	=>	"none.gif",
				),
				array(
					type	=>	"stroy",
					text	=>	"��������������",
					link	=>	"stroy.php",
					picture	=>	"stroy.gif",
				),
				array(
					type	=>	"zap",
					text	=>	"��������",
					link	=>	"zap.php",
					picture	=>	"none.gif",
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