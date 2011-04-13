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
	$board_id = $form->request["board_id"];
	$niz = $form->request["niz"];
	$ldate=datepicker2date($ldate);

	if (!empty($edit)) {
		$sql = "UPDATE zadel SET number = '{$number}', ldate='{$ldate}', niz='{$niz}' WHERE id='{$edit}'";
		
	} else {
		$sql = "INSERT INTO zadel (board_id,ldate,number,niz) VALUES('{$board_id}','{$ldate}','{$number}','{$niz}')";
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