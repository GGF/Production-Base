<?
if(!headers_sent()) {
	header('Content-type: text/html; charset=windows-1251');
}

include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php"; // ��� ����� ��� ��� ��� notop �� ���������� �������� html
authorize(); // ����� �����������

if (!isset($notop)) {
showheader("����������");
?>
<div class="menu">
<table width="100%"><tr><td align="center">
<table><tr>
<?
$r = getright($user);
//print_r($user);
//echo $user." - ".$userid." - ".$sessionid;
if ($r["customers"]["edit"] || $r["customers"]["del"] || $r["customers"]["view"]) echo "<td><div class='menuitemcp' id='customers'><a onclick=\"selectmenu('customers','')\"><div>����� ����</div></a></div>";
if ($r["nzap"]["edit"] || $r["nzap"]["del"] || $r["nzap"]["view"])echo "<td><div class='menuitemcp' id='nzap'><a onclick=\"selectmenu('nzap','')\"><div>�� ���� ������</div></a></div>";
if ($r["zap"]["edit"] || $r["zap"]["del"] || $r["zap"]["view"])echo "<td><div class='menuitemcp' id='zap'><a onclick=\"selectmenu('zap','')\"><div>�������</div></a></div>";
if ($r["mp"]["edit"] || $r["mp"]["del"] || $r["mp"]["view"]) echo "<td><div class='menuitemcp' id='mp'><a onclick=\"selectmenu('mp','')\"><div>������-�����</div></a></div>";
if ($r["zd"]["edit"] || $r["zd"]["del"] || $r["zd"]["view"]) echo "<td><div class='menuitemcp' id='zd'><a onclick=\"selectmenu('zd','')\"><div>�����</div></a></div>";
if ($r["pt"]["edit"] || $r["pt"]["del"] || $r["pt"]["view"]) echo "<td><div class='menuitemcp' id='pt'><a onclick=\"selectmenu('pt','')\"><div>�����-��</div></a></div>";
if ($r["todo"]["edit"] || $r["todo"]["del"] || $r["todo"]["view"]) echo "<td><div class='menuitemcp' id='todo'><a onclick=\"selectmenu('todo','')\"><div>TODO</div></a></div>";
if ($r["logs"]["edit"] || $r["logs"]["del"] || $r["logs"]["view"]) echo "<td><div class='menuitemcp' id='logs'><a onclick=\"selectmenu('logs','')\"><div>Logs</div></a></div>";
if ($r["users"]["edit"] || $r["users"]["del"] || $r["users"]["view"]) echo "<td><div class='menuitemcp' id='users'><a onclick=\"selectmenu('users','')\"><div>Users</div></a></div>";
?>
<td><div class='menuitemcp' id='logout'><a href="logout.php"><div>�����</div></a></div>
</table>
</table>
</div>
<?
} // notop

if  ($user=="igor") {
	echo "<div id=userswin class=sun style='display:none'>";
	$sql="SELECT *,(UNIX_TIMESTAMP()-UNIX_TIMESTAMP(ts)) AS lt FROM session JOIN users ON session.u_id=users.id";
	$res=mysql_query($sql);
	while($rs=mysql_fetch_array($res)){
		echo $rs[nik]." - ".$rs[lt]."<br>";
	}
	echo "</div>";
}


echo "<div class='maindiv' id=maindiv>";
echo "������ �������!!!";
echo "</div>";
echo "<div class='loading' id='loading'>��������...</div>";

echo "<div class='editdiv' id=editdiv><img src=/picture/s_error2.png class='rigthtop' onclick='closeedit()'>";
echo "<div class='editdivin' id='editdivin'></div>";
echo "</div>";//����� ��� �������������� �����

echo "<script>newinterface=true;</script>";

if (!isset($notop)) {
	showfooter();
}
?>