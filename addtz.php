п»ї<?
/*
Р”РѕР±Р°РІР»СЏРµС‚ РўР— Рё Р·Р°РєР°Р· РІ Р±Р°Р·Сѓ
 РџР°СЂР°РјРµС‚СЂС‹
 customer - Р—Р°РєР°Р·С‡РёРє С‚РµРєСЃС‚РѕРІР°СЏ СЃС‚СЂРѕРєР° СЃРѕРєСЂР°С‰РµРЅРЅРѕРµ РёРјСЏ Р·Р°РєР°Р·С‡РёРєР°
 number - РЅРѕРјРµСЂ РёСЃСЊРјР° Р·Р°РєР°Р·С‡РёРєР°
 order_date - РґР°С‚Р° Р·Р°РєР°Р·Р°
 tz_date - РґР°С‚Р° Р·Р°РїРѕР»РЅРµРЅРёСЏ РўР—
 user - РёРјСЏ Р·Р°РїРѕР»РЅРёС‚РµР»СЏ С‚Р· - РєРѕСЂРѕС‚РєРѕРµ РїРѕ РёРјРµРЅРё РєРѕРјРїР°
 file_link - СЃСЃС‹Р»РєР° РЅР° С„Р°Р№Р» Р»РёР±Рѕ РІ РІРёРґРµ РґРёСЃРєР° Р»РёР±Рѕ РІ UNC

 Р’РѕР·РІСЂР°С‰Р°РµС‚ id РґРѕР±Р°РІР»РµРЅРѕРіРѕ С‚Р·
*/
include_once $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php"; // СЌС‚Рѕ РЅСѓР¶РЅРѕ С‚Р°Рє РєР°Рє РЅРµ РІС‹Р·С‹РІР°РµС‚СЃСЏ Р·Р°РіРѕР»РѕРІРє html

// РЅР°Р№РґРµРј РѕРјРµСЂ Р·Р°РєР°Р·С‡РёРєР°
$file_link = addslashes($file_link);
$file_link = strtolower($file_link);
$file_link = eregi_replace("\\\\\\\\\\\\\\\\servermpp\\\\\\\\(.)","\\1:",$file_link);
$sql="SELECT id FROM customers WHERE customer='$customer'";
$rs = sql::fetchOne($sql);
if (!empty($rs)) {
	$customer_id = $rs["id"];
} else {
	$sql="INSERT INTO customers (customer) VALUES ('$customer')";
	sql::query ($sql) or die(sql::error(true));
	$customer_id = sql::lastId();
}
// Р”РѕР±Р°РІРёРј Р·Р°РєР°Р· Рё РѕРїСЂРµРґРµР»РёРј РёРґРµРЅС‚РёС„РёРєР°С‚РѕСЂ
$sql="SELECT id FROM orders WHERE customer_id='$customer_id' AND orderdate='$order_date' AND number='$number'";
$rs = sql::fetchOne($sql);
if (!empty($rs)) {
	$order_id = $rs["id"];
} else {
	$sql="INSERT INTO orders (customer_id,number,orderdate) VALUES ('$customer_id','$number','$order_date')";
	sql::query ($sql) or die(sql::error(true));
	$order_id = sql::lastId();
}
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

// РћРїСЂРµРґРµР»РёРј РёРґРµРЅС‚РёС„РёРєР°С‚РѕСЂ С„Р°Р№Р»РѕРІРѕР№ СЃСЃС‹Р»РєРё
$file_link = str_replace("\\\\servermpp\\t","t:",$file_link);
$sql="SELECT id FROM filelinks WHERE file_link='$file_link'";
$rs = sql::fetchOne($sql);
if (!empty($rs)) {
	$file_id = $rs["id"];
} else {
	$sql="INSERT INTO filelinks (file_link) VALUES ('$file_link')";
	sql::query ($sql) or die(sql::error(true));
	$file_id = sql::lastId();
}
// РїС‰Р»СѓС‡РёРј РЅРѕРјРµСЂ РѕС‡РµСЂРµРґРЅРѕР№ РїРѕР·РёС†РёРё С‚Р· РІ Р·Р°РєР°Р·Рµ
$sql="SELECT max(pos_in_order) as pio FROM tz WHERE order_id='$order_id'";
$rs = sql::fetchOne($sql);
if (!empty($rs)) {
	$pos_in_order = $rs[pio]+1;
} else {
	$pos_in_order = 1;
}
// РґРѕР±Р°РІРёРј РўР— РµСЃР»Рё РµСЃС‚СЊ С‚Р°РєРѕРµ РґРѕР±Р°РІРёРј РІС‚РѕСЂСѓСЋ РїРѕР·РёС†РёСЋ - С‚РёРїР° Р”РџРџ1 Р”РџРџ2 Рё С‚.Рґ.
$sql="SELECT * FROM tz WHERE file_link_id='$file_id'";
$rs = sql::fetchOne($sql);
if (empty($rs)) {
	$sql="INSERT INTO tz (order_id,tz_date,user_id,pos_in_order,file_link_id) VALUES ('$order_id','$tz_date','$user_id','$pos_in_order','$file_id')";
	sql::query ($sql) or die(sql::error(true));
	$tz_id = sql::lastId();
		
} else {
	$sql="DELETE FROM posintz WHERE tz_id='".$rs["id"]."'";
	sql::query ($sql) or die(sql::error(true));
	$sql="UPDATE tz SET order_id='$order_id', tz_date='$tz_date', user_id='$user_id' WHERE file_link_id='$file_id'";
	sql::query ($sql) or die(sql::error(true));
	//$rs=mysql_fetch_array($res);
	$tz_id = $rs["id"];
}
printf("%08d",$tz_id);
?>