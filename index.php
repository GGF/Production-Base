<?
include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php";
authorize();
showheader( "������� ����");
// ��� ����� ��� �������
// ������ ���������� ����� ��������

$menu = new Menu();

$menu->add("lanch","�������",false,"/zapuski/");
$menu->add("storage","������",false,"/sklads/");
$menu->add("wiki","���� ������",false,"http://mppwiki");
$menu->add("docsearch","����&shy;�����",false,"http://igor");
$menu->add("logout","�����",false,'/logout.php');

$menu->show();

showfooter();
?>