<?
include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php";
authorize();
showheader( "������");
// ��� ����� ��� �������
// ������ ���������� ����� ��������


$menu = new Menu();

$menu->add("himiya","��&shy;��&shy;��&shy;���",false,"himiya.php");
$menu->add("materials","���&shy;���&shy;���",false,"materials/");
$menu->add("himiya2","��&shy;��&shy;��&shy;��&shy;���",false,"himiya2/");
$menu->add("sverla","���&shy;��� 3.0",false,"sverla/");
$menu->add("halaty","����&shy;����&shy;��",false,"halaty/");
$menu->add_newline();
$menu->add("instr","���. ����&shy;����",false,"instr/");
$menu->add("nepon","���&shy;��� 3.175",false,"nepon/");
$menu->add("maloc","��&shy;��&shy;���&shy;��",false,"maloc/");
$menu->add("stroy","�����&shy;��&shy;��&shy;��&shy;���",false,"stroy/");
$menu->add("zap","���&shy;���&shy;�� ��&shy;����&shy;���&shy;��",false,"zap/");

//$menu->add("logout","�����",false,'/logout.php');

$menu->show();


showfooter();
?>