<?
// ���������� ������ �� ������������������
include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php";
authorize(); // ����� �����������


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
		echo "<input type=button value='���������' onclick=\"editrecord('todo',$('#editform').serialize())\"><input type=button value='������' onclick='closeedit()'><input type=button onclick=\"alert($('#editform').serialize())\">";
		echo "<script>$(function()
  {
      $('#wysiwyg').wysiwyg();
  });</script>";
	} else {
		// ����������

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
			my_error("�� ������� �������� ������� todo");
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
	$cols[nik]="���";
	$cols[cts]="�����";
	$cols[rts]="��������";
	$cols[what]="��� �������";
	
	$addbutton=true;
	$opentype='todo';

include "table.php";
/*
	echo "<pre>
0. <del>���������� �����������!!!</del>
0.1 <del>����� ����������</del> 21-10-2009 15:04
0.1.1 <del>�������������� ����</del> 22-10-2009 11:41
0.1.2 <del>���� � ����������� �� ����</del> 22-10-2009 12:06
0.1.3 �������� � ����������� �� ����
0.2 <del>����������� �� ������ �������</del> 16-10-2009
0.3 ������ ������ - �� �� �� excel � ����� �������
0.4 
1. � ����� ������� ��������������
2. ���������� ����� � ����� ��� ���������� ��
3. � ���������� ����� ����� �� ���� � ���������� ����������������� ��������������
4. ��� �� ���������� ����� � �����������
5. ���� ������� �����, � ����� ����� - ���������� ����� ���� ��� �� ���� �� ��������� �� ������
6. ����� ���� �������� �����
7. ������ �� - 4 (���,���,��� ������, ��� �������)  ���������� ���� ��� � ��� ������ �������, �����������
8. <del>������ ���� ��� ������������ � ����������</del> 21-10-2009 13:41
</pre>";
*/
}
?>