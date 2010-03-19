<?

include_once $_SERVER["DOCUMENT_ROOT"]."/lib/sql.php"; 
//authorize();
if (isset($dbname) && $dbname!="zaompp" && !mysql_select_db("zaompp") ) my_error("Не удалось выбрать таблицу zaompp");
$sql="SELECT *,(UNIX_TIMESTAMP()-UNIX_TIMESTAMP(MAX(ts))) AS lt FROM session JOIN users ON session.u_id=users.id GROUP BY u_id";
$res=mysql_query($sql);
while($rs=mysql_fetch_array($res)){
	echo "<div>".$rs[nik]." - ".date("H:i:s",mktime(0, 0, 0, date("m")  , date("d"), date("Y"))+$rs[lt])."</div>";
}
if (isset($dbname) && $dbname!="zaompp" && !mysql_select_db($dbname) ) my_error("Не удалось выбрать таблицу $dbname");

?>