<?
/*
 * Показывает движения архива склада
 * 
 * Параметры $_GET передаются посредством вызова AJAX
 * $id - индекс записи движения которой нужно показать
 * $delete - индекс удаляемой записи 
 * $edit - индекс редактируемой записи, если 0 то добавляется
 * $all - выводить все записи
 * $find - строка поиска
 * $order - имя поля сортировки, возможно с добавлением DESC - обратного порядка
 * 
 * Обязательно перекодируются в cp1251 в engine (особенно важно для $find)
 * и сериализуются, то есть переводятся в глобальные по имени
 * Поэтому обращать внимание на предупреждения не следует, использовать можно по имени
 * А вот isset проверять именно $_GET[имя] 
 */

include_once $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php";
authorize();

$db = '`zaomppsklads`.';
$sklad = $_COOKIE["sklad"];

ob_start();

if(!empty($id)) $spr_id=$id;

if (isset($delete)) {
	// из архива не удаляем
} 
elseif (isset($_GET[edit])) {
	// не редактируем
}
else
{
	// вывести таблицу

	$sql="SELECT *,sk_arc_".$sklad."_dvizh.id FROM ".$db."sk_arc_".$sklad."_dvizh JOIN (".$db."sk_".$sklad."_postav,".$db."coments) ON (sk_".$sklad."_postav.id=sk_arc_".$sklad."_dvizh.post_id AND coments.id=sk_arc_".$sklad."_dvizh.comment_id) WHERE spr_id='$spr_id'".(!empty($find)?"AND comment LIKE '%".$find."%' OR supply LIKE '%".$find."%' OR numd LIKE '%".$find."%'":"").(!empty($order)?"ORDER BY ".$order." ":"ORDER BY ddate DESC ").((isset($_GET[all]))?"":"LIMIT 20");
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
printpage();
?>
