<?

require $_SERVER[DOCUMENT_ROOT]."/lib/engine.php"; 

$sql="SELECT *,(UNIX_TIMESTAMP()-UNIX_TIMESTAMP(MAX(ts))) AS lt FROM session JOIN users ON session.u_id=users.id GROUP BY u_id";
$res=sql::fetchAll($sql);
foreach($res as $rs){
	echo "<div>".$rs[nik]." - ".date("H:i:s",mktime(0, 0, 0, date("m")  , date("d"), date("Y"))+$rs[lt])."</div>";
}

?>