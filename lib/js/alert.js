	
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