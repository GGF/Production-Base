	
	/*
	if (!console) { // no FireBug console object
		
		function consoleNull() { alert("Вывод Firebug не был отключен"); }
		
		var console = {
			"log": consoleNull,
			"error": consoleNull,
			"info": consoleNull,
			"dir": consoleNull
		};
		
	}
	*/
	
	
	function cmsTitle(name) { document.title = name + " " + document.title; }
	
	function cmsPreload(arr) {
		
		if (!window.cmsPreload) window.cmsPreload = new Array();
		
		for (var i = 0; i < arr.length; i++) {
			
			var l = window.cmsPreload.length;
			
			window.cmsPreload[l] = new Image();
			window.cmsPreload[l].src = arr[i];
			
		}
		
	}
	
	function newWindow(src, w, h) {
		
		if (!w) w = 950;
		if (!h) h = 500;
		
		var x = (screen.width - w) / 2;
		var y = (screen.height - h - 120) / 2;
		
		if (w == 'blank') {
			
			window.open(src , '_blank');
			
		} else { 
			
			if (w == 'top') {
				
				window.open(src , '_top');
				
			} else {
				
				window.open(src , '_blank' , 'menubar=yes, toolbar=no, width=' + w + ', height=' + h + ', resizable=no, location=yes, scrollbars=yes, status=yes, top=' + y + ', left=' + x);
				
			}
			
		}
		
	}
	
	function borderBottomColor(obj) {
		
		$(obj).css("borderBottomColor", $(obj).css("color"));
		
	}
	
	$(document).ready(function() { 
		
		$(".lined tbody tr:odd").addClass("odd");
		$(".lined tbody tr:even").addClass("even");
		
		if (cmsBrowser.firefox) {
			
			$("optgroup").each(function() {
				
				this.label = "\u00A0" + this.label; // add &nbsp;
				
			});
			
		}
		
	});
	
	function cmsVar(obj) {
		
		var text = "";
		
		for (id in obj) {
			
			text += id + ": " + obj[id].toString() + "\n\n";
			
		}
		
		return text;
		
	}
	
	
