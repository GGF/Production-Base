<?
defined("CMS") or die("Restricted usage: " . basename(__FILE__));

// ����������� ����������.
require_once "hypher.php";

// �������� ����� �������� � ������ ������.
$hy_ru = hypher_load(dirname(__FILE__).'/hyph_ru_RU.conf');

// "����������" � ��������� �������� ������ � ����� �������. ����� ������� ��� �������� ����
function addhypher($text) {
	global $hy_ru;
	return hypher($hy_ru, $text);
}

?>