<?
$dbname = 'zaomppsklads';
require $_SERVER[DOCUMENT_ROOT]."/lib/engine.php";
authorize();


setcookie('sklad',$sklad);
showheader("����� - $skladname");

$menu = new Menu();

$menuitems=array(
				array(
					type	=>	"ost",
					text	=>	"�����",
					picture	=>	"sclads.gif",
					right =>	true,
				),
				array(
					type	=>	"arc",
					text	=>	"�����",
					picture	=>	"slads_arc.gif",
					right =>	true,
				),
				array(
					type	=>	"movecheck",
					text	=>	"�������� �����",
					picture	=>	"otch.gif",
					right =>	true,
				),
				array(
					type	=>	"trebcheck",
					text	=>	"����������",
					picture	=>	"otch.gif",
					right =>	true,
				),
			);

$sql = "SELECT YEAR(NOW())>(YEAR(sk_".$sklad."_dvizh_arc.ddate)+1) FROM zaomppsklads.sk_".$sklad."_dvizh_arc ORDER BY ddate DESC LIMIT 1";
$rs = sql::fetchOne($sql);
if ($rs==1 || isset($year)) {
	array_push($menuitems,array(
						type	=>	"year",
						text	=>	"�����",
						picture	=>	"slads_arc.gif",
						right =>	true,
					));
}

array_push($menuitems,array(
					type	=>	"back",
					text	=>	"�����",
					link	=>	"/sklads/",
					picture	=>	"backsclads.gif",
				));
$menu->adds($menuitems);
$menu->show();

showfooter();

?>