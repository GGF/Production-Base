	
	// ������� ��� ��������� �����
	
	function modCache_delete(id) {
		
		if (confirm("�� ������������� ������ ������� ���� ������?")) { 
			
			cmsAjax(
				'includes/ajax_delete.php', // backend
				{
					'id': id
				},
				function(res) { // ONREADYSTATE
					
					if (res.status) $("#tr-" + id).remove(); else alert("������ ��� ��������");
					
				}
			);
			
		}
		
	}
	
