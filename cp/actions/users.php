<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php";
REQUIRE $_SERVER["DOCUMENT_ROOT"] . "/lib/core/classes/ajax.php";
$processing_type=basename (__FILE__,".php");

$form = new cmsForm_ajax($processing_type);
$form->initBackend();

if (!$form->errors) {
	// сохранение
	//$edit = $form->request["edit"];
	extract($form->request);
	// сохрнение
	if ($password1!=$password2)
	{
		echo ("Пароли не совпадают!"); exit;
	}
	if (!empty($edit)) 
	{
		// редактирование
		$sql = "UPDATE users SET nik='$nik', fullname='$fullname', position='$position', password='$password1' WHERE id='$edit'";
	}
	else 
	{
		// добавление
		$sql = "INSERT INTO users (nik,fullname,position,password) VALUES ('$nik','$fullname','$position','$password1')";
	}
	sql::query($sql);
	$form->processed("$('#dialog').dialog('close');selectmenu('".$processing_type."','');");
}
else 
{
	foreach($form->errors as $err) {
		$form->alert(print_r($err,true));
	}
	
	// в случае ошибок обработка без закрытия
	$form->processed();
}

?>
