<?
// �������� � �������������� ��� �������
include_once $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php";
authorize(); // ����� �����������
$processing_type=basename (__FILE__,".php");
// serialize form
if (isset(${'form_'.$processing_type})) extract(${'form_'.$processing_type});

ob_start();

if (isset($edit)) {
	// �� �����������, ����
} elseif (isset($delete)) 
{
	// ��������
	$sql = "DELETE FROM posintz WHERE id='$delete'";
	sql::query($sql);
	// �������� ������
	echo "ok";
} else 
{
	// ������
	if (!empty($_SESSION[tz_id]))
	{
		$tzid = $_SESSION[tz_id];
		$sql="SELECT *,posintz.id as posid,posintz.id FROM `posintz` JOIN (blocks) ON ( posintz.block_id = blocks.id ) ".(isset($find)?"WHERE (blocks.blockname LIKE '%$find%')":"").(isset($tzid)?(isset($find)?"AND tz_id='$tzid'":"WHERE tz_id='$tzid'"):"").(!empty($order)?" ORDER BY ".$order." ":" ORDER BY posintz.id DESC ").(isset($all)?"":"LIMIT 20");
		$title = "������� � �� ".$_SESSION[customer]." - ".$_SESSION[order]." �� ".$_SESSION[orderdate]." - #".$_SESSION[tz_id]."";
	} 
	elseif (true)
	{
	}
	elseif (true)
	{
	}
	$cols[posid]="ID";
	$cols[blockname]="�����";
	$cols[numbers]="����������";

	$table = new SqlTable($processing_type,$processing_type,$sql,$cols);
	$table->title=$title;
	$table->show();
}

printpage();
?>