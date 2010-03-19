	
	var managerW = 950;
	var managerH = 600;

	$(document).ready(function() {
		
		$(".cmsInfo tbody").each(function() {
			
			if (!$(this).hasClass("cmsInfo_noHighlight")) {
				
				$(this).bind("mouseover", function(e) {
					
					$(this).addClass("lightBlue");
					
				}).bind("mouseout", function(e) {
					
					$(this).removeClass("lightBlue");
					
				});
				
			}
			
		});
		
	});

