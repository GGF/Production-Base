<?
require $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php"; // ��� ����� ��� ��� ��� notop �� ���������� �������� html
authorize(); // ����� �����������


showheader("����������");

$menu = new Menu();

$menu->add("customers","���������");
$menu->add("orders","������");
$menu->add("tz","��");
$menu->add("posintz","������� ��");
$menu->add("blocks","�����",false);
$menu->add("boards","�����",false);
$menu->add("back","�����",false,'/');

$menu->show();


showfooter();

?>