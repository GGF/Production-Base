<?
if ($action!='add') {
	include $GLOBALS["DOCUMENT_ROOT"]."/style/header.php";
	// тут первая часть заголовка дальше нужно напистаь название раздела

	echo "Фотошаблоны запуск";
	include $GLOBALS["DOCUMENT_ROOT"]."/style/header1.php";
	// тут ссылк ана главное
	// дальше собственно текст страницы
}
	include "inc.php";
if ($action!='add') {
	include $GLOBALS["DOCUMENT_ROOT"]."/style/footer.php";
}
?>