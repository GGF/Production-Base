<?
// Отображает запущенные мастерплаты
if ($action=='add') {

} else
{
// вывести таблицу
	$sql="SELECT masterplate.id,masterplate.mpdate,users.nik,customers.customer,plates.plate,filelinks.file_link FROM masterplate JOIN (tz,posintz,orders,customers,plates,filelinks,users) ON (masterplate.user_id=users.id AND masterplate.tz_id=tz.id AND masterplate.tz_id=posintz.tz_id AND masterplate.posintz=posintz.posintz AND tz.order_id=orders.id AND posintz.plate_id=plates.id AND customers.id=plates.customer_id AND tz.file_link_id=filelinks.id) ".(isset($find)?"WHERE plate LIKE '%$find%'":"").($order!=''?"ORDER BY ".$order." ":"ORDER BY masterplate.id DESC ").(isset($all)?"":"LIMIT 20");
	
	$type="mp";
	$cols[id]="ID";
	$cols[mpdate]="Дата";
	$cols[nik]="Кто запустил";
	$cols[customer]="Заказчик";
	$cols[plate]="Плата";
	$cols[file_link]="Файл";	
	
	include "table.php";
}

?>