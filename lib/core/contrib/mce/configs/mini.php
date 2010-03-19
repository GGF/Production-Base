<?
	
	REQUIRE "_core.php";
	
?>
<script>
	
	var extendedValidElements = [
		"style",
		"code[language]",
		"small",
		"ol[start]",
		"hr",
		"div[class|style]"
	].join(",");
	
	var invalid_elements = [
		"table",
		"tr",
		"td",
		"th",
		"tbody",
		"thead",
		"tfoot",
		"script",
	].join(",");
	
	tinyMCE.init({
		
		language :														"ru",
		mode :																"textareas",
		theme :																"advanced",
		editor_deselector:										"cmsMce_disabled",
		
		content_css :													"/core/css/raw.php",
		
		plugins :															"safari,table,preview,paste,contextmenu,advimage,advlink,advcode,typograf,pagebreak", //,inlinepopups
		
		theme_advanced_buttons1 :							"undo,redo,|,cut,copy,pastetext,pasteword,|,bold,italic,underline,strikethrough,|,sub,sup,|,bullist,numlist", //,backcolor,|
		theme_advanced_buttons2 :							"justifyleft,justifycenter,justifyright,justifyfull,|,image,charmap,|,link,unlink,|,removeformat,|,cleanup,|,typograf,|,advcode,|,pagebreak",
		theme_advanced_buttons3 :							"",
		theme_advanced_toolbar_location :			"top",
		theme_advanced_toolbar_align :				"center",
		theme_advanced_path_location :				"bottom",
		theme_advanced_statusbar_location :		"bottom",
		
		theme_advanced_styles :								"<?=$stylesStr?>",
		theme_advanced_blockformats :					"p,h1,h2,h3,h4,code",
		
		theme_advanced_path :									true,
		theme_advanced_resizing : 						true,
		theme_advanced_statusbar_location :		"bottom",
		theme_advanced_toolbar_align :				"left",
		theme_advanced_resize_horizontal :		false,
		theme_advanced_source_editor_wrap :		false,
		
		extended_valid_elements :							extendedValidElements,
		docs_language :												"en",
		remove_linebreaks :										false,
		
		convert_urls :												true,
		relative_urls :												false,
		remove_script_host :									true,
		document_base_url :										"",
		
		invalid_elements :										invalid_elements,
		accessibility_warnings :							false,
		fix_table_elements :									true,
		
		pagebreak_separator :									"<?=$_SERVER[splitter]?>"
		
	});
	
</script>	