<?

if ($action=='add') {

} 
 elseif (isset($dvizh))
{
	include 'arcdvizh.php';
} 
else
{
// вывести таблицу
	if(isset($view) & $view=='all')
		print "<center><a href='http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."?arc'>Первые 20</a>";
	else
		print "<center><a href='?arc&view=all'>Все</a>";
	print "<form method=post action=''><input type=hidden name=action value='find'>Поиск:<input type=text name='ssrt' size=10><input type=hidden name=arc></form>";
	print "<table width=100% border=1>";
	print "<tr>";
	print "<td align=center><b>Название</b></td><td align=center><b>Ед.изм</b></td><td align=center><b>Остаток на складе</b></td><td align=center><b>Критич. кол-во</b></td><td align=center><b>Внимание</b></td><td align=center><b>Движение</b></td><td align=center><b>Удалить</b></td></b>";
	print "</tr>";
	if ($action=='find') 
		$sql="SELECT *,sk_arc_".$sklad."_spr.id FROM `sk_arc_".$sklad."_spr` JOIN sk_arc_".$sklad."_ost ON sk_arc_".$sklad."_ost.spr_id=sk_arc_".$sklad."_spr.id WHERE nazv!='' AND nazv LIKE '%$ssrt%' ORDER BY nazv";
	else 
		$sql="SELECT *,sk_arc_".$sklad."_spr.id FROM `sk_arc_".$sklad."_spr` JOIN sk_arc_".$sklad."_ost ON sk_arc_".$sklad."_ost.spr_id=sk_arc_".$sklad."_spr.id WHERE nazv!='' ORDER BY nazv".($view=='all'?"":" LIMIT 20");
	mylog1($sql);
	$i = 0;
	$res = mysql_query($sql);
	while ($rs=mysql_fetch_array($res)) {
		if (!($i++%2)) 
			print "<tr class='chettr'>\n";
		else 
			print "<tr class='nechettr''>\n";
		print "
		<td><a href='?arc&dvizh=".$rs["id"]."' style='text-decoration:none;'><div style='width:100%; cursor:hand;'>".$rs["nazv"]."</div><a></td>
		<td align=center><a href='?arc&dvizh=".$rs["id"]."' style='text-decoration:none;'><div style='width:100%; cursor:hand;'>".$rs["edizm"]."</div></a></td>
		<td align=center><a href='?arc&dvizh=".$rs["id"]."' style='text-decoration:none;'><div style='width:100%; cursor:hand;'>".$rs["ost"]."</div></a></td>
		<td align=center><a href='?arc&dvizh=".$rs["id"]."' style='text-decoration:none;'><div style='width:100%; cursor:hand;'>".$rs["krost"]."</div></a></td>
		<td align=center><a href='?arc&dvizh=".$rs["id"]."' style='text-decoration:none;'><div style='width:100%; cursor:hand;'>".($rs["ost"]<=$rs["krost"]?"<span style='color:red'><b>Мало</b></span>":"&nbsp;")."</div></a></td>
		<td align=center><a href='?arc&dvizh=".$rs["id"]."' style='text-decoration:none;'><div style='width:100%; cursor:hand;'>Движения</div></a></td>
		<td align=center><a href='?arc&dvizh=".$rs["id"]."' style='text-decoration:none;'>&nbsp;</a></td>\n";
		print "</tr>\n";
	}
	print "</table>";
	print "</form>";
}

?>