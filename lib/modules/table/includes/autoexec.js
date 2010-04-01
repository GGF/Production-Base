// TABLE
// Функции для использования класса Table

// глобальные переменные
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

$(document).ready(function(){
	$('.nechettr').live('hover',function(){$('#'+curtr).removeClass('yellow');curtr=$(this).attr('id');$('#'+curtr).addClass('yellow');});
	$('.chettr').live('hover',function(){ $('#'+curtr).removeClass('yellow');curtr=$(this).attr('id');$('#'+curtr).addClass('yellow');});
	$('.find').live('focus',function(){$(this).val(''); $(this).addClass('hasFocus');});
	$('.find').live('blur',function(){$(this).val($(this).attr('orgvalue')); $(this).removeClass('hasFocus');});
	});
