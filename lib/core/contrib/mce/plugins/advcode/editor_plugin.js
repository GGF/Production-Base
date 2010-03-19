(function() {
  tinymce.PluginManager.requireLangPack('advcode');
  
  tinymce.create('tinymce.plugins.AdvancedCodePlugin', {
    init : function(ed, url) {
      
			// Register commands
      ed.addCommand('mceAdvancedCode', function() {
        ed.windowManager.open({
          file : url + '/dialog.html',
          width : 900 + parseInt(ed.getLang('advcode.delta_width', 0)),
          height : 650 + parseInt(ed.getLang('advcode.delta_height', 0)),
          inline : 1
        }, {
          plugin_url : url
        });
      });
			
      // Register buttons
      ed.addButton('advcode', {
        title : ed.getLang('advcode.desc', 0),
        cmd : 'mceAdvancedCode',
        image : url + '/img/html.png'
      });
			
      ed.onNodeChange.add(function(ed, cm, n) {});
			
    },

    getInfo : function() {
      return {
        longname : 'Advanced Code Editor',
        author : 'Kirill DiS Konshin',
        authorurl : 'http://www.dis.dj',
        infourl : 'http://www.osmio.ru',
        version : tinymce.majorVersion + "." + tinymce.minorVersion
      };
    }
  });

  // Register plugin
  tinymce.PluginManager.add('advcode', tinymce.plugins.AdvancedCodePlugin);
})();
