<?
// Добавляет позицию ТЗ в базу в базу
// Параметры
// tz - идентификатор ТЗ
// posintz - номер позиции в ТЗ
// customer - заказчик
// board - имя платы
// numbers - количество
// zap - запущена или нет (0/1)
// user - кто добавил
// Возвращает id 

$GLOBALS["debugAPI"] = true;
include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php"; // это нужно при добавлении так как не вызывается заголовк html

// Определим идентификатор пользователя
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

// найдем номер заказчика
$file_link = addslashes($file_link);
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
// определим плату
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
	if (!$plate_id) my_error();
}
// определим плату
$sql="SELECT id FROM boards WHERE customer_id='$customer_id' AND board_name='$board'";
debug($sql);
$res = mysql_query($sql);
if ($rs=mysql_fetch_array($res)){
	$board_id = $rs["id"];
} else {
	// не буду добавлять - данных нет и это делается в другом месте
}
// Определим блок - он уже должен быть в базе
$sql = "SELECT * FROM blockpos WHERE board_id='$board_id'";
debug($sql);
$res = mysql_query($sql);
if ($rs=mysql_fetch_array($res)){
	$block_id = $rs["block_id"];
} else {
	// не буду добавлять - данных нет и это делается в другом месте
}

// добавим МП если есть такое исправим
$sql="SELECT * FROM posintz WHERE tz_id='$tz' AND posintz='$posintz'";
debug($sql);
$res = mysql_query($sql);
if (mysql_num_rows($res) == 0){
	$sql="INSERT INTO posintz (tz_id,posintz,plate_id,board_id,block_id,numbers,first,srok,priem,constr,template_check,template_make,eltest,numpl,numbl,pitz_mater,pitz_psimat) VALUES ('$tz','$posintz','$plate_id','$board_id','$block_id','$numbers','$first','$srok','$priem','$constr','$template_check','$template_make','$eltest','$numpl','$numbl','$textolite','$textolitepsi')";
	debug($sql);
	mylog1($sql);
	mysql_query($sql);
	$pit_id = mysql_insert_id();
	if (!$pit_id) my_error();
		
} else {
	$sql="UPDATE posintz SET numbers='$numbers', plate_id='$board_id',plate_id='$board_id', block_id='$block_id',first='$first',srok='$srok',priem='$priem',constr='$constr',template_check='$template_check',template_make='$template_make', eltest='$eltest', numpl='$numpl', numbl='$numbl', pitz_mater='$textolite', pitz_psimat='$textolitepsi' WHERE tz_id='$tz' AND posintz='$posintz'";
	debug($sql);
	mylog1($sql);
	mysql_query($sql);
	$rs=mysql_fetch_array($res);
	$pit_id = $rs["id"];
}

// обновить запуски если некоторые позиции уже запускались
$sql="SELECT * FROM lanch WHERE tz_id='$tz' AND pos_in_tz='$posintz'";
$res = mysql_query($sql);
if($rs=mysql_fetch_array($res)) {
	$sql="UPDATE lanch SET pos_in_tz_id='$pit_id' WHERE id='".$rs["id"]."'";
	mysql_query($sql);
	$sql="UPDATE posintz SET ldate='".$rs["ldate"]."' WHERE id='$pit_id'";
	mysql_query($sql);
}

printf("%08d",$pit_id);
?>