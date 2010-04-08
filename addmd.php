<?
/*
 * Добавляет дополнительные данны к мастерплатам 
 */
// Парамтеры
$customer=''; //Заказчик  тут обозначаю потому что при вызове движка будут переписаны из $_GET, а warnings будет меньше
$board=''; // Плата
$ndraw=
$osob=
$nstek=
$dop=
$nprokl=
$tprokl=
$dfrez=
$mn1=
$mn2=
$mn3=
$mn4=
$mn5=
$mn6=
$mn7=
$mn8=
$m1=
$m2=
$m3=
$m4=
$m5=
$m6=
$m7=
$m8=''; // параметры из ТЗ, но они не заполняются никем и судя по всему этот скрипт уже не нужен
require $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php"; 

// заказчик 
$sql="SELECT id FROM customers WHERE customer='$customer'";
$rs = sql::fetchOne($sql);
if (!empty($rs)) {
	$customer_id = $rs["id"];
} else {
	$sql="INSERT INTO customers (customer) VALUES ('$customer')";
	sql::query ($sql) or die(sql::error(true));
	$customer_id = sql::lastId();
}

// изменение платы
$sql="SELECT id FROM blocks WHERE customer_id='$customer_id' AND blockname='$board'";
$rs = sql::fetchOne($sql);
if (empty($rs)) {
	exit;
} else {
	$block_id = $rs[id];
}

$sql="SELECT id FROM mppdop WHERE block_id='$block_id'";
$rs = sql::fetchOne($sql);
if (empty($rs)) {
	$sql = "INSERT INTO mppdop (id,block_id,ndraw,osob,nstek,dop,nprokl,tprokl,dfrez,mn1,mn2,mn3,mn4,mn5,mn6,mn7,mn8,m1,m2,m3,m4,m5,m6,m7,m8) VALUES (NULL,'$block_id','$ndraw','$osob','$nstek','$dop','$nprokl','$tprokl','$dfrez','$mn1','$mn2','$mn3','$mn4','$mn5','$mn6','$mn7','$mn8','$m1','$m2','$m3','$m4','$m5','$m6','$m7','$m8')";
	sql::query ($sql) or die(sql::error(true));
} else {
	$et_id = $rs[id];
	$sql = "DELETE FROM mppdop WHERE id='$et_id'";
	sql::query ($sql) or die(sql::error(true));
	$sql = "INSERT INTO mppdop (id,block_id,ndraw,osob,nstek,dop,nprokl,tprokl,dfrez,mn1,mn2,mn3,mn4,mn5,mn6,mn7,mn8,m1,m2,m3,m4,m5,m6,m7,m8) VALUES ('$et_id','$block_id','$ndraw','$osob','$nstek','$dop','$nprokl','$tprokl','$dfrez','$mn1','$mn2','$mn3','$mn4','$mn5','$mn6','$mn7','$mn8','$m1','$m2','$m3','$m4','$m5','$m6','$m7','$m8')";
	sql::query ($sql) or die(sql::error(true));
}