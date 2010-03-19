	
	// -- INIT --------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	var cmsCalendar_last = new Object();
	var cmsCalendar_text = new Object();
	var cmsCalendar_time = new Object();

	$(document).ready(function() {
		
		$("body").append("<div id='cmsCalendar'></div>");
		$(document).bind("mouseup", function() { cmsCalendar_hide(); return false; });
		
	});
	
	// -- FUNCTIONS ---------------------------------------------------------------------------------------------------------------------------------------------------//
	
	var cmsDate_space = "      ";
	
	function cmsDate(date, pattern) {
		
		var months = new Array("Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек");
		
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
		
		if (!pattern) {
			
			if (d < 10) d = '0' + d.toString();
			if (m < 10) m = '0' + m.toString();
			
			return d + '.' + (m + 1) + '.' + y;
			
		} else if (pattern=='ru') {
			
			if (d < 10) d = '0' + d.toString();
			
			return d + ' ' + months[m] + ' ' + y;
			
		} else if (pattern=='x-ru') {
			
			return months[m] + ' ' + y;
			
		} else if (pattern=='abs') {
			
			return date.getTime();
			
		}
		
	}
	
	function cmsDate_set(d, m, y) {
		
		var date = new Date();
		date.setFullYear(y, m, d);
		
		if (cmsCalendar_time != false) {
			
			var time = cmsCalendar_time.value.split(cmsDate_space);
			date.setHours(time[0], time[1], time[2], 0);
			
		} else {
			
			date.setHours(12, 0, 0, 0);
			
		}
		
		return date;
		
	}
	
	function cmsCalendar_select(time) {
		
		var date = new Date();
		
		date.setTime(time);
		
		cmsCalendar_last.value = Math.round(parseInt(time) / 1000);
		cmsCalendar_text.value = cmsDate(date, 'ru');
		if (cmsCalendar_time != false) cmsCalendar_time.value = date.getHours() + cmsDate_space + date.getMinutes() + cmsDate_space + date.getSeconds();
		
		cmsCalendar_hide();
		
	}
	
	function cmsCalendar_hide() {
		
		$("#cmsCalendar").hide();
		$("#cmsCalendarTime").hide();
		
	}
	
	function getDayX(obj) {
		
		var day = obj.getDay();
		
		//     Sun Mon Tus Wed Thu Fri Sat
		// US:  0   1   2   3   4   5   6
		//     Пон Вто Сре Чет Пят Суб Вос
		// RU:  1   2   3   4   5   6   0
		// RU:  0   1   2   3   4   5   6
		
		day--;
		
		if (day < 0) day = 6;
		
		return day;
		
	}
	
	function cmsCalendar_show(id, override) {
		
		html = new String();
		
		var xdate = new Array();
		
		if (override) {
			
			var time = override;
			
		} else {
			
			var obj_date = document.getElementById(id);
			var obj_text = document.getElementById(id + "_text");
			var obj_time = ($("#" + id + "_time").length) ? document.getElementById(id + "_time") : false;
			
			var time = obj_date.value * 1000;
			
			var c = $(obj_text).position();
			
			$("#cmsCalendar").insertAfter(obj_text).css({
				left: c.left,
				top: parseInt(c.top) + parseInt($(obj_text).outerHeight())
			});
			
			window.cmsCalendar_last = obj_date;
			window.cmsCalendar_text = obj_text;
			window.cmsCalendar_time = obj_time;
			
		}
		
		$("#cmsCalendar").show();
		
		//////
		
		var date = new Date();
		
		if (time < 1000000) {
			
			time = date.getTime();
			
		}
		
		date.setTime(time);
		
		xdate.d = parseInt(date.getDate());
		xdate.m = parseInt(date.getMonth());
		xdate.y = parseInt(date.getFullYear());
		
		var first = cmsDate_set(1, xdate.m, xdate.y);
		
		var prevMon  = cmsDate(cmsDate_set(1, xdate.m - 1, xdate.y), 'abs');
		var nextMon  = cmsDate(cmsDate_set(1, xdate.m + 1, xdate.y), 'abs');
		var prevYear = cmsDate(cmsDate_set(1, xdate.m, xdate.y - 1), 'abs');
		var nextYear = cmsDate(cmsDate_set(1, xdate.m, xdate.y + 1), 'abs');
		
		html += "<table class='frame nav'><tr>\n";
		html += "	<td><a id='cmsCalendar_prevy' href='javascript: cmsCalendar_show(false, " + prevYear + ")' class='arrow'>&laquo;</a></td>\n";
		html += "	<td><a id='cmsCalendar_prevm' href='javascript: cmsCalendar_show(false, " + prevMon + ")' class='arrow'>&lsaquo;</a></td>\n";
		html += "	<td class='fullWidth'><div class='current'>" + cmsDate(date, 'x-ru') + "</div></td>\n";
		html += "	<td><a id='cmsCalendar_nextm' href='javascript: cmsCalendar_show(false, " + nextMon + ")' class='arrow'>&rsaquo;</a></td>\n";
		html += "	<td><a id='cmsCalendar_nexty' href='javascript: cmsCalendar_show(false, " + nextYear + ")' class='arrow'>&raquo;</a></td>\n";
		html += "</tr></table>\n\n";
		
		html += "<table class='frame'>\n	<tr>\n";
		
		var day = getDayX(first);
		var mon = first.getMonth();
		var year = first.getFullYear();
		
		for(var i=1; i<=day; i++) html += "		<td>&nbsp;</td>\n";
		
		for(var i=1; i<=31; i++) {
			
			first.setDate(i);
			
			if (mon != first.getMonth()) break;
			k = i == xdate.d ? " class='sel'" : "";
			
			html += "		<td><a href='javascript: cmsCalendar_select(\"" + cmsDate(first, 'abs') + "\")'" + k + " title='" + cmsDate(first, 'ru') + "'>" + i + "</a></td>\n";
			
			if (getDayX(first) == 6) html += "	</tr>\n	<tr>\n";
			
		}
		
		html += "	</tr>\n</table>\n";
		
		html += "<div class='today'>Сегодня: <a href='javascript: cmsCalendar_select(\"" + cmsDate(new Date(), 'abs') + "\")'>" + cmsDate(new Date(), 'ru') + "</a></div>";
		
		$("#cmsCalendar").html(html);
		
		$("#cmsCalendar").bind("mouseup", function () { return false; });
		//$("#cmsCalendar_prevy").bind("click", function () { cmsCalendar_show(false, prevYear);	return false; });
		//$("#cmsCalendar_prevm").bind("click", function () { cmsCalendar_show(false, prevMon);		return false; });
		//$("#cmsCalendar_nextm").bind("click", function () { cmsCalendar_show(false, nextMon);		return false; });
		//$("#cmsCalendar_nexty").bind("click", function () { cmsCalendar_show(false, nextYear);	return false; });
		
		var h = $("#cmsCalendar").height();
		var c = $(obj_text).offset();
		if (c.top + h + 1 + 20 > $(document).height()) {
			
			$("#cmsCalendar").css("top", c.top - h - 7);
			
		}
		
	}
	
	var cmsCalendar_timeTimeout;
	
	function cmsCalendar_setTime(id, type, to) {
		
		cmsCalendar_setTimeClear();
		
		var obj = $("#" + id);
		
		var date = new Date();
		
		date.setTime(parseInt(obj.val()) * 1000);
		
		var h = date.getHours();
		var m = date.getMinutes();
		var s = date.getSeconds();

		if (type == "h") h += to;
		if (type == "m") m += to;
		if (type == "s") s += to;
		
		date.setHours(h, m, s, 0);
		
		var h = date.getHours().toString();
		var m = date.getMinutes().toString();
		var s = date.getSeconds().toString();
		
		if (h.length == 1) h = "0" + h;
		if (m.length == 1) m = "0" + m;
		if (s.length == 1) s = "0" + s;
		
		obj.val(Math.round(date.getTime() / 1000));
		$("#" + id + "_time").val(h + cmsDate_space + m + cmsDate_space + s);
		$("#" + id + "_text").val(cmsDate(date, 'ru'));
		
		cmsCalendar_timeTimeout = setTimeout("cmsCalendar_setTime('" + id + "', '" + type + "', " + to + ")", 200);
		
	}
	
	function cmsCalendar_setTimeClear() {
		
		clearTimeout(cmsCalendar_timeTimeout);
		
	}
	
	//-----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
