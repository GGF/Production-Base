<?
$dbname = 'zaomppsklads';
include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php";
authorize();
$sklad = $_COOKIE["sklad"];

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
	echo "ok";
} 
 elseif (isset($dvizh))
{
	include 'dvizh.php';
} 
 elseif (isset($edit))
{
	if (isset($accept)) 
	{
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
		echo "<script>updatetable('$tid','ost','');closeedit();</script>";
	} 
	else 
	{
		if($edit!=0) {
			$sql="SELECT * FROM sk_".$sklad."_spr WHERE id='".$edit."'";
			$res=mysql_query($sql);
			if (!$rs=mysql_fetch_array($res)) my_error();
		}
		echo "<form method=post id=editform>";
		echo "<input type='hidden' value='".(isset($edit)?$edit:"0")."' name='edit'>";
		echo "<input type=hidden name=tid value='$tid'>";
		echo "<input type=hidden name=uid value='$uid'>";
		echo "<input type=hidden name=accept value='yes'>";
		echo "Наименование:<input size=50 type=text name=nazv value='".$rs["nazv"]."'><br>Единица измерения:<input type=text name=edizm value='".$rs["edizm"]."'><br>Критический остаток:<input type=text name=krost value='".$rs["krost"]."'>";
		echo "<br><input type=button value='Сохранить' onclick=\"editrecord('ost',$('#editform').serialize())\"><input type=button value='Отмена' onclick='closeedit()'><input type=button onclick=\"alert($('#editform').serialize())\">";
	}
} else
{
// вывести таблицу

	$sql="SELECT *,if((krost>ost),'<span style=\'color:red\'><b>мало</b></span>','') as malo,sk_".$sklad."_spr.id FROM `sk_".$sklad."_spr` JOIN sk_".$sklad."_ost ON sk_".$sklad."_ost.spr_id=sk_".$sklad."_spr.id WHERE nazv!='' ".(isset($find)?"AND nazv LIKE '%$find%' ":"").($order!=''?"ORDER BY ".$order." ":"ORDER BY nazv ").(isset($all)?"":"LIMIT 20");
	//echo $sql;
	
	$cols[nazv]="Название";
	$cols[edizm]="Ед.Изм.";
	$cols[ost]="Остаток на складе";
	$cols[krost]="Крит. кол-во";
	$cols[malo]="Внимание";

	
	$table = new Table("ost","dvizh",$sql,$cols,false);
	$table->addbutton=true;
	$table->show();
}

?>