<?
/*
* ����� - ������ ���� �� ���������
*/

require $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php";
authorize(); // ����� �����������
$processing_type=basename (__FILE__,".php");
// serialize form
if (isset(${'form_'.$processing_type})) extract(${'form_'.$processing_type});

ob_start();
if (isset($edit)) 
{
}
elseif (!empty($delete))
{
}
else
{
	if (empty($_SESSION[customer_id])) 
	{
		$customer = "�������� ���������!!!";
		$sql="SELECT *,blocks.id AS bid,blocks.id FROM blocks JOIN customers ON customers.id=customer_id ".(!empty($find)?"WHERE (blocks.blockname LIKE '%$find%') ":"").(isset($order)?"ORDER BY ".$order." ":"ORDER BY blocks.blockname DESC ").((isset($all))?"LIMIT 50":"LIMIT 20");
		$cols[customer]="��������";
	}
	else
	{
		$cusid = $_SESSION[customer_id];
		$customer = $_SESSION[customer];
		$sql="SELECT *,blocks.id AS bid,blocks.id FROM blocks JOIN customers ON customers.id=customer_id WHERE customer_id='$cusid' ".(!empty($find)?"AND (blocks.blockname LIKE '%$find%') ":"").(isset($order)?"ORDER BY ".$order." ":"ORDER BY blocks.blockname DESC ").((isset($all))?"LIMIT 50":"LIMIT 20");
	}
	
	$cols[blockname]="������������";
	$cols[sizex]="������";
	$cols[sizey]="�����";
	$cols[thickness]="�������";
	$cols[scomp]="S<sub>c</sub>";
	$cols[ssolder]="S<sub>s</sub>";
	$cols[drlname]="���������";
	$cols[thickness]="�������";

	//echo $sql;
	$table = new Table($processing_type,"blockpos",$sql,$cols);
	$table->title="�������� - $customer ";
	$table->addbutton=true;
	$table->show();
}

printpage();
?>
