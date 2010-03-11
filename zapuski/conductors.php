<?
// Отображает запущенные платы

include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php";
authorize(); // вызов авторизации


if (isset($delete)) 
{
	// удаление
	$sql = "UPDATE conductors SET ts=NOW(), user_id='$userid', ready='1' WHERE id='$delete'";
	mylog('conductors',$delete);
	mysql_query($sql);
}
elseif (isset($show) || isset($edit)|| isset($add) )
{
	if (!isset($accept) ) {
	$id=isset($show)?$id:(isset($edit)?$edit:$add);
	$r = getright($user);

		echo "<form method=post id=editform>";
		if ($edit!=0) {
			$sql="SELECT *, customers.id AS cusid, conductors.board_id AS plid FROM conductors JOIN (customers,plates) ON (conductors.board_id=plates.id AND plates.customer_id=customers.id) WHERE conductors.id='$edit'";
			$res = mysql_query($sql);
			if ($rs=mysql_fetch_array($res)) {
				$customer=$rs["cusid"];
				$plid = $rs["plid"];
				$side = $rs["side"];
				$pib=$rs["pib"];
				$lays=$rs["lays"];
				echo "Заказчик:<input type=input readonly style='background-color:gray;' value='".$rs["customer"]."'><br>";
				echo "Плата:<input type=input readonly style='background-color:gray;' value='".$rs["plate"]."'>";
				echo "<input type=hidden name=plate_id value='$plid'>";
			}
		} else {
			echo "Заказчик:<SELECT name=customer_id ".($edit!=0?"readonly":"")." id=cusid onchange=\"var plat=$.ajax({url:'http://".$_SERVER['HTTP_HOST']."/zapuski/zd.php',data:'cusid='+$('#cusid').val()+'&selectplates',async:false}).responseText; $('#plates').html(plat); $('#plates').removeAttr('disabled');\">";
			$sql = "SELECT * FROM customers ORDER BY customer";
			$res = mysql_query($sql);
			while ($rs=mysql_fetch_array($res)) {
				echo "<option value=".$rs["id"].">".$rs["customer"];
			}
			echo "</SELECT><br>";
			echo "Плата:<SELECT name=plate_id disabled id=plates>";
			echo "</SELECT>";
		}
		echo "<br>Плат в блоке:<input size=3 name=pib value=$pib>";
		echo "<br>Пластин:<input size=3 name=lays value=$lays>";
		echo "<br>Сторона:<select name=side><option value='TOP'>TOP<option value='BOT'>BOT<option value='TOPBOT'>TOPBOT";
		echo "<input type=hidden name=accept value='yes'>";
		echo "<input type=hidden value='$edit' name='edit'>";
		echo "<input type=hidden name=tid value='$tid'>";
		echo "<input type=hidden name=uid value='$uid'>";

		echo "<br><input type=button value='Сохранить' onclick=\"editrecord('conductors',$('#editform').serialize())\"><input type=button value='Отмена' onclick='closeedit()'><input type=button onclick=\"alert($('#editform').serialize())\">";
		echo "</form>";
		
	} 
	else 
		{
		// сохранение
		
		if ($edit!=0) {
			$sql = "UPDATE conductors SET board_id='$plate_id', pib='$pib', side='$side', lays='$lays', user_id='$userid', ts=NOW() WHERE id='$edit'";
			
		} else {
			$sql = "INSERT INTO conductors (board_id,pib,side,lays,user_id,ts) VALUES('$plate_id','$pib','$side','$lays','$userid',NOW())";
		}
		if (!mysql_query($sql)) {
			my_error("Не удалось изменить Кондуктор");
		} else {
			echo "<script>updatetable('$tid','conductors','');closeedit();</script>";
		}
	}

}
elseif (isset($print)) {
	
}
else
{
// вывести таблицу

	// sql
	$sql="SELECT *,conductors.id FROM conductors JOIN (plates,customers) ON (conductors.board_id=plates.id AND plates.customer_id=customers.id ) WHERE ready='0' ".(isset($find)?"AND (plates.plate LIKE '%$find%')":"").($order!=''?" ORDER BY ".$order." ":" ORDER BY conductors.id DESC ").(isset($all)?"":"LIMIT 20");

	//echo $sql;
	
	$cols[id]="ID";
	$cols[customer]="Заказчик";
	$cols[plate]="Плата";
	$cols[side]="Сторона";
	$cols[lays]="Пластин";
	$cols[pib]="Плат в блоке";


	$table = new Table("conductors","conductors",$sql,$cols);
	$table->addbutton=true;
	$table->show();

}

?>