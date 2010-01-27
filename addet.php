<?

$GLOBALS["debugAPI"] = true;
include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php"; // это нужно при добавлении так как не вызывается заголовк html

// заказчик 
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

// изменение платы
$sql="SELECT id FROM boards WHERE customer_id='$customer_id' AND board_name='$board'";
debug($sql);
$res = mysql_query($sql);
if (!($rs=mysql_fetch_array($res))){
	my_error();
} else {
	$plate_id = $rs[0];
}

$sql="SELECT id FROM eltest WHERE board_id='$plate_id'";
debug($sql);
$res = mysql_query($sql);
if (!($rs=mysql_fetch_array($res))){
	$sql = "INSERT INTO eltest (id,board_id,type,points,pib,pointsb,factor,numcomp,sizex,sizey,numpl) VALUES (
NULL,'$plate_id','$type','$points','$pib','$pointsb','$factor','$numcomp','$sizex','$sizey','$numpl')";
	debug($sql);
	mysql_query($sql);
} else {
	$et_id = $rs[0];
	$sql = "DELETE FROM eltest WHERE id='$et_id'";
	debug($sql);
	mysql_query($sql);
	$sql = "INSERT INTO eltest (id,board_id,type,points,pib,pointsb,factor,numcomp,sizex,sizey,numpl) VALUES (
'$et_id','$plate_id','$type','$points','$pib','$pointsb','$factor','$numcomp','$sizex','$sizey','$numpl')";
	debug($sql);
	mysql_query($sql);
}