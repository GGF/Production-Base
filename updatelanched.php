<?
include_once $_SERVER[DOCUMENT_ROOT]."/lib/engine.php"; // ��� ����� ��� ��� �� ���������� �������� html

$sql="TRUNCATE TABLE `lanched`";
sql::query ($sql) or die(sql::error(true));
$sql="INSERT INTO lanched
SELECT board_id, MAX(ldate)
FROM lanch
GROUP BY board_id
ORDER BY `ldate` DESC";
sql::query ($sql) or die(sql::error(true));

?>
