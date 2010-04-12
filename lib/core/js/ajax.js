// функции
var firsttr = '';
var curtr = firsttr;
var lasttr = '';

function CMenu_builder(oEvent) {
    var objMenu = [];
    switch (true) {
        /* тут даже можно вставить проверку, чтобы целевой фрагмент не был выделенным текстом, 
         * который мы, например, можем хотеть скопировать/вставить при помощи основного меню :-)*/
        case (document.getSelection().length > 0) : break;
        case oEvent.target.nodeName == 'A' && oEvent.target.className.search('filelink')>-1 :
            /* генерируем данные для одного случая в массив objMenu */
			objMenu.push({'Скопировать в буффер и запустить через nncron' : function () {alert(oEvent.target.href); return true; }});
            break;
        case oEvent.target.nodeName == 'BUTTON' && oEvent.target.className.search('subElems')>-1 :
            /* генерируем данные для другого случая objMenu */
            break;
        case oEvent.target.nodeName == 'a' && oEvent.target.parentNode.id =='footer' :
            /* генерируем данные для другого случая objMenu из статичного набора*/
            objMenu.push({'Действие №1' : function () { return true; }});
            objMenu.push({'Действие №2' : function () { return true; }});
            //etc
            break;
        default:
            break;
    }
    return objMenu.length ? objMenu : false;
}

function debug(text) {
  if (window.console) {
	console.log(text);
  } else if (window.opera) {
	opera.postError(text);
  } else {
   window.alert(text);
  }
}


$(document).ready(function () 
{
	$('textarea[wysiwyg]').live('focus',function(){$(this).wysiwyg();});

	var html = "<div class='loading' id='loading'>Загрузка...</div>";
	$('body').append(html);
	$("#loading").bind("ajaxSend", function(){
	  $(this).show();
	}).bind("ajaxComplete", function(){
	  $(this).hide();
	});
	$("#loading").hide();

	//$(document).contextMenu( function () { return CMenu_builder(this.event);});
	
	$.datepicker.setDefaults($.extend({
		showMonthAfterYear: false, 
		showOn: 'button',
		buttonImage: '/images/calendar.gif',
		buttonImageOnly: true
		}, $.datepicker.regional["ru"]));
	$("#datepicker").live("focus",function(){$(this).datepicker();});
	$("input[datepicker]").live("focus",function(){$(this).datepicker();});
	
});

