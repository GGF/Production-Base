<?
$dbname = 'zaomppsklads';
include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php";
authorize();
$sklad = $_COOKIE["sklad"];

if(isset($id)) $spr_id=$id;

if (isset($delete)) {
	// из архива не удаляем
} 
elseif (isset($edit)) {
	// не редактируем
}
else
{
	// вывести таблицу

	$sql="SELECT *,sk_arc_".$sklad."_dvizh.id FROM sk_arc_".$sklad."_dvizh JOIN (sk_".$sklad."_postav,coments) ON (sk_".$sklad."_postav.id=sk_arc_".$sklad."_dvizh.post_id AND coments.id=sk_arc_".$sklad."_dvizh.comment_id) WHERE spr_id='$spr_id'".(isset($find)?"AND comment LIKE '%$find%' OR supply LIKE '%$find%' OR numd LIKE '%$find%'":"").($order!=''?"ORDER BY ".$order." ":"ORDER BY ddate DESC ").((isset($all))?"":"LIMIT 20");
	//echo $sql;

	$cols[ddate]="Дата";
	$cols[prras]="+/-";
	$cols[numd]="№ док.";
	$cols[supply]="Поставщик";
	$cols[quant]="Кол-во";
	$cols[comment]="Примечание";
	$cols[price]="Цена";

	
	$table = new Table("arcdvizh","",$sql,$cols,false);
	$table->del= false;
	$table->edit= false;
	if (isset($spr_id)) $table->idstr = "&spr_id=$spr_id";
	$table->show();
	
}
?>