<?
// ���������� ���������

require $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php";
authorize(); // ����� �����������
$processing_type=basename (__FILE__,".php");
// serialize form
if (isset(${'form_'.$processing_type})) extract(${'form_'.$processing_type});

if (isset($edit)) {
	// ������
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
	$sql="SELECT *,unix_timestamp(ts) AS uts,phototemplates.id AS ptid,phototemplates.id FROM phototemplates JOIN users ON phototemplates.user_id=users.id ".(isset($find)?"WHERE filenames LIKE '%$find%'":"").(!empty($order)?"ORDER BY ".$order." ":"ORDER BY ts DESC ").(isset($all)?"LIMIT 50":"LIMIT 20");
	//echo $sql;
	$cols[ptid]="ID";
	$cols[ts]="����";
	$cols[nik]="��� ��������";
	$cols[filenames]="����� � �������";
	
	$table = new Table($processing_type,$processing_type,$sql,$cols);
	$table->show();
}
?>