<?
/*
 * ѕоказывает архив склада
 * 
 * ѕараметры $_GET передаютс€ посредством вызова AJAX
 * $delete - индекс удал€емой записи 
 * $edit - индекс редактируемой записи, если 0 то добавл€етс€
 * $all - выводить все записи
 * $find - строка поиска
 * $order - им€ пол€ сортировки, возможно с добавлением DESC - обратного пор€дка
 * 
 * ќб€зательно перекодируютс€ в cp1251 в engine (особенно важно дл€ $find)
 *  и сериализуютс€, то есть перевод€тс€ в глобальные по имени
 * ѕоэтому обращать внимание на предупреждени€ не следует 
 */

$db = '`zaomppsklads`.';
require $_SERVER[DOCUMENT_ROOT]."/lib/engine.php";
authorize();
$sklad = $_COOKIE["sklad"];
$processing_type=basename (__FILE__,".php");
ob_start();


if (isset($delete)) {
	// не удалетс€
} 
elseif (isset($edit))
{
	include 'arcdvizh.php';
} 
else
{
// вывести таблицу
	

	$sql="SELECT *,if((krost>ost),'<span style=\'color:red\'><b>мало</b></span>','') as malo,sk_arc_".$sklad."_spr.id FROM ".$db."`sk_arc_".$sklad."_spr` JOIN ".$db."sk_arc_".$sklad."_ost ON sk_arc_".$sklad."_ost.spr_id=sk_arc_".$sklad."_spr.id WHERE nazv!='' ".(!empty($find)?"AND nazv LIKE '%".$find."%' ":"").(!empty($order)?"ORDER BY ".$order." ":"ORDER BY nazv ").(isset($all)?"":"LIMIT 20");
	//echo $sql;
	
	$cols[nazv]="Ќазвание";
	$cols[edizm]="≈д.»зм.";
	$cols[ost]="ќстаток на складе";
	$cols[krost]=" рит. кол-во";
	$cols[malo]="¬нимание";

	
	$table = new Table("arc","arcdvizh",$sql,$cols,false);
	$table->del= false;
	$table->edit= false;
	$table->show();
}

printpage();
?>