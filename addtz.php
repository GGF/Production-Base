<?
// ��������� �� � ����� � ����
// ���������
// customer - �������� ��������� ������ ����������� ��� ���������
// number - ����� ����� ���������
// order_date - ���� ������
// tz_date - ���� ���������� ��
// user - ��� ����������� �� - �������� �� ����� �����
// file_link - ������ �� ���� ���� � ���� ����� ���� � UNC
//
// ���������� id ����������� ��

$GLOBALS["debugAPI"] = false;
include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php"; // ��� ����� ��� ��� �� ���������� �������� html

// ������ ���� ���������
$file_link = addslashes($file_link);
$file_link = strtolower($file_link);
$file_link = eregi_replace("\\\\\\\\\\\\\\\\servermpp\\\\\\\\(.)","\\1:",$file_link);
debug ($file_link);
$sql="SELECT id FROM customers WHERE customer='$customer'";
debug($sql);
$res = mysql_query($sql);
if ($rs=mysql_fetch_array($res)){
	$customer_id = $rs["id"];
} else {
	$sql="INSERT INTO customers (customer) VALUES ('$customer')";
	debug($sql);
	mysql_query($sql);
	$customer_id = mysql_insert_id();
	if (!$customer_id) my_error();
}
// ������� ����� � ��������� �������������
$sql="SELECT id FROM orders WHERE customer_id='$customer_id' AND orderdate='$order_date' AND number='$number'";
debug($sql);
$res = mysql_query($sql);
if ($rs=mysql_fetch_array($res)){
	$order_id = $rs["id"];
} else {
	$sql="INSERT INTO orders (customer_id,number,orderdate) VALUES ('$customer_id','$number','$order_date')";
	debug($sql);
	mysql_query($sql);
	$order_id = mysql_insert_id();
	if (!$order_id) my_error();
}
// ��������� ������������� ������������
$sql="SELECT id FROM users WHERE nik='$user'";
debug($sql);
$res = mysql_query($sql);
if ($rs=mysql_fetch_array($res)){
	$user_id = $rs["id"];
} else {
	$sql="INSERT INTO users (nik) VALUES ('$user')";
	debug($sql);
	mysql_query($sql);
	$user_id = mysql_insert_id();
	if (!$user_id) my_error();
}
// ��������� ������������� �������� ������
$file_link = str_replace("\\\\servermpp\\t","t:",$file_link);
$sql="SELECT id FROM filelinks WHERE file_link='$file_link'";
debug($sql);
$res = mysql_query($sql);
if ($rs=mysql_fetch_array($res)){
	$file_id = $rs["id"];
} else {
	$sql="INSERT INTO filelinks (file_link) VALUES ('$file_link')";
	debug($sql);
	mysql_query($sql);
	$file_id = mysql_insert_id();
	if (!$file_id) my_error();
}
// ������� ����� ��������� ������� �� � ������
$sql="SELECT max(pos_in_order) FROM tz WHERE order_id='$order_id'";
debug($sql);
$res = mysql_query($sql);
if ($rs=mysql_fetch_array($res)) {
	$pos_in_order = $rs[0]+1;
} else {
	$pos_in_order = 1;
}
// ������� �� ���� ���� ����� ������� ������ ������� - ���� ���1 ���2 � �.�.
$sql="SELECT * FROM tz WHERE file_link_id='$file_id'";
debug($sql);
$res = mysql_query($sql);
if (!$rs=mysql_fetch_array($res)){
	$sql="INSERT INTO tz (order_id,tz_date,user_id,pos_in_order,file_link_id) VALUES ('$order_id','$tz_date','$user_id','$pos_in_order','$file_id')";
	debug($sql);
	mysql_query($sql);
	$tz_id = mysql_insert_id();
	if (!$tz_id) my_error();
		
} else {
	$sql="DELETE FROM posintz WHERE tz_id='".$rs["id"]."'";
	debug($sql);
	mysql_query($sql);
	$sql="UPDATE tz SET order_id='$order_id', tz_date='$tz_date', user_id='$user_id' WHERE file_link_id='$file_id'";
	debug($sql);
	mysql_query($sql);
	//$rs=mysql_fetch_array($res);
	$tz_id = $rs["id"];
}
printf("%08d",$tz_id);
?>