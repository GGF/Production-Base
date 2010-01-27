<?
// Добавляет сопроводиетльный лист в журнал запуска
// Параметры
// sl_date - дата запуска
// customer - Заказчик текстовая строка сокращенное имя заказчика
// board - имя платы
// party - номер партии
// numbz - количество заготовок
// numbp - количество плат
// user - имя заполнителя тз - короткое по имени компа
// file_link - ссылка на файл либо в виде диска либо в UNC
// comment - комментарий
// posintz - в тз
// tz_id - идентификатор ТЗ
//
// Возвращает id добавленого СЛ

$GLOBALS["debugAPI"] = false;
include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php"; // это нужно при добавлении так как не вызывается заголовк html

// найдем номер заказчика
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
// Определим идентификатор файловой ссылки
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
// Определим идентификатор коментария
$sql="SELECT id FROM coments WHERE comment='$comment'";
debug($sql);
$res = mysql_query($sql);
if ($rs=mysql_fetch_array($res)){
	$comment_id = $rs["id"];
} else {
	$sql="INSERT INTO coments (comment) VALUES ('$comment')";
	debug($sql);
	mysql_query($sql);
	$comment_id = mysql_insert_id();
	if (!$comment_id) my_error();
}

if (!isset($tz)) {
	$wheresql = "file_link_id='$file_id'"; 
	$sql="SELECT id FROM tz WHERE file_link_id='$file_id'";
	$rs=mysql_fetch_array(mysql_query($sql));
	$tz=$rs[0];
}

$wheresql = "tz_id='$tz'";

// определим идентификатор позиции в тз
$sql="SELECT id, numbers FROM posintz WHERE $wheresql AND posintz='$posintz'";
$rs=mysql_fetch_array(mysql_query($sql));
$posid = $rs[0];
$numbers=$rs[1];

// добавим или обновим
$sql="SELECT * FROM lanch WHERE ".$wheresql." AND pos_in_tz='$posintz' AND part='$party'";
debug($sql);
$res = mysql_query($sql);
if (mysql_num_rows($res) == 0){
	$sql="INSERT INTO lanch (ldate,board_id,part,numbz,numbp,comment_id,file_link_id,user_id,pos_in_tz,tz_id,pos_in_tz_id) VALUES ('$sl_date','$plate_id','$party','$numbz','$numbp','$comment_id','$file_id','$user_id','$posintz','$tz','$posid')";
	debug($sql);
	mysql_query($sql);
	$lanch_id = mysql_insert_id();
	if (!$lanch_id) my_error();
		
} else {
	$rs = mysql_fetch_array($res);
	$lanch_id = $rs["id"];
	$sql="UPDATE lanch SET ldate='$sl_date', board_id='$plate_id', numbz='$numbz', numbp='$numbp', comment_id='$comment_id', user_id='$user_id', tz_id='$tz', pos_in_tz_id='$posid' WHERE id='".$rs["id"]."'";
	debug($sql);
	mysql_query($sql);
}
printf("%08d",$lanch_id);
$sql="DELETE FROM lanched WHERE board_id='$plate_id'";
mysql_query($sql);
$sql="INSERT INTO lanched SELECT board_id,MAX(ldate),SUM(numbp) FROM `lanch` WHERE board_id='$plate_id' AND pos_in_tz_id='$posid' GROUP BY board_id";
mysql_query($sql);
// если всё запущено закроем позицию
$sql="SELECT SUM(numbp) FROM lanch WHERE pos_in_tz_id='$posid' GROUP BY pos_in_tz_id";
$rs=mysql_fetch_array(mysql_query($sql));
//echo $numbers."___".$rs[0];
if ($rs[0]>=$numbers) {
	$sql="UPDATE posintz SET ldate='$sl_date', luser_id='$user_id' WHERE id='$posid'";
	mysql_query($sql);
}
?>