<?
$GLOBALS["rootpath"]=realpath($GLOBALS["DOCUMENT_ROOT"]);
include_once $rootpath."/lib/sql.php";
$GLOBALS["debugAPI"] = 0;
?>
<!--   Copyright 2000 Igor Fedoroff   |  g_g_f@mail.ru  -->
<html>
<head>
	<LINK REL='STYLESHEET' TYPE='text/css' HREF='/style/style.css'>
	<link type="text/css" href="/style/themes/base/ui.all.css" rel="stylesheet" />
	<link type="text/css" href="/style/jquery.wysiwyg.css" rel="stylesheet" />
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
	<meta http-equiv="Content-Script-Type" content="text/javascript; charset=windows-1251">
	<meta name="Author" content="Игорь Федоров">
	<meta name="Description" content="ЗАО МПП">
	<script type="text/javascript" src="/lib/jquery-1.4.min.js"></script>
	<script type="text/javascript" src="/lib/jquery.wysiwyg.js"></script>
	<script type="text/javascript" src="/lib/jquery.keyboard.js"></script>
	<script type="text/javascript" src="/lib/myfunction.js"></script>
	<script type="text/javascript" src="/lib/ui/ui.core.js"></script>
	<script type="text/javascript" src="/lib/ui/ui.datepicker.js"></script>
	<script type="text/javascript" src="/lib/ui/i18n/ui.datepicker-ru.js"></script>
	<script type="text/javascript" src="/lib/ui/ui.draggable.js"></script>
	<script type="text/javascript" src="/lib/ui/ui.droppable.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
		yellowtr();
		setkeyboard();
		
		$("#loading").bind("ajaxSend", function(){
		  $(this).show();
		}).bind("ajaxComplete", function(){
		  $(this).hide();
		});
		$("#loading").hide();
 		
		$("#editdiv").hide();
		$('#editdiv').draggable();
		
		<?
			if (isadminhere()) {
				echo "$('#sun').show();";
				echo "$('div:visible').fadeTo(0,0.95);";
			}
		?>
	});

	$(function() {
		$.datepicker.setDefaults($.extend({showMonthAfterYear: false}, $.datepicker.regional['']));
		$("#datepicker").datepicker($.datepicker.regional['ru']);
	});
	</script>
<title>
База данных ЗАО МПП - 