<?
include_once $_SERVER["DOCUMENT_ROOT"]."/lib/sql.php";
authorize();
showheader( "������");

$menu = new Menu();

$menu->add("himiya","���������",false,"himiya.php");
$menu->add("materials","���������",false,"materials.php");
$menu->add("himiya2","�����������",false,"himiya2.php");
$menu->add("sverla","������ 3.0",false,"sverla.php");
$menu->add("halaty","����������",false,"halaty.php");
$menu->add("back","�����",false,'/');
$menu->add_newline();
$menu->add("instr","���. ��������",false,"instr.php");
$menu->add("nepon","������ 3.175",false,"nepon.php");
$menu->add("maloc","���������",false,"maloc.php");
$menu->add("stroy","��������������",false,"stroy.php");
$menu->add("zap","��������",false,"zap.php");
$menu->add("back","�����",false,'/');

$menu->show();


showfooter();
?>