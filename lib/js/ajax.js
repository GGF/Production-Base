	
	cmsPreload(["/admin/ui/loading_ajax.gif", "/admin/ui/loading_upload.gif"]);

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
	 *	@param	selector	id	Селектор лоадера
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
