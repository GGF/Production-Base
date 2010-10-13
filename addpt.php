п»ї<?
include_once $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php"; // СЌС‚Рѕ РЅСѓР¶РЅРѕ РїСЂРё РґРѕР±Р°РІР»РµРЅРёРё С‚Р°Рє РєР°Рє РЅРµ РІС‹Р·С‹РІР°РµС‚СЃСЏ Р·Р°РіРѕР»РѕРІРє html

// РћРїСЂРµРґРµР»РёРј РёРґРµРЅС‚РёС„РёРєР°С‚РѕСЂ РїРѕР»СЊР·РѕРІР°С‚РµР»СЏ
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