<?
include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php"; // ��� ����� ��� ��� ��� notop �� ���������� �������� html
authorize(); // ����� �����������


showheader("����������");

$menu = new Menu();

$menu->add("customers","����� ����");
$menu->add("nzap","�� ���� ������");
$menu->add("conductors","������-����");
$menu->add("zap","�������");
$menu->add("mp","������-�����");
$menu->add("zd","�����");
$menu->add("pt","�����-��");
$menu->add("todo","ToDo");
$menu->add("logs","Logs");
$menu->add("users","Users");
$menu->add("logout","�����",false);

$menu->show();


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

	showfooter();

?>