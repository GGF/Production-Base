<?
include_once $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php"; // ��� ����� ��� ��� ��� notop �� ���������� �������� html
authorize(); // ����� �����������


showheader("����������");

$menu = new Menu();

$menu->add("nzap","�� ����������");
$menu->add("zap","� ������������");
$menu->add("conductors","������&shy;����");
//$menu->add("mp","������&shy;�����");
$menu->add("zd","�����");
$menu->add("pt","�����&shy;��");
$menu->add("plates","�����",false);
$menu->add("back","�����",false,'/');

$menu->show();


showfooter();

?>