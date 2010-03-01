<?
include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php"; // это нужно так как при notop не вызывается заголовк html
authorize(); // вызов авторизации


showheader("Управление");

$menu = new Menu();

$menu->add("customers","Заказ чики");
$menu->add("nzap","Не запу щенные");
$menu->add("conductors","Кондук-торы");
$menu->add("zap","Запуски");
$menu->add("mp","Мастер-платы");
$menu->add("zd","Задел");
$menu->add("pt","Шабло-ны");
$menu->add("todo","ToDo");
$menu->add("logs","Logs");
$menu->add("users","Users");
$menu->add("logout","Выход",false);

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
echo "Выбери чтонить!!!";
echo "</div>";
echo "<div class='loading' id='loading'>Загрузка...</div>";

echo "<div class='editdiv' id=editdiv><img src=/picture/s_error2.png class='rigthtop' onclick='closeedit()'>";
echo "<div class='editdivin' id='editdivin'></div>";
echo "</div>";//место для редактирования всего

echo "<script>newinterface=true;</script>";

	showfooter();

?>