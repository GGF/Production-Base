<?
	echo "<center><a href='http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."?".$type.((isset($find) && $find!='')?"&find=".urlencode($find)."":"").(isset($all)?"'>Последние 20":"&all'>Все")."</a></center><br>
	<form method=post action='http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."'>
	Поиск:<input type=text name='find' size=10 value='$find'>
	<input type=hidden name=".$type.">
	<input type=hidden name=".$all.">
	<input type=hidden name=order value=".$order.">
	</form>";
	echo "<table class='listtable' cellspacing=0 cellpadding=0>";
	echo "<thead>";
	echo "<tr>";
	while (list($key, $val) = each($cols)) {
		echo "<th><a href='?".$type.(isset($all)?"&all":"").(isset($find)?"&find=".urlencode($find)."":"")."&order=".($order==$key?$key."%20DESC":$key)."'>$val".($order==$key?"&darr;":(($order==$key.' DESC')?"&uarr;":""))."</a>";
	}
	if ($del) {
		echo "<th>Удаление";
	}
	echo "<tbody>";

	$i = 0;
	$res = mysql_query($sql);
	while ($rs=mysql_fetch_array($res)) {
		if (!($i++%2)) 
			echo "<tr class='chettr'>";
		else 
			echo "<tr class='nechettr'>";
		$rs["№"]=$i;
		if ($rs["file_link"]!="") {
			$link = "<a alt='Ссылка на файл' title='Ссылка на файл' href='file://servermpp/".str_replace("\\","/",str_replace(":","",$rs["file_link"]))."'><div class='fullwidth'>";
			$rs["file_link"] = substr($rs["file_link"],strrpos($rs["file_link"],"\\")+1);
		} else {
			$link = "<a alt='Редактировать' title='Редактировать' href='?$type&edit=".$rs["id"]."'><div class='fullwidth'>";
		}
		$linkend = "</div></a>";
		reset($cols);
		while (list($key, $val) = each($cols)) {
			echo "<td>".$link.$rs["$key"].$linkend;
		}
		if ($del) {
			echo "<td><a href='#' onclick=\"if (confirm('Удалить ".addslashes(htmlspecialchars($rs[0]."-".$rs[1]."-".$rs[2]."-".$rs[3]))."')) window.location='http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."?".$type."&delete=".$rs["id"]."'\">Удалить</a>";
		}
	}
	echo "</table>";
	
?>