<?
$sql="SELECT *,sk_".$sklad."_spr.id FROM `sk_".$sklad."_spr` JOIN sk_".$sklad."_ost ON sk_".$sklad."_ost.spr_id=sk_".$sklad."_spr.id WHERE nazv!='' AND sk_".$sklad."_spr.id='$dvizh' ORDER BY nazv";
debug($sql);
$res = mysql_query($sql);
if (!$rs=mysql_fetch_array($res))
	my_error();
print "<b>".$rs["nazv"]."</b> - Остаток на складе:<b>".$rs["ost"]." ".$rs["edizm"]."</b>";
$nazv = $rs["nazv"]; $edizm=$rs["edizm"];
if (isset($ddelete)) {
	$sql = "SELECT * FROM sk_".$sklad."_dvizh WHERE id='$ddelete'";
	debug($sql);
	$res = mysql_query($sql);
	if(!$rs=mysql_fetch_array($res))
		my_error();
	$sql = "UPDATE sk_".$sklad."_ost SET ost=ost".($rs["type"]?"-":"+").abs($rs["quant"])." WHERE spr_id='$dvizh'";
	debug($sql);
	if(!mysql_query($sql)) 
		my_error();
	$sql = "DELETE FROM sk_".$sklad."_dvizh WHERE id='$ddelete'";
	debug($sql);
	if(!mysql_query($sql)) 
		my_error();
	print "<script>window.location='http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."?dvizh=$dvizh';</script>";
} elseif (isset($dedit)) {
	if (isset($submit)) {
		// отредактировано
		// найдем поставщика
		if ($supply_id!=0) 
		{
			$post_id = $supply_id;
			/*
			$sql="SELECT id FROM sk_".$sklad."_postav WHERE id='$supply_id'";
			debug($sql);
			$res = mysql_query($sql);
			if ($rs=mysql_fetch_array($res)){
				$post_id = $rs["id"];
			} else 
				my_error();
			*/
		} 
		else 
		{
			$sql="SELECT id FROM sk_".$sklad."_postav WHERE supply='$supply'";
			debug($sql);
			$res = mysql_query($sql);
			if ($rs=mysql_fetch_array($res)){
				$post_id = $rs["id"];
			} else 
			{			
				$sql="INSERT INTO sk_".$sklad."_postav (supply) VALUES ('$supply')";
				debug($sql);
				mysql_query($sql);
				$post_id = mysql_insert_id();
				if (!$post_id) 
					my_error();
			}
		}			
		// Определим идентификатор коментария
		$sql="SELECT id FROM coments WHERE comment='$comment'";
		debug($sql);
		$res = mysql_query($sql);
		if ($rs=mysql_fetch_array($res)){
			$comment_id = $rs["id"];
		} else {
			$sql="INSERT INTO coments (comment) VALUES ('$comment')";
			debug($sql);
			mysql_query($sql);
			$comment_id = mysql_insert_id();
			if (!$comment_id) my_error();
		}		
		list($numdf,$docyr)=explode("/",$numd);
		if ($dedit==0) {
			//добавление нового
			$ddate = date("Y-m-d",mktime(0,0,0,substr($ddate,3,2),substr($ddate,0,2),substr($ddate,6,4)));//$dyear."-".$dmonth."-".$dday;
			$sql="INSERT INTO sk_".$sklad."_dvizh (type,numd,numdf,docyr,spr_id,quant,ddate,post_id,comment_id,price) VALUES ('$type','$numd','$numdf','$numyr','$dvizh','$quant','$ddate','$post_id','$comment_id','$price')" ;
			debug($sql);
			if(!mysql_query($sql)) 
				my_error();
			$sql = "UPDATE sk_".$sklad."_ost SET ost=ost".($type?"+":"-").abs($quant)." WHERE spr_id='$dvizh'";
			debug($sql);
			if(!mysql_query($sql)) 
				my_error();	
		} 
		else 
		{
			// удалить  старое движенеи
			$sql = "SELECT * FROM sk_".$sklad."_dvizh WHERE id='$dedit'";
			debug($sql);
			$res = mysql_query($sql);
			if(!$rs=mysql_fetch_array($res))
				my_error();
			$sql = "UPDATE sk_".$sklad."_ost SET ost=ost".($rs["type"]?"-":"+").abs($rs["quant"])." WHERE spr_id='$dvizh'";						debug($sql);
			if(!mysql_query($sql)) 
				my_error();
			$ddate = date("Y-m-d",mktime(0,0,0,substr($ddate,3,2),substr($ddate,0,2),substr($ddate,6,4)));//$dyear."-".$dmonth."-".$dday;
			$sql="UPDATE sk_".$sklad."_dvizh SET type='$type',numd='$numd',numdf='$numdf',docyr='$numyr',spr_id='$dvizh',quant='$quant',ddate='$ddate',post_id='$post_id',comment_id='$comment_id',price='$price' WHERE id='$dedit'" ;
			debug($sql);
			if(!mysql_query($sql)) 
				my_error();
			$sql = "UPDATE sk_".$sklad."_ost SET ost=ost".($type?"+":"-").abs($quant)." WHERE spr_id='$dvizh'";
			debug($sql);
			if(!mysql_query($sql)) 
				my_error();					
		}
		print "<script>window.location='http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."?dvizh=$dvizh';</script>";
	} else {
		if($dedit!=0) {
			$sql="SELECT *,sk_".$sklad."_dvizh.id,sk_".$sklad."_postav.id as supply_id FROM sk_".$sklad."_dvizh JOIN (sk_".$sklad."_postav,coments) ON (sk_".$sklad."_postav.id=sk_".$sklad."_dvizh.post_id AND coments.id=sk_".$sklad."_dvizh.comment_id) WHERE spr_id='$dvizh' AND sk_".$sklad."_dvizh.id='$dedit'";
			debug($sql);
			$res=mysql_query($sql);
			if (!$rs=mysql_fetch_array($res)) my_error();
		}
		$form = "<form action='' method=post>
		Дата:<input size=10 id=datepicker type=text name=ddate value='".($dedit!=0?date("d.m.Y",mktime(0,0,0,substr($rs["ddate"],5,2),substr($rs["ddate"],8,2),substr($rs["ddate"],1,4))):date("d.m.Y"))."'><br>
		Тип докмента:<select id=type name=type onchange=\"if ($('#type').attr('value')>0) $('#prihod').show(); else $('#prihod').hide();\">
		<option value=1 ".($rs["type"]==1?"selected":"").">Приход</option>
		<option value=0 ".($rs["type"]==0?"selected":"").">Расход</option>
		</select><br>
		Номер документа:<input size=10 type=text name=numd value='".$rs["numd"]."'><br>
		Количество:<input type=text name=quant value='".abs($rs["quant"])."'><br>";
		//if ($rs["type"]==1) { 
			$form .= "<div id=prihod ".($rs["type"]==1?"style='display:block'":"style='display:none'").">
			Поставщик:<select name=supply_id>
			<option value=0 ".($dedit==0?"selected":"").">Новый</option>";
			$sql="SELECT * FROM sk_".$sklad."_postav";
			debug($sql);
			$res1 = mysql_query($sql);
			while($rs1=mysql_fetch_array($res1)) {
				$form .= "<option value=".$rs1["id"]." ".($rs["supply_id"]==$rs1["id"]?"selected":"").">".$rs1["supply"]."</option>";
			}
			$form .= "</select>:<input type=text name=supply value='' size=20><br>
			Стоимость:<input type=text name=price value='".$rs["price"]."'><br>
			</div>";
			$form .= "Примечание:<input size=70 type=text name=comment value='".$rs["comment"]."'><br>";
		//}
		$form .= "
		<input type=submit name=submit value='Сохранить'><input type=button onclick='history.back()' value='Отмена'>
		<input type=hidden name=dedit value='$dedit'></form>
		<input type=hidden name=dvizh value='$dvizh'></form>";
		print $form;
		if ($dedit!=0 & $rs["type"]==0) {
			include "trebform.php";
		}
}		
} else {
	// вывести таблицу
	if(isset($all))
		print "<center><a href='http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."?dvizh=$dvizh'>Этот год</a>";
	else
		print "<center><a href='http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."?dvizh=$dvizh&all'>Все</a>";
	print "<form method=post action=''><input type=hidden name=action value='find'>Поиск:<input type=text name='ssrt' size=10><input type=hidden name=dvizh value=$dvizh></form>";
	print "<input type=button onclick=\"window.location='http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."?dvizh=$dvizh&dedit=0'\" value='Добавить новый'><br>";
	print "<input type=button onclick=\"window.location='http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."'\" value='Вернуться'><br>";
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
	if (isset($all)) 
		$proh=2; 
	else 
		$proh=1;
	for ($i=1;$i<=$proh;$i++) {
		if ($action=='find') 
			$sql="SELECT *,sk_".$sklad."_dvizh".((isset($all)&$i==1)?"_arc":"").".id FROM sk_".$sklad."_dvizh".((isset($all)&$i==1)?"_arc":"")." JOIN (sk_".$sklad."_postav,coments) ON (sk_".$sklad."_postav.id=sk_".$sklad."_dvizh".((isset($all)&$i==1)?"_arc":"").".post_id AND coments.id=sk_".$sklad."_dvizh".((isset($all)&$i==1)?"_arc":"").".comment_id) WHERE spr_id='$dvizh' AND (comment LIKE '%$ssrt%' OR supply LIKE '%$ssrt%' OR numd LIKE '%$ssrt%') ORDER BY ddate";
		else 
			$sql="SELECT *,sk_".$sklad."_dvizh".((isset($all)&$i==1)?"_arc":"").".id FROM sk_".$sklad."_dvizh".((isset($all)&$i==1)?"_arc":"")." JOIN (sk_".$sklad."_postav,coments) ON (sk_".$sklad."_postav.id=sk_".$sklad."_dvizh".((isset($all)&$i==1)?"_arc":"").".post_id AND coments.id=sk_".$sklad."_dvizh".((isset($all)&$i==1)?"_arc":"").".comment_id) WHERE spr_id='$dvizh' ORDER BY ddate ";
		debug($sql);
		$j = 0;
		$res = mysql_query($sql);
		while ($rs=mysql_fetch_array($res)) {
			if (!($j%2)) 
				print "<tr class='chettr'>";
			else 
				print "<tr class='nechettr'>";
			print "<td rowspan=2>";
			print ((isset($all)&$i==1)?"":"<a href='?dvizh=$dvizh&dedit=".$rs["id"]."' style='text-decoration:none;'>");
			print "<div style='width:100%; cursor:hand; height:100%;'>".$rs["ddate"]."</div>";
			print ((isset($all)&$i==1)?"":"</a>");
			print "</td>";
			print "<td align=center>";
			print ((isset($all)&$i==1)?"":"<a href='?dvizh=$dvizh&dedit=".$rs["id"]."' style='text-decoration:none;'>");
			print "<div style='width:100%; cursor:hand; height:100%;'>".($rs["type"]==1?"Приход":"Расход")."</div>";
			print ((isset($all)&$i==1)?"":"</a>");
			print "</td>";
			print "<td align=center>";
			print ((isset($all)&$i==1)?"":"<a href='?dvizh=$dvizh&dedit=".$rs["id"]."' style='text-decoration:none;'>");
			print "<div style='width:100%; cursor:hand; height:100%;'>".$rs["numd"]."</div>";
			print ((isset($all)&$i==1)?"":"</a>");
			print "</td>";
			print "<td rowspan=2 align=center>";
			print ((isset($all)&$i==1)?"":"<a href='?dvizh=$dvizh&dedit=".$rs["id"]."' style='text-decoration:none;'>");
			print "<div style='width:100%; cursor:hand; height:100%;'>".$rs["price"]."</div>";
			print ((isset($all)&$i==1)?"":"</a>");
			print "</td>";
			print "<td align=center>";
			print ((isset($all)&$i==1)?"":"<a href='?dvizh=$dvizh&dedit=".$rs["id"]."' style='text-decoration:none;'>");
			print "<div style='width:100%; cursor:hand; height:100%;'>".$rs["quant"]."</div>";
			print ((isset($all)&$i==1)?"":"</a>");
			print "</td>";
			print "<td rowspan=2 align=center>";
			print ((isset($all)&$i==1)?"":"<form action='' onsubmit=\"return confirm('Удалить ".$rs["ddate"]."--".$rs["numd"]."')\"><input type=hidden name=ddelete value=".$rs["id"]."><input type=hidden name=dvizh value=$dvizh><input type=submit value='Удалить'></form>");
			print "</td>";
			print "</tr>";
			if (!($j++%2)) 
				print "<tr class='chettr'>";
			else 
				print "<tr class='nechettr'>";
			print "<td colspan=2>";
			print ((isset($all)&$i==1)?"":"<a href='?dvizh=$dvizh&dedit=".$rs["id"]."' style='text-decoration:none;'>");
			print "<div style='width:100%; cursor:hand; height:100%;'>".$rs["supply"]."</div>";
			print ((isset($all)&$i==1)?"":"</a>");
			print "</td>";
			print "<td align=center>";
			print ((isset($all)&$i==1)?"":"<a href='?dvizh=$dvizh&dedit=".$rs["id"]."' style='text-decoration:none;'>");
			print "<div style='width:100%; cursor:hand; height:100%;'>".($rs["comment"]?$rs["comment"]:"&nbsp;")."</div>";
			print ((isset($all)&$i==1)?"":"</a>");
			print "</td>";
			print "</tr>";
		}
	}
	print "</table>";	
	print "<input type=button onclick=\"window.location='http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."'\" value='Вернуться'><br>";
}
?>