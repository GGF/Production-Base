	
	function cmsPosition(table, id, pos, options) {
		
		var options = options || {};
		var prefix = options.prefix ? options.prefix + "_" : "";
		
		cmsAjax(
			"/admin/includes/ajax_move.php", // backend
			{
				"table":	table,
				"id":			id,
				"pos":		pos
			},
			function(res) { // ONREADYSTATE
				
				var e1 = $("#" + prefix + res.posOld + "_pos").contents();
				var e2 = $("#" + prefix + res.posNew + "_pos").contents();
				
				$("#" + prefix + res.posOld + "_pos").empty().append(e2);
				$("#" + prefix + res.posNew + "_pos").empty().append(e1).addClass("lightGreen");
				
				setTimeout(function() {
					
					$("#" + prefix + res.posNew + "_pos").removeClass("lightGreen");
					
				}, 1000);
				
				if (options.offset) cmsPosition_checkOffset(table, res, options); else if(options.callback) options.callback(res);
				
			}
		);
		
	}
	
	function cmsPosition_checkOffset(table, xres, options) {
		
		
		cmsAjax(
			"/admin/includes/ajax_checkOffset.php", // backend
			{
				"table":	table
			},
			function(res) { // ONREADYSTATE
				
				if (options.callback) options.callback(xres);
				
			}
		);
		
	}
	