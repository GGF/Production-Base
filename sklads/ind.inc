<?
$dbname = 'zaomppsklads';
include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php";
authorize();

showheader("Склад - $skladname");

$menu = new Menu();

$menu->add("ost","Склад",false,"?");
$menu->add("arc","Архив",false,"?arc");
$menu->add("dviz","Дви&shy;же&shy;ние от&shy;чет",false,"?o1");



$sql = "SELECT YEAR(NOW())>(YEAR(sk_".$sklad."_dvizh_arc.ddate)+1) FROM sk_".$sklad."_dvizh_arc ORDER BY ddate DESC LIMIT 1";
$rs = mysql_fetch_array(mysql_query($sql));
if ($rs[0]=='1' || isset($year)) {
	$menu->add("year","Сжать",false,"?year");
}

$menu->add("back","Назад",false,"/sklads");
$menu->show();

ob_start();

if (isset($o1)) {
	include "o1.php";
} elseif (isset($o2)) {
	include "o2.php";
} elseif (isset($o3)) {
	include "o3.php";
} elseif (isset($arc)) {
	include "arc.php";
} elseif (isset($year)) {
	include "year.php";
} else {
	include "ost.php";
}
$buffer=ob_get_contents ( );
ob_end_clean();

showfooter($buffer);

?>