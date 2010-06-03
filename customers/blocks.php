<?
/*
* Блоки - наборы плат на заготовке
*/

require $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php";
authorize(); // вызов авторизации
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
		$customer = "Выберите заказчика!!!";
		$sql="SELECT *,blocks.id AS bid,blocks.id FROM blocks JOIN customers ON customers.id=customer_id ".(!empty($find)?"WHERE (blocks.blockname LIKE '%$find%') ":"").(isset($order)?"ORDER BY ".$order." ":"ORDER BY blocks.blockname DESC ").((isset($all))?"LIMIT 50":"LIMIT 20");
		$cols[customer]="Заказчик";
	}
	else
	{
		$cusid = $_SESSION[customer_id];
		$customer = $_SESSION[customer];
		$sql="SELECT *,blocks.id AS bid,blocks.id FROM blocks JOIN customers ON customers.id=customer_id WHERE customer_id='$cusid' ".(!empty($find)?"AND (blocks.blockname LIKE '%$find%') ":"").(isset($order)?"ORDER BY ".$order." ":"ORDER BY blocks.blockname DESC ").((isset($all))?"LIMIT 50":"LIMIT 20");
	}
	
	$cols[blockname]="Наименование";
	$cols[sizex]="Ширина";
	$cols[sizey]="Длина";
	$cols[thickness]="Толщина";
	$cols[scomp]="S<sub>c</sub>";
	$cols[ssolder]="S<sub>s</sub>";
	$cols[drlname]="сверловка";
	$cols[thickness]="Толщина";

	//echo $sql;
	$table = new Table($processing_type,"blockpos",$sql,$cols);
	$table->title="Заказчик - $customer ";
	$table->addbutton=true;
	$table->show();
}

printpage();
?>
