	
	var cmsTooltip_parse = true;
	
	if (cmsTooltip_parse) {
	
		$(function(){
			
			$("body").append("<div id='cmsTooltip'></div>");
			
			$("*:not(.cmsTooltip_noParse)[title]").live("mouseover", function(){
				
				var obj = $(this);
				var tip = $("#cmsTooltip");
				
				obj.data("oldTitle", obj.attr("title"));
				obj.attr("title", "");
				
				tip.html(obj.data("oldTitle").replace(/\\n|\n/g, '<br>')).show();
				
				var c = obj.offset();
				
				var left = c.left + obj.outerWidth() + 1;
				var top = c.top;
				var w = parseInt(tip.outerWidth());
				var h = parseInt(tip.outerHeight());
				
				var max = parseInt($(document).width());
				
				if (max < left + w + 10) left = left - 1 - w - obj.outerWidth();
				
				tip.css({
					left:	left,
					top:	top
				});
				
				obj.unbind("mouseout").mouseout(function(){
					
					$(this).attr("title", $(this).data("oldTitle")).data("oldTitle", "");
					$("#cmsTooltip").hide();
					
				});
				
			});
			
		});
		
	}