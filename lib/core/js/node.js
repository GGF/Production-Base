	
	var prevNode = new Array();
	
	function cmsNode_select(id, state) {
		
		if (state == 0) {
			$("#" + id).removeClass("nodeSel");
			$("#" + id + "_acts").hide();
		} else {
			$("#" + id).addClass("nodeSel");
			$("#" + id + "_acts").show();
		}
		
	}
	
	function cmsNode_expand(id, state) {
		
		if ($("#" + id + "_cont").html()) { 
			
			$("#" + id).removeClass("collapse");
			$("#" + id).removeClass("expand");
			$("#" + id + " > span").removeClass("collapse");
			$("#" + id + " > span").removeClass("collapse");
			
			if (state == 0) {
				
				$("#" + id + "_cont").hide();
				$("#" + id).addClass("expand");
				$("#" + id + " > span").addClass("expand");
				
			} else {
				
				$("#" + id + "_cont").show();
				if ($("#" + id + "_cont")) $("#" + id).addClass("collapse");
				$("#" + id + " > span").addClass("collapse");
				
			}
			
		}
		
	}
	
	function cmsNode_state(id) {
		
		var state = $("#" + id + "_cont").css("display") == "block" ? 0 : 1;
		
		cmsNode_expand(id, state);
		
	}
	
	function cmsNode(id, pane) {
		
		if (!pane) pane = 1;
		
		$("#" + id + " a").blur();
		
		if (prevNode[pane]) cmsNode_select(prevNode[pane], 0);
		
		cmsNode_select(id, 1);
		cmsNode_expand(id, 1);
		
		prevNode[pane] = id;
		
	}
