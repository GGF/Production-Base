<?
if ($action!='add') {
	include $GLOBALS["DOCUMENT_ROOT"]."/style/header.php";
	// ��� ������ ����� ��������� ������ ����� �������� �������� �������

	echo "����������� ������";
	include $GLOBALS["DOCUMENT_ROOT"]."/style/header1.php";
	// ��� ����� ��� �������
	// ������ ���������� ����� ��������
}
	include "inc.php";
if ($action!='add') {
	include $GLOBALS["DOCUMENT_ROOT"]."/style/footer.php";
}
?>