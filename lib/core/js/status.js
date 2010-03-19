	
	function status_change(id, table) {
		
		var obj = $("#" + id);
		
		obj.attr("disabled", true);
		
		cmsAjax(
			'/admin/includes/ajax_status.php', // backend
			{
				'id': id,	
				'table': table
			},
			function(res) { // ONREADYSTATE
				
				obj.attr("disabled", false).attr("checked", res.status).attr("title", "Статус: " + res.word);
				
			}
		);
		
	}