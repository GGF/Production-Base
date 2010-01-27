<?
// управление заказчиками

include "head.php";

if (isset($edit) || isset($add) ) {
	if (!isset($accept)) {
		if ($edit) {
			$sql = "SELECT * FROM customers WHERE id='".$edit."'";
			$res = mysql_query($sql);
			$rs=mysql_fetch_array($res);
		}
		echo "<form method=post id=editform action='http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."'>";
		echo "<input type='hidden' value='".(isset($edit)?$edit:"0")."' name='edit'>";
		echo "<input type=hidden name=tid value=$tid>";
		if (isset($order)) echo "<input type=hidden name=order value=$order>";
		if (isset($idstr)) echo "<input type=hidden name=idstr value=$idstr>";
		if (isset($find))echo "<input type=hidden name=find value=$find>";
		if (isset($all)) echo "<input type=hidden name=all value=$all>";
		echo "<input type=hidden name=accept value='yes'>";
		echo "Краткое название (имя каталога):<input type=text name=customer size=20 value='".$rs["customer"]."'><br>";
		echo "Полное название (для теззаданий): <input type=text name=fullname size=50 value='".$rs["fullname"]."'><br>";
		echo "<input type=button value='Сохранить' onclick=\"editrecord('customers',$('#editform').serialize())\"><input type=button value='Отмена' onclick='closeedit()'>";
	} else {
		// сохрнение
		foreach ($_GET as $key => $val) {
			if (mb_detect_encoding($val)=="UTF-8") $$key=mb_convert_encoding($val,"cp1251");
		}
		foreach ($_POST as $key => $val) {
			if (mb_detect_encoding($val)=="UTF-8") $$key=mb_convert_encoding($val,"cp1251");
		}
		if ($edit) {
			// редактирование
			$sql = "UPDATE customers SET customer='$customer', fullname='$fullname' WHERE id='$edit'";
			mylog('customers',$edit,'UPDATE');
			mylog($sql);
		} else {
			// добавление
			$sql = "INSERT INTO customers (customer,fullname) VALUES ('$customer','$fullname')";
			mylog($sql);
		}
		if (!mysql_query($sql)) {
			my_error("Не удалось внести изменения в таблицу customers!!!");
		} else {
			echo "<script>updatetable('$tid','customers','');closeedit();</script>";
		}
	}
} elseif (isset($delete)) {
	// удаление
	$sql = "DELETE FROM customers WHERE id='$delete'";
	mylog('customers',$delete);
	mysql_query($sql);
	// удаление связей
	// удалить и платы заказчика
	$sql = "SELECT * FROM plates WHERE customer_id='$delete'";
	$res = mysql_query($sql);
	while ($rs=mysql_fetch_array($res)) {
		$sql = "DELETE FROM plates WHERE id='".$rs["id"]."'";
		mylog(plates,$rs["id"]);
		mysql_query($sql);
		// надо бы удалить и блоки т.п.
	}
	// удалить вязанные заказы и тз
	$sql = "SELECT * FROM orders WHERE customer_id='$delete'";
	$res2 = mysql_query($sql);
	while($rs2=mysql_fetch_array($res1)) {
		// удаление
		$delete = $rs2["id"];
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
	}
}
else
{
// вывести таблицу
	// sql
	$sql="SELECT * FROM customers ".(isset($find)?"WHERE (customers.customer LIKE '%$find%' OR customers.fullname LIKE '%$find%' ) ":"").(isset($order)?"ORDER BY ".$order." ":"ORDER BY customers.customer ").(isset($all)?"":"LIMIT 20");
	//print $sql;
	$type="customers";
	$cols[id]="ID";
	$cols[customer]="Заказчик";
	$cols[fullname]="Полное название";
	$del=true;
	$edit=true;
	$openfunc = "opencustr";
	$bgcolor='#FFFFFF';
	$title = 'Заказчики';
	
	include "table.php";
}
?>