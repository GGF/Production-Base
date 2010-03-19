/**
 *	@author		DiS
 *	@version	0.2
 */
(function($){
	
	$.fn.corner = function(options) {
		
		var settings = {
			marginVml: "0px 0px -2px 0px",
			margin: "2px 2px -1px 2px",
			radius: 10,
			border: 0,
			bg: "transparent",
			hover: false,
			width: "100%"
		};
		
		$.extend(settings, options || {});
		
		this.each(function(){
			
			var t = $(this);
			
			if (!t.attr("jquery-corner-fixed") || settings.hover) {
				
				var radius = parseInt(t.css("border-radius")) || settings.radius;
				var bg = t.css("backgroundColor") || settings.bg;
				var border = parseFloat(t.css("borderWidth")) || settings.border;
				var width = t.css("width") || settings.width;
				
				if ($.browser.msie) {
					
					if (document.namespaces["vx"] == null) {
						document.namespaces.add("vx", "urn:schemas-microsoft-com:vml");
						document.createStyleSheet().owningElement.styleSheet.cssText = 
							".jquery-corner-inline { display: inline-block; *zoom: 1; *display: inline }" + "\n" + 
							".jquery-corner-reset { background: transparent !important; border: none !important; zoom: 1 }" + "\n" + 
							"vx\\:*{behavior:url(#default#VML);}" + "\n" + 
							"vx\\:roundrect { height: 100%; margin-bottom: -1px }"; // margin для бордера… видимо надо сдвигать? width: 100%
					}
					
					var arcsize = Math.round((radius / (t.height() > t.width() ? t.width() : t.height())) * 100) + "%";
					
					var vml = t.wrap(
						"<vx:roundrect class='jquery-corner-inline' arcsize='" + arcsize + "' style='cursor: " + t.css("cursor") + "'" +
						"  fillcolor='" + bg + "' filled='" + (bg == "transparent" ? "false" : "true") + "'" +//
						"  strokecolor='" + (border ? t.css("borderColor") : bg) + "' strokeweight='" + (border ? border : 1) + "' stroked='true'" +
						"></vx:roundrect>"
					).parent().wrap("<span class='jquery-corner-inline'></span>"); //  PreferRelative='true'
					
					vml.css({margin: settings.marginVml})
					t.css({margin: settings.margin})
					
					// reset
					t.addClass("jquery-corner-reset");
					
					var timeout = null;
					
					function ieRedraw(t, vml) {
						
						t.removeClass("jquery-corner-reset");
						
						var border = parseFloat(t.css("borderWidth")) || 0;
						
						vml.attr("fillcolor", t.css("backgroundColor"));
						vml.attr("strokecolor", (border ? t.css("borderColor") : t.css("backgroundColor")));
						vml.attr("strokeweight", (border ? border : 1));
						
						t.addClass("jquery-corner-reset");
						
					}
					
					if (!settings.hover) {
						
						t.mouseover(function(){
							
							ieRedraw(t, vml);
							
						}).mouseout(function(){
							
							ieRedraw(t, vml);
							
						});
						
					}
					
				}
				
				if ($.browser.opera) {
					
					var svg = "data:image/svg+xml;base64," + $.base64Encode(
						"<svg xmlns=\"http://www.w3.org/2000/svg\">" +
						"	<mask id='mask' class='" + Math.random() + "'>" +
						"		<rect fill='white' rx='" + radius + "' ry='" + radius + "' width='100%' height='100%' />" +
						"	</mask>" +
						"	<rect " + (border ? "stroke='" + t.css("borderColor") + "' rx='" + radius + "' ry='" + radius + "' stroke-width='" + (border * 2) + "'" : "") + " mask='url(#mask)' fill='" + bg + "' fill-opacity='" + (bg == "transparent" ? 0 : 1) + "' width='100%' height='100%' />" +
						"</svg>"
					);
					
					// нужно добавить фон, приплюсовать бордер к паддингу, а сам бордер убрать
					t.css({
						background: "transparent url(" + svg + ")",
						border: "none"
					});
					
					if (!settings.hover) {
						
						// нужно добавить фон, приплюсовать бордер к паддингу, а сам бордер убрать
						t.css({
							paddingTop: parseInt(t.css("paddingTop")) + border,
							paddingRight: parseInt(t.css("paddingRight")) + border,
							paddingBottom: parseInt(t.css("paddingBottom")) + border,
							paddingLeft: parseInt(t.css("paddingLeft")) + border
						});
						
						t.hover(function(){
							
							// текущий фон в переменную
							$(this).attr("jquery-corner-bg", $(this).css("background"));
							
							// отменяем патчи
							$(this).css({
								background: "",
								border: ""
							});
							
							// здесь может быть кеш, впрочем — и так быстро работает ;)
							//if (!t.attr("jquery-corner-bg-hover")) {
								
								$(this).corner({hover: true});
								
								t.attr("jquery-corner-bg-hover", $(this).css("background"));
								
							//} else $(this).css("background", t.attr("jquery-corner-bg-hover"));
							
						}, function(){
							
							// восстанавливаем фон
							$(this).css("background", $(this).attr("jquery-corner-bg"));
							
						});
						
					}
					
				}
				
				t.css({
					"-moz-border-radius": radius,
					"-webkit-border-radius": radius
				}).attr("jquery-corner-fixed", true);
				
			}
			
			
		});
		
		return this;
		
	}
	
})(jQuery);