<?
// ���������� ���������� �����

require $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php";
authorize(); // ����� �����������
$processing_type=basename (__FILE__,".php");
// serialize form
if (isset(${'form_'.$processing_type})) extract(${'form_'.$processing_type});

if (isset($delete)) 
{
	// ������ ������� �������
	$sql="SELECT pos_in_tz_id FROM lanch WHERE id='$delete'";
	$rs=sql::fetchOne($sql);
	$sql="UPDATE posintz SET ldate='0000-00-00' WHERE id='".$rs["pos_in_tz_id"]."'";
	sql::query($sql);
	sql::error(true);
	// ��������
	$sql = "DELETE FROM lanch WHERE id='$delete'";
	sql::query($sql);
	sql::error(true);
	echo "ok";
}
elseif (isset($edit))
{
	$posid=isset($show)?$id:(isset($edit)?$edit:$add);
	echo "<a class=filelink href=zap.php?print=sl&id=$posid title='������� �� (��������� �����)'>�� - $posid</a><br>";
	$sql="SELECT tz.id FROM posintz JOIN (lanch,tz) ON (lanch.pos_in_tz_id=posintz.id AND tz.id=posintz.tz_id ) WHERE lanch.id='$posid'";
	//echo $sql;
	$rs=sql::fetchOne($sql);
	echo "<a class=filelink href=zap.php?print=tz&id=".$rs[id]." title='������� �� (��������� �����)'>�� - $rs[id]</a>";
	$r = getright();
	if ($r["zap"]["edit"]) {
		echo "<br>����������� <input id=dozap type=text size=2 name=dozap> ����";
		echo "<script>$('#dozap').keyboard('enter',function () {editrecord('nzap','print=sl&dozap='+$(this).val() + '&posid=$posid')});</script>";
	}
	//echo "<br><input type=button onclick='closeedit()' value='�������'>";
}
elseif (isset($print)) 
{
	if ($print=="tz") 
	{
		$print=$id;
		$sql="SELECT file_link FROM tz JOIN (filelinks) ON (tz.file_link_id=filelinks.id) WHERE tz.id='$print'";
		$rs=sql::fetchOne($sql);
		$filelink = createdironserver($rs["file_link"]);
		$file = file_get_contents($filelink);
		header("Content-type: application/vnd.ms-excel");
		echo $file;
	}
	elseif ($print=="sl")
	{
		$print = $id;
		$sql="SELECT file_link FROM lanch JOIN (filelinks) ON (file_link_id=filelinks.id) WHERE lanch.id='$print'";
		$rs=sql::fetchOne($sql);
		$filelink =  createdironserver($rs["file_link"]);
		$file = file_get_contents($filelink);
		header("Content-type: application/vnd.ms-excel");
		echo $file;
	}
}
else
{
// ������� �������

	// sql
	$sql="SELECT *,lanch.id AS lanchid,lanch.id FROM lanch JOIN (users,filelinks,coments,blocks,customers,tz,orders) ON (lanch.user_id=users.id AND lanch.file_link_id=filelinks.id AND lanch.comment_id=coments.id AND lanch.block_id=blocks.id AND blocks.customer_id=customers.id AND lanch.tz_id=tz.id AND orders.id=tz.order_id) ".(isset($find)?"AND (blocks.blockname LIKE '%$find%' OR file_link LIKE '%$find%' OR orders.number LIKE '%$find%')":"").(!empty($order)?" ORDER BY ".$order." ":" ORDER BY lanch.id DESC ").(isset($all)?"LIMIT 50":"LIMIT 20");
	//echo $sql; exit;

	
	$cols["�"]="�";
	$cols[ldate]="����";
	$cols[lanchid]="ID";
	$cols[nik]="��������";
	$cols[customer]="��������";
	$cols[number]="�����";
	$cols[blockname]="�����";
	$cols[part]="������";
	$cols[numbz]="���.";
	$cols[numbp]="����";
	

	$table = new SqlTable($processing_type,$processing_type,$sql,$cols);
	$table->show();

}

?>