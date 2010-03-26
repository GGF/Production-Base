<?
// управление заказчиками

require $_SERVER["DOCUMENT_ROOT"]."/lib/sql.php";
authorize(); // вызов авторизации
$processing_type=basename (__FILE__,".php");


if (isset($edit) || isset(${'form_'.$processing_type})) 
{
	// serialize form
	if(!empty(${'form_'.$processing_type})){
		serializeform(${'form_'.$processing_type});
	}
	
	if (!isset($accept)) {
		$sql = "SELECT * FROM orders WHERE id='$edit'";
		$ord=sql::fetchOne($sql);
		
		$form = new Edit($processing_type);
		$form->init();
		$form->addFields(array(
			array(
				"type"		=> CMSFORM_TYPE_TEXT,
				"name"		=> "orderdate",
				"label"			=>'Дата:',
				"value"		=> date2datepicker($ord[orderdate]),
				"options"		=> array( "html" => ' datepicker=1 '),
			),
			array(
				"type"		=>	CMSFORM_TYPE_TEXT,
				"name"		=>	"number",
				"label"		=>	"Номер письма:",
				"value"		=>	$ord["number"],
				"options"	=>	array( "html" => "size=30", ),
			),
			array(
				"type"		=>	CMSFORM_TYPE_HIDDEN,
				"name"		=>	"customerid",
				"value"		=>	!empty($cusid)?$cusid:$ord["customer_id"],
				"options"	=>	array( "html" => "size=30", ),
			),
		));
		$form->show();
	} else {
		// сохрнение
		if ($edit) {
			// редактирование
			$sql = "UPDATE orders SET customer_id='$customerid', orderdate='".datepicker2date($orderdate)."', number=".addslashes($number)." WHERE id='$edit'";
		} else {
			// добавление
			$sql = "INSERT INTO orders (customer_id,orderdate,number) VALUES ('$customerid','".datepicker2date($orderdate)."','".addslashes($number)."')";
		}
		sql::query($sql);
		sql::error(true);
		echo "ok";
	}
} 
elseif (isset($delete)) 
{
	// удаление
	$sql = "DELETE FROM orders WHERE id='$delete'";
	sql::query($sql);
	sql::error(true);
	// удаление связей
	$sql = "SELECT * FROM tz WHERE order_id='$delete'";
	$res = sql::fetchAll($sql);
	foreach ($res as $rs) 
	{
		// удаление
		$delete = $rs["id"];
		$sql = "DELETE FROM tz WHERE id='$delete'";
		sql::query($sql);
		sql::error(true);
		// удаление связей
		$sql = "SELECT * FROM posintz WHERE tz_id='$delete'";
		$res1 = sql::fetchAll($sql);
		foreach ($res1 as $rs1) 
		{
			$delete = $rs1["id"];
			$sql = "DELETE FROM posintz WHERE id='$delete'";
			sql::query($sql);
			sql::error(true);
		}
	}
	echo "ok";
}
else
{
// вывести таблицу
	if (isset($id)) $cusid=$id;
		
	$sql = "SELECT customer FROM customers WHERE id='$cusid'";
	$rs=mysql_fetch_array(mysql_query($sql));
	$customer = $rs[0];


	// sql
	$sql="SELECT * FROM orders ".(isset($find)?"WHERE (number LIKE '%$find%' OR orderdate LIKE '%$find%' ) ":"").(isset($cusid)?(isset($find)?"AND customer_id='$cusid'":"WHERE customer_id='$cusid'"):"").(isset($order)?"ORDER BY ".$order." ":"ORDER BY orders.orderdate DESC ").((isset($all))?"":"LIMIT 20");


	$cols[id]="ID";
	$cols[number]="Номер заказа";
	$cols[orderdate]="Дата заказа";

	$table = new Table($processing_type,"tz",$sql,$cols);
	$table->title="Заказы <h1>Заказчик - $customer <input type=button onclick=\"selectmenu('customers','')\" value='Другой'></h1>";;
	if (isset($cusid)) $table->idstr = "&cusid=$cusid";
	$table->addbutton=true;
	$table->show();

}
?>