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
		if (isset($edit)) {
			$sql="SELECT *, customers.id AS cusid, plates.id AS plid FROM conductors JOIN (customers,plates) ON (conductors.board_id=plates.id AND plates.customer_id=customers.id) WHERE conductors.id='$edit'";
			$res = mysql_query($sql);
			if ($rs=mysql_fetch_array($res)) {
				$customer=$rs["cusid"];
				$plid = $rs["plid"];
				$side = $rs["side"];
				$pib=$rs["pib"];
				$lays=$rs["lays"];
				echo "<input type=hidden name=edit value=$edit>";
			}
		} else {
			$disabled = "disabled";
		}
		echo "Заказчик:<SELECT name=customer_id ".($edit!=0?"disabled":"")." id=cusid onchange=\"var plat=$.ajax({url:'http://".$_SERVER['HTTP_HOST']."/zapuski/zd.php',data:'cusid='+$('#cusid').val()+'&selectplates',async:false}).responseText; $('#plates').html(plat); $('#addplbut').removeAttr('disabled');$('#plates').removeAttr('disabled');\">";
		$sql = "SELECT * FROM customers ORDER BY customer";
		$res = mysql_query($sql);
		while ($rs=mysql_fetch_array($res)) {
			echo "<option value=".$rs["id"]." ".($rs["id"]==$customer?"SELECTED":"").">".$rs["customer"];
		}
		echo "</SELECT><!--input type=button value='Добавить' onclick='window.location=\"http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."?zd&addcus\";'--><br>";
		echo "Плата:<SELECT name=plate_id disabled id=plates>";
		if(isset($edit)) {
			$sql = "SELECT * FROM plates WHERE customer_id='$customer' ORDER BY plate ";
			//echo $sql;
			$res = mysql_query($sql);
			while ($rs=mysql_fetch_array($res)) {
				echo "<option value=".$rs["id"]." ".($rs["id"]==$plid?"SELECTED":"").">".$rs["plate"];
			}
		}
		echo "</SELECT><!--input id=addplbut type=button value=Добавить $disabled onclick='window.location=\"http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."?zd&addpl&cusid=\"+$(\"#cusid\").val();'-->";
		echo "<br>Плат в блоке:<input size=3 name=pib value=$pib>";
		echo "<br>Пластин:<input size=3 name=lays value=$lays>";
		$ldate = substr($ldate,8,2).".".substr($ldate,5,2).".".substr($ldate,0,4);
		echo "<br>Сторона:<select name=side><option value='TOP'>TOP<option value='BOT'>BOT<option value='TOPBOT'>TOPBOT";
		//echo "<br>Дата запуска:<input size=10 name=ldate id=datepicker Value=$ldate><br>";
		//echo "<script>$('#datepicker').datepicker($.datepicker.regional['ru']);</script>";
		echo "<input type=hidden name=accept value='yes'>";
		echo "<input type='hidden' value='".(isset($edit)?$edit:"0")."' name='edit'>";
		echo "<input type=hidden name=tid value='$tid'>";
		echo "<input type=hidden name=uid value='$uid'>";

		echo "<br><input type=button value='Сохранить' onclick=\"editrecord('conductors',$('#editform').serialize())\"><input type=button value='Отмена' onclick='closeedit()'><input type=button onclick=\"alert($('#editform').serialize())\">";
		echo "</form>";
		
	} 
	else 
		{
		// сохранение
		$ldate=substr($ldate,6,4)."-".substr($ldate,3,2)."-".substr($ldate,0,2);
		
		if ($edit!=0) {
			$sql = "UPDATE conductors SET board_id='$plate_id', pib='$pib', side='$side', lays='$lays' WHERE id='$edit'";
			
		} else {
			$sql = "INSERT INTO conductors (board_id,pib,side,lays) VALUES('$plate_id','$pib','$side','$lays')";
		}
		mylog1($sql);
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
	
	$type="conductors";
	$cols[id]="ID";
	$cols[customer]="Заказчик";
	$cols[plate]="Плата";
	$cols[side]="Сторона";
	$cols[lays]="Пластин";
	$cols[pib]="Плат в блоке";
	
	$edit=true;
	$del=true;
	$addbutton=true;
	$opentype = "conductors";

	include "table.php";

}

?>