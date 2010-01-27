<?
{
// вывести таблицу
	print "<center><a href='?action=".($action=='all'?"'>Последние 20":"all'>Все")."</a></center><br><form method=post action=''><input type=hidden name=action value='find'>Поиск:<input type=text name='ssrt' size=10></form>";
	print "<table class='listtable' cellspacing=0 cellpadding=0>";
	print "<thead>";
	print "<tr>";
	print "<td>Дата - время</td><td>Кто добавил</td><td>Количество шаблонов и каталог</td>";
	print "</tr>";
	print "<tbody>";
	$sql="SELECT *,unix_timestamp(ts) AS uts FROM phototemplates JOIN users ON phototemplates.user_id=users.id ".($action=='find'?"WHERE filenames LIKE '%$ssrt%'":"")."ORDER BY ts DESC ".($action=='all'?"":"LIMIT 20");
	debug($sql);
	$res = mysql_query($sql);
	while ($rs=mysql_fetch_array($res)) {
		if (!($i++%2)) 
			print "<tr class='chettr'>";
		else 
			print "<tr class='nechettr'>";
		print "<td>" .date("d-m-Y H:i",$rs["uts"]). "</td><td>".$rs["nik"]."</td><td>".$rs["filenames"]."</td>";
		print "</tr>";
	}
	print "</table>";
}
?>