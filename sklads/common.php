<?
$dbname = 'zaomppsklads';
include_once $_SERVER["DOCUMENT_ROOT"]."/lib/sql.php";
authorize();


setcookie('sklad',$sklad);
showheader("����� - $skladname");

$menu = new Menu();

$menu->add("ost","�����",false);
$menu->add("arc","�����",false);
$menu->add("movecheck","���&shy;��&shy;��� ��&shy;���",false);
$menu->add("trebcheck","���&shy;��&shy;��&shy;���",false);



$sql = "SELECT YEAR(NOW())>(YEAR(sk_".$sklad."_dvizh_arc.ddate)+1) FROM zaomppsklads.sk_".$sklad."_dvizh_arc ORDER BY ddate DESC LIMIT 1";
$rs = sqlShared::fetchOne($sql);
if ($rs==1 || isset($year)) {
	$menu->add("year","�����",false);
}

$menu->add("back","�����",false,"/sklads");
$menu->show();

showfooter();

?>