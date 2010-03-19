<?
// Отображает запущенные платы

include_once $_SERVER["DOCUMENT_ROOT"]."/lib/sql.php";
authorize(); // вызов авторизации


if (isset($delete)) 
{
	// уберем признак запуска
	$sql="SELECT pos_in_tz_id FROM lanch WHERE id='$delete'";
	$rs=sql::fetchOne($sql);
	$sql="UPDATE posintz SET ldate='0000-00-00' WHERE id='".$rs["pos_in_tz_id"]."'";
	mylog('posintz',$rs["pos_in_tz_id"],'UPDATE');
	sql::query($sql);
	// удаление
	$sql = "DELETE FROM lanch WHERE id='$delete'";
	mylog('lanch',$delete);
	sql::query($sql);
	echo "ok";
}
elseif (isset($show) || isset($edit) )
{
	$posid=isset($show)?$id:(isset($edit)?$edit:$add);
	$r = getright();
	//$sql="SELECT file_link FROM lanch JOIN (filelinks) ON (file_link_id=filelinks.id) WHERE lanch.id='$posid'";
	//echo $sql;
	//$rs=mysql_fetch_array(mysql_query($sql));
	//echo "<br><a onclick=\"window.open('zap.php?print=$posid')\" href='file://servermpp/".str_replace("\\","/",str_replace(":","",$rs[0]))."'>СЛ - $posid</a>";
	//echo "<a onclick=\"window.open('zap.php?print=$posid')\">СЛ - $posid</a><br>";
	echo "<a class=under href=zap.php?print=sl&id=$posid title='Открыть СЛ (локальную копию)'>СЛ - $posid</a><br>";
	//$sql="SELECT file_link,tz.id FROM posintz JOIN (lanch,tz,filelinks) ON (lanch.pos_in_tz_d=posintz.id AND tz.id=posintz.tz_id AND tz.file_link_id=filelinks.id) WHERE lanch.id='$posid'";
	$sql="SELECT tz.id FROM posintz JOIN (lanch,tz) ON (lanch.pos_in_tz_id=posintz.id AND tz.id=posintz.tz_id ) WHERE lanch.id='$posid'";
	//echo $sql;
	$rs=mysql_fetch_array(mysql_query($sql));
	//echo "<br><a onclick=\"window.open('zap.php?print=$posid')\" href='file://servermpp/".str_replace("\\","/",str_replace(":","",$rs[0]))."'>СЛ - $posid</a>";
	//echo "<a onclick=\"window.open('zap.php?print=tz&id=".$rs[0]."')\">ТЗ - $rs[0]</a>";
	echo "<a class=under href=zap.php?print=tz&id=".$rs[0]." title='Открыть ТЗ (локальную копию)'>ТЗ - $rs[0]</a>";
	if ($r["zap"]["edit"]) {
		echo "<br>Дозапустить <input id=dozap type=text size=2 name=dozap> штук";
		echo "<script>$('#dozap').keypress(function (e) {if (e.which==13) {
			//alert('А вот фиг!'+$('#dozap').attr('value'));
			editrecord('nzap','print=sl&dozap='+$('#dozap').val() + '&posid=$posid');
		}});</script>";
	}
	echo "<br><input type=button onclick='closeedit()' value='Закрыть'>";
}
elseif (isset($print)) {
	if ($print=="tz") 
	{
		$print=$id;
		$sql="SELECT file_link FROM tz JOIN (filelinks) ON (tz.file_link_id=filelinks.id) WHERE tz.id='$print'";
		//echo $sql;
		$rs=mysql_fetch_array(mysql_query($sql));
		//echo str_replace("\\","/",str_replace(":","",$rs[0]));
		$filelink =  mb_convert_encoding("/home/common/".str_replace("заказчики","Заказчики",str_replace("\\","/",str_replace(":","",$rs[0]))),"KOI8R","cp1251");
		//echo $filelink;
		$file = file_get_contents($filelink);
		//echo $file;
		header("Content-type: application/vnd.ms-excel");
		//header("content-disposition: attachment;filename=mp.xml");
		echo $file;
	}
	elseif ($print=="sl")
	{
		$print = $id;
		$sql="SELECT file_link FROM lanch JOIN (filelinks) ON (file_link_id=filelinks.id) WHERE lanch.id='$print'";
		//echo $sql;
		$rs=mysql_fetch_array(mysql_query($sql));
		//echo str_replace("\\","/",str_replace(":","",$rs[0]));
		$filelink =  mb_convert_encoding("/home/common/".str_replace("заказчики","Заказчики",str_replace("\\","/",str_replace(":","",$rs[0]))),"KOI8R","cp1251");
		//echo $filelink;
		$file = file_get_contents($filelink);
		//echo $file;
		header("Content-type: application/vnd.ms-excel");
		//header("content-disposition: attachment;filename=mp.xml");
		echo $file;
	}
	
}
else
{
// вывести таблицу

	// sql
	$sql="SELECT *,lanch.id FROM lanch JOIN (users,filelinks,coments,plates,customers,tz,orders) ON (lanch.user_id=users.id AND lanch.file_link_id=filelinks.id AND lanch.comment_id=coments.id AND lanch.board_id=plates.id AND plates.customer_id=customers.id AND lanch.tz_id=tz.id AND orders.id=tz.order_id) ".(isset($find)?"AND (plates.plate LIKE '%$find%' OR file_link LIKE '%$find%' OR orders.number LIKE '%$find%')":"").(!empty($order)?" ORDER BY ".$order." ":" ORDER BY lanch.id DESC ").(isset($all)?"LIMIT 50":"LIMIT 20");
	//echo $sql; exit;

	
	$cols["№"]="№";
	$cols[ldate]="Дата";
	$cols[id]="ID";
	$cols[nik]="Запустил";
	$cols[customer]="Заказчик";
	$cols[number]="Заказ";
	$cols[plate]="Плата";
	$cols[part]="Партия";
	$cols[numbz]="Заг.";
	$cols[numbp]="Плат";
	

	$table = new Table("zap","zap",$sql,$cols);
	$table->show();

}

?>