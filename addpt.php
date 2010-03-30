<?
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

$sql="INSERT INTO phototemplates (ts,user_id,filenames) VALUES (NOW(),'$user_id','$filenames')";
sql::query ($sql) or die(sql::error(true));
?>