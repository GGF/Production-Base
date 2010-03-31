<?
$dbname = 'zaomppsklads';
include_once $_SERVER["DOCUMENT_ROOT"]."/lib/sql.php";
authorize();


setcookie('sklad',$sklad);
showheader("Склад - $skladname");

$menu = new Menu();

$menuitems=array(
				array(
					type	=>	"ost",
					text	=>	"Склад",
					picture	=>	"sclads.gif",
				),
				array(
					type	=>	"arc",
					text	=>	"Архив",
					picture	=>	"slads_arc.gif",
				),
				array(
					type	=>	"movecheck",
					text	=>	"Движение отчет",
					picture	=>	"otch.gif",
				),
				array(
					type	=>	"trebcheck",
					text	=>	"Требования",
					picture	=>	"otch.gif",
				),
			);

$sql = "SELECT YEAR(NOW())>(YEAR(sk_".$sklad."_dvizh_arc.ddate)+1) FROM zaomppsklads.sk_".$sklad."_dvizh_arc ORDER BY ddate DESC LIMIT 1";
$rs = sqlShared::fetchOne($sql);
if ($rs==1 || isset($year)) {
	array_push($menuitems,array(
						type	=>	"year",
						text	=>	"Сжать",
						picture	=>	"slads_arc.gif",
					));
}

array_push($menuitems,array(
					type	=>	"back",
					text	=>	"Назад",
					link	=>	"/",
					picture	=>	"backsclads.gif",
				));
$menu->adds($menuitems);
$menu->show();

showfooter();

?>