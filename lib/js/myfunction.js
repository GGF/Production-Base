// глобальные переменные
var firsttr = '';
var curtr = firsttr;
var lasttr = '';
var newinterface = false;

// функции

function editrecord(type,data)
{
	var html=$.ajax({url:type+'.php',data:data,async: false}).responseText;
	//alert(data);
	//alert(html);
	if (html=='') {
		html="Не редактируется!!!!!<br><input type=button onclick='closeedit()' value='Закрыть'>";
	} else if (html.search('^ok')!=-1) {
		$('#editdivin').html(html); // для выполнения скриптов отладки
		var temp = data.split('&');
		var tid;
		for( var i=0; i< temp.length; i++) {
			if (temp[i].search('tid') != -1) {
				tid = temp[i].split('=')[1];
				break;
			}
		}
		updatetable(tid,type,'');
		closeedit();
		return;
	}
	$('#editdivin').html(html);
	$('#editdiv').trigger('EditShow');
	$('form').focus_first();
}

function closeedit() {
	$('#editdivin').html('');
	$('#editdiv').hide();
	//alert("AAAA!!!");
}

function selectmenu(type,data)
{
	if (data=='') {
		$('.menuitemcp').removeClass('menuitemsel');;
		$('#'+type).addClass('menuitemsel');;
		var html=$.ajax({url:type+'.php',data:data,async: false}).responseText;
		//alert(html);
		$('#maindiv').html(html);
		closeedit();
	} else {
		window.location = data;
	}
}

function updatetable(id,type,data)
{
	if (data=='') {
		if ($('#'+id).attr('order'))
			data = 'order='+$('#'+id).attr('order');
		if ($('#'+id).attr('tall'))
			data = data+'&all';
		if ($('#'+id).attr('find'))
			data = data+'&find='+$('#'+id).attr('find');
		if ($('#'+id).attr('idstr'))
			data = data+$('#'+id).attr('idstr');
	}
	//alert(data);
	var html=$.ajax({url:type+'.php',data:data,async: false}).responseText;
	$('#'+id).replaceWith(html);
}


function opentr(id,trid,type,show)
{
	$('#'+curtr).removeClass('yellow');
	if (show==null) {
		var opdiv = $('#'+trid+'_op');
		var parent = $('#'+trid).attr('parent');
		var trop=$('#'+parent+' tr[op]');
		if (opdiv.html()==null) {
			var html = $.ajax({url: type+'.php',data: 'id='+id,async: false}).responseText;
			var text='<tr id='+trid+'_op class='+$('#'+trid).attr('class')+' op><td colspan=20>'+html;
			trop.remove();
			$('#'+parent+' th').fadeTo(0,0.4);
			$('#'+parent+' td').fadeTo(0,0.4);
			$('#'+trid).after(text);
		} else {
			opdiv.remove();
			trop.remove();
			$('#'+parent+' th').fadeTo(0,1);
			$('#'+parent+' td').fadeTo(0,1);
		}
	} else {
		var html=$.ajax({url:type+'.php',data:'edit='+id+'&id='+id+'&trid='+trid+'&tid='+$('#'+trid).attr('parent'),async: false}).responseText;
		//alert(html);
		if (html=='') {
			html="Не редактируется!!!!!<br><input type=button onclick='closeedit()' value='Закрыть'>";
		}
		$('#editdivin').html(html);
		if (html.search('^ok')==-1) $('#editdiv').trigger('EditShow');
	}
}

// для открытия заказчика в новой таблице
function opencustr(cusid,trid)
{
	var html = $.ajax({	url: "orders.php?cusid="+cusid, async: false}).responseText;
	$('#maindiv').html(html);
}


// пустая открывалка для позиций в таблице, бывает нужна
function openempty() {
}


function sort(url,id) {
	var html = $.ajax({url: url, async: false}).responseText;
	$('#'+id).replaceWith(html);
}

function my_delete(url,trid,delstr) {
	if (confirm('Удалить - '+delstr)) {
		//alert(url);
		var html = $.ajax({url: url, async: false}).responseText;
		//alert(html);
		if (html.search('^ok')!= -1) {
			$('#'+trid).remove();
			$('#'+trid+'_op').remove();
			$('#editdivin').html(html); // для выполнения скриптов отладки
			return;
		} else if(html=='') {
			html="Не удаляется!!!!!<br><input type=button onclick='closeedit()' value='Закрыть'>";
		} 
		$('#editdivin').html(html);
		$('#editdiv').trigger('EditShow');
	}
}

function showuserswin() {
	$('#userswin').toggle();
	if ($('#userswin').is(':visible')) {
		var html=$.ajax({url:'/lib/modules/auth/userswin.php',data:'',async: false}).responseText;
		$('#userswin').html(html).fadeTo(0,0.8);
	}
}

function yellowtr() {
	$('.nechettr').live('hover',function(){ $('#'+curtr).removeClass('yellow');$(this).addClass('yellow');curtr=$(this).attr('id');});
	$('.chettr').live('hover',function(){ $('#'+curtr).removeClass('yellow');$(this).addClass('yellow');curtr=$(this).attr('id');});
	$('#editdiv').bind('EditShow',function() {
		$(this).show();
		var mywidth = ($('body').width() - $(this).width())/2;
		var myheight = ($('body').height() - $(this).height())/2-70;
		$(this).css({'top': myheight}).css({'left': mywidth});
	});
	$('.find').live('focus',function(){$(this).val(''); $(this).addClass('hasFocus');});
	$('.find').live('blur',function(){$(this).val($(this).attr('orgvalue')); $(this).removeClass('hasFocus');});
	$('textarea[wysiwyg]').live('focus',function(){alert(111);$(this).wysiwyg();});
}



function setkeyboard() {
	if (newinterface) {
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
}


function docloaded() 
{
		yellowtr();
		setkeyboard();
		
		$("#loading").bind("ajaxSend", function(){
		  $(this).show();
		}).bind("ajaxComplete", function(){
		  $(this).hide();
		});
		$("#loading").hide();
 		
		$("#editdiv").hide();
		//$("#editdiv").draggable();
		
		if ($.cookie('adminhere')==1) {
			$("#sun").show();
			$("div:visible").fadeTo(0,0.95);
		}
		
		$(document).contextMenu( function () { return CMenu_builder(this.event);});
		
		$.datepicker.setDefaults($.extend({showMonthAfterYear: false}, $.datepicker.regional[""]));
		$("#datepicker").live("focus",function(){$(this).datepicker($.datepicker.regional["ru"]);});
		$("input[datepicker]").live("focus",function(){$(this).datepicker($.datepicker.regional["ru"]);});
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


// Focus first element
$.fn.focus_first = function() {
  var elem = $('input:visible', this).get(0);
  var select = $('select:visible', this).get(0);
  if (select && elem) {
    if (select.offsetTop < elem.offsetTop) {
      elem = select;
    }
  }
  var textarea = $('textarea:visible', this).get(0);
  if (textarea && elem) {
    if (textarea.offsetTop < elem.offsetTop) {
      elem = textarea;
    }
  }
	if (elem) {
		elem.focus();
	}
	return this;
}

function debug(text) {
  ((window.console && console.log) ||
   (window.opera && opera.postError) ||
   window.alert).call(this, text);
}
