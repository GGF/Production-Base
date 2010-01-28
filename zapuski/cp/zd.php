<?
// управление заделом

include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php";
authorize(); // вызов авторизации


if (isset($edit) || isset($add)) {
	if (!isset($accept) ) 
		{
		$sql = "SELECT * FROM users WHERE nik='".$user."'";
		$res = mysql_query($sql);
		$rs=mysql_fetch_array($res);
		$uid = $rs["id"];
		echo "<form method=post id=editform>";
		if (isset($edit)) {
			$sql="SELECT *, customers.id AS cusid, plates.id AS plid FROM zadel JOIN (customers,plates) ON (zadel.board_id=plates.id AND plates.customer_id=customers.id) WHERE zadel.id='$edit'";
			$res = mysql_query($sql);
			if ($rs=mysql_fetch_array($res)) {
				$customer=$rs["cusid"];
				$plid = $rs["plid"];
				$number = $rs["number"];
				$ldate = $rs["ldate"];
				$niz=$rs["niz"];
				echo "<input type=hidden name=edit value=$edit>";
			}
		} else {
			$disabled = "disabled";
		}
		echo "Заказчик:<SELECT name=customer_id id=cusid onchange=\"var plat=$.ajax({url:'http://".$_SERVER['HTTP_HOST']."/zapuski/zd.php',data:'cusid='+$('#cusid').val()+'&selectplates',async:false}).responseText; $('#plates').html(plat); $('#addplbut').removeAttr('disabled');\">";
		$sql = "SELECT * FROM customers ORDER BY customer";
		$res = mysql_query($sql);
		while ($rs=mysql_fetch_array($res)) {
			echo "<option value=".$rs["id"]." ".($rs["id"]==$customer?"SELECTED":"").">".$rs["customer"];
		}
		echo "</SELECT><!--input type=button value='Добавить' onclick='window.location=\"http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."?zd&addcus\";'--><br>";
		echo "Плата:<SELECT name=plate_id id=plates>";
		if(isset($edit)) {
			$sql = "SELECT * FROM plates WHERE customer_id='$customer' ORDER BY plate ";
			//echo $sql;
			$res = mysql_query($sql);
			while ($rs=mysql_fetch_array($res)) {
				echo "<option value=".$rs["id"]." ".($rs["id"]==$plid?"SELECTED":"").">".$rs["plate"];
			}
		}
		echo "</SELECT><!--input id=addplbut type=button value=Добавить $disabled onclick='window.location=\"http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."?zd&addpl&cusid=\"+$(\"#cusid\").val();'-->";
		echo "<br>Количество:<input size=3 name=number value=$number>";
		$ldate = substr($ldate,8,2).".".substr($ldate,5,2).".".substr($ldate,0,4);
		echo "<br>№ извещения:<input size=10 name=niz Value=$niz>";
		echo "<br>Дата запуска:<input size=10 name=ldate id=datepicker Value=$ldate><br>";
		echo "<script>$('#datepicker').datepicker($.datepicker.regional['ru']);</script>";
		echo "<input type=hidden name=accept value='yes'>";
		echo "<input type='hidden' value='".(isset($edit)?$edit:"0")."' name='edit'>";
		echo "<input type=hidden name=tid value='$tid'>";
		echo "<input type=hidden name=uid value='$uid'>";

		echo "<input type=button value='Сохранить' onclick=\"editrecord('zd',$('#editform').serialize())\"><input type=button value='Отмена' onclick='closeedit()'><input type=button onclick=\"alert($('#editform').serialize())\">";
		echo "</form>";
		
	} 
	else 
		{
		// сохранение
		$ldate=substr($ldate,6,4)."-".substr($ldate,3,2)."-".substr($ldate,0,2);
		
		if ($edit!=0) {
			$sql = "UPDATE zadel SET number = '$number', ldate='$ldate', board_id='$plate_id', niz='$niz' WHERE id='$edit'";
			
		} else {
			$sql = "INSERT INTO zadel (board_id,ldate,number,niz) VALUES('$plate_id','$ldate','$number','$niz')";
		}
		if (!mysql_query($sql)) {
			my_error("Не удалось изменить задел");
		} else {
			echo "<script>updatetable('$tid','zd','');closeedit();</script>";
		}
	}

} elseif (isset($delete)) {
	$sql = "DELETE FROM zadel WHERE id='".$delete."'";
	mylog('zd',$delete,"DELETE");
	mysql_query($sql);
} else {
	$sql="SELECT *,zadel.id AS zid,zadel.id FROM zadel JOIN (plates,customers) ON (zadel.board_id=plates.id AND plates.customer_id=customers.id) ".(isset($find)?"AND (plates.plate LIKE '%$find%' OR customers.customer LIKE '%$find%')":"").($order!=''?" ORDER BY ".$order." ":" ORDER BY zadel.id DESC ").(isset($all)?"":"LIMIT 20");
	//print $sql;
	
	$type="zd";
	$cols["№"]="№";
	$cols[zid]="ID";
	$cols[customer]="Заказчик";
	$cols[plate]="Плата";
	$cols[niz]="№ изв.";
	$cols[ldate]="Дата запуска";
	$cols[number]="Кол-во";

	$opentype='zd';
	include "table.php";
}
?>