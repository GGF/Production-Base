п»ї<?
include_once $_SERVER [DOCUMENT_ROOT] . "/lib/engine.php"; // СЌС‚Рѕ РЅСѓР¶РЅРѕ С‚Р°Рє РєР°Рє РЅРµ РІС‹Р·С‹РІР°РµС‚СЃСЏ Р·Р°РіРѕР»РѕРІРє html


$sql = "SELECT posintz.id as posid, masterplate.id as mpid FROM posintz JOIN masterplate ON (masterplate.tz_id=posintz.tz_id AND masterplate.posintz=posintz.posintz)";
$res = sql::fetchAll ( $sql );
foreach ( $res as $rs ) {
	$sql = "UPDATE masterplate SET posid='" . $rs ["posid"] . "' WHERE id='" . $rs ["mpid"] . "'";
	echo $sql;
	sql::query ( $sql );
	sql::error ( true );
}

?>
