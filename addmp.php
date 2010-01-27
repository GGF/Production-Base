<?
// Добавляет МП в базу
// Параметры
// tz - идентификатор ТЗ
// posintz - номер позиции в ТЗ
// mpdate - дата запуска
// user - кто добавил
// Возвращает id добавленого МП

$GLOBALS["debugAPI"] = false;
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

// добавим МП если есть такое исправим
$sql="SELECT * FROM masterplate WHERE tz_id='$tz' AND posintz='$posintz'";
debug($sql);
$res = mysql_query($sql);
if (mysql_num_rows($res) == 0){
	$sql="INSERT INTO masterplate (tz_id,posintz,mpdate,user_id) VALUES ('$tz','$posintz','$mpdate','$user_id')";
	debug($sql);
	mysql_query($sql);
	$mp_id = mysql_insert_id();
	if (!$mp_id) my_error();
		
} else {
	$sql="UPDATE masterplate SET mpdate='$mpdate', user_id='$user_id' WHERE tz_id='$tz' AND posintz='$posintz'";
	debug($sql);
	mysql_query($sql);
	$rs=mysql_fetch_array($res);
	$mp_id = $rs["id"];
}
printf("%08d",$mp_id);
?>