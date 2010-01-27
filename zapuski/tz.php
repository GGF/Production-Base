<?
// Отображает ТЗ
//else
{
	if (isset($delete)) {
		$sql="DELETE FROM tz WHERE id='$delete'";
		debug($sql);
		mysql_query($sql);
		$sql="DELETE FROM posintz WHERE tz_id='$delete'";
		debug($sql);
		mysql_query($sql);
	} 
		$sql="SELECT *,tz.id as tzid,tz.id FROM `tz` JOIN (orders, customers, users, filelinks) ON ( tz.order_id = orders.id AND orders.customer_id = customers.id AND tz.user_id = users.id AND tz.file_link_id = filelinks.id ) ".(isset($find)?"AND (number LIKE '%$find%' OR file_link LIKE '%$find%')":"").($order!=''?" ORDER BY ".$order." ":" ORDER BY tz.id DESC ").(isset($all)?"":"LIMIT 20");

	$type="tz";
	$cols[tzid]="ID";
	$cols[tz_date]="Дата";
	$cols[nik]="Кто заполнил";
	$cols[customer]="Заказчик";
	$cols[number]="Заказ";
	$cols[orderdate]="Число";
	$cols[file_link]="Файл";
	$del = true;
	
	include "table.php";
}

?>