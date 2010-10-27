<?
$dbname = 'zaomppsklads';
require $_SERVER[DOCUMENT_ROOT]."/lib/engine.php";
authorize();


setcookie('sklad',$sklad);
showheader("Склад - $skladname");

$menu = new Menu();

$menuitems=array(
				array(
					type	=>	"ost",
					text	=>	"Склад",
					picture	=>	"sclads.gif",
					right =>	true,
				),
				array(
					type	=>	"arc",
					text	=>	"Архив",
					picture	=>	"slads_arc.gif",
					right =>	true,
				),
				array(
					type	=>	"movecheck",
					text	=>	"Движение отчет",
					picture	=>	"otch.gif",
					right =>	true,
				),
				array(
					type	=>	"trebcheck",
					text	=>	"Требования",
					picture	=>	"otch.gif",
					right =>	true,
				),
			);

$sql = "SELECT YEAR(NOW())>(YEAR(sk_".$sklad."_dvizh_arc.ddate)+1) FROM zaomppsklads.sk_".$sklad."_dvizh_arc ORDER BY ddate DESC LIMIT 1";
$rs = sql::fetchOne($sql);
if ($rs==1 || isset($year)) {
	array_push($menuitems,array(
						type	=>	"year",
						text	=>	"Сжать",
						picture	=>	"slads_arc.gif",
						right =>	true,
					));
}

array_push($menuitems,array(
					type	=>	"back",
					text	=>	"Назад",
					link	=>	"/sklads/",
					picture	=>	"backsclads.gif",
				));
$menu->adds($menuitems);
$menu->show();

showfooter();

?>