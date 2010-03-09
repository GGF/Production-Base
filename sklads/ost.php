<?

if ($action=='add') {

} 
 elseif (isset($delete))
{
	$sql = "INSERT INTO sk_arc_".$sklad."_spr (nazv,edizm,krost) SELECT sk_".$sklad."_spr.nazv,sk_".$sklad."_spr.edizm,sk_".$sklad."_spr.krost FROM sk_".$sklad."_spr WHERE sk_".$sklad."_spr.id='$delete'";
	mylog1($sql);
	if(!mysql_query($sql)) 
		my_error();
	$id = mysql_insert_id();
	
	$sql = "DELETE FROM sk_".$sklad."_spr WHERE id='$delete'";
	mylog1($sql);
	if(!mysql_query($sql)) 
		my_error();

	$sql = "INSERT INTO sk_arc_".$sklad."_ost (spr_id,ost) SELECT $id,sk_".$sklad."_ost.ost FROM sk_".$sklad."_ost WHERE sk_".$sklad."_ost.spr_id='$delete'";
	mylog1($sql);
	if(!mysql_query($sql)) 
		my_error();
	
	$sql = "DELETE FROM sk_".$sklad."_ost WHERE spr_id='$delete'";
	mylog1($sql);
	if(!mysql_query($sql)) 
		my_error();
		
	$sql = "INSERT INTO sk_arc_".$sklad."_dvizh (type,numd,numdf,docyr,spr_id,quant,ddate,post_id,comment_id,price) SELECT sk_".$sklad."_dvizh.type,sk_".$sklad."_dvizh.numd,sk_".$sklad."_dvizh.numdf,sk_".$sklad."_dvizh.docyr,$id,sk_".$sklad."_dvizh.quant,sk_".$sklad."_dvizh.ddate,sk_".$sklad."_dvizh.post_id,sk_".$sklad."_dvizh.comment_id,sk_".$sklad."_dvizh.price FROM sk_".$sklad."_dvizh WHERE sk_".$sklad."_dvizh.spr_id='$delete'";
	mylog1($sql);
	if(!mysql_query($sql)) 
		my_error();

	$sql = "DELETE FROM sk_".$sklad."_dvizh WHERE spr_id='$delete'";
	mylog1($sql);
	if(!mysql_query($sql)) 
		my_error();

	$sql = "INSERT INTO sk_arc_".$sklad."_dvizh (type,numd,numdf,docyr,spr_id,quant,ddate,post_id,comment_id,price) SELECT sk_".$sklad."_dvizh_arc.type,sk_".$sklad."_dvizh_arc.numd,sk_".$sklad."_dvizh_arc.numdf,sk_".$sklad."_dvizh_arc.docyr,$id,sk_".$sklad."_dvizh_arc.quant,sk_".$sklad."_dvizh_arc.ddate,sk_".$sklad."_dvizh_arc.post_id,sk_".$sklad."_dvizh_arc.comment_id,sk_".$sklad."_dvizh_arc.price FROM sk_".$sklad."_dvizh_arc WHERE sk_".$sklad."_dvizh_arc.spr_id='$delete'";
	mylog1($sql);
	if(!mysql_query($sql)) 
		my_error();
		
	$sql = "DELETE FROM sk_".$sklad."_dvizh_arc WHERE spr_id='$delete'";
	mylog1($sql);
	if(!mysql_query($sql)) 
		my_error();
	echo "<script>window.location='http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."';</script>";
} 
 elseif (isset($dvizh))
{
	include 'dvizh.php';
} 
 elseif (isset($edit))
{
	if (isset($submit)) {
		// отредактировано
		if ($edit==0) {
			//добавление нового
			$sql="INSERT INTO sk_".$sklad."_spr (nazv,edizm,krost) VALUES ('$nazv','$edizm','$krost')" ;
			mylog1($sql);
			mysql_query($sql);
			$spr_id = mysql_insert_id();
			if(!$spr_id) 
				my_error();
			$sql="INSERT INTO sk_".$sklad."_ost (spr_id,ost) VALUES ($spr_id,'0')" ;
		} else {
			$sql="UPDATE sk_".$sklad."_spr SET nazv='$nazv', edizm='$edizm', krost='$krost' WHERE id='$edit'";
		}
		mylog1($sql);
		if(!mysql_query($sql)) 
			my_error();
		print "<script>window.location='http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."';</script>";
	} else {
		if($edit!=0) {
			$sql="SELECT * FROM sk_".$sklad."_spr WHERE id='".$edit."'";
			$res=mysql_query($sql);
			if (!$rs=mysql_fetch_array($res)) my_error();
		}
		print "<form action='' method=post>Наименование:<input size=50 type=text name=nazv value='".$rs["nazv"]."'><br>Единица измерения:<input type=text name=edizm value='".$rs["edizm"]."'><br>Критический остаток:<input type=text name=krost value='".$rs["krost"]."'><br><input type=submit name=submit value='Сохранить'><input type=button onclick='history.back()' value='Отмена'><input type=hidden name=edit value='$edit'></form>";
	}
} else
{
// вывести таблицу
	
	if(isset($view) & $view=='all')
		print "<center><a href='http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."'>Первые 20</a>";
	else
		print "<center><a href='?view=all'>Все</a>";
	print "<form method=post action=''><input type=hidden name=action value='find'>Поиск:<input type=text name='ssrt' size=10><input type=hidden name=tz></form>";
	print "<input type=button onclick=\"window.location='http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."?edit=0'\" value='Добавить новый'><br>";
	print "<form name=treb target=_blank method=post action='../treball.php'>";
	include "trebformall.php";
	print "<table width=100% border=1>";
	print "<tr>";
	print "<td>";
	//print "<input type=checkbox ".($checkall?"checked":"")." onclick=\"window.location='http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"].($checkall?"":"?checkall=1")."'\">";
	print "<input type=checkbox id='ucuc' onclick=\"if ($('#ucuc').attr('checked')) $('.check-me').attr({checked:true}); else $('.check-me').attr({checked:false});\">";
	print "</td><td align=center><b>Название</b></td><td align=center><b>Ед.изм</b></td><td align=center><b>Остаток на складе</b></td><td align=center><b>Критич. кол-во</b></td><td align=center><b>Внимание</b></td><td align=center><b>Движение</b></td><td align=center><b>Удалить</b></td></b>";
	print "</tr>";
	if ($action=='find') 
		$sql="SELECT *,sk_".$sklad."_spr.id FROM `sk_".$sklad."_spr` JOIN sk_".$sklad."_ost ON sk_".$sklad."_ost.spr_id=sk_".$sklad."_spr.id WHERE nazv!='' AND nazv LIKE '%$ssrt%' ORDER BY nazv";
	else 
		$sql="SELECT *,sk_".$sklad."_spr.id FROM `sk_".$sklad."_spr` JOIN sk_".$sklad."_ost ON sk_".$sklad."_ost.spr_id=sk_".$sklad."_spr.id WHERE nazv!='' ORDER BY nazv".($view=='all'?"":" LIMIT 20");
	$i = 0;
	$res = mysql_query($sql);
	while ($rs=mysql_fetch_array($res)) {
		if (!($i++%2)) 
			print "<tr class='chettr'>\n";
		else 
			print "<tr class='nechettr'>\n";
		print "
		<td><input type=checkbox name=id[".$rs["id"]."] value=".$rs["id"]." class='check-me'".($checkall?"checked":"")."></td>
		<td><a href='?edit=".$rs["id"]."' style='text-decoration:none;'><div style='width:100%; cursor:hand;'>".$rs["nazv"]."</div></a></td>
		<td align=center><a href='?edit=".$rs["id"]."' style='text-decoration:none;'><div style='width:100%; cursor:hand;'>".$rs["edizm"]."</div></a></td>
		<td align=center><a href='?edit=".$rs["id"]."'  style='text-decoration:none;'><div style='width:100%; cursor:hand;'>".$rs["ost"]."</div></a></td>
		<td align=center><a href='?edit=".$rs["id"]."' style='text-decoration:none;'><div style='width:100%; cursor:hand;'>".$rs["krost"]."</div></a></td>
		<td align=center><a href='?edit=".$rs["id"]."' style='text-decoration:none;'><div style='width:100%; cursor:hand;'>".($rs["ost"]<=$rs["krost"]?"<span style='color:red'><b>Мало</b></span>":"&nbsp;")."</div></a></td>
		<td align=center><a href='?dvizh=".$rs["id"]."' style='text-decoration:none;'><div style='width:100%; cursor:hand;'>Движения</div></a></td>
		<td align=center><a href='#' style='text-decoration:none;' onclick=\"if (confirm('Удалить ".addslashes(htmlspecialchars($rs["nazv"]))."')) window.location='http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."?delete=".$rs["id"]."'\">Удалить</a></td>\n";
		print "</tr>\n";
	}
	print "</table>";
	print "</form>";
}

?>