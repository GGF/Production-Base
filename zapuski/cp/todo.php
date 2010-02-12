<?
// отображает задачи по усовершенствованию
include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php";
authorize(); // вызов авторизации


if (isset($edit) || isset($add)) {
	if (!isset($accept)) {
		$sql = "SELECT * FROM users WHERE nik='".$user."'";
		$res = mysql_query($sql);
		$rs=mysql_fetch_array($res);
		$uid = $rs["id"];
		if ($edit) {
			$sql = "SELECT * FROM todo WHERE id='".$edit."'";
			$res = mysql_query($sql);
			$rs=mysql_fetch_array($res);
		}
		echo "<form method=post id=editform>";
		echo "<input type='hidden' value='".(isset($edit)?$edit:"0")."' name='edit'>";
		echo "<input type=hidden name=tid value='$tid'>";
		echo "<input type=hidden name=uid value='$uid'>";
		echo "<input type=hidden name=accept value='yes'>";
		echo "<textarea rows=10 cols=70 name=what id='wysiwyg'>".$rs["what"]."</textarea><br>";
		echo "<input type=button value='Сохранить' onclick=\"editrecord('todo',$('#editform').serialize())\"><input type=button value='Отмена' onclick='closeedit()'><input type=button onclick=\"alert($('#editform').serialize())\">";
		echo "<script>$(function()
  {
      $('#wysiwyg').wysiwyg();
  });</script>";
	} else {
		// сохранение

		if ($edit!=0) {
			$sql="UPDATE todo SET what='".str_replace("'","\'",$what)."', cts=NOW(), rts='0', u_id='$uid' WHERE id='$edit'";
			mylog('todo',$edit,"UPDATE");
			mylog($sql);
		} else {
			$sql="INSERT INTO todo (what,cts,rts,u_id) VALUES ('".str_replace("'","\'",$what)."',NOW(),'0',$uid)";
			mylog($sql);
		}
		if (!mysql_query($sql)) {
			echo $sql;
			my_error("Не удалось изменить таблицу todo");
		} else {
			echo "<script>updatetable('$tid','todo','');closeedit();</script>";
		}
	}
} elseif (isset($delete)) {
	$sql = "SELECT * FROM todo WHERE id='".$delete."'";
	$res = mysql_query($sql);
	$rs=mysql_fetch_array($res);
	$sql = "UPDATE todo SET rts=NOW(), what='<del>".$rs["what"]."</del>' WHERE id='$delete'";
	mylog('todo',$delete,"UPDATE");
	mysql_query($sql);
} else {
$sql="SELECT *, todo.id FROM todo JOIN users ON users.id=u_id ".(isset($find)?"WHERE (what LIKE '%$find%' ) ":"").((isset($all))?"":(isset($find)?" AND rtsrts='000000000000' ":" WHERE rts='000000000000' ")).(isset($order)?"ORDER BY ".$order." ":"ORDER BY cts ").((isset($all))?"":"LIMIT 20");
	$type="todo";
	$cols[id]="ID";
	$cols[nik]="Кто";
	$cols[cts]="Задан";
	$cols[rts]="Закончен";
	$cols[what]="Что сделать";
	
	$addbutton=true;
	$opentype='todo';

include "table.php";
/*
	echo "<pre>
0. <del>Прикрутить авторизацию!!!</del>
0.1 <del>Права прикрутить</del> 21-10-2009 15:04
0.1.1 <del>Редактирование прав</del> 22-10-2009 11:41
0.1.2 <del>Меню в зависимости от прав</del> 22-10-2009 12:06
0.1.3 Просмотр в зависимости от прав
0.2 <del>Авторизацию на других уровнях</del> 16-10-2009
0.3 онлайн заказы - ну не из excel а через админку
0.4 
1. В логах сделать восстановление
2. Добавление блока в выбор при заполнении ТЗ
3. В добавлении блока выбор из плат и заполнение пропорционального прямоугольника
4. Там же добавление платы с параметрами
5. Учет толщины блока, а также маски - маркировка может быть или не быть на остальные не влияет
6. Пункт меню создание платы
7. Печать ТЗ - 4 (ДПП,МПП,для произв, для расчета)  маршрутный лист ДПП и МПП разных классов, мастерплаты
8. <del>Пункты меню для фотошаблонов и мастерплат</del> 21-10-2009 13:41
</pre>";
*/
}
?>