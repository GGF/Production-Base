<?
$dbname = 'zaomppsklads';
include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php";
authorize();
$sklad='him_';
$skladname = "���������";

setcookie('sklad',$sklad);
showheader("����� - $skladname");

$menu = new Menu();

$menu->add("ost","�����",false);
$menu->add("arc","�����",false);
$menu->add("movecheck","���&shy;��&shy;��� ��&shy;���",false);



$sql = "SELECT YEAR(NOW())>(YEAR(sk_".$sklad."_dvizh_arc.ddate)+1) FROM sk_".$sklad."_dvizh_arc ORDER BY ddate DESC LIMIT 1";
$rs = mysql_fetch_array(mysql_query($sql));
if ($rs[0]=='1' || isset($year)) {
	$menu->add("year","�����",false);
}

$menu->add("back","�����",false,"/sklads");
$menu->show();

showfooter();
?>