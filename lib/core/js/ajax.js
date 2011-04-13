	
	cmsPreload(["/images/loading_ajax.gif", "/images/loading_upload.gif"]);

	// -- INIT --------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	$(function(){
		
		$("body").append("<div id='cmsAjax_loader' style='display: none'></div>");
		
	});
	
	// -- FUNCTIONS ---------------------------------------------------------------------------------------------------------------------------------------------------//
	
	cmsAjax = function(url, params, callback, options) {
		
		var self = cmsAjax;
		
		self.count++;
		
		var count = self.count + 0; // переназываем, т.к. в процессе значение может поменяться…
		
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
						
						cmsConsole_warning("<b>cmsAJAX[" + count + "]</b>: Запрос к <b>" + url + "</b> успешно выполнен (<b>" + (date2.getTime() - date1.getTime()) + " мс</b>)");
						
						if (data.text) cmsConsole_notice(
							"<div><b>cmsAJAX[" + count + "]</b>: Ответ скрипта: <a href='#' onclick='$(\"#cmsAjax_report_" + count + "\").toggle()'>Показать/скрыть</a></div>" + 
							"<div id='cmsAjax_report_" + count + "' style='display: none'>" + data.text + "</div>" //.replace(/<.*?>/ig, "")
						);
						
						if (callback) callback(data.js);
						
					}
					
				} catch(e) { 
					
					cmsConsole_error(
						"<div><b>cmsAJAX[" + count + "]</b>: Не удалось интерпретировать ответ от скрипта <b>" + url + "</b> (Ошибка JS: " + e + "): <a href='#' onclick='$(\"#cmsAjax_report_" + count + "_error\").toggle()'>Показать/скрыть</a></div>" + 
						//"<div>" + data.replace(/<.*?>/ig, "").replace(/\n/, "<br>") + "</div>"
						"<div id='cmsAjax_report_" + count + "_error' style='display: none'>" + data + "</div>"
					);
					
				}
				
			},
			error: function(data, textStatus, errorThrown) {
				
				cmsConsole_error(
					"<div><b>cmsAJAX[" + count + "]</b>: Критическая ошибка в Backend <b>" + url + "</b>:</div>" + 
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
	cmsAjax.loading = "<div><img src='/admin/ui/loading_ajax.gif' class='iconLoading'>Загрузка, ждите…</div>";
	
	/**
	 *	Выставить кастомный лоадер
	 *	@param	id	Селектор лоадера
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
			objMenu.push({'Скопировать в буффер' : function () {document.bazaapplet.copytoclipboard(Url.decode(oEvent.target.href)); return true; }});
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
			return true;
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

	
	
	$.datepicker.setDefaults($.extend({
		showMonthAfterYear: false, 
		showOn: 'button',
		buttonImage: '/images/calendar.gif',
		buttonImageOnly: true
		}, $.datepicker.regional["ru"]));
	$("#datepicker").live("focus",function(){$(this).datepicker();});
	$("input[datepicker]").live("focus",function(){$(this).datepicker();});
	
	/* следующий блок добавляет контекстное меню для копирования ссылок
	   и для открытия по клику использует applet
	*/
	$(document).contextMenu( function () { return CMenu_builder(this.event);});
	html = "<applet codetype='application/java' code='zaompp.bazaApplet' archive='/lib/applet/BazaApplet.jar' width=1 height=1 name='bazaapplet'><param name='cmd' value='cmd.exe /c'>Applet for open files and clipboard (if you see it java-plugin not started)</applet>";
	$('body').append(html);
	$("a.filelink").live("click", function(){
		var link = $(this).attr("href");
		if (link.search("xml") != -1 || link.search("xls") != -1 ) {
			var re = new RegExp('/','gi');
			document.bazaapplet.openfile('\"'+link.split(':')[1].replace(re,'\\')+'\"');
			return false;
		}
		return true;
	});
	
});

/**
*
*  URL encode / decode
*  http://www.webtoolkit.info/
*
**/
 
var Url = {
 
	// public method for url encoding
	encode : function (string) {
		return escape(this._utf8_encode(string));
	},
 
	// public method for url decoding
	decode : function (string) {
		return this._utf8_decode(unescape(string));
	},
 
	// private method for UTF-8 encoding
	_utf8_encode : function (string) {
		string = string.replace(/\r\n/g,"\n");
		var utftext = "";
 
		for (var n = 0; n < string.length; n++) {
 
			var c = string.charCodeAt(n);
 
			if (c < 128) {
				utftext += String.fromCharCode(c);
			}
			else if((c > 127) && (c < 2048)) {
				utftext += String.fromCharCode((c >> 6) | 192);
				utftext += String.fromCharCode((c & 63) | 128);
			}
			else {
				utftext += String.fromCharCode((c >> 12) | 224);
				utftext += String.fromCharCode(((c >> 6) & 63) | 128);
				utftext += String.fromCharCode((c & 63) | 128);
			}
 
		}
 
		return utftext;
	},
 
	// private method for UTF-8 decoding
	_utf8_decode : function (utftext) {
		var string = "";
		var i = 0;
		var c = c1 = c2 = 0;
 
		while ( i < utftext.length ) {
 
			c = utftext.charCodeAt(i);
 
			if (c < 128) {
				string += String.fromCharCode(c);
				i++;
			}
			else if((c > 191) && (c < 224)) {
				c2 = utftext.charCodeAt(i+1);
				string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
				i += 2;
			}
			else {
				c2 = utftext.charCodeAt(i+1);
				c3 = utftext.charCodeAt(i+2);
				string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
				i += 3;
			}
 
		}
 
		return string;
	}
 
}