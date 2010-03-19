	
	function cmsTab_over(n) {
		
		$("#tab" + n + "1").addClass("cmsTabs_hover1");
		$("#tab" + n + "2").addClass("cmsTabs_hover2");
		$("#tab" + n + "3").addClass("cmsTabs_hover3");
		
	}
	
	function cmsTab_out(n) {
		
		$("#tab" + n + "1").removeClass("cmsTabs_hover1");
		$("#tab" + n + "2").removeClass("cmsTabs_hover2");
		$("#tab" + n + "3").removeClass("cmsTabs_hover3");
		
	}