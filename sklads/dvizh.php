<?
$dbname = 'zaomppsklads';
include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php";
authorize();
$sklad = $_COOKIE["sklad"];

if(isset($id)) $spr_id=$id;


if (isset($delete)) 
{
	$sql = "SELECT * FROM sk_".$sklad."_dvizh WHERE id='$delete'";
	mylog1($sql);
	$res = mysql_query($sql);
	if(!$rs=mysql_fetch_array($res))
		my_error();
	$sql = "UPDATE sk_".$sklad."_ost SET ost=ost".($rs["type"]?"-":"+").abs($rs["quant"])." WHERE spr_id='".$rs["spr_id"]."'";
	mylog1($sql);
	if(!mysql_query($sql)) 
		my_error();
	$sql = "DELETE FROM sk_".$sklad."_dvizh WHERE id='$delete'";
	mylog1($sql);
	if(!mysql_query($sql)) 
		my_error();
	echo "ok";
} 
elseif (isset($edit)) 
{
	if (isset($accept)) {
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
		if ($edit==0) {
			//добавление нового
			$ddate = date("Y-m-d",mktime(0,0,0,substr($ddate,3,2),substr($ddate,0,2),substr($ddate,6,4)));//$dyear."-".$dmonth."-".$dday;
			$sql="INSERT INTO sk_".$sklad."_dvizh (type,numd,numdf,docyr,spr_id,quant,ddate,post_id,comment_id,price) VALUES ('$type','$numd','$numdf','$numyr','$id','$quant','$ddate','$post_id','$comment_id','$price')" ;
			debug($sql);
			if(!mysql_query($sql)) 
				my_error();
			$sql = "UPDATE sk_".$sklad."_ost SET ost=ost".($type?"+":"-").abs($quant)." WHERE spr_id='$id'";
			debug($sql);
			if(!mysql_query($sql)) 
				my_error();	
		} 
		else 
		{
			// удалить  старое движенеи
			$sql = "SELECT * FROM sk_".$sklad."_dvizh WHERE id='$edit'";
			debug($sql);
			$res = mysql_query($sql);
			if(!$rs=mysql_fetch_array($res))
				my_error();
			$sql = "UPDATE sk_".$sklad."_ost SET ost=ost".($rs["type"]?"-":"+").abs($rs["quant"])." WHERE spr_id='$id'";						debug($sql);
			if(!mysql_query($sql)) 
				my_error();
			$ddate = date("Y-m-d",mktime(0,0,0,substr($ddate,3,2),substr($ddate,0,2),substr($ddate,6,4)));//$dyear."-".$dmonth."-".$dday;
			$sql="UPDATE sk_".$sklad."_dvizh SET type='$type',numd='$numd',numdf='$numdf',docyr='$numyr',spr_id='$id',quant='$quant',ddate='$ddate',post_id='$post_id',comment_id='$comment_id',price='$price' WHERE id='$edit'" ;
			debug($sql);
			if(!mysql_query($sql)) 
				my_error();
			$sql = "UPDATE sk_".$sklad."_ost SET ost=ost".($type?"+":"-").abs($quant)." WHERE spr_id='$id'";
			debug($sql);
			if(!mysql_query($sql)) 
				my_error();					
		}
	} 
	else 
	{
		if($edit!=0) {
			$sql="SELECT *,sk_".$sklad."_dvizh.id,sk_".$sklad."_postav.id as supply_id FROM sk_".$sklad."_dvizh JOIN (sk_".$sklad."_postav,coments) ON (sk_".$sklad."_postav.id=sk_".$sklad."_dvizh.post_id AND coments.id=sk_".$sklad."_dvizh.comment_id) WHERE spr_id='$id' AND sk_".$sklad."_dvizh.id='$edit'";
			debug($sql);
			$res=mysql_query($sql);
			if (!$rs=mysql_fetch_array($res)) my_error();
		}
		$form = "<form action='' method=post>
		Дата:<input size=10 id=datepicker type=text name=ddate value='".($edit!=0?date("d.m.Y",mktime(0,0,0,substr($rs["ddate"],5,2),substr($rs["ddate"],8,2),substr($rs["ddate"],1,4))):date("d.m.Y"))."'><br>
		Тип докмента:<select id=type name=type onchange=\"if ($('#type').attr('value')>0) $('#prihod').show(); else $('#prihod').hide();\">
		<option value=1 ".($rs["type"]==1?"selected":"").">Приход</option>
		<option value=0 ".($rs["type"]==0?"selected":"").">Расход</option>
		</select><br>
		Номер документа:<input size=10 type=text name=numd value='".$rs["numd"]."'><br>
		Количество:<input type=text name=quant value='".abs($rs["quant"])."'><br>";
		//if ($rs["type"]==1) { 
			$form .= "<div id=prihod ".($rs["type"]==1?"style='display:block'":"style='display:none'").">
			Поставщик:<select name=supply_id>
			<option value=0 ".($edit==0?"selected":"").">Новый</option>";
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
		<input type=hidden name=dedit value='$edit'></form>
		<input type=hidden name=dvizh value='$id'></form>";
		print $form;
		if ($edit!=0 & $rs["type"]==0) {
			include "trebform.php";
		}
}		
} 
else 
{

	if (isset($all)) {
		$sql="(SELECT *,if(type='1','Приход','Расход') AS prras, sk_".$sklad."_dvizh.id FROM sk_".$sklad."_dvizh JOIN (sk_".$sklad."_postav,coments) ON (sk_".$sklad."_postav.id=sk_".$sklad."_dvizh.post_id AND coments.id=sk_".$sklad."_dvizh.comment_id) WHERE spr_id='$spr_id' ".(isset($find)?"AND comment LIKE '%$find%' OR supply LIKE '%$find%' OR numd LIKE '%$find%' ":"").") UNION (SELECT *,if(type='1','Приход','Расход') AS prras, sk_".$sklad."_dvizh_arc.id FROM sk_".$sklad."_dvizh_arc JOIN (sk_".$sklad."_postav,coments) ON (sk_".$sklad."_postav.id=sk_".$sklad."_dvizh_arc.post_id AND coments.id=sk_".$sklad."_dvizh_arc.comment_id) WHERE spr_id='$spr_id' ".(isset($find)?"AND comment LIKE '%$find%' OR supply LIKE '%$find%' OR numd LIKE '%$find%' ":"").") ".($order!=''?"ORDER BY ".$order." ":"ORDER BY ddate ");
	} else {
		$sql="SELECT *,if(type='1','Приход','Расход') AS prras, sk_".$sklad."_dvizh.id FROM sk_".$sklad."_dvizh JOIN (sk_".$sklad."_postav,coments) ON (sk_".$sklad."_postav.id=sk_".$sklad."_dvizh.post_id AND coments.id=sk_".$sklad."_dvizh.comment_id) WHERE spr_id='$spr_id' ".(isset($find)?"AND comment LIKE '%$find%' OR supply LIKE '%$find%' OR numd LIKE '%$find%' ":"").($order!=''?"ORDER BY ".$order." ":"ORDER BY ddate ");
	}
	//echo $sql;

	$cols[ddate]="Дата";
	$cols[prras]="+/-";
	$cols[numd]="№ док.";
	$cols[supply]="Поставщик";
	$cols[quant]="Кол-во";
	$cols[comment]="Примечание";
	$cols[price]="Цена";

	
	$table = new Table("dvizh","dvizh",$sql,$cols,false);
	if (isset($spr_id)) $table->idstr = "&spr_id=$spr_id";
	$table->addbutton=true;
	$table->show();
	
	
}
?>