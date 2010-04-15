<?
// управление мастерплатами

require $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php"; 
authorize(); // вызов авторизации
$processing_type=basename (__FILE__,".php");
// serialize form
if (isset(${'form_'.$processing_type})) extract(${'form_'.$processing_type});

if (isset($edit) || isset($add) ) {
	// ничего
} elseif (isset($delete)) {
	// удаление
	$sql = "DELETE FROM masterplate WHERE id='$delete'";
	sql::query($sql);
	sql::error(true);
	echo "ok";
}
else
{
// вывести таблицу
	
	// sql
	$sql="SELECT masterplate.id,masterplate.mpdate,users.nik,customers.customer,plates.plate,filelinks.file_link,masterplate.id FROM masterplate JOIN (tz,posintz,orders,customers,plates,filelinks,users) ON (masterplate.user_id=users.id AND masterplate.tz_id=tz.id AND masterplate.tz_id=posintz.tz_id AND masterplate.posintz=posintz.posintz AND tz.order_id=orders.id AND posintz.plate_id=plates.id AND customers.id=plates.customer_id AND tz.file_link_id=filelinks.id) ".(isset($find)?"WHERE plate LIKE '%$find%' OR customer LIKE '%$find%'":"").(!empty($order)?"ORDER BY ".$order." ":"ORDER BY masterplate.id DESC ").(isset($all)?"LIMIT 50":"LIMIT 20");
	
	$cols[id]="ID";
	$cols[mpdate]="Дата";
	$cols[nik]="Кто запустил";
	$cols[customer]="Заказчик";
	$cols[plate]="Плата";
	
	$table = new Table($processing_type,$processing_type,$sql,$cols);
	$table->show();
	
}
?>