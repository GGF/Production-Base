<?
// ���������� �������������

include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php";
authorize(); // ����� �����������

if (isset($edit) || isset($add) ) {
	if (!isset($accept)) {
		if ($edit) {
			$sql = "SELECT * FROM users WHERE id='".$edit."'";
			$res = mysql_query($sql);
			$rs=mysql_fetch_array($res);
		}
		echo "<form method=post id=editform>";
		echo "<input type='hidden' value='".(isset($edit)?$edit:"0")."' name='edit'>";
		echo "<input type=hidden name=tid value='$tid'>";
		echo "<input type=hidden name=accept value='yes'>";
		echo "���:<input type=text name=nik size=10 value='".$rs["nik"]."'><br>";
		echo "������ ���:<input type=text name=fullname size=30 value='".$rs["fullname"]."'><br>";
		echo "���������:<input type=text name=position size=30 value='".$rs["position"]."'><br>";
		echo "������:<input type=password name=password1 size=30 value='".$rs["password"]."'><br>";
		echo "������ ������:<input type=password name=password2 size=30 value='".$rs["password"]."'><br>";
		echo "<input type=button value='���������' onclick=\"editrecord('users',$('#editform').serialize())\"><input type=button value='������' onclick='closeedit()'><input type=button onclick=\"alert($('#editform').serialize())\">";
	} else {
		// ���������
		foreach ($_GET as $key => $val) {
			if (mb_detect_encoding($val)=="UTF-8") $$key=mb_convert_encoding($val,"cp1251");
		}
		foreach ($_POST as $key => $val) {
			if (mb_detect_encoding($val)=="UTF-8") $$key=mb_convert_encoding($val,"cp1251");
		}

		if ($password1!=$password2)
			my_error("������ �� ���������!");
		if ($edit) {
			// ��������������
			$sql = "UPDATE users SET nik='$nik', fullname='$fullname', position='$position', password='$password1' WHERE id='$edit'";
			mylog('users',$edit,'UPDATE');
			mylog($sql);
		} else {
			// ����������
			$sql = "INSERT INTO users (nik,fullname,position,password) VALUES ('$nik','$fullname','$position','$password1')";
			mylog($sql);
		}
		if (!mysql_query($sql)) {
			my_error("�� ������� ������ ��������� � ������� users!!!");
		} else {
			echo "<script>updatetable('$tid','users','');closeedit();</script>";
		}
	}

} elseif (isset($delete)) {
	// ��������
	$sql = "DELETE FROM users WHERE id='$delete'";
	mylog('users',$delete,'DELETE');
	mysql_query($sql);
}
else
{
// ������� �������
	// sql	
	$sql="SELECT * FROM users ".(isset($find)?"WHERE (nik LIKE '%$find%' OR fullname LIKE '%$find%' OR position LIKE '%$find%') ":"").(isset($order)?"ORDER BY ".$order." ":"ORDER BY nik ").(isset($all)?"":"LIMIT 20");
	//print $sql;

	$cols[id]="ID";
	$cols[nik]="Nik";
	$cols[fullname]="Fullname";
	$cols[position]="Position";
	
	$table = new Table("users","rights",$sql,$cols);
	$table->addbutton=true;
	$table->show();
}
?>