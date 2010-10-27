<?
// отображает логи
// TODO: Переделать логи - сейчас показывает старые, и соответственно не работает вообще - не ведуться
include_once $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php";
authorize(); // вызов авторизации
$processing_type=basename (__FILE__,".php");
// serialize form
if (isset(${'form_'.$processing_type})) extract(${'form_'.$processing_type});

if (isset($edit) || isset($add) ) {
	echo "<script>window.close();</script>";
} 
elseif (isset($delete)) 
{
	// удаление
	$sql = "DELETE FROM logs WHERE id='$delete'";
	mysql_query($sql);
	echo "ok";
}
else
{
// вывести таблицу
	// sql
	$sql="SELECT *,logs.id as logid FROM logs JOIN (users) ON (users.id=logs.user_id) ".(isset($find)?"WHERE (logs.sqltext LIKE '%$find%') ":"").(isset($order)?"ORDER BY ".$order." ":"ORDER BY logs.logdate DESC ").(isset($all)?"":"LIMIT 20");
	//print $sql;

	$cols[logid]="ID";
	$cols[logdate]="TS";
	$cols[nik]="User";
	$cols[action]="Action";
	$cols[sqltext]="SQL";

	
	$table = new SqlTable($processing_type,"",$sql,$cols);
	$table->show();
	
}
?>