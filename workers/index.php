<?
include_once $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php"; // ��� ����� ��� ��� ��� notop �� ���������� �������� html
authorize(); // ����� �����������


showheader("����������");

$menu = new Menu();

$menu->add("workers","���������",false);

$menu->add("back","�����",false,'/');

$menu->show();


showfooter();

?>