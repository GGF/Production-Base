<?
/*
* ����� (����� ������, ������ ���������� �����)
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
	//$cols[bid]="ID";
	if (empty($_SESSION[customer_id])) 
	{
		$customer = "�������� ���������!!!";
		$sql="SELECT *,boards.id AS bid,boards.id FROM boards JOIN customers ON customers.id=customer_id ".(!empty($find)?"WHERE (boards.board_name LIKE '%$find%') ":"").(isset($order)?"ORDER BY ".$order." ":"ORDER BY boards.board_name DESC ").((isset($all))?"LIMIT 50":"LIMIT 20");
		$cols[customer]="��������";
	}
	else
	{
		$cusid = $_SESSION[customer_id];
		$customer = $_SESSION[customer];
		$sql="SELECT *,boards.id AS bid,boards.id FROM boards JOIN customers ON customers.id=customer_id WHERE customer_id='$cusid'".(!empty($find)?"AND (boards.board_name LIKE '%$find%') ":"").(isset($order)?"ORDER BY ".$order." ":"ORDER BY boards.board_name DESC ").((isset($all))?"LIMIT 50":"LIMIT 20");
	}
	
	$cols[board_name]="����������� �����";
	$cols[layers]="�����";
	$cols[sizex]="������";
	$cols[sizey]="�����";
	$cols[thickness]="�������";

	//echo $sql;
	$table = new Table($processing_type,$processing_type,$sql,$cols);
	$table->title="�������� - $customer ";
	$table->addbutton=true;
	$table->show();
}
printpage();
?>