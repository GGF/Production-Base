<?
include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php"; // ��� ����� ��� ��� ��� notop �� ���������� �������� html
authorize(); // ����� �����������


showheader("����������");

$menu = new Menu();

$menu->add("todo","ToDo");
$menu->add("logs","Logs");
$menu->add("users","Users");
$menu->add("logout","�����",false,'/logout.php');

$menu->show();


showfooter();

?>