<?
if(!headers_sent() && !isset($print)) {
	header('Content-type: text/html; charset=windows-1251');
}
include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php"; // ��� ����� ��� ��� �� ���������� �������� html

if (isset($dbname) && $dbname!="zaompp" && !mysql_select_db("zaompp") ) my_error("�� ������� ������� ������� zaompp");
$sql="DELETE FROM session WHERE session='".$sessionid."'";
mysql_query($sql);
if (isset($dbname) && $dbname!="zaompp" && !mysql_select_db($dbname) ) my_error("�� ������� ������� ������� $dbname");
setcookie("user","",time() - 3600,'/');
setcookie("userid","",time() - 3600,'/');
setcookie("sessionid","",time() - 3600,'/');
//echo $sql;
header('Location: http://'.$_SERVER['HTTP_HOST'].'');


?>