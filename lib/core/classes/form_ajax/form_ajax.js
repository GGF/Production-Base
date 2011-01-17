	
	var cmsForm_ajax = {
		
		clicked: "",
		fields: {},
		callbacks: {
			send:		{},	// ��������������� ����� ���������
			before:	{},	// ����� �������� ����� ����� ������ ������
			after:	{},	// ����� ������� html, htmlReplace � �������
			finish:	{}	// ����� �����, �� ����� ����������
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
					
					//alert(id + ", ������ " + $("#" + id).width());
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
		 *	������������ � ������� ����� �������
		 *	@param	string		formName		�������� �����
		 *	@param	function	func				������� ��� ������
		 *	@param	string		type				��� �������� � normal|replace
		 *	@return	void
		 */
		callback: function(formName, func, type) {
			
			type = type || "after";
			
			if (!this.callbacks[type][formName]) this.callbacks[type][formName] = new Array();
			
			this.callbacks[type][formName].push(func);
			
		},
		
		// ------------------------------------------------------------------------------------------------------------------------------------------------------------- //
		
		/**
		 *	���������� ������������ ID ����
		 *	@param	string		name				�������� ����
		 *	@param	string		formID			ID �����
		 *	@return	string
		 */
		getID: function(name, formID) {
			
			return formID + "_" + name.replace(/\|/ig, "__");
			
		},
		
		// ------------------------------------------------------------------------------------------------------------------------------------------------------------- //
		
		/**
		 *	������� ������ ����� ����
		 *	@param	string	id		ID ����
		 *	@param	string	html	HTML ����� ������
		 *	@param	string	text	����� ������ ��� title
		 *	@return	void
		 */
		errorHTML: function(id, html, text) {
			
			var obj = $("#" + id); // src
			
			if (obj.attr("rel")) { // ������ �������� ����� �������������� ��������
				
				cmsConsole_warning("��������������� ������ �� REL � ������� ID:" + id + " �� ID:" + obj.attr("rel"));
				obj = $("#" + obj.attr("rel"));
				
			}
			
			if (obj.get(0)) {
				
				if (!$("#" + id + "_errorText").get(0))	obj.after("<span class='cmsForm_errorText' id='" + id + "_errorText'></span>");
				if (!$("#" + id + "_error").get(0))			obj.after("<span class='cmsForm_error'     id='" + id + "_error'><img src='/images/free.gif'></span>");
				
				$("#" + id + "_error").css({display: "block"}); //, position: "absolute"
				
				if (html) { // ��� span
					
					$("#" + id + "_errorText").css({display: "block"}).append(html);
					
				}
				
				if (text) { // ��� title
					
					var title = obj.attr("title");
					title = (title) ? title + ", " + text : text;
					
					obj.attr("title", title);
					
				}
				
				this.updater.add(id, obj.attr("id"));
				obj.addClass("cmsForm_errorField");
				
			} else cmsConsole_error("���������� ����� ������ � ID �" + id + "� ��� ������ ������.\n����� ������: �" + html + "�");
			
		},
		
		/**
		 *	�������� ������ ����� ����
		 *	@param	string	id		ID ����
		 *	@return	void
		 */
		errorHide: function(id) { //, src
			
			var obj = $("#" + id); // src
			
			if (obj.attr("rel") != "") { // ������ �������� ����� �������������� ��������
				
				obj = $("#" + obj.attr("rel"));
				
			}
			
			this.updater.remove(id, obj.attr("id"));
			
			$("#" + id + "_error").hide();
			$(obj).removeClass("cmsForm_errorField");
			
			$("#" + id + "_errorText").hide().html(""); // ��� span
			$(obj).attr("title", ""); // src ��� title
			
		},
		
		// ------------------------------------------------------------------------------------------------------------------------------------------------------------- //
		
		/**
		 *	�������� ������ ����� � ���������� �� �� ������, ����� ������������ ���������
		 *	@param	object		formObj			������ �����
		 *	@return	bool
		 */
		submit: function(formObj) {
			
			var formID = formObj.id;
			var formName = formObj.name;
			var send = {};
			var form = $("#" + formID);
			var self = this; // anti-jQuery
			
			//alert(JSON.stringify(self.fields[formName])); return false;
			
			// ������ ����� � ������������ ������� ��� ��������
			var sendTmp = $(formObj).serializeArray();
			for (i in sendTmp) {
				
				var tmp = sendTmp[i];
				
				send[tmp.name.substr(formName.length + 1, tmp.name.length - formName.length - 2)] = tmp.value;
				
			}
			
			delete sendTmp;
			delete tmp;
			
			send[self.clicked] = 1;
			self.clicked = "";
			
			// ����� ��������� callback'�� ����� �������� ����������
			if (self.callbacks.send[formName]) for (var i = 0; i < self.callbacks.send[formName].length; i++) {
				
				send = self.callbacks.send[formName][i](send, formID);
				if (send == null || send == false) return false; // ���������� �������� �������� ���� ������ break
				
			}
			
			//if (send == null || send == false) return false; // ���������� ��������
			
			// ��������� ������ ������������� rel ������ ���, ������� ����� �����������, ����� ����� � ����� ��� �������
			$("#" + formID).find("input[type=submit], input[type=image], input[type=reset], input[type=button]").data("disabled", true).attr("rel", "disabled").attr("disabled", true).addClass("cmsForm_disabled");
			
			var sendJSON = new Array();
			sendJSON[formName] = send;
			
			//alert(JSON.stringify(sendJSON)); return false; // debug sending array
			
			// AJAX ������
			cmsAjax(
				form.attr("action"), // backend
				sendJSON,
				function(res) {
					
					delete sendJSON;
					
					// ��� �����-�� ���� �������� �� res.criticalError, ������� ��������� �� �����, �� ����� ������ ���� � ������� ���, �����. ������ ����� DEPRECATED
					
					$("#" + formID + "_html").hide();
					
					// ��������� ���� �������
					if (res.fieldsDeleted) for (var i = 0; i < res.fieldsDeleted.length; i++) delete self.fields[formName][res.fieldsDeleted[i]];
					
					for (var name in self.fields[formName]) {
						
						// ���������� ������
						self.errorHide(self.getID(self.fields[formName][name].name, formID)); //, src
						
					}
					
					// ����� ��������� callback'�� ����� �������� ����������
					if (self.callbacks.before[formName]) for (var i = 0; i < self.callbacks.before[formName].length; i++) {
						
						res = self.callbacks.before[formName][i](res, formID);
						if (res == null || res == false) break;
						
					}
					
					// �������� ��-���������
					// ���� callback'� �� �������� res	
					if (res != null) {
						
						if (res.errors && res.errors.length > 0) {
							
							//alert(JSON.stringify(res.errors));
							
							for (var i = 0; i < res.errors.length; i++) {
								
								if (res.errors[i].type != "critical") {
								
									if (self.fields[formName][res.errors[i].name]) {
										
										self.errorHTML(res.errors[i].id, res.errors[i].html, res.errors[i].text);
										
									} else cmsConsole_error("��� ������ ������ �� ������� ���� �" + res.errors[i].id + "�");
									
								} else {
									
									// ����� ����� ������, ������� ��������� ��������
									cmsConsole_error("����������� ������ ����� �" + formName + "�: " + res.errors[i].text);
									
								}
								
							}
							
						} else cmsConsole_notice("�������� ������ ����� �" + formName + "� �������. ������ �� ����������.");
						
						// ������������� ��������� ������� �������������
						self.updater.resize();
						
						
						// ����� ��������� callback'�� ����� �������� ��������� � ���������� ����, ������ ����� ��� ������ ������� � �������� � html, � �� ������ � redirect
						if (self.callbacks.after[formName]) for (var i = 0; i < self.callbacks.after[formName].length; i++) {
							
							res = self.callbacks.after[formName][i](res, formID);
							if (res == null || res == false) break;
							
						}
						
						
						// �������� html � ������������ �������
						if (res.html) $("#" + formID + "_html").show().html(res.html);
						
						// �������� ��� ����� �����, ���� ��� ��
						if (res.htmlReplace) form.replaceWith(res.htmlReplace);
						
						// �������
						if (res.alert) alert(res.alert);
						
												
						// ����� ��������� callback'�� ����� ������ �����
						if (self.callbacks.finish[formName]) for (var i = 0; i < self.callbacks.finish[formName].length; i++) {
							
							res = self.callbacks.finish[formName][i](res, formID);
							if (res == null || res == false) break;
							
						}
						
						
						// ��������
						if (res.redirect) window.location.assign(res.redirect);
						
						// data
						if (res.data) eval(res.data);//alert('Data:'+res.data);
						
					}
					
					// ������������ ������, ��������������� ����� ���������
					if (!res.redirect) $("#" + formID).find("input[rel=disabled]").data("disabled", false).attr("disabled", false).attr("rel", "").removeClass("cmsForm_disabled");
					
				}
			);
			
			// ����� �� ������ ���� ����������, ��� ������� ����������������
			return false;
			
		},
		
		// ------------------------------------------------------------------------------------------------------------------------------------------------------------- //
		
		/**
		 *	������������� CAPTCHA, ��������� TODO � ����� ���� ���� ��� formID, �� ��-���� ����� � ������ ������ ���� ���� �� ��������
		 *	@param	string		formName		�������� �����
		 *	@return	void
		 */
		reloadCaptcha: function(formName, formID) {
			
			$("#" + this.getID("confirm", formID)).attr("value", "");
			
			var img = $("#" + this.getID("captcha", formID));
			img.attr("src", "/lib/core/classes/form_ajax/ajax_confirm.php?formName=" + formName + "&width=" + img.width() + "&height=" + img.height() + "&rnd=" + Math.random());
			
		},
		
		/**
		 *	������� CAPTCHA �� ������ ��������
		 *	@param	object	e			Event object
		 *	@param	object	obj		������ ������ ��� �����
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
		 * ���������� �� ������� � jquery.iframe-post-form
		 * ��������� ����� submit
		 */
		beforeSend: function(formObj) {
			var formID = formObj.id;
			var formName = formObj.name;
			var send = {};
			var form = $("#" + formID);
			var self = this; // anti-jQuery
			//alert(JSON.stringify(self.fields[formName])); return false;
			
			// ������ ����� � ������������ ������� ��� ��������
			var sendTmp = $(formObj).serializeArray();
			for (i in sendTmp) {
				
				var tmp = sendTmp[i];
				
				send[tmp.name.substr(formName.length + 1, tmp.name.length - formName.length - 2)] = tmp.value;
				
			}
			
			delete sendTmp;
			delete tmp;
			
			send[self.clicked] = 1;
			self.clicked = "";
			
			// ����� ��������� callback'�� ����� �������� ����������
			if (self.callbacks.send[formName]) for (var i = 0; i < self.callbacks.send[formName].length; i++) {
				
				send = self.callbacks.send[formName][i](send, formID);
				if (send == null || send == false) return false; // ���������� �������� �������� ���� ������ break
				
			}
			
			//if (send == null || send == false) return false; // ���������� ��������
			
			// ��������� ������ ������������� rel ������ ���, ������� ����� �����������, ����� ����� � ����� ��� �������
			$("#" + formID).find("input[type=submit], input[type=image], input[type=reset], input[type=button]").data("disabled", true).attr("rel", "disabled").attr("disabled", true).addClass("cmsForm_disabled");
			
			var sendJSON = new Array();
			sendJSON[formName] = send;
			
			//alert(JSON.stringify(sendJSON)); return false; // debug sending array

		},
		
		/*
		 * ���������� ����� jquery.iframe-post-form
		 * ��������� ����� submit
		 */
		afterSend: function(formObj,res){
			
			var formID = formObj[0].id;
			var formName = formObj[0].name;
			var form = $("#" + formID);
			var self = this; // anti-jQuery

			//alert(res);
			res = JSON.parse(res).js;
			//alert(formID);
			
			// ��� �����-�� ���� �������� �� res.criticalError, ������� ��������� �� �����, �� ����� ������ ���� � ������� ���, �����. ������ ����� DEPRECATED
			
			$("#" + formID + "_html").hide();
			
			// ��������� ���� �������
			if (res.fieldsDeleted) for (var i = 0; i < res.fieldsDeleted.length; i++) delete self.fields[formName][res.fieldsDeleted[i]];
			
			for (var name in self.fields[formName]) {
				
				// ���������� ������
				self.errorHide(self.getID(self.fields[formName][name].name, formID)); //, src
				
			}
			
			// ����� ��������� callback'�� ����� �������� ����������
			if (self.callbacks.before[formName]) for (var i = 0; i < self.callbacks.before[formName].length; i++) {
				
				res = self.callbacks.before[formName][i](res, formID);
				if (res == null || res == false) break;
				
			}
			
			// �������� ��-���������
			// ���� callback'� �� �������� res	
			if (res != null) {
				
				if (res.errors && res.errors.length > 0) {
					
					//alert(JSON.stringify(res.errors));
					
					for (var i = 0; i < res.errors.length; i++) {
						
						if (res.errors[i].type != "critical") {
						
							if (self.fields[formName][res.errors[i].name]) {
								
								self.errorHTML(res.errors[i].id, res.errors[i].html, res.errors[i].text);
								
							} else cmsConsole_error("��� ������ ������ �� ������� ���� �" + res.errors[i].id + "�");
							
						} else {
							
							// ����� ����� ������, ������� ��������� ��������
							cmsConsole_error("����������� ������ ����� �" + formName + "�: " + res.errors[i].text);
							
						}
						
					}
					
				} else cmsConsole_notice("�������� ������ ����� �" + formName + "� �������. ������ �� ����������.");
				
				// ������������� ��������� ������� �������������
				self.updater.resize();
				
				
				// ����� ��������� callback'�� ����� �������� ��������� � ���������� ����, ������ ����� ��� ������ ������� � �������� � html, � �� ������ � redirect
				if (self.callbacks.after[formName]) for (var i = 0; i < self.callbacks.after[formName].length; i++) {
					
					res = self.callbacks.after[formName][i](res, formID);
					if (res == null || res == false) break;
					
				}
				
				
				// �������� html � ������������ �������
				if (res.html) $("#" + formID + "_html").show().html(res.html);
				
				// �������� ��� ����� �����, ���� ��� ��
				if (res.htmlReplace) form.replaceWith(res.htmlReplace);
				
				// �������
				if (res.alert) alert(res.alert);
				
										
				// ����� ��������� callback'�� ����� ������ �����
				if (self.callbacks.finish[formName]) for (var i = 0; i < self.callbacks.finish[formName].length; i++) {
					
					res = self.callbacks.finish[formName][i](res, formID);
					if (res == null || res == false) break;
					
				}
				
				
				// ��������
				if (res.redirect) window.location.assign(res.redirect);
				
				// data
				if (res.data) eval(res.data);//alert('Data:'+res.data);
				
			}
			
			// ������������ ������, ��������������� ����� ���������
			if (!res.redirect) $("#" + formID).find("input[rel=disabled]").data("disabled", false).attr("disabled", false).attr("rel", "").removeClass("cmsForm_disabled");
			
		} 
		// ------------------------------------------------------------------------------------------------------------------------------------------------------------- //
		
	};
	
	$(function(){
		
		// ������������ ������
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
			html += "			<td class='cmsAlert_title' nowrap>��������� �����</td>";
			html += "			<td class='cmsAlert_titleRight' nowrap></td>";
			html += "		</tr>";
			html += "		<tr>";
			html += "			<td class='cmsAlert_content'><div>";
			
			html += "				<p id='cmsAlert_" + id + "_text'>" + text + "</p>";
			html += "				<center><input type='submit' class='submit' value='��' onclick='$(\"#cmsAlert_" + id + "\").hide(); cmsAlert_array[\"" + id + "\"] = false; return false'></center>";
			
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