<?
// �������� � �������������� ��� �������
include_once $_SERVER["DOCUMENT_ROOT"]."/lib/sql.php";
authorize(); // ����� �����������
$processing_type=basename (__FILE__,".php");

if (isset($edit) || isset($add) ) {
	// �� �����������
} elseif (isset($delete)) 
{
	// ��������
	$sql = "DELETE FROM posintz WHERE id='$delete'";
	sql::query($sql);
	sql::error(true);
	// �������� ������
	echo "ok";
} else 
{
	// ������
	if (isset($id)) $tzid=$id;
	
	$sql="SELECT *,posintz.id as posid,posintz.id FROM `posintz` JOIN (plates) ON ( posintz.plate_id = plates.id ) ".(isset($find)?"WHERE (plates.plate LIKE '%$find%')":"").(isset($tzid)?(isset($find)?"AND tz_id='$tzid'":"WHERE tz_id='$tzid'"):"").(!empty($order)?" ORDER BY ".$order." ":" ORDER BY posintz.id DESC ").(isset($all)?"":"LIMIT 20");
	//print $sql;

	$cols[posid]="ID";
	$cols[plate]="�����";
	$cols[numbers]="����������";

	$table = new Table($processing_type,$processing_type,$sql,$cols);
	$table->title='������� � ��';
	if (isset($tzid)) $table->idstr = "&tzid=$tzid";
	$table->show();
}
?>