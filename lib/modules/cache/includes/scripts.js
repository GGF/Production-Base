	
	// Скрипты для админской части
	
	function modCache_delete(id) {
		
		if (confirm("Вы действительно хотите удалить этот объект?")) { 
			
			cmsAjax(
				'includes/ajax_delete.php', // backend
				{
					'id': id
				},
				function(res) { // ONREADYSTATE
					
					if (res.status) $("#tr-" + id).remove(); else alert("Ошибка при удалении");
					
				}
			);
			
		}
		
	}
	
