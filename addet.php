<?
include_once $_SERVER ["DOCUMENT_ROOT"] . "/lib/engine.php"; // это нужно при добавлении так как не вызывается заголовк html


// заказчик 
$sql = "SELECT id FROM customers WHERE customer='$customer'";
$rs = sql::fetchOne($sql);
if (!empty($rs)) {
	$customer_id = $rs ["id"];
} else {
	$sql = "INSERT INTO customers (customer) VALUES ('$customer')";
	sql::query ($sql) or die(sql::error(true));
	$customer_id = sql::lastId();
}

// изменение платы
$sql = "SELECT id FROM boards WHERE customer_id='$customer_id' AND board_name='$board'";
$rs = sql::fetchOne($sql);
if (empty($rs)) {
	exit;
} else {
	$plate_id = $rs [id];
}

$sql = "SELECT id FROM eltest WHERE board_id='$plate_id'";
$rs = sql::fetchOne($sql);
if (empty($rs)) {
	$sql = "INSERT INTO eltest (id,board_id,type,points,pib,pointsb,factor,numcomp,sizex,sizey,numpl) VALUES (NULL,'$plate_id','$type','$points','$pib','$pointsb','$factor','$numcomp','$sizex','$sizey','$numpl')";
	sql::query ($sql) or die(sql::error(true));
} else {
	$et_id = $rs [id];
	$sql = "DELETE FROM eltest WHERE id='$et_id'";
	sql::query ($sql) or die(sql::error(true));
	$sql = "INSERT INTO eltest (id,board_id,type,points,pib,pointsb,factor,numcomp,sizex,sizey,numpl) VALUES ('$et_id','$plate_id','$type','$points','$pib','$pointsb','$factor','$numcomp','$sizex','$sizey','$numpl')";
	sql::query ($sql) or die(sql::error(true));
}