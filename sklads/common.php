<?
$dbname = 'zaomppsklads';
include_once $_SERVER["DOCUMENT_ROOT"]."/lib/sql.php";
authorize();


setcookie('sklad',$sklad);
showheader("����� - $skladname");

$menu = new Menu();

$menuitems=array(
				array(
					type	=>	"ost",
					text	=>	"�����",
					picture	=>	"sclads.gif",
				),
				array(
					type	=>	"arc",
					text	=>	"�����",
					picture	=>	"slads_arc.gif",
				),
				array(
					type	=>	"movecheck",
					text	=>	"�������� �����",
					picture	=>	"otch.gif",
				),
				array(
					type	=>	"trebcheck",
					text	=>	"����������",
					picture	=>	"otch.gif",
				),
			);

$sql = "SELECT YEAR(NOW())>(YEAR(sk_".$sklad."_dvizh_arc.ddate)+1) FROM zaomppsklads.sk_".$sklad."_dvizh_arc ORDER BY ddate DESC LIMIT 1";
$rs = sqlShared::fetchOne($sql);
if ($rs==1 || isset($year)) {
	array_push($menuitems,array(
						type	=>	"year",
						text	=>	"�����",
						picture	=>	"slads_arc.gif",
					));
}

array_push($menuitems,array(
					type	=>	"back",
					text	=>	"�����",
					link	=>	"/",
					picture	=>	"backsclads.gif",
				));
$menu->adds($menuitems);
$menu->show();

showfooter();

?>