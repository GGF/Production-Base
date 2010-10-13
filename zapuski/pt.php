<?
// управление шаблонами

require $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php";
authorize(); // вызов авторизации
$processing_type=basename (__FILE__,".php");
// serialize form
if (isset(${'form_'.$processing_type})) extract(${'form_'.$processing_type});

if (isset($edit)) {
	// ничего
} elseif (isset($delete)) {
	// удаление
	$sql = "DELETE FROM phototemplates WHERE id='$delete'";
	sql::query($sql);
	sql::error(true);
	echo "ok";
}
else
{
// вывести таблицу
	// sql
	$sql="SELECT *,unix_timestamp(ts) AS uts,phototemplates.id AS ptid,phototemplates.id FROM phototemplates JOIN users ON phototemplates.user_id=users.id ".(isset($find)?"WHERE filenames LIKE '%$find%'":"").(!empty($order)?"ORDER BY ".$order." ":"ORDER BY ts DESC ").(isset($all)?"LIMIT 50":"LIMIT 20");
	//echo $sql;
	$cols[ptid]="ID";
	$cols[ts]="Дата";
	$cols[nik]="Кто запустил";
	$cols[filenames]="Колво и Каталог";
	
	$table = new Table($processing_type,$processing_type,$sql,$cols);
	$table->show();
}
?>