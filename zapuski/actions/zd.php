<?
// Отображает запущенные платы

include_once $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php";
REQUIRE $_SERVER["DOCUMENT_ROOT"] . "/lib/core/classes/ajax.php";
$processing_type=basename (__FILE__,".php");

$form = new cmsForm_ajax($processing_type);
$form->initBackend();

if (!$form->errors) {
	$edit = $form->request["edit"];
	$number = $form->request["number"];
	$ldate = $form->request["ldate"];
	$plate_id = $form->request["plate_id"];
	$niz = $form->request["niz"];
	$ldate=datepicker2date($ldate);

	if (!empty($edit)) {
		$sql = "UPDATE zadel SET number = '{$number}', ldate='{$ldate}', board_id='{$plate_id}', niz='{$niz}' WHERE id='{$edit}'";
		
	} else {
		$sql = "INSERT INTO zadel (board_id,ldate,number,niz) VALUES('{$plate_id}','{$ldate}','{$number}','{$niz}')";
	}
	sql::query($sql);
	sql::error(true);
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