</title>
</head>
<body >
<?
echo "<div class=sun id=sun><img onclick=showuserswin() title='Admin �����' src=/picture/sun.gif></div>";
echo '<div class="glavmenu" onclick="window.location=\'http://'.$_SERVER['HTTP_HOST'].'/\';">������� ����</div>';

$mes = "<div class='soob'>";
if (isset($dbname) && $dbname!="zaompp" && !mysql_select_db("zaompp") ) my_error("�� ������� ������� ������� zaompp");
$sqlquery = "SELECT *, (YEAR(NOW())-YEAR(dr)) as let FROM workers WHERE DAYOFYEAR(dr)>= DAYOFYEAR(CURRENT_DATE()) AND DAYOFYEAR(dr)<= (DAYOFYEAR(CURRENT_DATE())+4) ORDER BY DAYOFYEAR(dr)";
$res = mysql_query($sqlquery);
if (isset($dbname) && $dbname!="zaompp" && !mysql_select_db($dbname) ) my_error("�� ������� ������� ������� $dbname");
while ($rs=mysql_fetch_array($res)) {
	$dr = true;
	$mes .= "<div>���� �������� - ".$rs["fio"]." - ".$rs["dr"]." - ".$rs["let"]." ���</div>";
}
$mes .= "</div>";
if (isset($dr)) print $mes;

// ������ ����
echo file_get_contents("http://computers/getbashlocal.php?$bash");
?>