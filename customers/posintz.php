<?
// создание и редактирование Тех заданий
include_once $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php";
authorize(); // вызов авторизации
$processing_type=basename (__FILE__,".php");
// serialize form
if (isset(${'form_'.$processing_type})) extract(${'form_'.$processing_type});

ob_start();

if (isset($edit)) {
	// не редактируем, пока
} elseif (isset($delete)) 
{
	// удаление
	$sql = "DELETE FROM posintz WHERE id='$delete'";
	sql::query($sql);
	// удаление связей
	echo "ok";
} else 
{
	// список
	if (!empty($_SESSION[tz_id]))
	{
		$tzid = $_SESSION[tz_id];
		$title = "Позиции в ТЗ ".$_SESSION[customer]." - ".$_SESSION[order]." от ".$_SESSION[orderdate]." - #".$_SESSION[tz_id]."";
		$sql = "SELECT file_link,file_link_id FROM tz JOIN filelinks ON filelinks.id=tz.file_link_id WHERE tz.id='$tzid'";
		//echo $sql;
		$rs=sql::fetchOne($sql);
		$title .="<br><a href='".sharefilelink($rs[file_link])."' class='filelink' onclick=\"window.open('tz.php?print=file&flink=".$rs[file_link_id]."');void(0);\">TZ-$tzid</a>";
		
		$sql="SELECT *,posintz.id as posid,posintz.id FROM `posintz` JOIN (plates) ON ( posintz.plate_id = plates.id ) ".(isset($find)?"WHERE (plates.plate LIKE '%$find%')":"").(isset($tzid)?(isset($find)?"AND tz_id='$tzid'":"WHERE tz_id='$tzid'"):"").(!empty($order)?" ORDER BY ".$order." ":" ORDER BY posintz.id DESC ").(isset($all)?"":"LIMIT 20");
	} 
	elseif (true)
	{
		$sql='SELECT * FROM posintz WHERE false';
		$title = "Не выбрано ТЗ";
	}

	$cols[posid]="ID";
	$cols[plate]="Плата";
	$cols[numbers]="Количество";

	$table = new Table($processing_type,$processing_type,$sql,$cols);
	$table->title=$title;
	$table->addbutton=true;
	$table->show();
}

printpage();
?>