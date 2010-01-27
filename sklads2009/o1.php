<?
// Отображает отчет
if (isset($month)) {
	$sql="SELECT *,sk_".$sklad."_spr.id FROM sk_".$sklad."_spr JOIN sk_".$sklad."_ost ON sk_".$sklad."_ost.spr_id=sk_".$sklad."_spr.id WHERE nazv<>'' ORDER BY nazv";
	$res = mysql_query($sql);
	
	print "<table class='listtable' cellspacing=0 cellpadding=0>";
	print "<thead>";
	print "<tr>";
	print "<th>Наименование<th>Приход<th>Расход<th>Остаток на сегодня<th>Ед.изм.";
	print "<tbody>";
	
	while ($rs=mysql_fetch_array($res)) {
		$prih = "";
		$rash = "";
		$sql = "SELECT quant as prihod FROM (sk_".$sklad."_dvizh) JOIN sk_".$sklad."_spr ON (sk_".$sklad."_spr.id=sk_".$sklad."_dvizh.spr_id) WHERE MONTH(ddate)=(FLOOR($month/10000)) AND YEAR(ddate)=($month%10000) AND sk_".$sklad."_spr.id='".$rs["id"]."' AND type='1'";
		$res1 = mysql_query($sql);
		while ($rs1=mysql_fetch_array($res1)){
			$prih .= $rs1["prihod"]."_";
		} 
		$sql = "SELECT quant as prihod FROM (sk_arc_".$sklad."_dvizh) JOIN sk_arc_".$sklad."_spr ON (sk_arc_".$sklad."_spr.id=sk_arc_".$sklad."_dvizh.spr_id) WHERE MONTH(ddate)=(FLOOR($month/10000)) AND YEAR(ddate)=($month%10000) AND sk_arc_".$sklad."_spr.nazv='".$rs["nazv"]."' AND type='1' ";
		$res1 = mysql_query($sql);
		while ($rs1=mysql_fetch_array($res1)){
			$prih .= $rs1["prihod"]."_";
		}
		$sql = "SELECT quant as prihod FROM (sk_".$sklad."_dvizh) JOIN sk_".$sklad."_spr ON (sk_".$sklad."_spr.id=sk_".$sklad."_dvizh.spr_id) WHERE MONTH(ddate)=(FLOOR($month/10000)) AND YEAR(ddate)=($month%10000)  AND sk_".$sklad."_spr.id='".$rs["id"]."' AND type='0' ";
		$res1 = mysql_query($sql);
		if ($rs1=mysql_fetch_array($res1)){
			$rash .= $rs1["prihod"]."_";
		} 
		$sql = "SELECT quant as prihod FROM (sk_arc_".$sklad."_dvizh) JOIN sk_arc_".$sklad."_spr ON (sk_arc_".$sklad."_spr.id=sk_arc_".$sklad."_dvizh.spr_id) WHERE MONTH(ddate)=(FLOOR($month/10000)) AND YEAR(ddate)=($month%10000)  AND sk_arc_".$sklad."_spr.nazv='".$rs["nazv"]."' AND type='0' ";
		$res1 = mysql_query($sql);
		while ($rs1=mysql_fetch_array($res1)){
			$rash .= $rs1["prihod"]."_";
		}
		if (!empty($prih) || !empty($rash)) {
			if (!($i++%2)) 
				print "<tr class='chettr'>";
			else 
				print "<tr class='nechettr'>";		
			print "<td>".$rs["nazv"]."<td>".($prih)."<td>".($rash)."<td>".$rs["ost"]."<td>".$rs["edizm"];
		}
	}
	print "</table>";
} 
else {
	echo "<form method=post action=''>Выберите месяц:";
	
	$sql="SELECT MONTH(ddate), YEAR(ddate) FROM (sk_".$sklad."_dvizh) GROUP BY MONTH(ddate)";
	$res=mysql_query($sql);
	//echo $sql."<br>";
	echo "<select name=month>";
	while($rs=mysql_fetch_array($res)) {
		echo "<option value=".($rs[0]*10000+$rs[1]).">".sprintf("%02d",$rs[0])."-".$rs[1]."</option>";
	}
	$sql="SELECT MONTH(ddate), YEAR(ddate) FROM (sk_arc_".$sklad."_dvizh) GROUP BY MONTH(ddate)";
	$res=mysql_query($sql);
	//echo $sql."<br>";
	while($rs=mysql_fetch_array($res)) {
		echo "<option value=".($rs[0]*10000+$rs[1]).">".sprintf("%02d",$rs[0])."-".$rs[1]."</option>";
	}
	echo "</select>
	<input type=submit value='Отчет'>
	</form>";
}
?>