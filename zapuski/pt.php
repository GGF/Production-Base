<?
// ���������� ���������

require $_SERVER["DOCUMENT_ROOT"]."/lib/sql.php";
authorize(); // ����� �����������
$processing_type=basename (__FILE__,".php");

if (isset($edit) || isset($add) ) {

} elseif (isset($delete)) {
	// ��������
	$sql = "DELETE FROM phototemplates WHERE id='$delete'";
	sql::query($sql);
	sql::error(true);
	echo "ok";
}
else
{
// ������� �������
	// sql
	$sql="SELECT *,unix_timestamp(ts) AS uts,phototemplates.id AS ptid FROM phototemplates JOIN users ON phototemplates.user_id=users.id ".(isset($find)?"WHERE filenames LIKE '%$find%'":"").(!empty($order)?"ORDER BY ".$order." ":"ORDER BY ts DESC ").(isset($all)?"LIMIT 50":"LIMIT 20");
	//echo $sql;
	$cols[ptid]="ID";
	$cols[ts]="����";
	$cols[nik]="��� ��������";
	$cols[filenames]="����� � �������";
	
	$table = new Table($processing_type,$processing_type,$sql,$cols);
	$table->show();
}
?>