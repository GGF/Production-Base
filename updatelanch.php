<?
$GLOBALS["debugAPI"] = false;
include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php"; // это нужно так как не вызывается заголовк html

$sql="SELECT * FROM lanch";
$res=mysql_query($sql);
while ($rs=mysql_fetch_array($res)) {
	$sql="SELECT * FROM posintz WHERE tz_id='".$rs["tz_id"]."' AND posintz='".$rs["pos_in_tz"]."'";
	if($res1=mysql_query($sql)) {
		if ($rs1=mysql_fetch_array($res1)) {
			$sql="UPDATE lanch SET pos_in_tz_id='".$rs1["id"]."' WHERE id='".$rs["id"]."'";
			mysql_query($sql);
			$sql="UPDATE posintz SET ldate='".$rs["ldate"]."', luser_id='".$rs["user_id"]."' WHERE id='".$rs1["id"]."'";
			mysql_query($sql);
			echo $rs["id"]."__".$rs1["id"]."<br>";
		}
	}
}

?>
