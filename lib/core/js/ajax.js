// �������
var firsttr = '';
var curtr = firsttr;
var lasttr = '';

function CMenu_builder(oEvent) {
    var objMenu = [];
    switch (true) {
        /* ��� ���� ����� �������� ��������, ����� ������� �������� �� ��� ���������� �������, 
         * ������� ��, ��������, ����� ������ �����������/�������� ��� ������ ��������� ���� :-)*/
        case (document.getSelection().length > 0) : break;
        case oEvent.target.nodeName == 'A' && oEvent.target.className.search('filelink')>-1 :
            /* ���������� ������ ��� ������ ������ � ������ objMenu */
			objMenu.push({'����������� � ������ � ��������� ����� nncron' : function () {alert(oEvent.target.href); return true; }});
            break;
        case oEvent.target.nodeName == 'BUTTON' && oEvent.target.className.search('subElems')>-1 :
            /* ���������� ������ ��� ������� ������ objMenu */
            break;
        case oEvent.target.nodeName == 'a' && oEvent.target.parentNode.id =='footer' :
            /* ���������� ������ ��� ������� ������ objMenu �� ���������� ������*/
            objMenu.push({'�������� �1' : function () { return true; }});
            objMenu.push({'�������� �2' : function () { return true; }});
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

	var html = "<div class='loading' id='loading'>��������...</div>";
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

