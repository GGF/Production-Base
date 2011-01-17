	
	var cmsForm_ajax = {
		
		clicked: "",
		fields: {},
		callbacks: {
			send:		{},	// непосредственно перед отправкой
			before:	{},	// после отправки сразу после сброса ошибок
			after:	{},	// перед выводом html, htmlReplace и алертов
			finish:	{}	// после всего, но перед редиректом
		},
		
		// ------------------------------------------------------------------------------------------------------------------------------------------------------------- //
		
		updater: {
			elements: {},
			add: function(id, src) {
				
				src = src || id;
				
				this.elements[id] = src;
				this.resize(); 
				
			},
			remove: function(id) {
				
				delete this.elements[id];
				
			},
			resize: function(){
				
				for (id in this.elements) {
					
					//alert(id + ", Ширина " + $("#" + id).width());
					var w = $("#" + this.elements[id]).outerWidth(true);
					var h = $("#" + this.elements[id]).outerHeight(true);
					var c = $("#" + this.elements[id]).position();
					
					$("#" + id + "_error").css({
						width:	w,
						top:		c.top + h,
						left:		c.left
					});
					
				}
				
			}
		},
		
		// ------------------------------------------------------------------------------------------------------------------------------------------------------------- //
		
		/**
		 *	Регистрирует в объекте новый коллбэк
		 *	@param	string		formName		Название формы
		 *	@param	function	func				Функция для вызова
		 *	@param	string		type				Тип коллбэка — normal|replace
		 *	@return	void
		 */
		callback: function(formName, func, type) {
			
			type = type || "after";
			
			if (!this.callbacks[type][formName]) this.callbacks[type][formName] = new Array();
			
			this.callbacks[type][formName].push(func);
			
		},
		
		// ------------------------------------------------------------------------------------------------------------------------------------------------------------- //
		
		/**
		 *	Возвращает просчитанный ID поля
		 *	@param	string		name				Название поля
		 *	@param	string		formID			ID формы
		 *	@return	string
		 */
		getID: function(name, formID) {
			
			return formID + "_" + name.replace(/\|/ig, "__");
			
		},
		
		// ------------------------------------------------------------------------------------------------------------------------------------------------------------- //
		
		/**
		 *	Выводит ошибку около поля
		 *	@param	string	id		ID поля
		 *	@param	string	html	HTML текст ошибки
		 *	@param	string	text	Текст ошибки для title
		 *	@return	void
		 */
		errorHTML: function(id, html, text) {
			
			var obj = $("#" + id); // src
			
			if (obj.attr("rel")) { // объект привязки может перенаправлять действия
				
				cmsConsole_warning("Перенаправление ошибки по REL с объекта ID:" + id + " на ID:" + obj.attr("rel"));
				obj = $("#" + obj.attr("rel"));
				
			}
			
			if (obj.get(0)) {
				
				if (!$("#" + id + "_errorText").get(0))	obj.after("<span class='cmsForm_errorText' id='" + id + "_errorText'></span>");
				if (!$("#" + id + "_error").get(0))			obj.after("<span class='cmsForm_error'     id='" + id + "_error'><img src='/images/free.gif'></span>");
				
				$("#" + id + "_error").css({display: "block"}); //, position: "absolute"
				
				if (html) { // для span
					
					$("#" + id + "_errorText").css({display: "block"}).append(html);
					
				}
				
				if (text) { // для title
					
					var title = obj.attr("title");
					title = (title) ? title + ", " + text : text;
					
					obj.attr("title", title);
					
				}
				
				this.updater.add(id, obj.attr("id"));
				obj.addClass("cmsForm_errorField");
				
			} else cmsConsole_error("Невозможно найти объект с ID «" + id + "» для вывода ошибки.\nТекст ошибки: «" + html + "»");
			
		},
		
		/**
		 *	Скрывает ошибку около поля
		 *	@param	string	id		ID поля
		 *	@return	void
		 */
		errorHide: function(id) { //, src
			
			var obj = $("#" + id); // src
			
			if (obj.attr("rel") != "") { // объект привязки может перенаправлять действия
				
				obj = $("#" + obj.attr("rel"));
				
			}
			
			this.updater.remove(id, obj.attr("id"));
			
			$("#" + id + "_error").hide();
			$(obj).removeClass("cmsForm_errorField");
			
			$("#" + id + "_errorText").hide().html(""); // для span
			$(obj).attr("title", ""); // src для title
			
		},
		
		// ------------------------------------------------------------------------------------------------------------------------------------------------------------- //
		
		/**
		 *	Собирает данные формы и отправляет их на сервер, затем обрабатывает результат
		 *	@param	object		formObj			Объект формы
		 *	@return	bool
		 */
		submit: function(formObj) {
			
			var formID = formObj.id;
			var formName = formObj.name;
			var send = {};
			var form = $("#" + formID);
			var self = this; // anti-jQuery
			
			//alert(JSON.stringify(self.fields[formName])); return false;
			
			// Разбор формы и формирование массива для отправки
			var sendTmp = $(formObj).serializeArray();
			for (i in sendTmp) {
				
				var tmp = sendTmp[i];
				
				send[tmp.name.substr(formName.length + 1, tmp.name.length - formName.length - 2)] = tmp.value;
				
			}
			
			delete sendTmp;
			delete tmp;
			
			send[self.clicked] = 1;
			self.clicked = "";
			
			// Вызов кастомных callback'ов перед основной обработкой
			if (self.callbacks.send[formName]) for (var i = 0; i < self.callbacks.send[formName].length; i++) {
				
				send = self.callbacks.send[formName][i](send, formID);
				if (send == null || send == false) return false; // Заваливаем отправку — раньше было просто break
				
			}
			
			//if (send == null || send == false) return false; // заваливаем отправку
			
			// блокируем кнопки — проставляем rel только тем, которые здесь блокируются, чтобы потом с таких его снимать
			$("#" + formID).find("input[type=submit], input[type=image], input[type=reset], input[type=button]").data("disabled", true).attr("rel", "disabled").attr("disabled", true).addClass("cmsForm_disabled");
			
			var sendJSON = new Array();
			sendJSON[formName] = send;
			
			//alert(JSON.stringify(sendJSON)); return false; // debug sending array
			
			// AJAX запрос
			cmsAjax(
				form.attr("action"), // backend
				sendJSON,
				function(res) {
					
					delete sendJSON;
					
					// Тут когда-то была проверка на res.criticalError, который вываливал бы алерт, но этого текста даже в бакенде нет, соотв. скорее всего DEPRECATED
					
					$("#" + formID + "_html").hide();
					
					// Удаленные поля грохаем
					if (res.fieldsDeleted) for (var i = 0; i < res.fieldsDeleted.length; i++) delete self.fields[formName][res.fieldsDeleted[i]];
					
					for (var name in self.fields[formName]) {
						
						// Сбрасываем ошибки
						self.errorHide(self.getID(self.fields[formName][name].name, formID)); //, src
						
					}
					
					// Вызов кастомных callback'ов перед основной обработкой
					if (self.callbacks.before[formName]) for (var i = 0; i < self.callbacks.before[formName].length; i++) {
						
						res = self.callbacks.before[formName][i](res, formID);
						if (res == null || res == false) break;
						
					}
					
					// Действия по-умолчанию
					// Если callback'и не зарубили res	
					if (res != null) {
						
						if (res.errors && res.errors.length > 0) {
							
							//alert(JSON.stringify(res.errors));
							
							for (var i = 0; i < res.errors.length; i++) {
								
								if (res.errors[i].type != "critical") {
								
									if (self.fields[formName][res.errors[i].name]) {
										
										self.errorHTML(res.errors[i].id, res.errors[i].html, res.errors[i].text);
										
									} else cmsConsole_error("Для вывода ошибки не найдено поле «" + res.errors[i].id + "»");
									
								} else {
									
									// Также будут алерты, которые генерятся бакендом
									cmsConsole_error("Критическая ошибка формы «" + formName + "»: " + res.errors[i].text);
									
								}
								
							}
							
						} else cmsConsole_notice("Отправка данных формы «" + formName + "» успешна. Ошибок не обнаружено.");
						
						// Принудительно обновляем размеры подчеркиваний
						self.updater.resize();
						
						
						// Вызов кастомных callback'ов после основной обработки — перемещено сюда, теперь можно что угодно творить с алертами и html, а не только с redirect
						if (self.callbacks.after[formName]) for (var i = 0; i < self.callbacks.after[formName].length; i++) {
							
							res = self.callbacks.after[formName][i](res, formID);
							if (res == null || res == false) break;
							
						}
						
						
						// Заменяем html в определенной области
						if (res.html) $("#" + formID + "_html").show().html(res.html);
						
						// Заменяем всю форму нафиг, если все ок
						if (res.htmlReplace) form.replaceWith(res.htmlReplace);
						
						// Алертим
						if (res.alert) alert(res.alert);
						
												
						// Вызов кастомных callback'ов после вообще всего
						if (self.callbacks.finish[formName]) for (var i = 0; i < self.callbacks.finish[formName].length; i++) {
							
							res = self.callbacks.finish[formName][i](res, formID);
							if (res == null || res == false) break;
							
						}
						
						
						// редирект
						if (res.redirect) window.location.assign(res.redirect);
						
						// data
						if (res.data) eval(res.data);//alert('Data:'+res.data);
						
					}
					
					// Разблокируем кнопки, заблокированные перед отправкой
					if (!res.redirect) $("#" + formID).find("input[rel=disabled]").data("disabled", false).attr("disabled", false).attr("rel", "").removeClass("cmsForm_disabled");
					
				}
			);
			
			// Форма не должна быть отправлена, это лишнаяя предосторожность
			return false;
			
		},
		
		// ------------------------------------------------------------------------------------------------------------------------------------------------------------- //
		
		/**
		 *	Перезагружает CAPTCHA, возможный TODO — отлов всех капч для formID, но по-идее форма с капчей должна быть одна на страницу
		 *	@param	string		formName		Название формы
		 *	@return	void
		 */
		reloadCaptcha: function(formName, formID) {
			
			$("#" + this.getID("confirm", formID)).attr("value", "");
			
			var img = $("#" + this.getID("captcha", formID));
			img.attr("src", "/lib/core/classes/form_ajax/ajax_confirm.php?formName=" + formName + "&width=" + img.width() + "&height=" + img.height() + "&rnd=" + Math.random());
			
		},
		
		/**
		 *	Очищает CAPTCHA от лишних символов
		 *	@param	object	e			Event object
		 *	@param	object	obj		Объект — поле для ввода
		 *	@return	void
		 */
		cleanCaptcha: function(e, obj) {
			
			if (
				e.which != 8 &&											// backspace
				e.which != 37 &&	e.which != 39 &&	// arrows
				e.which != 46 &&										// delete
				e.which != 35 &&										// end
				e.which != 36 &&										// home
				(e.which < 48 || e.which > 57)			// digits
			) {
				
				//alert(e.which);
				$(obj).val($(obj).val().replace(/[^\d]/ig, "").substr(0, 6));
				
			};
			
		},
		
		/*
		 * Вызывается до субмита в jquery.iframe-post-form
		 * дублирует часть submit
		 */
		beforeSend: function(formObj) {
			var formID = formObj.id;
			var formName = formObj.name;
			var send = {};
			var form = $("#" + formID);
			var self = this; // anti-jQuery
			//alert(JSON.stringify(self.fields[formName])); return false;
			
			// Разбор формы и формирование массива для отправки
			var sendTmp = $(formObj).serializeArray();
			for (i in sendTmp) {
				
				var tmp = sendTmp[i];
				
				send[tmp.name.substr(formName.length + 1, tmp.name.length - formName.length - 2)] = tmp.value;
				
			}
			
			delete sendTmp;
			delete tmp;
			
			send[self.clicked] = 1;
			self.clicked = "";
			
			// Вызов кастомных callback'ов перед основной обработкой
			if (self.callbacks.send[formName]) for (var i = 0; i < self.callbacks.send[formName].length; i++) {
				
				send = self.callbacks.send[formName][i](send, formID);
				if (send == null || send == false) return false; // Заваливаем отправку — раньше было просто break
				
			}
			
			//if (send == null || send == false) return false; // заваливаем отправку
			
			// блокируем кнопки — проставляем rel только тем, которые здесь блокируются, чтобы потом с таких его снимать
			$("#" + formID).find("input[type=submit], input[type=image], input[type=reset], input[type=button]").data("disabled", true).attr("rel", "disabled").attr("disabled", true).addClass("cmsForm_disabled");
			
			var sendJSON = new Array();
			sendJSON[formName] = send;
			
			//alert(JSON.stringify(sendJSON)); return false; // debug sending array

		},
		
		/*
		 * Вызывается после jquery.iframe-post-form
		 * дублирует часть submit
		 */
		afterSend: function(formObj,res){
			
			var formID = formObj[0].id;
			var formName = formObj[0].name;
			var form = $("#" + formID);
			var self = this; // anti-jQuery

			//alert(res);
			res = JSON.parse(res).js;
			//alert(formID);
			
			// Тут когда-то была проверка на res.criticalError, который вываливал бы алерт, но этого текста даже в бакенде нет, соотв. скорее всего DEPRECATED
			
			$("#" + formID + "_html").hide();
			
			// Удаленные поля грохаем
			if (res.fieldsDeleted) for (var i = 0; i < res.fieldsDeleted.length; i++) delete self.fields[formName][res.fieldsDeleted[i]];
			
			for (var name in self.fields[formName]) {
				
				// Сбрасываем ошибки
				self.errorHide(self.getID(self.fields[formName][name].name, formID)); //, src
				
			}
			
			// Вызов кастомных callback'ов перед основной обработкой
			if (self.callbacks.before[formName]) for (var i = 0; i < self.callbacks.before[formName].length; i++) {
				
				res = self.callbacks.before[formName][i](res, formID);
				if (res == null || res == false) break;
				
			}
			
			// Действия по-умолчанию
			// Если callback'и не зарубили res	
			if (res != null) {
				
				if (res.errors && res.errors.length > 0) {
					
					//alert(JSON.stringify(res.errors));
					
					for (var i = 0; i < res.errors.length; i++) {
						
						if (res.errors[i].type != "critical") {
						
							if (self.fields[formName][res.errors[i].name]) {
								
								self.errorHTML(res.errors[i].id, res.errors[i].html, res.errors[i].text);
								
							} else cmsConsole_error("Для вывода ошибки не найдено поле «" + res.errors[i].id + "»");
							
						} else {
							
							// Также будут алерты, которые генерятся бакендом
							cmsConsole_error("Критическая ошибка формы «" + formName + "»: " + res.errors[i].text);
							
						}
						
					}
					
				} else cmsConsole_notice("Отправка данных формы «" + formName + "» успешна. Ошибок не обнаружено.");
				
				// Принудительно обновляем размеры подчеркиваний
				self.updater.resize();
				
				
				// Вызов кастомных callback'ов после основной обработки — перемещено сюда, теперь можно что угодно творить с алертами и html, а не только с redirect
				if (self.callbacks.after[formName]) for (var i = 0; i < self.callbacks.after[formName].length; i++) {
					
					res = self.callbacks.after[formName][i](res, formID);
					if (res == null || res == false) break;
					
				}
				
				
				// Заменяем html в определенной области
				if (res.html) $("#" + formID + "_html").show().html(res.html);
				
				// Заменяем всю форму нафиг, если все ок
				if (res.htmlReplace) form.replaceWith(res.htmlReplace);
				
				// Алертим
				if (res.alert) alert(res.alert);
				
										
				// Вызов кастомных callback'ов после вообще всего
				if (self.callbacks.finish[formName]) for (var i = 0; i < self.callbacks.finish[formName].length; i++) {
					
					res = self.callbacks.finish[formName][i](res, formID);
					if (res == null || res == false) break;
					
				}
				
				
				// редирект
				if (res.redirect) window.location.assign(res.redirect);
				
				// data
				if (res.data) eval(res.data);//alert('Data:'+res.data);
				
			}
			
			// Разблокируем кнопки, заблокированные перед отправкой
			if (!res.redirect) $("#" + formID).find("input[rel=disabled]").data("disabled", false).attr("disabled", false).attr("rel", "").removeClass("cmsForm_disabled");
			
		} 
		// ------------------------------------------------------------------------------------------------------------------------------------------------------------- //
		
	};
	
	$(function(){
		
		// разблокируем кнопки
		$("input[rel=disabled]").data("disabled", false).attr("rel", "");
		
		setInterval(function(){				cmsForm_ajax.updater.resize(); }, 500);
		$(window).resize(function(){	cmsForm_ajax.updater.resize(); });
		
	});

	var cmsAlert_array = new Array();
	
	function cmsAlert(text) {
		
		if ($(".cmsAlert").get(0)) {
			
			var id = $(".cmsAlert").eq(0).attr("id").substr(9); // length of "cmsAlert_"
			
			$("#cmsAlert_" + id + "_text").text(text);
			$("#cmsAlert_" + id).show();
			
		} else {
			
			var id = md5(Math.random().toString());
			var html = "";
			
			html += "<div class='cmsAlert' id='cmsAlert_" + id + "'><table class='frame fullHeight'><tr><td align='center' class='middle'>";
			html += "	<table class='frame cmsAlert_table'>";
			html += "		<tr>";
			html += "			<td class='cmsAlert_title' nowrap>Сообщение сайта</td>";
			html += "			<td class='cmsAlert_titleRight' nowrap></td>";
			html += "		</tr>";
			html += "		<tr>";
			html += "			<td class='cmsAlert_content'><div>";
			
			html += "				<p id='cmsAlert_" + id + "_text'>" + text + "</p>";
			html += "				<center><input type='submit' class='submit' value='ОК' onclick='$(\"#cmsAlert_" + id + "\").hide(); cmsAlert_array[\"" + id + "\"] = false; return false'></center>";
			
			html += "			</div></td>";
			html += "			<td class='cmsAlert_contentRight'  nowrap></td>";
			html += "		</tr>";
			html += "		<tr>";
			html += "			<td class='cmsAlert_status' nowrap></td>";
			html += "			<td class='cmsAlert_statusRight' nowrap></td>";
			html += "		</tr>";
			html += "	</table>";
			html += "</td></tr></table></div>";
			
			$("body").append(html);
			
		}
		
		cmsAlert_array[id] = true;
		
	};