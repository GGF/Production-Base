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
//        $form->alert(print_r($form->request,true));
//        $form->processed();

	// сохрнение
        if (!empty($edit)) {
                $sql="UPDATE todo SET what='".addslashes($what)."', cts=NOW(), rts='0', u_id='".$_SERVER[userid]."' WHERE id='{$edit}'";
        } else {
                $sql="INSERT INTO todo (what,cts,rts,u_id) VALUES ('".addslashes($what)."',NOW(),'0',".$_SERVER[userid].")";
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
