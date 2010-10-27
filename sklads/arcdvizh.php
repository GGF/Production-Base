<?
/*
 * ѕоказывает движени€ архива склада
 * 
 * ѕараметры $_GET передаютс€ посредством вызова AJAX
 * $id - индекс записи движени€ которой нужно показать
 * $delete - индекс удал€емой записи 
 * $edit - индекс редактируемой записи, если 0 то добавл€етс€
 * $all - выводить все записи
 * $find - строка поиска
 * $order - им€ пол€ сортировки, возможно с добавлением DESC - обратного пор€дка
 * 
 * ќб€зательно перекодируютс€ в cp1251 в engine (особенно важно дл€ $find)
 * и сериализуютс€, то есть перевод€тс€ в глобальные по имени
 * ѕоэтому обращать внимание на предупреждени€ не следует, использовать можно по имени
 * ј вот isset провер€ть именно $_GET[им€] 
 */

include_once $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php";
authorize();

$db = '`zaomppsklads`.';
$sklad = $_COOKIE["sklad"];

ob_start();

if(!empty($id)) $spr_id=$id;

if (isset($delete)) {
	// из архива не удал€ем
} 
elseif (isset($_GET[edit])) {
	// не редактируем
}
else
{
	// вывести таблицу

	$sql="SELECT *,sk_arc_".$sklad."_dvizh.id FROM ".$db."sk_arc_".$sklad."_dvizh JOIN (".$db."sk_".$sklad."_postav,".$db."coments) ON (sk_".$sklad."_postav.id=sk_arc_".$sklad."_dvizh.post_id AND coments.id=sk_arc_".$sklad."_dvizh.comment_id) WHERE spr_id='$spr_id'".(!empty($find)?"AND comment LIKE '%".$find."%' OR supply LIKE '%".$find."%' OR numd LIKE '%".$find."%'":"").(!empty($order)?"ORDER BY ".$order." ":"ORDER BY ddate DESC ").((isset($_GET[all]))?"":"LIMIT 20");
	//echo $sql;

	$cols[ddate]="ƒата";
	$cols[prras]="+/-";
	$cols[numd]="є док.";
	$cols[supply]="ѕоставщик";
	$cols[quant]=" ол-во";
	$cols[comment]="ѕримечание";
	$cols[price]="÷ена";

	
	$table = new SqlTable("arcdvizh","",$sql,$cols,false);
	$table->del= false;
	$table->edit= false;
	if (isset($spr_id)) $table->idstr = "&spr_id=$spr_id";
	$table->show();
	
}
printpage();
?>
