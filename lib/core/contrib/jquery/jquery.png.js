(function($) {

jQuery.fn.pngFix = function(settings) {
	
	if (jQuery.support.opacity) return this;
	
	settings = jQuery.extend({
		method: {
			img: "scale",
			bg: "crop",
			input: "scale"
		},
		blank: "/images/free.gif"
	}, settings);
	
	function filter(obj, src, method) {
		
		obj.runtimeStyle.filter += "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + src + "',sizingMethod='" + method + "')";
		
	}
	
	//fix images with png-source
	var obj = cmsPng.filter(jQuery(this).find("img[src*='.png']"));
	obj.each(function() {
		
		if (!this.className.match(/noPNGFix|DD_belatedPNG_sizeFinder|cmsPng_belated|cmsPng_fixed/i)) {
			
			jQuery(this).css({
				width:	jQuery(this).width(),
				height:	jQuery(this).height(),
				zoom:		1
			});
			
			filter(this, jQuery(this).attr("src"), settings.method.img);
			
			jQuery(this).attr({
				src:	settings.blank,
				zoom: 1
			}).addClass("cmsPng_fixed");
			
		}
		
	});

	// fix css background pngs
	var obj = cmsPng.filter(jQuery(this).find("*"));
	obj.each(function() {
		var bgIMG = jQuery(this).css('background-image');
		
		if(bgIMG.indexOf(".png") != -1 && !this.className.match(/noPNGFix|DD_belatedPNG_sizeFinder|cmsPng_belated|cmsPng_fixed/i)){
			
			filter(this, bgIMG.split('url("')[1].split('")')[0], settings.method.bg);
			
			jQuery(this).css({
				"background-image": "none"
			}).addClass("cmsPng_fixed");
			
		}
		
	});
	
	//fix input with png-source
	var obj = cmsPng.filter(jQuery(this).find("input[src*='.png']"));
	obj.each(function() {
		
		if(!this.className.match(/noPNGFix|DD_belatedPNG_sizeFinder|cmsPng_belated|cmsPng_fixed/i)){
			
			filter(this, jQuery(this).attr("src"), settings.method.input);
			
			jQuery(this).attr({
				src:	settings.blank,
				zoom: 1
			}).addClass("cmsPng_fixed");
			
		}
		
	});
	
	return this;

};

})(jQuery);