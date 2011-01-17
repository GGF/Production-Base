<?
// Отображает запущенные платы

include_once $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php";
REQUIRE $_SERVER["DOCUMENT_ROOT"] . "/lib/core/classes/ajax.php";
$processing_type=basename (__FILE__,".php");

$form = new cmsForm_ajax($processing_type);
$form->initBackend();

if (!$form->errors) {
	// сохранение
	$edit = $form->request["edit"];
	$plate_id = $form->request["plate_id"];
	$pib = $form->request["pib"];
	$side = $form->request["side"];
	$lays = $form->request["lays"];
	if (!empty($edit)) {
		$sql = "UPDATE conductors SET board_id='{$plate_id}', pib='{$pib}', side='{$side}', lays='{$lays}', user_id='{$_SERVER["userid"]}', ts=NOW() WHERE id='{$edit}'";
		
	} else {
		$sql = "INSERT INTO conductors (board_id,pib,side,lays,user_id,ts) VALUES('{$plate_id}','{$pib}','{$side}','{$lays}','{$_SERVER["userid"]}',NOW())";
	}
	sql::query($sql);
	$form->processed("$('#dialog').dialog('close');selectmenu('{$processing_type}','');");
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