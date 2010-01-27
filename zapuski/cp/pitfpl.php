<?
	if (isset($plfindtext) && mb_detect_encoding($plfindtext)=="UTF-8") $plfindtext=mb_convert_encoding($plfindtext,"cp1251");
	if (isset($tzid)) {
		$sql="SELECT customer_id FROM tz JOIN orders ON orders.id=tz.order_id WHERE tz.id='$tzid' ";
		$rs=mysql_fetch_array(mysql_query($sql));
		$cusid = $rs[0];
	}
	$sql="SELECT * FROM plates WHERE customer_id='$cusid' ".(isset($plfindtext)?"AND plate LIKE '%$plfindtext%'":"")." LIMIT 20";
	$res = mysql_query($sql);
	while ($rs=mysql_fetch_array($res)) {
		$duid = uniqid('dr');
		$trid=uniqid('tr');
		echo "<div style='border: solid 1px red; float:left; background-color:white; padding:5; margin:5;' trid='$trid' plate='".$rs["plate"]."' id='$duid' plateid='".$rs["id"]."'><a onclick=\"editblock('".$rs["id"]."')\"><img src=/picture/b_edit.png></a>".$rs["plate"]."</div>";
		echo "<script>$('#$duid').draggable();</script>";
	}
?>	