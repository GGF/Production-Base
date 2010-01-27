<?
if(!headers_sent() && !isset($print)) {
	header('Content-type: text/html; charset=windows-1251');
}
include_once("../../lib/sql.php");
authorize();

?>