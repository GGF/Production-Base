<?
// ���������� ���������� �����

include_once $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php";
REQUIRE $_SERVER["DOCUMENT_ROOT"] . "/lib/core/classes/ajax.php";
$processing_type=basename (__FILE__,".php");

$form = new cmsForm_ajax($processing_type);
$form->initBackend();

if (!$form->errors) {
	// ����������
	$edit = $form->request["edit"];

	$form->processed("$('#dialog').dialog('close');selectmenu('".$processing_type."','');");
}
else 
{
	foreach($form->errors as $err) {
		$form->alert(print_r($err,true));
	}
	
	// � ������ ������ ��������� ��� ��������
	$form->processed();
}
?>