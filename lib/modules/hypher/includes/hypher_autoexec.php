<?
defined("CMS") or die("Restricted usage: " . basename(__FILE__));

// ����������� ����������.
require_once $_SERVER[DOCUMENT_ROOT] . "/lib/modules/hypher/includes/hypher.php";

// �������� ����� �������� � ������ ������.
$hy_ru = hypher_load($_SERVER[DOCUMENT_ROOT] . '/lib/modules/hypher/includes/hyph_ru_RU.conf');

// "����������" � ��������� �������� ������ � ����� �������. ����� ������� ��� �������� ����
function addhypher($text) {
	global $hy_ru;
	return hypher($hy_ru, $text);
}

?>