<?
if (!isset($accept)) {
	if ($editplate) {
		$sql = "SELECT * FROM boards WHERE id='".$editplate."'";
		$res = mysql_query($sql);
		$rs=mysql_fetch_array($res);
	}
	$cusid = ($editplate==0?$cusid:$rs["customer_id"]);	
	
	echo "
	Рудактирование платы:
	<div style='width:100%; height:100%; min-height:100; background-color:lightgray; border: solid -1px red;'>
	<form id=addplate>
	Имя платы:<input type=text name=blockname size=30 id=blockname><br>
	</form>";
	echo "<input type=button value='Сохранить' onclick=\"editrecord('posintz',$('#addplate').serialize())\"><input type=button value='Отмена' onclick='closeeditblock()'><input type=button onclick=\"alert($('#addblock').serialize())\">";
	echo "
	</div>";
} else {
	// сохранение
}
?>
