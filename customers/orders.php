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
		$sql="SELECT *,orders.id as order_id FROM orders JOIN customers ON customers.id=customer_id WHERE orders.id='$id'";
		$rs=sql::fetchOne($sql);
		$_SESSION[order_id]=$rs[order_id];
		$_SESSION[order]=$rs[number];
		$_SESSION[orderdate]=$rs[orderdate];
		$_SESSION[customer_id]=$rs[customer_id];
		$_SESSION[customer]=$rs[customer];
		echo "ok<script>selectmenu('tz','');</script>";
	} 
	else
	{
		if (!isset($accept)) {
			$sql = "SELECT * FROM orders WHERE id='$edit'";
			$ord=sql::fetchOne($sql);
			
			$form = new Edit($processing_type);
			$form->init();
			if(empty($edit) && empty($_SESSION[customer_id]))
			{
				$customers = array();
				$sql="SELECT id,customer FROM customers ORDER BY customer";
				$res=sql::fetchAll($sql);
				foreach($res as $rs) { $customers[$rs[id]] = $rs[customer]; }
				$form->addFields(array(
					array(
						"type"		=> CMSFORM_TYPE_SELECT,
						"name"		=> "customerid",
						"label"		=>	"��������:",
						"values"	=>	$customers,
					),
				));
			}
			else
			{
				$form->addFields(array(
					array(
						"type"		=>	CMSFORM_TYPE_HIDDEN,
						"name"		=>	"customerid",
						"value"		=>	!empty($_SESSION[customer_id])?$_SESSION[customer_id]:$ord["customer_id"],
						"options"	=>	array( "html" => "size=30", ),
					),
				));
			}
			$form->addFields(array(
				array(
					"type"		=> CMSFORM_TYPE_TEXT,
					"name"		=> "orderdate",
					"label"			=>'����:',
					"value"		=> date2datepicker($ord[orderdate]),
					"options"		=> array( "html" => ' datepicker=1 '),
				),
				array(
					"type"		=>	CMSFORM_TYPE_TEXT,
					"name"		=>	"number",
					"label"		=>	"����� ������:",
					"value"		=>	$ord["number"],
					"options"	=>	array( "html" => "size=30", ),
				),
			));
			$form->show();
		} 
		else 
		{
			// ���������
			if ($edit) {
				// ��������������
				$sql = "UPDATE orders SET customer_id='$customerid', orderdate='".datepicker2date($orderdate)."', number='".addslashes($number)."' WHERE id='$edit'";
			} else {
				// ����������
				$sql = "INSERT INTO orders (customer_id,orderdate,number) VALUES ('$customerid','".datepicker2date($orderdate)."','".addslashes($number)."')";
			}
			sql::query($sql);
			echo "ok";
		}
	}
} 
elseif (isset($delete)) 
{
	// ��������
	$sql = "DELETE FROM orders WHERE id='$delete'";
	sql::query($sql);
	// �������� ������
	$sql = "SELECT * FROM tz WHERE order_id='$delete'";
	$res = sql::fetchAll($sql);
	foreach ($res as $rs) 
	{
		// ��������
		$delete = $rs["id"];
		$sql = "DELETE FROM tz WHERE id='$delete'";
		sql::query($sql);
		// �������� ������
		$sql = "SELECT * FROM posintz WHERE tz_id='$delete'";
		$res1 = sql::fetchAll($sql);
		foreach ($res1 as $rs1) 
		{
			$delete = $rs1["id"];
			$sql = "DELETE FROM posintz WHERE id='$delete'";
			sql::query($sql);
		}
	}
	echo "ok";
}
else
{
// ������� �������
	if(isset($all)) $_SESSION[order_id]='';
	if (empty($_SESSION[customer_id])) 
	{
		$customer = "�������� ���������!!!";
		$sql="SELECT *,orders.id FROM orders JOIN customers ON customers.id=customer_id ".(isset($find)?"WHERE (number LIKE '%$find%' OR orderdate LIKE '%$find%' ) ":"").(isset($order)?"ORDER BY ".$order." ":"ORDER BY orders.orderdate DESC ").((isset($all))?"LIMIT 50":"LIMIT 20");
		$cols[customer]="��������";
	} 
	else 
	{
		$cusid = $_SESSION[customer_id];
		$customer = $_SESSION[customer];
			
		// sql
		$sql="SELECT * FROM orders ".(isset($find)?"WHERE (number LIKE '%$find%' OR orderdate LIKE '%$find%' ) ":"").(isset($cusid)?(isset($find)?"AND customer_id='$cusid'":"WHERE customer_id='$cusid'"):"").(isset($order)?"ORDER BY ".$order." ":"ORDER BY orders.orderdate DESC ").((isset($all))?"LIMIT 50":"LIMIT 20");
	}

		$cols[id]="ID";
		$cols[number]="����� ������";
		$cols[orderdate]="���� ������";

		$table = new Table($processing_type,$processing_type,$sql,$cols);
		$table->title="�������� - $customer ";;
		$table->addbutton=true;
		$table->show();

}

printpage();
?>