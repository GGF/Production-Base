<?
include_once $_SERVER["DOCUMENT_ROOT"]."/lib/sql.php";
authorize();
showheader( "������� ����");

$menu = new Menu();

$menu->add("lanch","�������",false,"/zapuski/");
$menu->add("lanch","������",false,"/customers/");
$menu->add("storage","������",false,"/sklads/");
$menu->add("cp","Admin",true,"/cp/");
$menu->add("wiki","���� ������",false,"http://mppwiki");
$menu->add("docsearch","����&shy;�����",false,"http://igor");
$menu->add("logout","�����",false,'/logout.php');

$menu->show();

showfooter();
?>