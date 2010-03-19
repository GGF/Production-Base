	
	var cmsConsole_errors = 0;
	
	function cmsConsole(text, pane)					{ cmsConsole_out(text, pane, ""); }
	function cmsConsole_error(text, pane)		{ cmsConsole_out(text, pane, "error"); }
	function cmsConsole_warning(text, pane) { cmsConsole_out(text, pane, "warning"); }
	function cmsConsole_notice(text, pane)	{ cmsConsole_out(text, pane, "notice"); }
	
	function cmsConsole_out(text, pane, type) {
		
		if (type == "error") cmsConsole_errors++;
		
		return null;
		
	}
