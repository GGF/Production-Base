<?
// управление правами доступа

include "head.php";

if (isset($edit) || isset($add) ) {
	if (!isset($accept)) {
		if ($edit) {
			$sql = "SELECT * FROM rights WHERE id='".$edit."'";
			$res = mysql_query($sql);
			$rs=mysql_fetch_array($res);
			$uid = $rs["u_id"];
		}
		echo "<form method=post id=editform>";
		echo "<input type='hidden' value='".(isset($edit)?$edit:"0")."' name='edit'>";
		echo "<input type=hidden name=tid value='$tid'>";
		echo "<input type=hidden name=uid value='$uid'>";
		echo "<input type=hidden name=accept value='yes'>";
		echo "<pre>";
		$sql="SELECT * FROM rtypes";
		$res=mysql_query($sql);
		while($rs=mysql_fetch_array($res)) {
			printf("[%-10s]:",$rs["type"]);
			$sql = "SELECT * FROM rrtypes";
			$res1=mysql_query($sql);
			while($rs1=mysql_fetch_array($res1)) {
				$sql="SELECT * FROM rights WHERE type_id='".$rs["id"]."' AND u_id='$uid' AND rtype_id='".$rs1["id"]."'";
				$rs2=mysql_fetch_array(mysql_query($sql));
				echo $rs1["rtype"]."-<input type=checkbox name=r[".$rs["id"]."][".$rs1["id"]."] ".($rs2["right"]=='1'?"checked":"").">";
			}
			echo "<br>";
		}
		echo "</pre>";
		echo "<input type=button value='Сохранить' onclick=\"editrecord('rights',$('#editform').serialize())\"><input type=button value='Отмена' onclick='closeedit()'><input type=button onclick=\"alert($('#editform').serialize())\">";
	} else {
		// сохрнение
		foreach ($_GET as $key => $val) {
			if (mb_detect_encoding($val)=="UTF-8") $$key=mb_convert_encoding($val,"cp1251");
		}
		foreach ($_POST as $key => $val) {
			if (mb_detect_encoding($val)=="UTF-8") $$key=mb_convert_encoding($val,"cp1251");
		}
//		print_r($r);
		/*
		foreach ($r as $key=>$val) {
			echo $key."<br>";
			foreach($val as $k=>$V) {
				echo "&nbsp;&nbsp;".$k."<br>";
			}
		}
		echo $uid;
		*/

		$sql="DELETE FROM rights WHERE u_id='$uid'";
		mysql_query($sql);
		foreach ($r as $key=>$val) {
			foreach($val as $k=>$V) {
				$sql="INSERT INTO rights (u_id,type_id,rtype_id,rights.right) VALUES ('$uid','$key','$k','1')";
				//echo $sql;
				if(!mysql_query($sql)) {
					my_error("Не удалось внести изменения в таблицу rights!!!");
				}
			}
		}
		echo "<script>updatetable('$tid','rights','');closeedit();</script>";
	}

} elseif (isset($delete)) {
	// удаление
	$sql = "DELETE FROM rights WHERE id='$delete'";
	mylog('rights',$delete,'DELETE');
	mysql_query($sql);
}
else
{
// вывести таблицу
	if (isset($id)) $uid=$id;
	// sql
	$sql="SELECT *,rights.id AS rid,rights.right AS enable,rights.id FROM rights JOIN (users,rtypes,rrtypes) ON (users.id=rights.u_id AND rtypes.id=rights.type_id AND rrtypes.id=rights.rtype_id) ".(isset($find)?"WHERE (type LIKE '%$find%' OR rtype LIKE '%$find%') ":"").(isset($uid)?(isset($find)?"AND u_id='$uid' ":"WHERE u_id='$uid' "):"").(isset($order)?"ORDER BY ".$order." ":"ORDER BY type ").(isset($all)?"":"LIMIT 20");
	//echo $sql;
	$type="rights";
	$cols[rid]="ID";
	//$cols[nik]="Nik";
	$cols[type]="tables";
	$cols[rtype]="right";
	$cols[enable]="on/off";
	$del=true;
	$edit=true;
	$openfunc = "openrights";
	if (isset($uid)) $idstr = "&uid=$uid";
	include "table.php";
}
?>