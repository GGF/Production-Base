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
	$number=$form->request["number"];
	
	/*
	if ($edit) {
		// редактирование
		$sql = "UPDATE orders SET customer_id='$customerid', orderdate='".datepicker2date($orderdate)."', number='".addslashes($number)."', filelink='{$ileid}' WHERE id='$edit'";
	} else {
		// добавление
		$sql = "INSERT INTO orders (customer_id,orderdate,number,filelink) VALUES ('$customerid','".datepicker2date($orderdate)."','".addslashes($number)."','".$fileid."')";
	}
	sql::query($sql);
	echo "ok";
	*/
	
	// если все нормально закрываем диалог и обновляем
	$form->alert(print_r($form->request,true).print_r($_FILES,true).print_r($form->files,true));
	$form->processed();
	//$form->processed("$('#dialog').dialog('close');selectmenu('".$processing_type."','');");
	
} else {
	
	foreach($form->errors as $err) {
		$form->alert(print_r($err,true));
	}
	
	// в случае ошибок обработка без закрытия
	$form->processed();
}

?>