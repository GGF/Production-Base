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
	if (html=='') {
		html="Не радактируется!!!!!<br><input type=button onclick='closeedit()' value='Закрыть'>";
	}
	$('#editdivin').html(html);
	//alert(html);
	if (data.match("accept")==null) {
		$('#editdiv').show();
		var mywidth = ($('body').width() - $('#editdiv').width())/2;
		var myheight = ($('body').height() - $('#editdiv').height())/2-70;
		$('#editdiv').css({'top': myheight});
		$('#editdiv').css({'left': mywidth});
		$('form').focus_first();
	}
}

function closeedit() {
	$('#editdivin').html('');
	$('#editdiv').hide();
	//alert("AAAA!!!");
}

function selectmenu(type,data)
{
	if (data=='') {
		$('.menuitem').css({'background-color' : 'rgb(200,255,200)'});
		$('.menuitemcp').css({'background-color' : 'rgb(200,255,200)'});
		$('#'+type).css({'background-color' : 'rgb(100,255,100)'});
		//updatetable('maindiv',type,data);
		var html=$.ajax({url:type+'.php',data:data,async: false}).responseText;
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
		//alert(data);
	}
	//data = data + '&update';
	var html=$.ajax({url:type+'.php',data:data,async: false}).responseText;
	$('#'+id).replaceWith(html);
}


function opentr(id,trid,type,show)
{
	if (show==null) {
		var opdiv = $('#'+trid+'_op');
		if (opdiv.html()==null) {
			$('#'+$('#'+trid).attr('parent')+' tr[op]').hide();
			var html = $.ajax({url: type+'.php',data: 'notop&id='+id,async: false}).responseText;
			var text='<tr id='+trid+'_op class='+$('#'+trid).attr('class')+' op><td colspan=20>'+html;
			$('#'+trid).after(text);
			$('#'+$('#'+trid).attr('parent')+' td').fadeTo(0,0.4);
			$('#'+$('#'+trid).attr('parent')+' th').fadeTo(0,0.4);
			$('#'+trid+' > *').fadeTo(0,1);
			$('#'+trid+'_op > *').fadeTo(0,1);
			$('#'+trid+'_op .listtable td').fadeTo(0,1);
			$('#'+trid+'_op .listtable th').fadeTo(0,1);
		} else {
			if (opdiv.css('display')=='none') {
				$('#'+$('#'+trid).attr('parent')+' tr[op]').hide();
				opdiv.show();
				$('#'+$('#'+trid).attr('parent')+' td').fadeTo(0,0.4);
				$('#'+$('#'+trid).attr('parent')+' th').fadeTo(0,0.4);
				$('#'+trid+' > *').fadeTo(0,1);
				$('#'+trid+'_op > *').fadeTo(0,1);
				$('#'+trid+'_op .listtable td').fadeTo(0,1);
				$('#'+trid+'_op .listtable th').fadeTo(0,1);
			} else {
				$('#'+$('#'+trid).attr('parent')+' *').fadeTo(0,1);
				$('#'+$('#'+trid).attr('parent')+' tr[op]').hide();
				opdiv.hide();
			}
		}
	} else {
		var html=$.ajax({url:type+'.php',data:'edit='+id+'&id='+id+'&trid='+trid+'&tid='+$('#'+trid).attr('parent'),async: false}).responseText;
		//alert(html);
		if (html=='') {
			html="Не радактируется!!!!!<br><input type=button onclick='closeedit()' value='Закрыть'>";
		}
		$('#editdivin').html(html);
		$('#editdiv').show();
		var mywidth = ($('body').width() - $('#editdiv').width())/2;
		var myheight = ($('body').height() - $('#editdiv').height())/2-70;
		$('#editdiv').css({'top': myheight});
		$('#editdiv').css({'left': mywidth});
	}
	

}

// для открытия заказчика в новой таблице
function opencustr(cusid,trid)
{
	var html = $.ajax({	url: "orders.php?notop&cusid="+cusid, async: false}).responseText;
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
		$('#'+trid).remove();
		$('#'+trid+'_op').remove();
	}
}

function showuserswin() {
	$('#userswin').toggle();
	$('#userswin').css({'background-color':'white'});
	if ($('#userswin').is(':visible')) {
		var html=$.ajax({url:'userswin.php',data:'',async: false}).responseText;
		//alert(html);
		$('#userswin').html(html);
		$('#userswin').fadeTo(0,0.8);
	}
}

function yellowtr() {
		$('.chettr').hover(function () {
			$(this).css({'background-color' : 'yellow'});
			curtr=$(this).attr('id');
		}, function () {
			$('.chettr').css({'background-color' : 'rgb(250,250,250)'});
			$('.nechettr').css({'background-color' : 'rgb(230,230,230)'});
		});
		$('.nechettr').hover(function () {
			$(this).css({'background-color' : 'yellow'});
			curtr=$(this).attr('id');
		}, function () {
			$('.chettr').css({'background-color' : 'rgb(250,250,250)'});
			$('.nechettr').css({'background-color' : 'rgb(230,230,230)'});
		});
}

function setkeyboard() {
	if (newinterface) {
		$.keyboard('ctrl+f1',function(){$('#customers a').click();});
		$.keyboard('ctrl+f2',function(){$('#nzap a').click();});
		$.keyboard('ctrl+f3',function(){$('#zap a').click();});
		$.keyboard('ctrl+f4',function(){$('#mp a').click();});
		$.keyboard('ctrl+f5',function(){$('#zd a').click();});
		$.keyboard('ctrl+f6',function(){$('#pt a').click();});
		$.keyboard('ctrl+f7',function(){$('#todo a').click();});
		$.keyboard('ctrl+f8',function(){$('#logs a').click();});
		$.keyboard('ctrl+f9',function(){$('#users a').click();});
		//$.keyboard('ctrl+w',{ preventDefault : true },function(){alert(111);});
		$.keyboard('[(letters)|(numbers)]',function(){
			if ($('#editdiv').is(':visible')) {
				//$('#editdiv input[type!="hidden"]:first').focus();
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
		$.keyboard('enter',{ event  : 'keyup', preventDefault : true },function(){
			if ( $('#editdiv').is(':visible')) {
				//$('#editdiv input[type!="hidden"]:next').focus();
			} else {
				find=$('.find:visible:last');
				//alert(find.hasClass('hasFocus')+' '+find.val());
				if (find.hasClass('hasFocus')) {
					updatetable(find.attr('tid'),find.attr('ttype'),'find='+find.val()+find.attr('tall')+find.attr('idstr'));
					//find.blur();
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
				$('.chettr').css({'background-color' : 'rgb(250,250,250)'});
				$('.nechettr').css({'background-color' : 'rgb(230,230,230)'});
				curtr = $('#'+curtr).attr('prev');
				$('#'+curtr).css({'background-color' : 'yellow'});
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
				$('.chettr').css({'background-color' : 'rgb(250,250,250)'});
				$('.nechettr').css({'background-color' : 'rgb(230,230,230)'});
				curtr = $('#'+curtr).attr('next');
				$('#'+curtr).css({'background-color' : 'yellow'});
				if (($('#'+curtr).position().top+$('#'+curtr).height())>($('#maindiv').position().top+$('#maindiv').height())) {
					$('#maindiv').scrollTop($('#maindiv').scrollTop()+$('#'+curtr).height());
				}
				//debug(($('#'+curtr).position().top+$('#'+curtr).height())>($('#maindiv').position().top+$('#maindiv').height()));
			}
		});
	}
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