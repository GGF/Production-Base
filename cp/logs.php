<?
// ���������� ����

include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php";
authorize(); // ����� �����������


if (isset($edit) || isset($add) ) {
	echo "<script>window.close();</script>";
} 
elseif (isset($delete)) 
{
	// ��������
	$sql = "DELETE FROM logs WHERE id='$delete'";
	mysql_query($sql);
	echo "ok";
}
else
{
// ������� �������
	// sql
	$sql="SELECT *,logs.id FROM logs JOIN (users) ON (users.id=logs.user_id) ".(isset($find)?"WHERE (logs.sqltext LIKE '%$find%') ":"").(isset($order)?"ORDER BY ".$order." ":"ORDER BY logs.logdate DESC ").(isset($all)?"":"LIMIT 20");
	//print $sql;

	$cols[id]="ID";
	$cols[logdate]="TS";
	$cols[nik]="User";
	$cols[action]="Action";
	$cols[sqltext]="SQL";

	
	$table = new Table("logs","",$sql,$cols);
	$table->show();
	
}
?>