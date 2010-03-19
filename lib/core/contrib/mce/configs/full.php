<?
	
	REQUIRE "_core.php";
	
?>
<script>
	
	var extendedValidElements = [
		"code[language]",
		"div[id|class|style|align]",
		"hr",
		"img[src|title|alt|hspace|vspace|align|valign|width|height|border|lowsrc|ismap|style|class|onmouseover|onmouseout]",
		"ol[class|start]",
		"ul[class]",
		"p[id|class|style|align]",
		"script",
		//"style",
		"small",
		"span[id|class|style]",
		"tr[id]",
		"td[id|style|class|width|height|colspan|rowspan|align|valign]", //nowrap
		"th[id|style|class|width|height|colspan|rowspan|align|valign]" //nowrap
	].join(",");
	
	var invalid_elements = [
		"div[class=mceTempl]"
	].join(",");
	
	tinyMCE.init({
		
		language:															"ru",
		mode:																	"textareas",
		theme:																"advanced",
		editor_deselector:										"cmsMce_disabled",
		
		content_css:													"/core/css/raw.php",
		popup_css_add:												"/core/css/mce.css",
		
		plugins:															"safari,pagebreak,table,advimage,advlink,preview,paste,contextmenu,fullscreen,media,template,advcode,typograf", //,autoresize,inlinepopups
		
		theme_advanced_buttons1:							"undo,redo,|,cut,copy,pastetext,pasteword,|,bold,italic,underline,strikethrough,|,sub,sup,|,bullist,numlist,|,justifyleft,justifycenter,justifyright,justifyfull,|,forecolor,|,formatselect,styleselect,|,removeformat", //,backcolor,|
		theme_advanced_buttons2:							"image,media,charmap,hr,|,link,unlink,anchor,|,tablecontrols,|,template,|,fullscreen,preview,|,cleanup,visualaid,|,pagebreak,|,spellchecker,typograf,|,advcode",
		theme_advanced_buttons3:							"",
		
		theme_advanced_styles:								"<?=$styles[styles]?>",
		table_styles:													"<?=$styles[table]?>",
		table_cell_styles:										"<?=$styles[tableCell]?>",
		table_row_styles:											"<?=$styles[tableRow]?>",
		theme_advanced_blockformats:					"p,h1,h2,h3,h4,code",
		
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
		
		convert_urls:													false,
		relative_urls:												false,
		remove_script_host:										true,
		document_base_url:										"",
		
		accessibility_warnings:								false,
		body_class:														"cmsMce",
		docs_language:												"en",
		extended_valid_elements:							extendedValidElements,
		file_browser_callback:								"modFiles_mceBrowser",
		fix_list_elements:										true,
		fix_nesting:													true,
		fix_table_elements:										true,
		invalid_elements:											invalid_elements,
		pagebreak_separator:									"<?=$_SERVER[splitter]?>",
		paste_remove_spans:										true,
		paste_strip_class_attributes:					"mso",
		remove_linebreaks:										false,
		table_default_border:									0,
		table_default_cellpadding:						5,
		table_default_cellspacing:						5,
		template_templates :									<?=$templates?>,
		
		dummy: true
		
	});
	
	/* Some TODO
		media_use_script Ч можно бы и включитьЕ по-умолчанию он выключен
	*/
	
</script>	