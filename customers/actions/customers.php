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
	$customer = $form->request["customer"];
	$fullname = $form->request["fullname"];
	$kdir = $form->request["kdir"];
	if (!empty($edit)) {
		// ��������������
		$sql = "UPDATE customers SET customer='{$customer}', fullname='{$fullname}', kdir='{$kdir}' WHERE id='{$edit}'";
	} else {
		// ����������
		$sql = "INSERT INTO customers (customer,fullname,kdir) VALUES ('{$customer}','{$fullname}','{$kdir}')";
	}
	sql::query($sql);
	$form->processed("$('#dialog').dialog('close');selectmenu('{$processing_type}','');");
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