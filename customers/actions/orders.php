<?php
require $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php";
REQUIRE $_SERVER["DOCUMENT_ROOT"] . "/lib/core/classes/ajax.php";
$processing_type=basename (__FILE__,".php");

$form = new cmsForm_ajax($processing_type);
$form->initBackend();

if (!$form->errors) {
	// ����������
	$edit=$form->request["edit"];
	$customerid=$form->request["customerid"];
	$orderdate=$form->request["orderdate"];
	$number=$form->request["number"];
	
	/*
	if ($edit) {
		// ��������������
		$sql = "UPDATE orders SET customer_id='$customerid', orderdate='".datepicker2date($orderdate)."', number='".addslashes($number)."', filelink='{$ileid}' WHERE id='$edit'";
	} else {
		// ����������
		$sql = "INSERT INTO orders (customer_id,orderdate,number,filelink) VALUES ('$customerid','".datepicker2date($orderdate)."','".addslashes($number)."','".$fileid."')";
	}
	sql::query($sql);
	echo "ok";
	*/
	
	// ���� ��� ��������� ��������� ������ � ���������
	$form->alert(print_r($form->request,true).print_r($_FILES,true).print_r($form->files,true));
	$form->processed();
	//$form->processed("$('#dialog').dialog('close');selectmenu('".$processing_type."','');");
	
} else {
	
	foreach($form->errors as $err) {
		$form->alert(print_r($err,true));
	}
	
	// � ������ ������ ��������� ��� ��������
	$form->processed();
}

?>