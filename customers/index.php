<?
include_once $_SERVER["DOCUMENT_ROOT"]."/lib/sql.php"; // ��� ����� ��� ��� ��� notop �� ���������� �������� html
authorize(); // ����� �����������


showheader("����������");

$menu = new Menu();

$menu->add("customers","�����&shy;����");
$menu->add("orders","������");
$menu->add("tz","��");
$menu->add("posintz","������� ��");
$menu->add("blocks","�����",false);
$menu->add("boards","�����",false);
$menu->add("back","�����",false,'/');

$menu->show();


showfooter();

?>