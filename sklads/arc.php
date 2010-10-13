<?
/*
 * Показывает архив склада
 * 
 * Параметры $_GET передаются посредством вызова AJAX
 * $delete - индекс удаляемой записи 
 * $edit - индекс редактируемой записи, если 0 то добавляется
 * $all - выводить все записи
 * $find - строка поиска
 * $order - имя поля сортировки, возможно с добавлением DESC - обратного порядка
 * 
 * Обязательно перекодируются в cp1251 в engine (особенно важно для $find)
 *  и сериализуются, то есть переводятся в глобальные по имени
 * Поэтому обращать внимание на предупреждения не следует 
 */

$db = '`zaomppsklads`.';
require $_SERVER[DOCUMENT_ROOT]."/lib/engine.php";
authorize();
$sklad = $_COOKIE["sklad"];
$processing_type=basename (__FILE__,".php");
ob_start();


if (isset($delete)) {
	// не удалется
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
	
	$cols[nazv]="Название";
	$cols[edizm]="Ед.Изм.";
	$cols[ost]="Остаток на складе";
	$cols[krost]="Крит. кол-во";
	$cols[malo]="Внимание";

	
	$table = new Table("arc","arcdvizh",$sql,$cols,false);
	$table->del= false;
	$table->edit= false;
	$table->show();
}

printpage();
?>