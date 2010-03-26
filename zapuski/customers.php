<?
// ���������� �����������

require $_SERVER["DOCUMENT_ROOT"]."/lib/sql.php"; 
authorize(); // ����� �����������
$processing_type=basename (__FILE__,".php");

if (isset($edit) || isset(${'form_'.$processing_type})) 
{
	// serialize form
	if(!empty(${'form_'.$processing_type})){
		foreach(${'form_'.$processing_type} as $key => $val) {
			if (mb_detect_encoding($val)=="UTF-8") 
				${$key}=mb_convert_encoding($val,"cp1251","UTF-8");
			else 
				${$key}=$val;
		}
	}
	
	if (!isset($accept)) {
		$sql = "SELECT * FROM customers WHERE id='$edit'";
		$cust=sql::fetchOne($sql);
		
		$form = new Edit($processing_type);
		$form->init();
		$form->addFields(array(
			array(
				"type"		=>	CMSFORM_TYPE_TEXT,
				"name"		=>	"customer",
				"label"		=>	"������� �������� (��� ��������):",
				"value"		=>	$cust["customer"],
				//"options"	=>	array( "html" => "size=10", ),
			),
			array(
				"type"		=>	CMSFORM_TYPE_TEXT,
				"name"		=>	"fullname",
				"label"		=>	"������ �������� (��� ����������):",
				"value"		=>	$cust["fullname"],
				"options"	=>	array( "html" => "size=60", ),
			),
			array(
				"type"		=>	CMSFORM_TYPE_TEXT,
				"name"		=>	"kdir",
				"label"		=>	"������� �� ����� � (��� ���������):",
				"value"		=>	$cust["kdir"],
			),
		));
		$form->show();
	} else {
		// ���������
		if (!empty($edit)) {
			// ��������������
			$sql = "UPDATE customers SET customer='$customer', fullname='$fullname', kdir='$kdir' WHERE id='$edit'";
		} else {
			// ����������
			$sql = "INSERT INTO customers (customer,fullname,kdir) VALUES ('$customer','$fullname','$kdir')";
		}
		sql::query($sql);
		sql::error(true);
		echo "ok";
	}
} 
elseif (isset($delete)) 
{
	// ��������
	$sql = "DELETE FROM customers WHERE id='$delete'";
	sql::query($sql);
	sql::error(true);
	// �������� ������
	// ������� � ����� ���������
	$sql = "SELECT * FROM plates WHERE customer_id='$delete'";
	$res = sql::fetchAll($sql);
	foreach ($res as $rs) 
	{
		$sql = "DELETE FROM plates WHERE id='".$rs["id"]."'";
		sql::query($sql);
		sql::error(true);
		// ���� �� ������� � ����� �.�.
	}
	// ������� �������� ������ � ��
	$sql = "SELECT * FROM orders WHERE customer_id='$delete'";
	$res = sql::fetchAll($sql);
	foreach ($res as $rs) 
	{
		// ��������
		$delete = $rs["id"];
		$sql = "DELETE FROM orders WHERE id='$delete'";
		sql::query($sql);
		sql::error(true);
		// �������� ������
		$sql = "SELECT * FROM tz WHERE order_id='$delete'";
		$res1 =  sql::fetchAll($sql);
		foreach($res1 as $rs1) {
			// ��������
			$delete = $rs1["id"];
			$sql = "DELETE FROM tz WHERE id='$delete'";
			sql::query($sql);
			sql::error(true);
			// �������� ������
			$sql = "SELECT * FROM posintz WHERE tz_id='$delete'";
			$res2 =  sql::fetchAll($sql);
			foreach($res2 as $rs2)
				$delete = $rs2["id"];
				$sql = "DELETE FROM posintz WHERE id='$delete'";
				sql::query($sql);
				sql::error(true);
			}
		}
	echo "ok";
}
else
{
// ������� �������
	// sql
	$sql="SELECT * FROM customers ".(isset($find)?"WHERE (customers.customer LIKE '%$find%' OR customers.fullname LIKE '%$find%' ) ":"").(isset($order)?"ORDER BY ".$order." ":"ORDER BY customers.customer ").(isset($all)?"":"LIMIT 20");
	//echo $sql;
	$cols[id]="ID";
	$cols[customer]="��������";
	$cols[fullname]="������ ��������";
	$cols[kdir]="���������";

	$openfunc = "opencustr";

	
	$table = new Table($processing_type,"opencustr",$sql,$cols);
	$table->title='���������';
	$table->addbutton=true;
	$table->show();

}
?>