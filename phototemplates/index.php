<?
include_once $_SERVER["DOCUMENT_ROOT"]."/lib/sql.php";

if ($action!='add') {
	showheader("����������� ������");
}
	include "inc.php";
if ($action!='add') {
	showfooter();
}
?>