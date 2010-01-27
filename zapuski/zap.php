<?
// Отображает запущенные платы
if ($action=='add') {
}
else
{
	$sql="SELECT *,lanch.id FROM lanch JOIN (users,filelinks,coments,plates,customers,tz,orders) ON (lanch.user_id=users.id AND lanch.file_link_id=filelinks.id AND lanch.comment_id=coments.id AND lanch.board_id=plates.id AND plates.customer_id=customers.id AND lanch.tz_id=tz.id AND orders.id=tz.order_id) ".(isset($find)?"AND (plates.plate LIKE '%$find%' OR file_link LIKE '%$find%' OR orders.number LIKE '%$find%')":"").($order!=''?" ORDER BY ".$order." ":" ORDER BY lanch.id DESC ").(isset($all)?"":"LIMIT 20");
	//print $sql;
	
	$type="zp";
	$cols["№"]="№";
	$cols[ldate]="Дата";
	$cols[id]="ID";
	$cols[nik]="Запустил";
	$cols[customer]="Заказчик";
	$cols[number]="Заказ";
	$cols[plate]="Плата";
	$cols[part]="Партия";
	$cols[numbz]="Заг.";
	$cols[numbp]="Плат";
	$cols[file_link]="Файл";
	//$cols[comment]="Коментарий";
	
	include "table.php";
}

?>