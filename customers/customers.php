<?
// ���������� �����������

require $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php"; 
authorize(); // ����� �����������
$processing_type=basename (__FILE__,".php");
// serialize form
if (isset(${'form_'.$processing_type})) extract(${'form_'.$processing_type});

ob_start();

if (isset($edit)) 
{
	if (!empty($id))
	{
		$sql="SELECT * FROM customers WHERE id='$id'";
		$rs=sql::fetchOne($sql);
		$_SESSION[customer_id]=$rs[id];
		$_SESSION[customer]=$rs[customer];
		echo "ok<script>selectmenu('orders','');</script>";
		exit;
	}
	else 
	{
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
		} 
		else 
		{
			// ���������
			if (!empty($edit)) {
				// ��������������
				$sql = "UPDATE customers SET customer='$customer', fullname='$fullname', kdir='$kdir' WHERE id='$edit'";
			} else {
				// ����������
				$sql = "INSERT INTO customers (customer,fullname,kdir) VALUES ('$customer','$fullname','$kdir')";
			}
			sql::query($sql);
			echo "ok";
		}
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
	if(isset($all)) $_SESSION[customer_id]='';
	// sql
	$sql="SELECT * FROM customers ".(isset($find)?"WHERE (customers.customer LIKE '%$find%' OR customers.fullname LIKE '%$find%' ) ":"").(isset($order)?"ORDER BY ".$order." ":"ORDER BY customers.customer ").(isset($all)?"":"LIMIT 20");
	//echo $sql;
	$cols[id]="ID";
	$cols[customer]="��������";
	$cols[fullname]="������ ��������";
	$cols[kdir]="���������";

	$openfunc = "opencustr";

	
	$table = new Table($processing_type,$processing_type,$sql,$cols);
	//$table->title='���������';
	$table->addbutton=true;
	$table->show();

}
printpage();
?>