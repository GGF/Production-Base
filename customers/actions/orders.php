<?php
require $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php";
REQUIRE $_SERVER["DOCUMENT_ROOT"] . "/lib/core/classes/ajax.php";
$processing_type=basename (__FILE__,".php");

$form = new cmsForm_ajax($processing_type);
$form->initBackend();

if (!$form->errors) {
	// сохранение
	$edit=$form->request["edit"];
	$customerid=$form->request["customerid"];
	$orderdate=$form->request["orderdate"];
	$number=addslashes($form->request["number"]);
	$orderdate = datepicker2date($orderdate);
	$fileid = 0;
	
	// файл если есть сохраним
	if (!empty($form->files["order_file"]["size"])) {
		$filename = $_SERVER["DOCUMENT_ROOT"].UPLOAD_FILES_DIR."/customers/".cmsUTF_encode($form->files["order_file"]["name"]);
		$i = 0;
		while (file_exists($filename))
		{
			$i++;
			$filename = $_SERVER["DOCUMENT_ROOT"].UPLOAD_FILES_DIR."/customers/{$i}_".cmsUTF_encode($form->files["order_file"]["name"]);
		}
		if (@move_uploaded_file($form->files["order_file"]["tmp_name"], $filename)) {
			//$form->alert('перекинул');
			$fileid = getFileId(cmsUTF_decode($filename));
		} else {
			$form->alert("Не удалось сохранить файл! Попробуйте еще.");
			$form->processed();
			exit;
		}
	} 
	else
	{
		//$form->alert('Нет файлов');
		// линк получить
		if ($form->request["curfile"] != 'None')
			$fileid = getFileId($_SERVER["DOCUMENT_ROOT"]."/customers/ordersfile/".$form->request["curfile"]);
	}
	
	if ($edit) {
		// редактирование
		$sql = "UPDATE orders SET customer_id='{$customerid}', orderdate='{$orderdate}', number='{$number}', filelink='{$fileid}' WHERE id='{$edit}'";
	} else {
		// добавление
		$sql = "INSERT INTO orders (customer_id,orderdate,number,filelink) VALUES ('{$customerid}','{$orderdate}','{$number}','{$fileid}')";
	}
	sql::query($sql);
	
	// если все нормально закрываем диалог и обновляем
	$form->processed("$('#dialog').dialog('close');selectmenu('{$processing_type}','');");
	
} else {
	
	foreach($form->errors as $err) {
		$form->alert(print_r($err,true));
	}
	
	// в случае ошибок обработка без закрытия
	$form->processed();
}

?>