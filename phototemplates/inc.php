<?
// Добавляет b отображает фотошаблоны отправленые на рисование
// Параметры
// action - команда добавления =add, без нее отображает таблицу
// user - имя заполнителя тз - короткое по имени компа
// filenames - количество файлов и путь откуда отправлены
//
// Возвращает либо таблицу либо пустую строку
$GLOBALS["debugAPI"] = false;

if ($action=='add') 
	{
	include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php"; // это нужно при добавлении так как не вызывается заголовк html
	// найдем номер заказчика
	$files = addslashes($file_link);
	debug ($file_link);
	// Определим идентификатор пользователя
	$sql="SELECT id FROM users WHERE nik='$user'";
	debug($sql);
	$res = mysql_query($sql);
	if ($rs=mysql_fetch_array($res)){
		$user_id = $rs["id"];
	} else {
		$sql="INSERT INTO users (nik) VALUES ('$user')";
		debug($sql);
		mysql_query($sql);
		$user_id = mysql_insert_id();
		if (!$user_id) my_error();
	}
	// добавим
	if (!isset($ts))
		$ts='NOW()';
	else
		$ts="'$ts'";
	
	$sql="INSERT INTO phototemplates (ts,user_id,filenames) VALUES ($ts,'$user_id','$filenames')";
	debug($sql);
	mysql_query($sql);
	$filenames_id = mysql_insert_id();
	if (!$filenames_id) my_error();

} 
else
{
// вывести таблицу
	print "<center><a href='?action=".($action=='all'?"'>Последние 20":"all'>Все")."</a></center><br><form method=post action=''><input type=hidden name=action value='find'>Поиск:<input type=text name='ssrt' size=10></form>";
	print "<table class='listtable' cellspacing=0 cellpadding=0>";
	print "<thead>";
	print "<tr>";
	print "<td>Дата - время</td><td>Кто добавил</td><td>Количество шаблонов и каталог</td>";
	print "</tr>";
	print "<tbody>";
	$sql="SELECT *,unix_timestamp(ts) AS uts FROM phototemplates JOIN users ON phototemplates.user_id=users.id ".($action=='find'?"WHERE filenames LIKE '%$ssrt%'":"")."ORDER BY ts DESC ".($action=='all'?"":"LIMIT 20");
	debug($sql);
	$res = mysql_query($sql);
	while ($rs=mysql_fetch_array($res)) {
		if (!($i++%2)) 
			print "<tr class='chettr'>";
		else 
			print "<tr class='nechettr'>";
		print "<td>" .date("d-m-Y H:i",$rs["uts"]). "</td><td>".$rs["nik"]."</td><td>".$rs["filenames"]."</td>";
		print "</tr>";
	}
	print "</table>";
}

?>