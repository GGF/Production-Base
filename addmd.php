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
$sql="SELECT id FROM blocks WHERE customer_id='$customer_id' AND blockname='$board'";
debug($sql);
$res = mysql_query($sql);
if (!($rs=mysql_fetch_array($res))){
	my_error();
} else {
	$block_id = $rs[0];
}

$sql="SELECT id FROM mppdop WHERE block_id='$block_id'";
debug($sql);
$res = mysql_query($sql);
if (!($rs=mysql_fetch_array($res))){
	$sql = "INSERT INTO mppdop (id,block_id,ndraw,osob,nstek,dop,nprokl,tprokl,dfrez,mn1,mn2,mn3,mn4,mn5,mn6,mn7,mn8,m1,m2,m3,m4,m5,m6,m7,m8) VALUES (NULL,'$block_id','$ndraw','$osob','$nstek','$dop','$nprokl','$tprokl','$dfrez','$mn1','$mn2','$mn3','$mn4','$mn5','$mn6','$mn7','$mn8','$m1','$m2','$m3','$m4','$m5','$m6','$m7','$m8')";
	debug($sql);
	mysql_query($sql);
} else {
	$et_id = $rs[0];
	$sql = "DELETE FROM mppdop WHERE id='$et_id'";
	debug($sql);
	mysql_query($sql);
	$sql = "INSERT INTO mppdop (id,block_id,ndraw,osob,nstek,dop,nprokl,tprokl,dfrez,mn1,mn2,mn3,mn4,mn5,mn6,mn7,mn8,m1,m2,m3,m4,m5,m6,m7,m8) VALUES ('$et_id','$block_id','$ndraw','$osob','$nstek','$dop','$nprokl','$tprokl','$dfrez','$mn1','$mn2','$mn3','$mn4','$mn5','$mn6','$mn7','$mn8','$m1','$m2','$m3','$m4','$m5','$m6','$m7','$m8')";
	debug($sql);
	mysql_query($sql);
}