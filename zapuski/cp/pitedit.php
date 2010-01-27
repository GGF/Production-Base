<?
	if (!isset($accept)) {
		if ($edit) {
			$sql = "SELECT * FROM posintz WHERE id='".$edit."'";
			$res = mysql_query($sql);
			$rs=mysql_fetch_array($res);
		}
		$tzid = ($edit==0?$tzid:$rs["tz_id"]);

		$sql="SELECT customer_id FROM tz JOIN orders ON orders.id=tz.order_id WHERE tz.id='$tzid' ";
		$rs=mysql_fetch_array(mysql_query($sql));
		$cusid = $rs[0];

		echo "<div style=''>";
		echo "Редактирование ТЗ №$tzid</div>";
		echo "<div class='preposblock' id=''>";
		echo "<div class='position' id='position'>";
		echo "<form method=post id=editform>";
		echo "<input type='hidden' value='".(isset($edit)?$edit:"0")."' name='edit'>";
		echo "<input type=hidden name=tid value='$tid'>";
		echo "<input type=hidden name=tzid value='$tzid'>";
		echo "<input type=hidden name=accept value='yes'>";


		echo "<table class='listtable' id='tposition' cellpadding=0 cellspacing=0>";
		echo "<thead>";
		echo "<tr>";
		echo "<th>Наименование";
		//echo "<th>Колво в блоке";
		echo "<th>Колво в заказе";
		//echo "<th>Маска";
		//echo "<th>Маркировка";
		echo "<th>Удалить";

		$sql = "SELECT * FROM posintz JOIN plates ON plates.id=posintz.plate_id WHERE tz_id='$tzid'";
		$res = mysql_query($sql);
		while ($rs=mysql_fetch_array($res)) {
			$trid=uniqid('tr');
			echo "<tr class='chettr' id='$trid'><td>".$rs["plate"]."</td><td><input type=text value='".$rs["numbers"]."' size=8 name='numbers[".$rs["id"]."]'></td><td><a onclick=\"my_delete('','$trid','".$rs["plate"]."')\"><img src=/picture/b_drop.png></a></td>";
		}
		echo "</table>";

		echo "</div>";
		echo "<script>activatetz();</script>";
//
		echo "<input type=button value='Сохранить' onclick=\"editrecord('posintz',$('#editform').serialize())\"><input type=button value='Отмена' onclick='closeedit()'><input type=button onclick=\"alert($('#editform').serialize())\">";
		echo "</div>";
		echo "<div style='width:100%;'>";

		echo "<input type=text size=10 name='find' style='width:100%;' id='plfindtext'><script>$('#plfindtext').keypress(function (e) {
if (e.which==13) {
		find('http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."','tzid=$tzid&findplate&plfindtext='+$(this).val(),'blocks');
}
});</script>";
		echo "</div>";

		echo "<div class='preposblock'>";
		echo "<div class='blocks' id='blocks'>";

		echo "</div>";
		echo "<input type=button onclick=\"editblock('0','$cusid')\" value='Добавить блок'><br>";
		echo "</div>";
		echo "<script>find('http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."','tzid=$tzid&findplate','blocks');</script>";

	} else {
		// сохрнение
		foreach ($_GET as $key => $val) {
			if (mb_detect_encoding($val)=="UTF-8") $$key=mb_convert_encoding($val,"cp1251");
		}
		foreach ($_POST as $key => $val) {
			if (mb_detect_encoding($val)=="UTF-8") $$key=mb_convert_encoding($val,"cp1251");
		}

		$sql = "SELECT * FROM posintz WHERE tz_id='$tzid'";
		$res = mysql_query($sql);
		while($rs=mysql_fetch_array($res)) {
			$sql="DELETE FROM posintz WHERE id='".$rs["id"]."'";
			mylog('posintz',$rs["id"]);
			mysql_query($sql);
		}

		$i=1;

		foreach ($numbers as $key => $val) {
			$sql = "INSERT INTO posintz (tz_id,posintz,plate_id,numbers) VALUES ('$tzid','$i','$key','$val')";
			mylog($sql);
			mysql_query($sql);
			$i++;
		}

		echo "<script>updatetable('$tid','posintz','');closeedit();</script>";
	}
?>