<?
include_once $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php"; // ��� ����� ��� ��� ��� notop �� ���������� �������� html
authorize(); // ����� �����������


showheader("����������");

$menu = new Menu();

$menu->add("todo","ToDo");
$menu->add("logs","Logs");
$menu->add("users","Users");
$menu->add("backup","Backup",false,'/lib/modules/backup/admin_dumper.php');
$menu->add("back","�����",false,'/');

$menu->show();


showfooter();

?>