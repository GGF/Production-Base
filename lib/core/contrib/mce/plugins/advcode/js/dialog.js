tinyMCEPopup.requireLangPack();

var CodeDialog = {
  init : function() {
		
    //var f = document.forms[0];
    //f.codepress.value = ;
		
		editAreaLoader.init({
			id: "codepress",
			start_highlight: true,
			allow_resize: "no",
			allow_toggle: false,
			word_wrap: false,
			language: "ru",
			toolbar: "undo,redo,|,search,go_to_line,|,change_smooth_selection,reset_highlight,highlight,word_wrap",
			font_size: 10,
			font_family: "Consolas, Courier New, Monospace",
			syntax: "html"
		});
		
		editAreaLoader.setValue("codepress", tinyMCEPopup.editor.getContent());
		
  },

  insert : function() {
		
    tinyMCEPopup.editor.setContent(editAreaLoader.getValue("codepress"));
    tinyMCEPopup.close();
		
  }
};

tinyMCEPopup.onInit.add(CodeDialog.init, CodeDialog);