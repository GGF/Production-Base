<?
if (!isset($accept)) {
	if ($editblock) {
		$sql = "SELECT * FROM plates WHERE id='".$editblock."'";
		$res = mysql_query($sql);
		$rs=mysql_fetch_array($res);
	}
	$cusid = ($editblock==0?$cusid:$rs["customer_id"]);	
	
	echo "
	Редактирование блока:
	<div class='blockform'>
	<form id=addblock>
	Имя блока:<input type=text name=blockname size=30 id=blockname><br>
	Размер Х:<input type=text name=sizex size=5 id=sizex>&nbsp;Размер Y:<input type=text name=sizey size=5 id=sizey>
	</form>
	<div id=abhelp>Кидай сюда платы</div>
	<input type=button value='Сохранить' onclick=\"editrecord('posintz',$('#addblock').serialize())\">
	<input type=button value='Отмена' onclick='closeeditblock()'><input type=button onclick=\"alert($('#addblock').serialize())\">
	</div>
	<div class='platepool'>
	<input type=button onclick=\"$('#plateedit').show();var html=$.ajax({url:'http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."',data:'editplate=0&cusid=$cusid',async:false}).responseText; $('#plateedit').html(html);\" value='Добавить плату'><br>
	Найти :<input type=text size=10 name='find' id='blplfindtext'><script>$('#blplfindtext').keypress(function (e) { 
if (e.which==13) {
	find('http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."','cusid=$cusid&findplate&plfindtext='+$(this).val(),'plates');
}
});</script>";
	echo "<div style='width:100%; height:100%; min-height:100' id='plates'>";
	echo "</div>";
	echo "</div>";
	echo "<script>find('http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."','cusid=$cusid&findplate','plates');</script>";
	echo "</div>";
} else {
	// сохранение
}
?>
