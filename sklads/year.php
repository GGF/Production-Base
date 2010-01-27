<?
// годова€ архиваци€
	// перенести движени€
	$sql = "INSERT INTO sk_".$sklad."_dvizh_arc (type,numd,numdf,docyr,spr_id,quant,ddate,post_id,comment_id,price) SELECT sk_".$sklad."_dvizh.type,sk_".$sklad."_dvizh.numd,sk_".$sklad."_dvizh.numdf,sk_".$sklad."_dvizh.docyr,sk_".$sklad."_dvizh.spr_id,sk_".$sklad."_dvizh.quant,sk_".$sklad."_dvizh.ddate,sk_".$sklad."_dvizh.post_id,sk_".$sklad."_dvizh.comment_id,sk_".$sklad."_dvizh.price FROM sk_".$sklad."_dvizh";
	//$sql = "INSERT INTO sk_".$sklad."_dvizh_arc SELECT * FROM sk_".$sklad."_dvizh";
	mylog1($sql);
	if(!mysql_query($sql)) 
		my_error();	
	// очистить движени€
	$sql="TRUNCATE TABLE sk_".$sklad."_dvizh";
	mylog1($sql);
	if(!mysql_query($sql)) 
		my_error();	



$sql = "SELECT * FROM sk_".$sklad."_spr";
$res= mysql_query($sql);
while ($rs=mysql_fetch_array($res)){
	$id = $rs["id"];
	// получить остатки
	$sql="SELECT * FROM sk_".$sklad."_ost WHERE sk_".$sklad."_ost.spr_id='$id'";
	print $sql."<br>";
	$ost = mysql_fetch_array(mysql_query($sql));
	$ost = $ost["ost"];
	// создать архивное движение
	// поставщик
	$sql="SELECT id FROM sk_".$sklad."_postav WHERE supply=''";
	print $sql."<br>";
	$res1 = mysql_query($sql);
	if ($rs1=mysql_fetch_array($res1)){
		$post_id = $rs1["id"];
	} else 
		my_error();	
	// коментарий
	$sql="SELECT id FROM coments WHERE comment='ѕередача остатка'";
	print $sql."<br>";
	$res1 = mysql_query($sql);
	if ($rs1=mysql_fetch_array($res1)){
		$comment_id = $rs1["id"];
	} else {
		$sql="INSERT INTO coments (comment) VALUES ('ѕередача остатка')";
		mylog1($sql);
		mysql_query($sql);
		$comment_id = mysql_insert_id();
		if (!$comment_id) my_error();
	}
	$numd = "9999";
	$numdf = "9999";
	$docyr = date("Y")-1;
	$ddate = date("Y-m-d",mktime(0,0,0,12,31,$docyr));
	$sql="INSERT INTO sk_".$sklad."_dvizh_arc (type,numd,numdf,docyr,spr_id,quant,ddate,post_id,comment_id,price) VALUES ('0','$numd','$numdf','$docyr','$id','$ost','$ddate','$post_id','$comment_id','0')" ;
	mylog1($sql);
	print $sql."<br>";
	if(!mysql_query($sql)) 
		my_error();	
	// создадим первое движение года
	// коментарий
	$sql="SELECT id FROM coments WHERE comment='ќстаток на 31.12.$docyr'";
	print $sql."<br>";
	$res1 = mysql_query($sql);
	if ($rs1=mysql_fetch_array($res1)){
		$comment_id = $rs1["id"];
	} else {
		$sql="INSERT INTO coments (comment) VALUES ('ќстаток на 31.12.$docyr')";
		mylog1($sql);
		mysql_query($sql);
		$comment_id = mysql_insert_id();
		if (!$comment_id) my_error();
	}
	$docyr = date("Y");
	$ddate = date("Y-m-d",mktime(0,0,0,1,1,$docyr));
	$sql="INSERT INTO sk_".$sklad."_dvizh (type,numd,numdf,docyr,spr_id,quant,ddate,post_id,comment_id,price) VALUES ('1','$numd','$numdf','$docyr','$id','$ost','$ddate','$post_id','$comment_id','0')" ;
	mylog1($sql);
	print $sql."<br>";
	if(!mysql_query($sql)) 
		my_error();
	
}

print "<script>window.location='http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."';</script>";
?>