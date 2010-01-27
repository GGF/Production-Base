</title>
</head>
<body >
<?
echo "<div class=sun id=sun><img onclick=showuserswin() title='Admin здесь' src=/picture/sun.gif></div>";
echo '<div class="glavmenu" onclick="window.location=\'http://'.$_SERVER['HTTP_HOST'].'/\';">Главное меню</div>';

$mes = "<div class='soob'>";
if (isset($dbname) && $dbname!="zaompp" && !mysql_select_db("zaompp") ) my_error("Не удалось выбрать таблицу zaompp");
$sqlquery = "SELECT *, (YEAR(NOW())-YEAR(dr)) as let FROM workers WHERE DAYOFYEAR(dr)>= DAYOFYEAR(CURRENT_DATE()) AND DAYOFYEAR(dr)<= (DAYOFYEAR(CURRENT_DATE())+4) ORDER BY DAYOFYEAR(dr)";
$res = mysql_query($sqlquery);
if (isset($dbname) && $dbname!="zaompp" && !mysql_select_db($dbname) ) my_error("Не удалось выбрать таблицу $dbname");
while ($rs=mysql_fetch_array($res)) {
	$dr = true;
	$mes .= "<div>День рождения - ".$rs["fio"]." - ".$rs["dr"]." - ".$rs["let"]." лет</div>";
}
$mes .= "</div>";
if (isset($dr)) print $mes;

// цитаты баша
echo file_get_contents("http://computers/getbashlocal.php?$bash");
?>