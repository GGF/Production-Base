	
	// --	INIT --------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	$(document).ready(function() {
		
		$("form[class!=noParse]").bind("submit", function() {
			
			if (!cmsForm_errors) {
				
				contentSave(true);
				return true;
				
			} else {
				
				alert("��� ���������� ����� ����� js �������� ������ cmsForm_errors.");
				return false;
				
			}
			
		});
		
	});
	
	// -- FUNCTIONS ---------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function contentSaveURL(url) {
		
		contentSave(url);
		
	}
	
	// �������� ����������� �������� �� ������
	function contentSave_loading() {
		
		$("#saveLoading").css({display: "block", height: $(window).height()});
		
	}
	
	function contentSave(src, w, h) {
		
		// ���������� �����
		$("#contentSave").show();
		
		// ���������� �������, ��� ������������ ������ ��� �� ��������� ������, ����� ����
		$(frames["saveFrame"]).ready(function() { try { frames["saveFrame"].contentSave_loading(); } catch(e) {} });
		
		// �������� �����
		$("#contentSave").hide();
		
		contentSaveShow();
		
		if (src != true) $("#saveFrame").attr("src", src);
		
	}
	
	function contentSaveShow() {
		
		// ��������� ������
		$("#contentSaveBG").css({
			opacity:	0,
			top:			"0px",
			display:	"block",
			height:		$(document).height()
		}).animate({opacity:0.3}, 400, "linear", function() {
			
			// ���������� �����
			$("#contentSave").show();
			
		});
		
	}
	
	function contentSaveClose() {
		
		$("#contentSave").css({display: "none"});
		$("#contentSaveBG").animate({opacity:0}, 200, "linear", function(){
			
			$("#contentSaveBG").css({display: "none"});
			
		});
		
	}
