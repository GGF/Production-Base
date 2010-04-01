// функции
var firsttr = '';
var curtr = firsttr;
var lasttr = '';

function setkeyboard() {
	$.keyboard('[(letters)|(numbers)|np0|np1|np2|np3|np4|np5|np6|np7|np8|np9]',function(){
		if ($('#editdiv').is(':visible')) {
		} else {
			find=$('.find:visible:last');
			if (find.hasClass('hasFocus')==false) {
				find.focus();
			}
			find.addClass('hasFocus');
		}
	});
	$.keyboard('esc',{ event  : 'keydown', preventDefault : true },function(){ 
		$('.find').val($(this).attr('orgvalue'));
		$('.find').removeClass('hasFocus');
	});
	$.keyboard('esc',{ event  : 'keyup', preventDefault : true },function(){
		if ($('.rigthtop').is(':visible')) {
			$('.rigthtop').click();
			return false;
		}
	});
	$.keyboard('enter',{ event  : 'keydown', preventDefault : true },function(){});
	$.keyboard('enter',{ event  : 'keyup', preventDefault : true },function(){
		//alert(111);
		if ( $('#editdiv').is(':visible')) {
			
		} else {
			find=$('.find:visible:last');
			if (find.hasClass('hasFocus')) {
				updatetable(find.attr('tid'),find.attr('ttype'),'find='+find.val()+find.attr('tall')+find.attr('idstr'));
			} else {
				$('#'+curtr+' #showlink').click();
			}
		}
	});
	$.keyboard('delete',function(){
		if ( $('#editdiv').is(':visible') == false) {
			$('#'+curtr+' #dellink').click();
		}
	});
	$.keyboard('aright',{ event  : 'keyup', preventDefault : true },function(){
		if ( $('#editdiv').is(':visible') == false) {
			$('#'+curtr+' #editlink').click();
		}
	});
	$.keyboard('insert',{ event  : 'keyup' },function(){
		if ( $('#editdiv').is(':visible') == false) {
			var par = $('#'+curtr).attr('parent');
			$('#'+par+' #addbutton:first').click();
			return false;
		}
	});
	$.keyboard('end',function(){
		if ( $('#editdiv').is(':visible') == false) {
			var par = $('#'+curtr).attr('parent');
			$('#'+par+' #allbutton:first').click();
		}
	});
	$.keyboard('aup',function(){
		if ( $('#editdiv').is(':visible')) {
			//
		} else {
			$('#'+curtr).removeClass("yellow");
			curtr = $('#'+curtr).attr('prev');
			$('#'+curtr).addClass("yellow");
			if (($('#'+curtr).position().top)<($('#maindiv').position().top)) {
				if (curtr == firsttr) {
					$('#maindiv').scrollTop(0);
				} else {
					$('#maindiv').scrollTop($('#maindiv').scrollTop()-$('#'+curtr).height());
				}
			}

		}
		
	});
	$.keyboard('adown',function(){
		if ( $('#editdiv').is(':visible')) {
			//
		} else {
			$('#'+curtr).removeClass("yellow");
			curtr = $('#'+curtr).attr('next');
			$('#'+curtr).addClass("yellow");
			if (($('#'+curtr).position().top+$('#'+curtr).height())>($('#maindiv').position().top+$('#maindiv').height())) {
				$('#maindiv').scrollTop($('#maindiv').scrollTop()+$('#'+curtr).height());
			}
		}
	});
}

CMenu_builder = function (oEvent) {
    var objMenu = [];
    switch (true) {
        /* тут даже можно вставить проверку, чтобы целевой фрагмент не был выделенным текстом, 
         * который мы, например, можем хотеть скопировать/вставить при помощи основного меню :-)*/
        case (document.getSelection().length > 0) : break;
        case oEvent.target.nodeName == 'A' && oEvent.target.className.search('filelink')>-1 :
            /* генерируем данные для одного случая в массив objMenu */
			objMenu.push({'Это ссылка на файл в шаре' : function () { alert(11111); return true; }});
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
  ((window.console && console.log) ||
   (window.opera && opera.postError) ||
   window.alert).call(this, text);
}


$(document).ready(function () 
{
	$('textarea[wysiwyg]').live('focus',function(){$(this).wysiwyg();});

	setkeyboard();

	$("#loading").bind("ajaxSend", function(){
	  $(this).show();
	}).bind("ajaxComplete", function(){
	  $(this).hide();
	});
	$("#loading").hide();

	$(document).contextMenu( function () { return CMenu_builder(this.event);});
	
	$.datepicker.setDefaults($.extend({showMonthAfterYear: false}, $.datepicker.regional[""]));
	$("#datepicker").live("focus",function(){$(this).datepicker($.datepicker.regional["ru"]);});
	$("input[datepicker]").live("focus",function(){$(this).datepicker($.datepicker.regional["ru"]);});
});

