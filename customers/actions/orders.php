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
	
	// ���� ���� ���� ��������
	if (!empty($form->files["order_file"])) {
		if (@move_uploaded_file($form->files["order_file"]["tmp_name"], $_SERVER["DOCUMENT_ROOT"]."/tmp/".$form->files["order_file"]["name"])) {
			//$form->alert('���������');
		} else {
			//$form->alert('�� ���������');
		}
	}
	
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
	//move_uploaded_file($form->files["order_file"]["tmp_name"], $_SERVER["DOCUMENT_ROOT"]."/tmp");
	$form->alert(@file_get_contents($form->files["order_file"]["tmp_name"]));
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