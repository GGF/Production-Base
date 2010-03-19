	
	var pngFix_msie = true; // backward compatibility
	
	var cmsPng = {
		selectors: [],
		fixed: false,
		fix: true,
		force: false,
		belate: true,
		add: function(expr){
			
			this.selectors.push(expr);
			return this;
			
		},
		fix: function(){
			
			if (!this.fixed && this.belate) {
				
				for(i in this.selectors) {
					
					DD_belatedPNG.fix(this.selectors[i]);
					$(function(){ $(cmsPng.selectors[i]).addClass("cmsPng_belated"); });
					
				}
				
				this.fixed = true;
				
			} else alert("cmsPng: Фиксер уже отработал или выключен cmsPng.belate");
			
		},
		filter: function(obj){
			
			for(i in this.selectors) obj = obj.not(this.selectors[i]);
			return obj;
			
		},
		init: function(){
			
			$(document).ready(function(){ 
				
				if ((cmsPng.force && cmsBrowser.ie) || (cmsBrowser.ie6 && (pngFix_msie && cmsPng.fix))) $(document).pngFix();
				
			});
			
			return this;
			
		}
	}.init();
	
	
