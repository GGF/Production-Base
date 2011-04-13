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
	$number=addslashes($form->request["number"]);
	$orderdate = datepicker2date($orderdate);
	$fileid = 0;
	
	// ���� ���� ���� ��������
	if (!empty($form->files["order_file"]["size"])) {
		$filename = $_SERVER["DOCUMENT_ROOT"].UPLOAD_FILES_DIR."/customers/".cmsUTF_encode($form->files["order_file"]["name"]);
		$i = 0;
		while (file_exists($filename))
		{
			$i++;
			$filename = $_SERVER["DOCUMENT_ROOT"].UPLOAD_FILES_DIR."/customers/{$i}_".cmsUTF_encode($form->files["order_file"]["name"]);
		}
		if (@move_uploaded_file($form->files["order_file"]["tmp_name"], $filename)) {
			//$form->alert('���������');
			$fileid = getFileId(cmsUTF_decode($filename));
		} else {
			$form->alert("�� ������� ��������� ����! ���������� ���.");
			$form->processed();
			exit;
		}
	} 
	else
	{
		//$form->alert('��� ������');
		// ���� ��������
		if ($form->request["curfile"] != 'None')
			$fileid = getFileId($_SERVER["DOCUMENT_ROOT"]."/customers/ordersfile/".$form->request["curfile"]);
	}
	
	if ($edit) {
		// ��������������
		$sql = "UPDATE orders SET customer_id='{$customerid}', orderdate='{$orderdate}', number='{$number}', filelink='{$fileid}' WHERE id='{$edit}'";
	} else {
		// ����������
		$sql = "INSERT INTO orders (customer_id,orderdate,number,filelink) VALUES ('{$customerid}','{$orderdate}','{$number}','{$fileid}')";
	}
	sql::query($sql);
	
	// ���� ��� ��������� ��������� ������ � ���������
	$form->processed("$('#dialog').dialog('close');selectmenu('{$processing_type}','');");
	
} else {
	
	foreach($form->errors as $err) {
		$form->alert(print_r($err,true));
	}
	
	// � ������ ������ ��������� ��� ��������
	$form->processed();
}

?>