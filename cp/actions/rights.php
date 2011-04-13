<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php";
REQUIRE $_SERVER["DOCUMENT_ROOT"] . "/lib/core/classes/ajax.php";
$processing_type=basename (__FILE__,".php");

$form = new cmsForm_ajax($processing_type);
$form->initBackend();

if (!$form->errors) {
	// сохранение
	extract($form->request);
	// сохрнение
	$sql="DELETE FROM rights WHERE u_id='$userid'";
	sql::query($sql);
	if (!empty($r)) {
		foreach ($r as $key=>$val) {
			foreach($val as $k=>$V) {
				$sql="INSERT INTO rights (u_id,type_id,rtype_id,rights.right) VALUES ('$userid','$key','$k','1')";
				sql::query($sql);
			}
		}
	}
        // почистить сессию для того чтоб вступили права пользователь должен перезайти
        //$sql = "DELETE FROM session WHERE u_id='{$userid}'";
        //sql::query($sql);
	$form->processed("$('#dialog').dialog('close');selectmenu('users','');");
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
