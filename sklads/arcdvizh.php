<?
$sql="SELECT *,sk_arc_".$sklad."_spr.id FROM `sk_arc_".$sklad."_spr` JOIN sk_arc_".$sklad."_ost ON sk_arc_".$sklad."_ost.spr_id=sk_arc_".$sklad."_spr.id WHERE nazv!='' AND sk_arc_".$sklad."_spr.id='$dvizh' ORDER BY nazv";
$res = mysql_query($sql);
if (!$rs=mysql_fetch_array($res))
	my_error();
print "<b>".$rs["nazv"]."</b> - Остаток на складе:<b>".$rs["ost"]." ".$rs["edizm"]."</b>";
$nazv = $rs["nazv"]; $edizm=$rs["edizm"];
if (isset($ddelete)) {
	// из архива не удаляем
} 
else
{
	// вывести таблицу
	print "<form method=post action=''><input type=hidden name=action value='find'>Поиск:<input type=text name='ssrt' size=10><input type=hidden name=dvizh value=$dvizh><input type=hidden name=arc></form>";
	print "<input type=button onclick=\"window.location='http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."?arc'\" value='Вернуться'><br>";
	print "<table width=100% border=1>";
	print "<tr>";
	print "<td rowspan=2 align=center valign=center><b>Дата</b></td>
	<td align=center><b>Приход/Расход</b></td>
	<td align=center><b>№ док.</b></td>
	<td rowspan=2 valign=center align=center><b>Стоимость</b></td>
	<td align=center><b>Количество</b></td>
	<td rowspan=2 valign=center align=center><b>Удалить</b></td>";
	print "</tr>";
	print "<tr>";
	print "<td colspan=2 align=center><b>Поставщик</b></td>
	<td align=center><b>Примечание</b></td>";
	print "</tr>";
	if ($action=='find') 
		$sql="SELECT *,sk_arc_".$sklad."_dvizh".((isset($all)&$i==1)?"_arc":"").".id FROM sk_arc_".$sklad."_dvizh".((isset($all)&$i==1)?"_arc":"")." JOIN (sk_".$sklad."_postav,coments) ON (sk_".$sklad."_postav.id=sk_arc_".$sklad."_dvizh".((isset($all)&$i==1)?"_arc":"").".post_id AND coments.id=sk_arc_".$sklad."_dvizh".((isset($all)&$i==1)?"_arc":"").".comment_id) WHERE spr_id='$dvizh' AND (comment LIKE '%$ssrt%' OR supply LIKE '%$ssrt%' OR numd LIKE '%$ssrt%')  ORDER BY ddate";
	else 
		$sql="SELECT *,sk_arc_".$sklad."_dvizh".((isset($all)&$i==1)?"_arc":"").".id FROM sk_arc_".$sklad."_dvizh".((isset($all)&$i==1)?"_arc":"")." JOIN (sk_".$sklad."_postav,coments) ON (sk_".$sklad."_postav.id=sk_arc_".$sklad."_dvizh".((isset($all)&$i==1)?"_arc":"").".post_id AND coments.id=sk_arc_".$sklad."_dvizh".((isset($all)&$i==1)?"_arc":"").".comment_id) WHERE spr_id='$dvizh' ORDER BY ddate ";
	mylog1($sql);
	$j = 0;
	$res = mysql_query($sql);
	while ($rs=mysql_fetch_array($res)) {
		if (!($j%2)) 
			print "<tr class='chettr'>";
		else 
			print "<tr class='nechettr'>";
		print "<td rowspan=2>";
		print "<div style='width:100%; cursor:hand; height:100%;'>".$rs["ddate"]."</div>";
		print "</td>";
		print "<td align=center>";
		print "<div style='width:100%; cursor:hand; height:100%;'>".($rs["type"]==1?"Приход":"Расход")."</div>";
		print "</td>";
		print "<td align=center>";
		print "<div style='width:100%; cursor:hand; height:100%;'>".$rs["numd"]."</div>";
		print "</td>";
		print "<td rowspan=2 align=center>";
		print "<div style='width:100%; cursor:hand; height:100%;'>".$rs["price"]."</div>";
		print "</td>";
		print "<td align=center>";
		print "<div style='width:100%; cursor:hand; height:100%;'>".$rs["quant"]."</div>";
		print "</td>";
		print "<td rowspan=2 align=center>";
		print "&nbsp;";
		print "</td>";
		print "</tr>";
		if (!($j++%2)) 
			print "<tr class='chettr'>";
		else 
			print "<tr class='nechettr'>";
		print "<td colspan=2>";
		print "<div style='width:100%; cursor:hand; height:100%;'>".$rs["supply"]."</div>";
		print "</td>";
		print "<td align=center>";
		print "<div style='width:100%; cursor:hand; height:100%;'>".($rs["comment"]?$rs["comment"]:"&nbsp;")."</div>";
		print "</td>";
		print "</tr>";
	}

	print "</table>";	
	print "<input type=button onclick=\"window.location='http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."?arc'\" value='Вернуться'><br>";
}
?>