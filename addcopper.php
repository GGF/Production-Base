<?
/*
 *  Заносит площадь и размеры заготовки в базу
 */
// Парамтеры
$customer=''; //Заказчик  тут обозначаю потому что при вызове движка будут переписаны из $_GET, а warnings будет меньше
$board=''; // Плата
$comp=$solder=$drillname=$sizex=$sizey='';
// соответственно
// площадь, площадь, имя сверловки, размер, размер 
require $_SERVER [DOCUMENT_ROOT] . "/lib/engine.php"; // это нужно при добавлении так как не вызывается заголовк html


if (empty ( $customer ))
	return;
if (empty ( $board ))
	return;
$sql = "SELECT id FROM customers WHERE customer='$customer'";
$rs = sql::fetchOne($sql);
if (!empty($rs)) {
	$customer_id = $rs["id"];
} else {
	$sql = "INSERT INTO customers (customer) VALUES ('$customer')";
	sql::query ($sql) or die(sql::error(true));
	$customer_id = sql::lastId();
}
$sql = "SELECT id FROM plates WHERE customer_id='$customer_id' AND plate='$board'";
$rs = sql::fetchOne($sql);
if (!empty($rs)) {
	$plate_id = $rs ["id"];
} else {
	$sql = "INSERT INTO plates (customer_id,plate) VALUES ('$customer_id','$board')";
	sql::query ($sql) or die(sql::error(true));
	$plate_id = sql::lastId();
}
$sql = "SELECT * FROM coppers WHERE customer_id='$customer_id' AND plate_id='$plate_id'";
$rs = sql::fetchOne($sql);
if (empty($rs)) {
	$sql = "INSERT INTO coppers (scomp,ssolder,drlname,customer_id,plate_id,sizex,sizey) VALUES ('$comp','$solder','$drillname','$customer_id','$plate_id','$sizex','$sizey')";
	sql::query ($sql) or die(sql::error(true));
} else {
	$sql = "UPDATE coppers SET scomp='$comp', ssolder='$solder', drlname='$drillname', sizex='$sizex', sizey='$sizey' WHERE customer_id='$customer_id' AND plate_id='$plate_id'";
	sql::query ($sql) or die(sql::error(true));
}
// изменения в блоки
$sql = "SELECT id FROM blocks WHERE customer_id='$customer_id' AND blockname='$board'";
$rs = sql::fetchOne($sql);
if (empty($rs)) {
	$sql = "INSERT INTO blocks (scomp,ssolder,drlname,customer_id,blockname,sizex,sizey) VALUES ('$comp','$solder','$drillname','$customer_id','$board','$sizex','$sizey')";
	sql::query ($sql) or die(sql::error(true));
} else {
	$plate_id = $rs ["id"];
	$sql = "UPDATE blocks SET scomp='$comp', ssolder='$solder', drlname='$drillname', sizex='$sizex', sizey='$sizey' WHERE id='$plate_id'";
	sql::query ($sql) or die(sql::error(true));
}

// а тепрерь созадидим фал копирования сверловок
$sql = "SELECT kdir FROM customers WHERE id='$customer_id'";
$rs = sql::fetchOne($sql);
if (empty($rs)) {
	if ($customer == "Импульс") {
		$rs [kdir] .= "\\$drillname";
		$mpp = -1;
	}
	echo "mkdir k:\\" . $rs [0] . ($mpp != - 1 ? "\\MPP" : "") . "\\\n";
	echo "copy /Y .\\$drillname.mk2 k:\\" . $rs [0] . ($mpp != - 1 ? "\\MPP" : "") . "\\\n";
	echo "copy /Y .\\$drillname.mk4 k:\\" . $rs [0] . ($mpp != - 1 ? "\\MPP" : "") . "\\\n";
	echo "copy /Y .\\$drillname.frz k:\\" . $rs [0] . ($mpp != - 1 ? "\\MPP" : "") . "\\\n";
}
?>