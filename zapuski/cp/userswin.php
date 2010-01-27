<?
if(!headers_sent() && !isset($print)) {
	header('Content-type: text/html; charset=windows-1251');
}
include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php"; // это нужно так как не вызывается заголовк html
//authorize();

$sql="SELECT *,(UNIX_TIMESTAMP()-UNIX_TIMESTAMP(MAX(ts))) AS lt FROM session JOIN users ON session.u_id=users.id GROUP BY u_id";
$res=mysql_query($sql);
while($rs=mysql_fetch_array($res)){
	echo "<div>".$rs[nik]." - ".date("H:i:s",mktime(0, 0, 0, date("m")  , date("d"), date("Y"))+$rs[lt])."</div>";
}

?>