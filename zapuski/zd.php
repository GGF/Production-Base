<?
// Отображает платы в заделе
if(!headers_sent()) {
	header('Content-type: text/html; charset=windows-1251');
}
include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php"; // это нужно так как не вызывается заголовк html

if (isset($add) || isset($edit)) {
	if (!isset($accept) ) {
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
		echo "</SELECT><input type=button value='Добавить' onclick='window.location=\"http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."?zd&addcus\";'><br>";
		echo "Плата:<SELECT name=plate_id id=plates>";
		if(isset($edit)) {
			$sql = "SELECT * FROM plates WHERE customer_id='$customer' ORDER BY plate ";
			//echo $sql;
			$res = mysql_query($sql);
			while ($rs=mysql_fetch_array($res)) {
				echo "<option value=".$rs["id"]." ".($rs["id"]==$plid?"SELECTED":"").">".$rs["plate"];
			}
		}
		echo "</SELECT><input id=addplbut type=button value=Добавить $disabled onclick='window.location=\"http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."?zd&addpl&cusid=\"+$(\"#cusid\").val();'>";
		echo "<br>Количество:<input size=3 name=number value=$number>";
		$ldate = substr($ldate,8,2).".".substr($ldate,5,2).".".substr($ldate,0,4);
		echo "<br>№ извещения:<input size=10 name=niz Value=$niz>";
		echo "<br>Дата запуска:<input size=10 name=ldate id=datepicker Value=$ldate>";
		echo "<input type=hidden name=accept value='yes'>";
		echo "<br><input type=button value='Сохранить' onclick='window.location=\"http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."?zd&add&\"+$(\"#editform\").serialize();'>";
		echo "<input type=button value='Отмена'        onclick='window.location=\"http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."?zd\";'>";
		echo "<input type=button onclick=\"alert($('#editform').serialize())\">";
		echo "</form>";
		
	} else {
		// сохранение
		foreach ($_GET as $key => $val) {
			if (mb_detect_encoding($val)=="UTF-8") $$key=mb_convert_encoding($val,"cp1251");
		}
		foreach ($_POST as $key => $val) {
			if (mb_detect_encoding($val)=="UTF-8") $$key=mb_convert_encoding($val,"cp1251");
		}
		$ldate=substr($ldate,6,4)."-".substr($ldate,3,2)."-".substr($ldate,0,2);
		
		if (isset($edit)) {
			$sql = "UPDATE zadel SET number = '$number', ldate='$ldate', board_id='$plate_id', niz='$niz' WHERE id='$edit'";
		} else {
			$sql = "INSERT INTO zadel (board_id,ldate,number,niz) VALUES('$plate_id','$ldate','$number','$niz')";
		}
		mysql_query($sql);
		echo "<script>window.location='http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."?zd';</script>";
	}
}
elseif (isset($addcus)) {
	if(!isset($accept)) {
		echo "<form method=post id=editform >";
		echo "<input type=hidden name=accept value='yes'>";
		echo "Краткое название (имя каталога):<input type=text name=customer size=20 value='".$rs["customer"]."'><br>";
		echo "Полное название (для теззаданий): <input type=text name=fullname size=50 value='".$rs["fullname"]."'><br>";
		echo "<input type=button value='Сохранить' onclick='window.location=\"http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."?zd&addcus&\"+$(\"#editform\").serialize();'><input type=button value='Отмена' onclick='window.location=\"http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."?zd&add\";'>";
	} else {
		//сохранение
		foreach ($_GET as $key => $val) {
			if (mb_detect_encoding($val)=="UTF-8") $$key=mb_convert_encoding($val,"cp1251");
		}
		foreach ($_POST as $key => $val) {
			if (mb_detect_encoding($val)=="UTF-8") $$key=mb_convert_encoding($val,"cp1251");
		}

		$sql = "INSERT INTO customers (customer,fullname) VALUES ('$customer','$fullname')";
		mysql_query($sql);
		echo "<script>window.location='http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."?zd&add';</script>";
	}
}
elseif (isset($addpl)) {
	if(!isset($accept)) {
		echo "<form method=post id=editform >";
		echo "<input type=hidden name=accept value='yes'>";
		echo "<input type=hidden name=customer_id value='$cusid'>";
		echo "Наименование:<input type=text name=plate size=20 value=''><br>";
		echo "<input type=button value='Сохранить' onclick='window.location=\"http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."?zd&addpl&\"+$(\"#editform\").serialize();'><input type=button value='Отмена' onclick='window.location=\"http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."?zd&add\";'>";
	} else {
		//сохранение
		foreach ($_GET as $key => $val) {
			if (mb_detect_encoding($val)=="UTF-8") $$key=mb_convert_encoding($val,"cp1251");
		}
		foreach ($_POST as $key => $val) {
			if (mb_detect_encoding($val)=="UTF-8") $$key=mb_convert_encoding($val,"cp1251");
		}

		$sql = "INSERT INTO plates (customer_id,plate) VALUES ('$customer_id','$plate')";
		mysql_query($sql);
		echo "<script>window.location='http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."?zd&add';</script>";
	}
}
elseif (isset($selectplates)) {
	$sql = "SELECT * FROM plates WHERE customer_id='$cusid' ORDER BY plate ";
	//echo $sql;
	$res = mysql_query($sql);
	while ($rs=mysql_fetch_array($res)) {
		echo "<option value=".$rs["id"].">".$rs["plate"];
	}
}
elseif (isset($selectcustomers)) {
	$sql = "SELECT * FROM customers ORDER BY customer";
	$res = mysql_query($sql);
	while ($rs=mysql_fetch_array($res)) {
		echo "<option value=".$rs["id"].">".$rs["customer"];
	}
} elseif (isset($delete)) {
	$sql = "DELETE FROM zadel WHERE id='$delete'";
	mysql_query($sql);
	echo "<script>window.location='http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."?zd';</script>";
}
else
{
	foreach ($_GET as $key => $val) {
		if (mb_detect_encoding($val)=="UTF-8") $$key=mb_convert_encoding($val,"cp1251");
	}
	foreach ($_POST as $key => $val) {
		if (mb_detect_encoding($val)=="UTF-8") $$key=mb_convert_encoding($val,"cp1251");
	}
	echo '<div class="glavmenu" onclick="window.location=\'http://'.$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"].'?zd&add\';">Добавить</a></div>';
	$sql="SELECT *,zadel.id FROM zadel JOIN (plates,customers) ON (zadel.board_id=plates.id AND plates.customer_id=customers.id) ".(isset($find)?"AND (plates.plate LIKE '%$find%' OR customers.customer LIKE '%$find%')":"").($order!=''?" ORDER BY ".$order." ":" ORDER BY zadel.id DESC ").(isset($all)?"":"LIMIT 20");
	//print $sql;
	
	$type="zd";
	$cols["№"]="№";
	$cols[customer]="Заказчик";
	$cols[plate]="Плата";
	$cols[niz]="№ изв.";
	$cols[ldate]="Дата запуска";
	$cols[number]="Кол-во";
	//$cols[comment]="Коментарий";
	$del = true;
	
	include "table.php";
}

?>
