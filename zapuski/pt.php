<?
// ���������� ���������

include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php";
authorize(); // ����� �����������


if (isset($edit) || isset($add) ) {

} elseif (isset($delete)) {
	// ��������
	$sql = "DELETE FROM phototemplates WHERE id='$delete'";
	mylog('phototemplates',$delete,'DELETE');
	mysql_query($sql);
	echo "ok";
}
else
{
// ������� �������
	// sql
	$sql="SELECT *,unix_timestamp(ts) AS uts FROM phototemplates JOIN users ON phototemplates.user_id=users.id ".(isset($find)?"WHERE filenames LIKE '%$find%'":"").($order!=''?"ORDER BY ".$order." ":"ORDER BY ts DESC ").(isset($all)?"LIMIT 50":"LIMIT 20");
	
	$cols[id]="ID";
	$cols[ts]="����";
	$cols[nik]="��� ��������";
	$cols[filenames]="����� � �������";
	
	$table = new Table("pt","pt",$sql,$cols);
	$table->show();
}
?>