<?
require $_SERVER[DOCUMENT_ROOT]."/lib/engine.php";

authorize();
showheader( "������� ����");

$menu = new Menu();

$menu->add("lanch","�������",false,"/zapuski/");
$menu->add("orders","������",false,"/customers/");
$menu->add("storage","������",false,"/sklads/");
$menu->add("cp","����������",true,"/cp/");
$menu->add("wiki","���� ������",false,"http://mppwiki");
$menu->add("docsearch","���������",false,"http://igor");
$menu->add("help","������",false,"/help/");
$menu->add("logout","�����",false,'/logout.php');

$menu->show();

showfooter();
?>