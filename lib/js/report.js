	
	$(document).ready(function() { 
		
		$("#cmsReport_cont").animate({width:1, opacity:0}, 10);
		
	});
	
	function cmsReport_slide() {
		
		if ($("#cmsReport_cont").css("visibility") == "hidden") {
			
			$("#cmsReport_cont").css("opacity", 0).css("visibility", "visible").css("borderRight", "5px solid #AAAAAA").animate({width:800, opacity:1}, "normal");
			
		} else {
			
			$("#cmsReport_cont").animate({width:1, opacity:0}, "normal", "", function() {
				$("#cmsReport_cont").css("visibility", "hidden").css("borderRight", 0);
			});
			
		}
		
	}
