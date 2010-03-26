<?
$dbname = 'zaomppsklads';
include_once $_SERVER["DOCUMENT_ROOT"]."/lib/sql.php";
authorize();


setcookie('sklad',$sklad);
showheader("Склад - $skladname");

$menu = new Menu();

$menu->add("ost","Склад",false);
$menu->add("arc","Архив",false);
$menu->add("movecheck","Дви&shy;же&shy;ние от&shy;чет",false);
$menu->add("trebcheck","Тре&shy;бо&shy;ва&shy;ния",false);



$sql = "SELECT YEAR(NOW())>(YEAR(sk_".$sklad."_dvizh_arc.ddate)+1) FROM zaomppsklads.sk_".$sklad."_dvizh_arc ORDER BY ddate DESC LIMIT 1";
$rs = sqlShared::fetchOne($sql);
if ($rs==1 || isset($year)) {
	$menu->add("year","Сжать",false);
}

$menu->add("back","Назад",false,"/sklads");
$menu->show();

showfooter();

?>