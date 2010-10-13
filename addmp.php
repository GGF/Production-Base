<?
/*
Добавляет МП в базу
 Параметры
 tz - идентификатор ТЗ
 posintz - номер позиции в ТЗ
 mpdate - дата запуска
 user - кто добавил
 Возвращает id добавленого МП
*/

include_once $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php"; // это нужно при добавлении так как не вызывается заголовк html

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

// добавим МП если есть такое исправим
$sql="SELECT * FROM masterplate WHERE tz_id='$tz' AND posintz='$posintz'";
$rs = sql::fetchOne($sql);
if (empty($rs)) {
	$sql="INSERT INTO masterplate (tz_id,posintz,mpdate,user_id) VALUES ('$tz','$posintz','$mpdate','$user_id')";
	sql::query ($sql) or die(sql::error(true));
	$mp_id = sql::lastId();
} else {
	$sql="UPDATE masterplate SET mpdate='$mpdate', user_id='$user_id' WHERE tz_id='$tz' AND posintz='$posintz'";
	sql::query ($sql) or die(sql::error(true));
	$mp_id = $rs["id"];
}
printf("%08d",$mp_id);
?>