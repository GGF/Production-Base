<?
include_once $_SERVER["DOCUMENT_ROOT"]."/lib/sql.php"; // ��� ����� ��� ��� ��� notop �� ���������� �������� html
authorize(); // ����� �����������


showheader("����������");

$menu = new Menu();

$menu->add("todo","ToDo");
$menu->add("logs","Logs");
$menu->add("users","Users");
$menu->add("backup","Backup",false,'/lib/modules/backup/admin_dumper.php');
$menu->add("logout","�����",false,'/logout.php');

$menu->show();


showfooter();

?>