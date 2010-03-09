<?

if(!headers_sent() && !isset($print)) {
	header('Content-type: text/html; charset=windows-1251');
}
include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php"; // это нужно так как не вызывается заголовк html
authorize(); // вызов авторизации

foreach ($_GET as $key => $val) {
	if (mb_detect_encoding($val)=="UTF-8") $$key=mb_convert_encoding($val,"cp1251");
}
foreach ($_POST as $key => $val) {
	if (mb_detect_encoding($val)=="UTF-8") $$key=mb_convert_encoding($val,"cp1251");
}

?>