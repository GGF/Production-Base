<?
require $_SERVER[DOCUMENT_ROOT]."/lib/engine.php"; // ��� ����� ��� ��� �� ���������� �������� html

$sql="SELECT * FROM lanch";
$res = sql::fetchAll($sql);
foreach ($res as $rs) 
{
	echo $rs[board_id] . " - ";
	$sql = "SELECT * FROM posintz WHERE id='{$rs[pos_in_tz_id]}'";
	$bl = sql::fetchOne($sql);
	echo $bl[block_id] . "<br>";
	$sql = "UPDATE lanch SET block_id='{$bl[block_id]}' WHERE id='{$rs[id]}'";
	sql::query($sql);
/*
	$sql="SELECT * FROM posintz WHERE tz_id='".$rs["tz_id"]."' AND posintz='".$rs["pos_in_tz"]."'";
	$rs1 = sql::fetchOne($sql);
	if (!empty($rs1)) 
	{
			$sql="UPDATE lanch SET pos_in_tz_id='".$rs1["id"]."' WHERE id='".$rs["id"]."'";
			sql::query ($sql) or die(sql::error(true));
			$sql="UPDATE posintz SET ldate='".$rs["ldate"]."', luser_id='".$rs["user_id"]."' WHERE id='".$rs1["id"]."'";
			sql::query ($sql) or die(sql::error(true));
			echo $rs["id"]."__".$rs1["id"]."<br>";
	}
	
*/
}

?>