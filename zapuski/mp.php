<?
// ���������� �������������

include_once $_SERVER["DOCUMENT_ROOT"]."/lib/sql.php"; 
authorize(); // ����� �����������


if (isset($edit) || isset($add) ) {
	// ������
} elseif (isset($delete)) {
	// ��������
	$sql = "DELETE FROM masterplate WHERE id='$delete'";
	mylog('masterplate',$delete,'DELETE');
	mysql_query($sql);
	echo "ok";
}
else
{
// ������� �������
	
	// sql
	$sql="SELECT masterplate.id,masterplate.mpdate,users.nik,customers.customer,plates.plate,filelinks.file_link FROM masterplate JOIN (tz,posintz,orders,customers,plates,filelinks,users) ON (masterplate.user_id=users.id AND masterplate.tz_id=tz.id AND masterplate.tz_id=posintz.tz_id AND masterplate.posintz=posintz.posintz AND tz.order_id=orders.id AND posintz.plate_id=plates.id AND customers.id=plates.customer_id AND tz.file_link_id=filelinks.id) ".(isset($find)?"WHERE plate LIKE '%$find%' OR customer LIKE '%$find%'":"").($order!=''?"ORDER BY ".$order." ":"ORDER BY masterplate.id DESC ").(isset($all)?"LIMIT 50":"LIMIT 20");
	
	$cols[id]="ID";
	$cols[mpdate]="����";
	$cols[nik]="��� ��������";
	$cols[customer]="��������";
	$cols[plate]="�����";
	
	$table = new Table("mp","mp",$sql,$cols);
	$table->show();
	
}
?>