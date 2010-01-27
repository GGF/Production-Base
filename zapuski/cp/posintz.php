<?
// создание и редактирование Тех заданий
include "head.php";

if (isset($edit) || isset($add) ) {
	//include "pitedit.php";
} elseif (isset($editblock)) {
	include "pitedblock.php";
} elseif (isset($editplate)) {
	include "pitedplate.php";
} elseif (isset($findplate)) {
	include "pitfpl.php";
} elseif (isset($delete)) {
	// удаление
	$sql = "DELETE FROM posintz WHERE id='$delete'";
	mylog('posintz',$delete);
	mysql_query($sql);
	// удаление связей
} else {
	// список
	if (isset($id)) $tzid=$id;
	
	$sql="SELECT *,posintz.id as posid,posintz.id FROM `posintz` JOIN (plates) ON ( posintz.plate_id = plates.id ) ".(isset($find)?"WHERE (plates.plate LIKE '%$find%')":"").(isset($tzid)?(isset($find)?"AND tz_id='$tzid'":"WHERE tz_id='$tzid'"):"").(isset($order)?" ORDER BY ".$order." ":" ORDER BY posintz.id DESC ").(isset($all)?"":"LIMIT 20");
	//print $sql;
	$type="posintz";
	$cols[posid]="ID";
	$cols[plate]="Плата";
	$cols[numbers]="Количество";

	//$openfunc = "openposintztr";
	//$bgcolor='#999999';
	$addbutton=false;
	$opentype = "posintz";
	
	$title = 'Позиции в ТЗ';
	if (isset($tzid)) $idstr = "&tzid=$tzid";
	
	include "table.php";	
}
?>