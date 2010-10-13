п»ї<?
/*
Р”РѕР±Р°РІР»СЏРµС‚ РїРѕР·РёС†РёСЋ РўР— РІ Р±Р°Р·Сѓ РІ Р±Р°Р·Сѓ
 РџР°СЂР°РјРµС‚СЂС‹
 tz - РёРґРµРЅС‚РёС„РёРєР°С‚РѕСЂ РўР—
 posintz - РЅРѕРјРµСЂ РїРѕР·РёС†РёРё РІ РўР—
 customer - Р·Р°РєР°Р·С‡РёРє
 board - РёРјСЏ РїР»Р°С‚С‹
 numbers - РєРѕР»РёС‡РµСЃС‚РІРѕ
 zap - Р·Р°РїСѓС‰РµРЅР° РёР»Рё РЅРµС‚ (0/1)
 user - РєС‚Рѕ РґРѕР±Р°РІРёР»
 Р’РѕР·РІСЂР°С‰Р°РµС‚ id 
*/

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

// РЅР°Р№РґРµРј РЅРѕРјРµСЂ Р·Р°РєР°Р·С‡РёРєР°
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
// РѕРїСЂРµРґРµР»РёРј РїР»Р°С‚Сѓ
$sql="SELECT id FROM plates WHERE customer_id='$customer_id' AND plate='$board'";
$rs = sql::fetchOne($sql);
if (!empty($rs)) {
	$plate_id = $rs["id"];
} else {
	$sql="INSERT INTO plates (customer_id,plate) VALUES ('$customer_id','$board')";
	sql::query ($sql) or die(sql::error(true));
	$plate_id = sql::lastId();
}
// РѕРїСЂРµРґРµР»РёРј РїР»Р°С‚Сѓ
$sql="SELECT id FROM boards WHERE customer_id='$customer_id' AND board_name='$board'";
$rs = sql::fetchOne($sql);
if (!empty($rs)) {
	$board_id = $rs["id"];
} else {
	// РЅРµ Р±СѓРґСѓ РґРѕР±Р°РІР»СЏС‚СЊ - РґР°РЅРЅС‹С… РЅРµС‚ Рё СЌС‚Рѕ РґРµР»Р°РµС‚СЃСЏ РІ РґСЂСѓРіРѕРј РјРµСЃС‚Рµ
}
// РћРїСЂРµРґРµР»РёРј Р±Р»РѕРє - РѕРЅ СѓР¶Рµ РґРѕР»Р¶РµРЅ Р±С‹С‚СЊ РІ Р±Р°Р·Рµ
$sql = "SELECT * FROM blockpos WHERE board_id='$board_id'";
$rs = sql::fetchOne($sql);
if (!empty($rs)) {
	$block_id = $rs["block_id"];
} else {
	// РЅРµ Р±СѓРґСѓ РґРѕР±Р°РІР»СЏС‚СЊ - РґР°РЅРЅС‹С… РЅРµС‚ Рё СЌС‚Рѕ РґРµР»Р°РµС‚СЃСЏ РІ РґСЂСѓРіРѕРј РјРµСЃС‚Рµ
}

// РґРѕР±Р°РІРёРј РњРџ РµСЃР»Рё РµСЃС‚СЊ С‚Р°РєРѕРµ РёСЃРїСЂР°РІРёРј
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

// РѕР±РЅРѕРІРёС‚СЊ Р·Р°РїСѓСЃРєРё РµСЃР»Рё РЅРµРєРѕС‚РѕСЂС‹Рµ РїРѕР·РёС†РёРё СѓР¶Рµ Р·Р°РїСѓСЃРєР°Р»РёСЃСЊ
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