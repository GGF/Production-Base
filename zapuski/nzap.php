<?
// Отображает запущенные платы
// временно пока не пойдут нормальными тз
$sql = "SELECT * FROM lanch WHERE tz_id='0'";
debug($sql);
$res = mysql_query($sql);
while ($rs = mysql_fetch_array($res)) {
	$sql = "SELECT * FROM tz WHERE file_link_id = '".$rs["file_link_id"]."' ";
	debug ($sql);
	$res1 = mysql_query($sql);
	while ($rs1= mysql_fetch_array($res1)) {
		$sql = "UPDATE lanch SET tz_id='".$rs1["id"]."' WHERE id='".$rs["id"]."'";
		debug($sql);
		mysql_query($sql);
	}
}

$sql = "SELECT * FROM posintz";
debug($sql);
$res = mysql_query($sql);
while ($rs = mysql_fetch_array($res)) {
	$sql = "SELECT * FROM tz WHERE id = '".$rs["tz_id"]."' ";
	debug ($sql);
	$res1 = mysql_query($sql);
	if (!$rs1= mysql_fetch_array($res1)) {
		$sql = "DELETE FROM posintz WHERE id='".$rs["id"]."'";
		debug($sql);
		mysql_query($sql);
	}
}


if ($action=='add') {

} 
else
{
// вывести таблицу
	
	// sql
	$sql="CREATE TEMPORARY TABLE tmp (board_id BIGINT(10), lastdate DATE)";
	mysql_query($sql);
	//$sql="TRUNCATE TABLE `tmp`";
	//mysql_query($sql);
	//$sql="LOCK TABLES lanch read";
	//mysql_query($sql);
	$sql="INSERT INTO tmp SELECT board_id,MAX(ldate) FROM `lanch` GROUP BY board_id";
	mysql_query($sql);
	$sql="SELECT * FROM posintz LEFT JOIN (lanch) ON (lanch.tz_id = posintz.tz_id AND lanch.pos_in_tz = posintz.posintz) LEFT JOIN (tmp) ON (posintz.plate_id=tmp.board_id) JOIN (plates,tz,filelinks,customers,orders) ON (tz.order_id=orders.id AND plates.id=posintz.plate_id  AND posintz.tz_id=tz.id AND tz.file_link_id=filelinks.id AND plates.customer_id=customers.id) WHERE posintz.tz_id != '0' AND lanch.id IS NULL ".(isset($find)?"AND (plates.plate LIKE '%$find%' OR filelinks.file_link LIKE '%$find%' OR orders.number LIKE '%$find%') ":"").($order!=''?"ORDER BY ".$order." ":"ORDER BY customers.customer,tz.id,posintz.id ").(isset($all)?"":"LIMIT 20");
	//print $sql;	
	
	$type="nz";
	$cols["№"]="№";
	$cols[customer]="Заказчик";
	$cols[number]="Заказ";
	$cols[plate]="Плата";
	$cols[numbers]="Кол-во";
	$cols[tz_date]="Дата ТЗ";
	$cols[file_link]="Файл";
	$cols[lastdate]="Посл. зап";
	
		include "table.php";
	
	//$sql="UNLOCK TABLES";
	//mysql_query($sql);
	$sql="DROP TABLE tmp";
	mysql_query($sql);
}

?>