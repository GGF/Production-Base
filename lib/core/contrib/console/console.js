	
	var html = "";
	var cmsConsole_errors = 0;
	
	html += "<div id='cmsConsole' style='display: none'>";
	html += "	<table class='frame fullHeight'>";
	html += "		<tr><td colspan='2'><div id='cmsConsole_main' style='display: none'>";
	
	
	html += "			<table class='frame fullHeight'>";
	html += "				<tr><td colspan='3'>";
	html += "					<table class='cmsTabs frame fullHeight'>";
	html += "						<tr>";
	html += "							<td class='cmsTabs_first' nowrap></td>";
	html += "							<td class='cmsTabs_norm1 cmsTabs_sel1' id='tab_console1' nowrap></td>";
	html += "							<td class='cmsTabs_norm2 cmsTabs_sel2' id='tab_console2' nowrap><a href='javascript:cmsConsole_tab(\"console\");' class='tab' onMouseOver='cmsTab_over(\"_console\")' onMouseOut='cmsTab_out(\"_console\")'>Консоль</a></td>";
	html += "							<td class='cmsTabs_norm3 cmsTabs_sel3' id='tab_console3' nowrap></td>";
	html += "							<td class='cmsTabs_norm1' id='tab_time1' nowrap></td>";
	html += "							<td class='cmsTabs_norm2' id='tab_time2' nowrap><a href='javascript:cmsConsole_tab(\"time\");' class='tab' onMouseOver='cmsTab_over(\"_time\")' onMouseOut='cmsTab_out(\"_time\")'>Время</a></td>";
	html += "							<td class='cmsTabs_norm3' id='tab_time3' nowrap></td>";
	html += "							<td class='cmsTabs_norm1' id='tab_mysql1' nowrap></td>";
	html += "							<td class='cmsTabs_norm2' id='tab_mysql2' nowrap><a href='javascript:cmsConsole_tab(\"mysql\");' class='tab' onMouseOver='cmsTab_over(\"_mysql\")' onMouseOut='cmsTab_out(\"_mysql\")'>MySQL</a></td>";
	html += "							<td class='cmsTabs_norm3' id='tab_mysql3' nowrap></td>";
	html += "							<td class='cmsTabs_last' width='100%' align='right' nowrap><a href='javascript: cmsConsole_main_hide(); void(0);'><img src='/lib/core/contrib/console/ui/close.gif'></a></td>";
	html += "						</tr>";
	html += "					</table>";
	html += "				</td></tr>";
	html += "				<tr>";
	html += "					<td height='100%' class='cmsTabs_content cmsConsole_content'>";
	
	html += "						<div class='cmsConsole_tab' id='cmsConsole_tab_console'></div>";
	html += "						<div class='cmsConsole_tab' id='cmsConsole_tab_time' style='display: none'></div>";
	html += "						<div class='cmsConsole_tab' id='cmsConsole_tab_mysql' style='display: none'></div>";
	
	html += "					</div></td>";
	html += "				</tr>";
	html += "			</table>";
	
	
	html += "		</td></tr>";
	html += "		<tr onclick='cmsConsole_main_toggle()'>";
	html += "			<td class='cmsConsole_text' width='100%'>Отчет Osmio CMS <small>(щелкните для открытия)</small></td>";
	html += "			<td class='cmsConsole_text' id='cmsConsole_errors' nowrap>Нет ошибок</td>";
	html += "		</tr>";
	html += "	</table>";
	html += "</div>";
	
	//$("body").append(html);
	document.write(html);
	
	$(document).bind("keyup", function(e) {
		
		if (e.which == "120") {
			
			cmsConsole_main_show();
			cmsConsole_toggle();
			
		}
		
	});
	
	function cmsConsole_main_toggle() { $("#cmsConsole_main").toggle(1, function(){ cmsConsole_margin(); }); }
	function cmsConsole_main_show()		{ $("#cmsConsole_main").show(1, function(){ cmsConsole_margin(); }); }
	function cmsConsole_main_hide()		{ $("#cmsConsole_main").hide(1, function(){ cmsConsole_margin(); }); }
	
	function cmsConsole_toggle() 			{ $("#cmsConsole").toggle(1, function(){ cmsConsole_margin(); }); }
	function cmsConsole_show()				{ $("#cmsConsole").show(1, function(){ cmsConsole_margin(); }); }
	function cmsConsole_hide()				{ $("#cmsConsole").hide(1, function(){ cmsConsole_margin(); }); }
	
	function cmsConsole_margin() {
		
		var obj = $("#cmsConsole");
		
		if (obj.css("display") == "none") {
			
			$("html").css({paddingBottom: 0});
			
		} else {
			
			$("html").css({paddingBottom: obj.height()});
			
		}
		
	}
	
	function cmsConsole_tab(id) {
		
		$(".cmsTabs td").removeClass("cmsTabs_sel1").removeClass("cmsTabs_sel2").removeClass("cmsTabs_sel3");
		
		$("#tab_" + id + "1").addClass("cmsTabs_sel1");
		$("#tab_" + id + "2").addClass("cmsTabs_sel2");
		$("#tab_" + id + "3").addClass("cmsTabs_sel3");
		
		$(".cmsConsole_tab").hide();
		$("#cmsConsole_tab_" + id).show();
		
	}
	
	
	
	function cmsConsole(text, pane)					{ cmsConsole_out(text, pane, ""); }
	function cmsConsole_error(text, pane)		{ cmsConsole_out(text, pane, "error"); }
	function cmsConsole_warning(text, pane) { cmsConsole_out(text, pane, "warning"); }
	function cmsConsole_notice(text, pane)	{ cmsConsole_out(text, pane, "notice"); }
	function cmsConsole_plain(text, pane)		{ cmsConsole_out(text, pane, "plain"); }
	
	function cmsConsole_out(text, pane, type) {
		
		if (!pane) pane = "console";
		
		if (type == "error") {
			
			cmsConsole_errors++;
			
			cmsConsole_show();
			cmsConsole_tab(pane);
			$("#cmsConsole_errors").html("<span class='error'>Ошибки: " + cmsConsole_errors + "</span>");
			
		}
		
		if (type) type = " cmsConsole_" + type;
		
		$("#cmsConsole_tab_" + pane).append("<div class='cmsConsole_line" + type + "'>" + text + "</div>");
		
	}

	function cmsConsole_clear() {
		$("#cmsConsole_tab_console").html('');
		$("#cmsConsole_tab_time").html('');
		$("#cmsConsole_tab_mysql").html('');
	}