	
	cmsPreload(["/images/loading_ajax.gif", "/images/loading_upload.gif"]);

	// -- INIT --------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	$(function(){
		
		$("body").append("<div id='cmsAjax_loader' style='display: none'></div>");
		
	});
	
	// -- FUNCTIONS ---------------------------------------------------------------------------------------------------------------------------------------------------//
	
	cmsAjax = function(url, params, callback, options) {
		
		var self = cmsAjax;
		
		self.count++;
		
		var count = self.count + 0; // ������������, �.�. � �������� �������� ����� �����������
		
		self.show();
		
		var date1 = new Date();
		
		$.ajax({
			type: "post",
			url: url,
			async: true,
			cache: false,
			data: {"json" : JSON.stringify(params)},
			success: function(data) {
				
				try {
					
					data = JSON.parse(data);
					
					if (data.js) {
						
						var date2 = new Date();
						
						cmsConsole_warning("<b>cmsAJAX[" + count + "]</b>: ������ � <b>" + url + "</b> ������� �������� (<b>" + (date2.getTime() - date1.getTime()) + " ��</b>)");
						
						if (data.text) cmsConsole_notice(
							"<div><b>cmsAJAX[" + count + "]</b>: ����� �������: <a href='#' onclick='$(\"#cmsAjax_report_" + count + "\").toggle()'>��������/������</a></div>" + 
							"<div id='cmsAjax_report_" + count + "' style='display: none'>" + data.text + "</div>" //.replace(/<.*?>/ig, "")
						);
						
						if (callback) callback(data.js);
						
					}
					
				} catch(e) { 
					
					cmsConsole_error(
						"<div><b>cmsAJAX[" + count + "]</b>: �� ������� ���������������� ����� �� ������� <b>" + url + "</b> (������ JS: " + e + "): <a href='#' onclick='$(\"#cmsAjax_report_" + count + "_error\").toggle()'>��������/������</a></div>" + 
						//"<div>" + data.replace(/<.*?>/ig, "").replace(/\n/, "<br>") + "</div>"
						"<div id='cmsAjax_report_" + count + "_error' style='display: none'>" + data + "</div>"
					);
					
				}
				
			},
			error: function(data, textStatus, errorThrown) {
				
				cmsConsole_error(
					"<div><b>cmsAJAX[" + count + "]</b>: ����������� ������ � Backend <b>" + url + "</b>:</div>" + 
					"<div>" + data.responseText + "</div>"
				); //.replace(/<.*?>/ig, ""));
				
			},
			complete: function() {
				
				self.hide();
				
			}
		});
		
		return this;
		
	}
	
	cmsAjax.count = 0;
	cmsAjax.progress = 0;
	cmsAjax.loader = "#cmsAjax_loader";
	cmsAjax.loading = "<div><img src='/admin/ui/loading_ajax.gif' class='iconLoading'>��������, �����</div>";
	
	/**
	 *	��������� ��������� ������
	 *	@param	selector	id	�������� �������
	 */
	cmsAjax.setLoader = function(id) {
		
		this.loader = id;
		
	}
	
	cmsAjax.show = function() {
		
		this.progress++;
		this.loaderShow($(this.loader));
		
	}
	
	cmsAjax.hide = function() {
		
		this.progress--;
		if (this.progress <= 0) this.loaderHide($(this.loader));
		
	}
	
	cmsAjax.loaderShow = function(obj) {
		
		$(obj).show();
		
	}
	
	cmsAjax.loaderHide = function(obj) {
		
		$(obj).hide();
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------// 

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

