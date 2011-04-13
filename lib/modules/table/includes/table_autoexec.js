/*
 *  TABLE
 *  Функции для использования класса Table
 *  Используют диалог jquery.dialog
 */


var firsttr = '';
var curtr = firsttr;
var lasttr = '';

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
		if (html=='') {
			html="Не редактируется!!!!!";
		}
		dialog_modal(html,type,(html.search('^ok')==-1));
	}
}

function dialog_modal(html,type,show)
{
	var Title;
	var Buttons;
	if (html.search('<form')==-1) 
	{
		Title = 'Сообщение';
		resizeble = false;onesc=true;
		
		Buttons={
				Ok: function() {
						$(this).dialog('close');
					}
				};
	} else {
		Title='Редактирование';
		resizeble = true; onesc=false;
		Buttons={
				' ': function() {
						alert($('form[name=form_'+type+']').serialize());
				},
				Закрыть: function() {
						$(this).dialog('close');
					},
				Сохранить: function() {
						// изза файловых поле тут надо все же делать посылку формы в класс form_ajax
						//$(this).dialog('close');
						//editrecord(type,$('form[name=form_'+type+']').serialize());
						$('form').submit();
					}
				};
	}
	$('#dialog').attr('Title',Title)
				.html(html);	
	if(show==null | show)
	{
		$('#dialog').dialog({
			closeOnEscape: onesc,
			title:Title,
			width: 'auto',
			modal: true,
			resizable: resizeble,
			draggable: false,
			buttons: Buttons
		});
		$('form').focus_first();
	}
}

// для открытия заказчика в новой таблице
function opencustr(cusid,trid)
{
	var html = $.ajax({	url: "orders.php?cusid="+cusid, async: false}).responseText;
	$('#maindiv').html(html);
}

function openuser(userid,trid) {
	opentr(userid,trid,'rights',true);
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
		var html = $.ajax({url: url, async: false}).responseText;
		var show = (html.search('^ok')!= -1);
		if (show) {
			$('#'+trid).remove();
			$('#'+trid+'_op').remove();
		} else if(html=='') {
			html="Не удаляется!!!!!";
		} 
		dialog_modal(html,delstr,!show);
	}
}

function editrecord(type,data)
{
	var html=$.ajax({url:type+'.php',data:data,async: false}).responseText;
	if (html=='') {
		html="Не редактируется!!!!!";
	} 
	var show=(html.search('^ok')!=-1);
	if (show) {
		var temp = data.split('&');
		var tid;
		for( var i=0; i< temp.length; i++) {
			if (temp[i].search('tid') != -1) {
				tid = temp[i].split('=')[1];
				break;
			}
		}
		updatetable(tid,type,'');
	}
	dialog_modal(html,type,!show);
}


// Focus first element
$.fn.focus_first = function() {
	var elem = $(':text:visible', this).get(0);
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
};

$(document).ready(function(){
	$('.nechettr').live('hover',function(){$('#'+curtr).removeClass('yellow');curtr=$(this).attr('id');$('#'+curtr).addClass('yellow');});
	$('.chettr').live('hover',function(){ $('#'+curtr).removeClass('yellow');curtr=$(this).attr('id');$('#'+curtr).addClass('yellow');});
	$('.find').live('focus',function(){
			$(this).val('')
					.addClass('hasFocus')
					.keyboard('enter',function(){
								updatetable($(this).attr('tid'),$(this).attr('ttype'),'find='+$(this).val()+$(this).attr('tall')+$(this).attr('idstr'));
								})
					.keyboard('esc',function(){$(this).blur()});
				});
	$('.find').live('blur',function(){
								$(this).val($(this).attr('orgvalue'))
										.removeClass('hasFocus');
							});
	$('body').append('<div id=dialog Title="" style="display:none">&nbsp;</div>');
	table_set_keyboard();

});

function keybdebug(text) {
	if (window.console) {
		console.log(text);
	} else if (window.opera) {
		opera.postError(text);
	} else {
	   window.alert(text);
	}
}
function table_set_keyboard()
{

	// со сменой на версию 1.4.3 плохо работает jquery.keyboard попытаюсь сменить на обычный кейпрес
	// однако используем индесы оттуда
	// keypress имеет другие коды!!!
	$(document).keydown(function(event){
		//keybdebug('code:'+event.keyCode);
		if ($.inArray(event.keyCode,$.keyb.getRange('letters')) != -1 || 
				$.inArray(event.keyCode,$.keyb.getRange('allnum')) != -1) {
			// для ввода в строку поиска
			if ($('#dialog').is(':visible')) {
				return true;
			} else {
				find=$('.find:visible:last');
				if (find.hasClass('hasFocus')==false) {
					find.focus();
				}
				return true;
			}
		} else if (event.keyCode==$.keyb.getIndexCode('enter')) {
			if ($('#dialog').is(':hidden')) {
				var tr = $('#'+curtr+' #showlink:first');
                                //alert(tr.is(':visible'));
                                //if (tr.is(':visible')) {
                                  tr.click();  
                                //} 
			} else {
				//keybdebug('buttons:'+$('.partybutton').val()+' '+$('input[value="2 партия"]').is(':visible'));
				$('.partybutton').first().click();
			}
			return false;
		} else if (event.keyCode==$.keyb.getIndexCode('aup')) {
			if ($('#dialog').is(':hidden')) {
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
			return false;			
		} else if (event.keyCode==$.keyb.getIndexCode('adown')) {
			if ($('#dialog').is(':hidden')) {
				$('#'+curtr).removeClass("yellow");
				curtr = $('#'+curtr).attr('next');
				$('#'+curtr).addClass("yellow");
				if (($('#'+curtr).position().top+$('#'+curtr).height())>($('#maindiv').position().top+$('#maindiv').height())) {
					$('#maindiv').scrollTop($('#maindiv').scrollTop()+$('#'+curtr).height());
				}
			}
			return false;			
		}  /*else if (event.keyCode==$.keyb.getIndexCode('delete')) {
			if ($('#dialog').is(':hidden')) {
				$('#'+curtr+' #dellink').click();
			}
			return false;
						
		}*/
            return true;
		
	});

}
