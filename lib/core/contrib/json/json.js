// Proposal by Theodor Zoulias, 23 Jan 2006.

var JSON;

(function() {
  
  // JSON library
  JSON = {
    stringify: function (arg) {
      switch (typeof arg) {
        case 'string'   : return '"' + encodeString(arg) + '"'; // break command is redundant here and below.
        case 'number'   : return String(arg);
        case 'object'   : 
          if (arg) {
            var out = [];
            /*
						if (arg instanceof Array) {
              for (var i = 0; i < arg.length; i++) {
                var json = this.stringify(arg[i]);
                if (json != null) out[out.length] = json;
              }
              return '[' + out.join(',') + ']';
            } else {
						*/
              for (var p in arg) {
                var json = this.stringify(arg[p]);
                if (json != null) out[out.length] = '"' + encodeString(p) + '":' + json;
              }
              return '{' + out.join(',') + '}';
            //}
          }
          return 'null'; // if execution reaches here, arg is null.
        case 'boolean'  : return String(arg);
        // cases function & undefined return null implicitly.
      }
    },
    parse: function (text) {
      try {
        return !(/[^,:{}\[\]0-9.\-+Eaeflnr-u \n\r\t]/.test(text.replace(/"(\\.|[^"\\])*"/g, '')))
          && eval('(' + text + ')');
      } catch (e) {
        return new Array();
      }
    }
  };

  // Test for modern browser (any except IE5).
  var JS13 = ('1'.replace(/1/, function() { return ''; }) == '');

  // CHARS array stores special strings for encodeString() function.
  var CHARS = {
    '\b': '\\b',
    '\t': '\\t',
    '\n': '\\n',
    '\f': '\\f',
    '\r': '\\r',
    '\\': '\\\\',
    '"' : '\\"'
  };
  for (var i = 0; i < 32; i++) {
    var c = String.fromCharCode(i);
    if (!CHARS[c]) CHARS[c] = ((i < 16) ? '\\u000' : '\\u00') + i.toString(16);
  }
  
  function encodeString(str) {
    if (!/[\x00-\x1f\\"]/.test(str)) {
      return str;
    } else if (JS13) {
      return str.replace(/([\x00-\x1f\\"])/g, function($0, $1) {
        return CHARS[$1];
      });
    } else {
      var out = new Array(str.length);
      for (var i = 0; i < str.length; i++) {
        var c = str.charAt(i);
        out[i] = CHARS[c] || c;
      }
      return out.join('');
    }
  }
  
})();