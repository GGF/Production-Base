<?
$GLOBALS["debugAPI"] = true;
include "lib/sql.php";

$sql="SELECT id FROM customers WHERE customer='$customer'";
debug($sql);
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
debug($sql);
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
debug($sql);
$res = mysql_query($sql);
if (mysql_num_rows($res) == 0){
	$sql="INSERT INTO coppers (scomp,ssolder,drlname,customer_id,plate_id,sizex,sizey) VALUES ('$comp','$solder','$drillname','$customer_id','$plate_id','$sizex','$sizey')";
	debug($sql);
	mysql_query($sql);
} else {
	$sql="UPDATE coppers SET scomp='$comp', ssolder='$solder', drlname='$drillname', sizex='$sizex', sizey='$sizey' WHERE customer_id='$customer_id' AND plate_id='$plate_id'";
	debug($sql);
	mysql_query($sql);
}
// изменения в блоки
$sql="SELECT id FROM blocks WHERE customer_id='$customer_id' AND blockname='$board'";
debug($sql);
$res = mysql_query($sql);
if (!($rs=mysql_fetch_array($res))){
	$sql="INSERT INTO blocks (scomp,ssolder,drlname,customer_id,blockname,sizex,sizey) VALUES ('$comp','$solder','$drillname','$customer_id','$board','$sizex','$sizey')";
	debug($sql);
	mysql_query($sql);
} else {
	$plate_id = $rs["id"];
	$sql="UPDATE blocks SET scomp='$comp', ssolder='$solder', drlname='$drillname', sizex='$sizex', sizey='$sizey' WHERE id='$plate_id'";
	debug($sql);
	mysql_query($sql);
}
?>