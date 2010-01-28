<?
// отображает логи

include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php";
authorize(); // вызов авторизации


if (isset($edit) || isset($add) ) {
	echo "<script>window.close();</script>";
} elseif (isset($delete)) {
	// удаление
	$sql = "DELETE FROM logs WHERE id='$delete'";
	mysql_query($sql);
}
else
{
// вывести таблицу
	// sql
	$sql="SELECT *,logs.id FROM logs JOIN (users) ON (users.id=logs.user_id) ".(isset($find)?"WHERE (logs.sqltext LIKE '%$find%') ":"").(isset($order)?"ORDER BY ".$order." ":"ORDER BY logs.logdate DESC ").(isset($all)?"":"LIMIT 20");
	//print $sql;
	$type="logs";
	$cols[id]="ID";
	$cols[logdate]="TS";
	$cols[nik]="User";
	$cols[action]="Action";
	$cols[sqltext]="SQL";
	$del=true;
	$edit=false;
	$addbutton=false;
	$openfunc = "openempty";
	
	include "table.php";
}
?>