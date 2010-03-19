	
	// LANGUAGE DEFINITIONS
	
	var cmsForm_noAlert = false;
	var cmsForm_lang = {
		"en" : "Obligatory fields aren't filled...",
		"ru" : "Заполнены не все обязательные поля..."
	}
	
	// FUNCTIONS
	
	var cmsForm_timeout = new Array();
	
	function cmsForm_checkField(id, type, e) {
		
		var n = e.which;
		
		if ((n >= 65 && n <= 90) || (n >= 97 && n <= 122) || (n >= 1040 && n <= 1103)) {
			
			var obj = $("#" + id);
			
			clearTimeout(cmsForm_timeout[id]);
			
			cmsForm_timeout[id] = setTimeout(function() {
				
				var attr = obj.attr("value").toString();
				
				if (type == 'numeric')	obj.attr("value", attr.replace(/[^\d.,]/g, ""));
				if (type == 'login')		obj.attr("value", attr.replace(/[^_\da-zA-Z]/g, ""));
				if (type == 'code')			obj.attr("value", attr.replace(/[^\da-zA-Z]/g, "").toUpperCase());
				if (type == 'phone')		obj.attr("value", attr.replace(/[^-+() \d]/ig, ""));
				if (type == 'mail')			obj.attr("value", attr.replace(/[^-_@.\da-zA-Z]/g, ""));
				if (type == 'url')			obj.attr("value", attr.replace(/[^-\\_.:\/\da-zA-Z]/g, ""));
				
			}, 250);
			
		}
		
	}
	
	var cmsForm_errors = false;
	
	function cmsForm_submit(formID) {
		
		cmsForm_errors = true;
		
		try {
			
			var obligatory = eval("window.obligatory_" + formID);
			
			if (!obligatory) {
				
				return true;
				
			} else {
				
				var errors = 0;
				
				for(var i=0, length = obligatory.length; i<length; i++) {
					
					var id	= obligatory[i];
					var obj	= document.getElementById(id);
					
					if (obj) {
						
						var selectValue = false;
						
						if (obj.options) for (var j = 0; j < obj.options.length; j++) if (obj.options[j].value == obj.value) selectValue = obj.options[j].innerHTML.substr(0, 6);
						
						if (!obj.value || (!obj.checked && obj.type == 'checkbox') || selectValue == '------' || selectValue == 'Выбери' || selectValue == 'Select' || obj.value == 'null') {
							
							errors++;
							
							//var c = getPosition(obj);
							//var w = obj.offsetWidth;
							//var h = obj.offsetHeight;
							
							if (!$("#" + obj.id + "_error").get(0)) {
								
								$(obj).after("<div class='cmsForm_error' id='" + obj.id + "_error'><img src='/images/free.gif'></div>"); // style='position: absolute'
								
							}
							
							$("#" + obj.id + "_error").css({
								//top:		(c.y + h) + "px",
								//left:		(c.x) + "px",
								width:	obj.style.width,
								height:	"1px"
							}).show();
							
							$(obj).addClass("cmsForm_errorField");
							
						} else {
							
							$("#" + obj.id + "_error").hide();
							$(obj).removeClass("cmsForm_errorField");
							
						}
						
					} else {
						
						alert("Form is incomplete - missing obligatory field \"" + id + "\"");
						errors++;
						
					}
					
				}
				
				if (errors == 0) {
					
					cmsForm_errors = false;
					return true;
					
				} else {
					
					if (!cmsForm_noAlert) alert(cmsForm_lang[lang]);
					return false;
					
				}
				
			}
			
		} catch (e) {	alert("Errors in script:\n\n" + e);	}
		
		return false;
		
	}
	
	
	function cmsForm_reloadCaptcha(fid) {
		
		var img = $("#form_" + fid + "_captcha");
		
		cmsAjax(
			'/core/classes/form/ajax_confirm.php', // backend
			{},
			function(res) { // ONREADYSTATE
				
				img.attr("src", "/core/classes/form/ajax_confirm.php?code=" + res + "&width=" + img.width() + "&height=" + img.height()).bind("load", function() {
					
					$("#form_" + fid + "_confirm_code").attr("value", res);
					
				});
				
			}
		);
		
	}