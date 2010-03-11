<?
// управление заказчиками

include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php";
authorize(); // вызов авторизации


if (isset($edit) || isset($add) ) {
	if (!isset($accept)) {
		if ($edit) {
			$sql = "SELECT *,DATE_FORMAT(orderdate,'%d.%m.%Y') as odate FROM orders WHERE id='".$edit."'";
			$res = mysql_query($sql);
			$rs=mysql_fetch_array($res);
		}
		echo "<form method=post id=editform>";
		echo "<input type='hidden' value='".(isset($edit)?$edit:"0")."' name='edit'>";
		echo "<input type=hidden name=tid value='$tid'>";
		echo "<input type=hidden name=customerid value='".($edit==0?$cusid:$rs["customer_id"])."'>";
		echo "<input type=hidden name=accept value='yes'>";
		echo "Дата:<input type=text name=orderdate id=datepicker size=10 value='".$rs["odate"]."'><br>";
		echo "Номер письма:<input type=text name=number size=30 value='".$rs["number"]."'><br>";
		echo "<input type=button value='Сохранить' onclick=\"editrecord('orders',$('#editform').serialize())\"><input type=button value='Отмена' onclick='closeedit()'><input type=button onclick=\"alert($('#editform').serialize())\">";
	} else {
		// сохрнение
		foreach ($_GET as $key => $val) {
			if (mb_detect_encoding($val)=="UTF-8") $$key=mb_convert_encoding($val,"cp1251");
		}
		foreach ($_POST as $key => $val) {
			if (mb_detect_encoding($val)=="UTF-8") $$key=mb_convert_encoding($val,"cp1251");
		}
		$orderdate=substr($orderdate,6,4)."-".substr($orderdate,3,2)."-".substr($orderdate,0,2);
		if ($edit) {
			// редактирование
			$sql = "UPDATE orders SET customer_id='$customerid', orderdate='$orderdate', number='$number' WHERE id='$edit'";
			mylog('orders',$edit,'UPDATE');
			mylog($sql);
		} else {
			// добавление
			$sql = "INSERT INTO orders (customer_id,orderdate,number) VALUES ('$customerid','$orderdate','$number')";
			mylog($sql);
		}
		if (!mysql_query($sql)) {
			my_error("Не удалось внести изменения в таблицу orders!!!");
		} else {
			echo "<script>updatetable('$tid','orders','');closeedit();</script>";
		}
	}
} 
elseif (isset($delete)) 
{
	// удаление
	$sql = "DELETE FROM orders WHERE id='$delete'";
	mylog('orders',$delete);
	mysql_query($sql);

	// удаление связей
	$sql = "SELECT * FROM tz WHERE order_id='$delete'";
	$res1 = mysql_query($sql);
	while($rs1=mysql_fetch_array($res1)) {
		// удаление
		$delete = $rs1["id"];
		$sql = "DELETE FROM tz WHERE id='$delete'";
		mylog('tz',$delete);
		mysql_query($sql);
		// удаление связей
		$sql = "SELECT * FROM posintz WHERE tz_id='$delete'";
		$res = mysql_query($sql);
		while($rs=mysql_fetch_array($res)) {
			$delete = $rs["id"];
			$sql = "DELETE FROM posintz WHERE id='$delete'";
			mylog('posintz',$delete);
			mysql_query($sql);
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

	$table = new Table("orders","tz",$sql,$cols);
	$table->title="Заказы <h1>Заказчик - $customer <input type=button onclick=\"selectmenu('customers','')\" value='Другой'></h1>";;
	if (isset($cusid)) $table->idstr = "&cusid=$cusid";
	$table->addbutton=true;
	$table->show();

}
?>