<?
$GLOBALS["debugAPI"] = false;
include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php"; // это нужно при добавлении так как не вызывается заголовк html

if (empty($customer)) return;
if (empty($board)) return;
$sql="SELECT id FROM customers WHERE customer='$customer'";
debug("rem ".$sql);
$res = mysql_query($sql);
if ($rs=mysql_fetch_array($res)){
	$customer_id = $rs["id"];
} else {
	$sql="INSERT INTO customers (customer) VALUES ('$customer')";
	print $sql;
	mysql_query($sql);
	$customer_id = mysql_insert_id();
	if (!$customer_id) exit;
}
$sql="SELECT id FROM plates WHERE customer_id='$customer_id' AND plate='$board'";
debug("rem ".$sql);
$res = mysql_query($sql);
if ($rs=mysql_fetch_array($res)){
	$plate_id = $rs["id"];
} else {
	$sql="INSERT INTO plates (customer_id,plate) VALUES ('$customer_id','$board')";
	debug($sql);
	mysql_query($sql);
	$plate_id = mysql_insert_id();
	if (!$plate_id) exit;
}
$sql="SELECT * FROM coppers WHERE customer_id='$customer_id' AND plate_id='$plate_id'";
debug("rem ".$sql);
$res = mysql_query($sql);
if (mysql_num_rows($res) == 0){
	$sql="INSERT INTO coppers (scomp,ssolder,drlname,customer_id,plate_id,sizex,sizey) VALUES ('$comp','$solder','$drillname','$customer_id','$plate_id','$sizex','$sizey')";
	debug("rem ".$sql);
	mysql_query($sql);
} else {
	$sql="UPDATE coppers SET scomp='$comp', ssolder='$solder', drlname='$drillname', sizex='$sizex', sizey='$sizey' WHERE customer_id='$customer_id' AND plate_id='$plate_id'";
	debug("rem ".$sql);
	mysql_query($sql);
}
// изменения в блоки
$sql="SELECT id FROM blocks WHERE customer_id='$customer_id' AND blockname='$board'";
debug("rem ".$sql);
$res = mysql_query($sql);
if (!($rs=mysql_fetch_array($res))){
	$sql="INSERT INTO blocks (scomp,ssolder,drlname,customer_id,blockname,sizex,sizey) VALUES ('$comp','$solder','$drillname','$customer_id','$board','$sizex','$sizey')";
	debug("rem ".$sql);
	mysql_query($sql);
} else {
	$plate_id = $rs["id"];
	$sql="UPDATE blocks SET scomp='$comp', ssolder='$solder', drlname='$drillname', sizex='$sizex', sizey='$sizey' WHERE id='$plate_id'";
	debug("rem ".$sql);
	mysql_query($sql);
}

// а тепрерь созадидим фал копирования сверловок
$sql="SELECT kdir FROM customers WHERE id='$customer_id'";
debug("rem ".$sql);
$res = mysql_query($sql);
if($rs=mysql_fetch_array($res)) {
	if ($customer == "Импульс" ) { $rs[0].="\\$drillname"; $mpp=-1;}
	echo "mkdir k:\\".$rs[0].($mpp!=-1?"\\MPP":"")."\\\n";
	echo "copy /Y .\\$drillname.mk2 k:\\".$rs[0].($mpp!=-1?"\\MPP":"")."\\\n";
	echo "copy /Y .\\$drillname.mk4 k:\\".$rs[0].($mpp!=-1?"\\MPP":"")."\\\n";
	echo "copy /Y .\\$drillname.frz k:\\".$rs[0].($mpp!=-1?"\\MPP":"")."\\\n";
}
?>