<?
	
	REQUIRE "_core.php";
	
?>
<script>
	
	var validElements = [
		"a[title|href|target]",
		"abbr",
		"acronym",
		"address",
		"b",
		"bdo",
		"big",
		"blockquote",
		"br",
		"center",
		"cite",
		"code[language]",
		"dd",
		"del",
		"dfn",
		"dl",
		"dt",
		"em",
		"font",
		"h1",
		"h2",
		"h3",
		"h4",
		"h5",
		"h6",
		"h7",
		"h8",
		//"hr",
		"i",
		"img[src|align|title|width|height]",
		"ins",
		"kbd",
		"li",
		"nobr",
		"ol[start]",
		"p",
		"pre",
		"q",
		"samp",
		"small",
		"span",
		"strike",
		"strong",
		"sub",
		"sup",
		"tt",
		"ul",
		"var",
		"wbr",
		"xmp"
	].join(",");
	
	var invalid_elements = [
		"@[style|class]"
	].join(",");
	
	tinyMCE.init({
		
		language:															"ru",
		mode:																	"textareas",
		theme:																"advanced",
		editor_deselector:										"cmsMce_disabled",
		
		content_css:													"/core/css/raw.php",
		popup_css_add:												"/core/css/mce.css",
		
		plugins:															"safari,paste,contextmenu,advcode", //,autoresize,inlinepopups
		
		theme_advanced_buttons1:							"bold,italic,underline,strikethrough,|,sub,sup,|,bullist,numlist,|,image,charmap,|,link,unlink,|,removeformat,|,advcode", //,backcolor,|
		theme_advanced_buttons2:							"",
		theme_advanced_buttons3:							"",
		
		theme_advanced_styles:								"Не выбираем",
		theme_advanced_blockformats:					"p,h1,h2,h3,code",
		
		theme_advanced_path:									true,
		theme_advanced_path_location:					"bottom",
		theme_advanced_source_editor_wrap:		false,
		theme_advanced_statusbar_location:		"bottom",
		theme_advanced_toolbar_location:			"top",
		theme_advanced_toolbar_align:					"left",
		theme_advanced_resizing:							true,
		theme_advanced_resizing_min_height:		200,
		theme_advanced_resize_horizontal:			false,
		theme_advanced_resizing_use_cookie:		false,
		
		convert_urls:													true,
		relative_urls:												false,
		remove_script_host:										true,
		document_base_url:										"",
		
		accessibility_warnings:								false,
		body_class:														"cmsMce",
		fix_table_elements :									true,
		fix_list_elements:										true,
		fix_nesting:													true,
		invalid_elements:											invalid_elements,
		object_resizing:											false,
		remove_linebreaks:										false,
		valid_elements:												validElements,
		
		dummy: true
		
	});
	
</script>	