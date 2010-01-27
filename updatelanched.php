<?
$GLOBALS["debugAPI"] = false;
include "lib/sql.php";

$sql="TRUNCATE TABLE `lanched`";
mysql_query($sql);
$sql="INSERT INTO lanched
SELECT board_id, MAX(ldate)
FROM lanch
GROUP BY board_id
ORDER BY `ldate` DESC";
mysql_query($sql);

/*
$res=mysql_query($sql);
while ($rs=mysql_fetch_array($res)) {
}
*/
?>
