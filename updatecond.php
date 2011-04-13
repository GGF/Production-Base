<?
include_once $_SERVER [DOCUMENT_ROOT] . "/lib/engine.php"; // это нужно так как не вызывается заголовк html


$sql = "SELECT * FROM conductors";// WHERE plate_id=board_id";
$sql = "SELECT * FROM conductors WHERE plate_id=board_id";
$res = sql::fetchAll ( $sql );
foreach ( $res as $rs ) {
	$sql="SELECT plate FROM plates WHERE id='{$rs[plate_id]}'";
	$p=sql::fetchOne($sql);
	$p=$p[plate];
	echo $p."<br>";
	$sql = "SELECT id FROM boards WHERE board_name='{$p}'";
	$p=sql::fetchOne($sql);
	//$sql = "UPDATE conductors SET plate_id='{$rs[board_id]}' WHERE id='{$rs[id]}'";
	$sql = "UPDATE conductors SET board_id='{$p[id]}' WHERE id='{$rs[id]}'";
	sql::query ( $sql );
	sql::error ( true );
}

?>
