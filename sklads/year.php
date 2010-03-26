<?
// годова€ архиваци€
	// перенести движени€
	$sql = "INSERT INTO sk_".$sklad."_dvizh_arc (type,numd,numdf,docyr,spr_id,quant,ddate,post_id,comment_id,price) SELECT sk_".$sklad."_dvizh.type,sk_".$sklad."_dvizh.numd,sk_".$sklad."_dvizh.numdf,sk_".$sklad."_dvizh.docyr,sk_".$sklad."_dvizh.spr_id,sk_".$sklad."_dvizh.quant,sk_".$sklad."_dvizh.ddate,sk_".$sklad."_dvizh.post_id,sk_".$sklad."_dvizh.comment_id,sk_".$sklad."_dvizh.price FROM sk_".$sklad."_dvizh";
	//$sql = "INSERT INTO sk_".$sklad."_dvizh_arc SELECT * FROM sk_".$sklad."_dvizh";
	sql::query($sql) or die(sql::error(true));
	// очистить движени€
	$sql="TRUNCATE TABLE sk_".$sklad."_dvizh";
	sql::query($sql) or die(sql::error(true));


$sql = "SELECT * FROM sk_".$sklad."_spr";
$res= sql::fetchAll($sql);
foreach ($res as $rs){
	$id = $rs["id"];
	// получить остатки
	$sql="SELECT * FROM sk_".$sklad."_ost WHERE sk_".$sklad."_ost.spr_id='$id'";
	echo $sql."<br>";
	$ost = mysql_fetch_array(mysql_query($sql));
	$ost = $ost["ost"];
	// создать архивное движение
	// поставщик
	$sql="SELECT id FROM sk_".$sklad."_postav WHERE supply=''";
	echo$sql."<br>";
	$rs1 = sql::fetchOne($sql);
	if (!empty($rs1))
	{
		$post_id = $rs1["id"];
	}
	// коментарий
	$sql="SELECT id FROM coments WHERE comment='ѕередача остатка'";
	echo$sql."<br>";
	$rs1 = sql::fetchOne($sql);
	if (!empty($rs1)){
		$comment_id = $rs1["id"];
	} else {
		$sql="INSERT INTO coments (comment) VALUES ('ѕередача остатка')";
		sql::query($sql) or die(sql::error(true));
		$comment_id = sql::lastId();
	}
	$numd = "9999";
	$numdf = "9999";
	$docyr = date("Y")-1;
	$ddate = date("Y-m-d",mktime(0,0,0,12,31,$docyr));
	$sql="INSERT INTO sk_".$sklad."_dvizh_arc (type,numd,numdf,docyr,spr_id,quant,ddate,post_id,comment_id,price) VALUES ('0','$numd','$numdf','$docyr','$id','$ost','$ddate','$post_id','$comment_id','0')" ;
	echo $sql."<br>";
	sql::query($sql) or die(sql::error(true));	
	// создадим первое движение года
	// коментарий
	$sql="SELECT id FROM coments WHERE comment='ќстаток на 31.12.$docyr'";
	echo $sql."<br>";
	$rs1 = sql::fetchOne($sql);
	if (!empty($rs1)){
		$comment_id = $rs1["id"];
	} else {
		$sql="INSERT INTO coments (comment) VALUES ('ќстаток на 31.12.$docyr')";
		sql::query($sql) or die(sql::error(true));	
		$comment_id = sql::lastId();
	}
	$docyr = date("Y");
	$ddate = date("Y-m-d",mktime(0,0,0,1,1,$docyr));
	$sql="INSERT INTO sk_".$sklad."_dvizh (type,numd,numdf,docyr,spr_id,quant,ddate,post_id,comment_id,price) VALUES ('1','$numd','$numdf','$docyr','$id','$ost','$ddate','$post_id','$comment_id','0')" ;
	mylog1($sql);
	echo $sql."<br>";
		sql::query($sql) or die(sql::error(true));	
}

echo"<script>window.location='http://".$_SERVER['HTTP_HOST'].$_SERVER["PHP_SELF"]."';</script>";
?>