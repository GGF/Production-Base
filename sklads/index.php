<?
include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php";
authorize();
showheader( "������");
// ��� ����� ��� �������
// ������ ���������� ����� ��������


$menu = new Menu();

$menu->add("himiya","��&shy;��&shy;��&shy;���",false,"himiya.php");
$menu->add("materials","���&shy;���&shy;���",false,"materials.php");
$menu->add("himiya2","��&shy;��&shy;��&shy;��&shy;���",false,"himiya2.php");
$menu->add("sverla","���&shy;��� 3.0",false,"sverla.php");
$menu->add("halaty","����&shy;����&shy;��",false,"halaty.php");
$menu->add_newline();
$menu->add("instr","���. ����&shy;����",false,"instr.php");
$menu->add("nepon","���&shy;��� 3.175",false,"nepon.php");
$menu->add("maloc","��&shy;��&shy;���&shy;��",false,"maloc.php");
$menu->add("stroy","�����&shy;��&shy;��&shy;��&shy;���",false,"stroy.php");
$menu->add("zap","���&shy;���&shy;��",false,"zap.php");

//$menu->add("logout","�����",false,'/logout.php');

$menu->show();


showfooter();
?>