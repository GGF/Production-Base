<?
/*
Добавляет позицию ТЗ в базу в базу
 Параметры
 tz - идентификатор ТЗ
 posintz - номер позиции в ТЗ
 customer - заказчик
 board - имя платы
 numbers - количество
 zap - запущена или нет (0/1)
 user - кто добавил
 Возвращает id 
*/

include_once $_SERVER["DOCUMENT_ROOT"]."/lib/sql.php"; // это нужно при добавлении так как не вызывается заголовк html

// Определим идентификатор пользователя
$sql="SELECT id FROM users WHERE nik='$user'";
$rs = sql::fetchOne($sql);
if (!empty($rs)) {
	$user_id = $rs["id"];
} else {
	$sql="INSERT INTO users (nik) VALUES ('$user')";
	sql::query ($sql) or die(sql::error(true));
	$user_id = sql::lastId();
}

// найдем номер заказчика
$file_link = addslashes($file_link);
$sql="SELECT id FROM customers WHERE customer='$customer'";
$rs = sql::fetchOne($sql);
if (!empty($rs)) {
	$customer_id = $rs["id"];
} else {
	$sql="INSERT INTO customers (customer) VALUES ('$customer')";
	sql::query ($sql) or die(sql::error(true));
	$customer_id = sql::lastId();
}
// определим плату
$sql="SELECT id FROM plates WHERE customer_id='$customer_id' AND plate='$board'";
$rs = sql::fetchOne($sql);
if (!empty($rs)) {
	$plate_id = $rs["id"];
} else {
	$sql="INSERT INTO plates (customer_id,plate) VALUES ('$customer_id','$board')";
	sql::query ($sql) or die(sql::error(true));
	$plate_id = sql::lastId();
}
// определим плату
$sql="SELECT id FROM boards WHERE customer_id='$customer_id' AND board_name='$board'";
$rs = sql::fetchOne($sql);
if (!empty($rs)) {
	$board_id = $rs["id"];
} else {
	// не буду добавлять - данных нет и это делается в другом месте
}
// Определим блок - он уже должен быть в базе
$sql = "SELECT * FROM blockpos WHERE board_id='$board_id'";
$rs = sql::fetchOne($sql);
if (!empty($rs)) {
	$block_id = $rs["block_id"];
} else {
	// не буду добавлять - данных нет и это делается в другом месте
}

// добавим МП если есть такое исправим
$sql="SELECT * FROM posintz WHERE tz_id='$tz' AND posintz='$posintz'";
$rs = sql::fetchOne($sql);
if (empty($rs)) {
	$sql="INSERT INTO posintz (tz_id,posintz,plate_id,board_id,block_id,numbers,first,srok,priem,constr,template_check,template_make,eltest,numpl,numbl,pitz_mater,pitz_psimat) VALUES ('$tz','$posintz','$plate_id','$board_id','$block_id','$numbers','$first','$srok','$priem','$constr','$template_check','$template_make','$eltest','$numpl','$numbl','$textolite','$textolitepsi')";
	sql::query ($sql) or die(sql::error(true));
	$pit_id = sql::lastId();
} else {
	$sql="UPDATE posintz SET numbers='$numbers', plate_id='$board_id',plate_id='$board_id', block_id='$block_id',first='$first',srok='$srok',priem='$priem',constr='$constr',template_check='$template_check',template_make='$template_make', eltest='$eltest', numpl='$numpl', numbl='$numbl', pitz_mater='$textolite', pitz_psimat='$textolitepsi' WHERE tz_id='$tz' AND posintz='$posintz'";
	sql::query ($sql) or die(sql::error(true));
	$pit_id = $rs["id"];
}

// обновить запуски если некоторые позиции уже запускались
$sql="SELECT * FROM lanch WHERE tz_id='$tz' AND pos_in_tz='$posintz'";
$rs = sql::fetchOne($sql);
if (empty($rs)) {
	$sql="UPDATE lanch SET pos_in_tz_id='$pit_id' WHERE id='".$rs["id"]."'";
	sql::query ($sql) or die(sql::error(true));
	$sql="UPDATE posintz SET ldate='".$rs["ldate"]."' WHERE id='$pit_id'";
	sql::query ($sql) or die(sql::error(true));
}
printf("%08d",$pit_id);
?>