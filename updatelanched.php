<?
$GLOBALS["debugAPI"] = false;
include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php"; // ��� ����� ��� ��� �� ���������� �������� html

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
