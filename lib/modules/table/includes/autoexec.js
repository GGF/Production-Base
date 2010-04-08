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
			html="Не редактируется!!!!!";//<br><input type=button onclick='closeedit()' value='Закрыть'>";
		}
		//$('#editdivin').html(html);
		dialog_modal(html,type,(html.search('^ok')==-1));
		/*
		$('#dialog').attr('Title',type)
					.html(html);		
		if (html.search('^ok')==-1) {
			//$('#editdiv').trigger('EditShow');
			//$('#editdiv').dialog();
			//html="<div id=dialog title='"+type+"'>"+html+"</div>";

			$('#dialog').dialog({
							modal: true,
							resizable: true,
							buttons: {
								Закрыть: function() {
									$(this).dialog('close');
								}
							}
						});
		}
		*/
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
						editrecord(type,$('form[name=form_'+type+']').serialize());
						$(this).dialog('close');
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
		var show = (html.search('^ok')!= -1);
		if (show) {
			$('#'+trid).remove();
			$('#'+trid+'_op').remove();
			//$('#editdivin').html(html); // для выполнения скриптов отладки
			//dialog_modal(html, '', false);
			//return;
		} else if(html=='') {
			html="Не удаляется!!!!!";//<br><input type=button onclick='closeedit()' value='Закрыть'>";
		} 
		//$('#editdivin').html(html);
		//$('#editdiv').trigger('EditShow');
		dialog_modal(html,delstr,!show);
	}
}

function editrecord(type,data)
{
	var html=$.ajax({url:type+'.php',data:data,async: false}).responseText;
	//alert(data);
	//alert(html);
	if (html=='') {
		html="Не редактируется!!!!!";//<br><input type=button onclick='closeedit()' value='Закрыть'>";
	} 
	var show=(html.search('^ok')!=-1);
	if (show) {
		//$('#editdivin').html(html); // для выполнения скриптов отладки
		var temp = data.split('&');
		var tid;
		for( var i=0; i< temp.length; i++) {
			if (temp[i].search('tid') != -1) {
				tid = temp[i].split('=')[1];
				break;
			}
		}
		updatetable(tid,type,'');
		//closeedit();
		//return;
	}
	//$('#editdivin').html(html);
	//$('#editdiv').trigger('EditShow');
	//$('form').focus_first();
	dialog_modal(html,type,!show);
}


// Focus first element
/*$.fn.focus_first = function() {
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
};
*/

$(document).ready(function(){
	$('.nechettr').live('hover',function(){$('#'+curtr).removeClass('yellow');curtr=$(this).attr('id');$('#'+curtr).addClass('yellow');});
	$('.chettr').live('hover',function(){ $('#'+curtr).removeClass('yellow');curtr=$(this).attr('id');$('#'+curtr).addClass('yellow');});
	$('.find').live('focus',function(){
		$(this).val('')
				.addClass('hasFocus')
				//.one('focus',function(){
					//$(this)
				.keyboard('enter',function(){
							updatetable($(this).attr('tid'),$(this).attr('ttype'),'find='+$(this).val()+$(this).attr('tall')+$(this).attr('idstr'));
							});
				//})
				});
	$('.find').live('blur',function(){
								$(this).val($(this).attr('orgvalue'))
										.removeClass('hasFocus')
										.keyboard('enter');
							});
	$('body').append('<div id=dialog Title="" style="display:none">&nbsp;</div>');

});

function table_set_keyboard()
{
	// для ввода в строку поиска
	$.keyboard('[(letters)|(numbers)|np0|np1|np2|np3|np4|np5|np6|np7|np8|np9]',function(){
		if ($('#dialog').is(':visible')) {
			return;
		} else {
			find=$('.find:visible:last');
			if (find.hasClass('hasFocus')==false) {
				find.focus();
			}
		}
	});
	
	// выбор в таблице
	$.keyboard('enter',{ event  : 'keyup', preventDefault : true },function(){
		if ($('#dialog').is(':visible')) {
			return;// если редактирование идет - игнорируем
		} else {
			/*
			if ( $('.find').is(':visible')) {
				find=$('.find:visible:last');
				if (find.hasClass('hasFocus')) {
					updatetable(find.attr('tid'),find.attr('ttype'),'find='+find.val()+find.attr('tall')+find.attr('idstr'));
				} else {
					$('#'+curtr+' #showlink').click();
				}
			}*/
			// у поиска своя привязка
			$('#'+curtr+' #showlink').click();
		}
	});
	
	$.keyboard('aup',{ event  : 'keyup', preventDefault : false },function(){
		if ($('#dialog').is(':visible')) {
			return;// если редактирование идет - игнорируем
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
	$.keyboard('adown',{ event  : 'keyup', preventDefault : false },function(){
		if ($('#dialog').is(':visible')) {
			return;// если редактирование идет - игнорируем
		} else {
			$('#'+curtr).removeClass("yellow");
			curtr = $('#'+curtr).attr('next');
			$('#'+curtr).addClass("yellow");
			if (($('#'+curtr).position().top+$('#'+curtr).height())>($('#maindiv').position().top+$('#maindiv').height())) {
				$('#maindiv').scrollTop($('#maindiv').scrollTop()+$('#'+curtr).height());
			}
		}
	});

	/*
	 * 	
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
		if ( $('.listtable').is(':visible')) {
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
		if ( $('.listtable').is(':visible')) {
			$('#'+curtr).removeClass("yellow");
			curtr = $('#'+curtr).attr('next');
			$('#'+curtr).addClass("yellow");
			if (($('#'+curtr).position().top+$('#'+curtr).height())>($('#maindiv').position().top+$('#maindiv').height())) {
				$('#maindiv').scrollTop($('#maindiv').scrollTop()+$('#'+curtr).height());
			}
		}
	});

	 */
}
