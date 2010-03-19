	
	// --	INIT --------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	$(document).ready(function() {
		
		$("form[class!=noParse]").bind("submit", function() {
			
			if (!cmsForm_errors) {
				
				contentSave(true);
				return true;
				
			} else {
				
				alert("При сохранении формы через js возникла ошибка cmsForm_errors.");
				return false;
				
			}
			
		});
		
	});
	
	// -- FUNCTIONS ---------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function contentSaveURL(url) {
		
		contentSave(url);
		
	}
	
	// включаем отображение загрузки во фрейме
	function contentSave_loading() {
		
		$("#saveLoading").css({display: "block", height: $(window).height()});
		
	}
	
	function contentSave(src, w, h) {
		
		// показываем фрейм
		$("#contentSave").show();
		
		// показываем спиннер, при непоказанном фрейме оно не посчитает высоту, будет бяка
		$(frames["saveFrame"]).ready(function() { try { frames["saveFrame"].contentSave_loading(); } catch(e) {} });
		
		// скрываем фрейм
		$("#contentSave").hide();
		
		contentSaveShow();
		
		if (src != true) $("#saveFrame").attr("src", src);
		
	}
	
	function contentSaveShow() {
		
		// анимируем задник
		$("#contentSaveBG").css({
			opacity:	0,
			top:			"0px",
			display:	"block",
			height:		$(document).height()
		}).animate({opacity:0.3}, 400, "linear", function() {
			
			// показываем фрейм
			$("#contentSave").show();
			
		});
		
	}
	
	function contentSaveClose() {
		
		$("#contentSave").css({display: "none"});
		$("#contentSaveBG").animate({opacity:0}, 200, "linear", function(){
			
			$("#contentSaveBG").css({display: "none"});
			
		});
		
	}
