<?
defined("CMS") or die("Restricted usage: " . basename(__FILE__));

// Подключение библиотеки.
require_once "hypher.php";

// Загрузка файла описания и набора правил.
$hy_ru = hypher_load(dirname(__FILE__).'/hyph_ru_RU.conf');

// "перегрузка" я собираюсь вызывать только с одним словарём. зачем якаждый раз учитыват буду
function addhypher($text) {
	global $hy_ru;
	return hypher($hy_ru, $text);
}

?>