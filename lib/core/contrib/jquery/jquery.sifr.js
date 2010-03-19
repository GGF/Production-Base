/* == jQuery Sifr Plugin (with selectors) == */
jQuery.fn.sifr = function(prefs) {

	/* == If false is set, set for unSIFR == */
	if (prefs===false) prefs = { unsifr: true };

	/* == Combine the current preferences with the saved preferences == */
	prefs = jQuery.extend({}, arguments.callee.prefs, prefs);

	/* == If prefs.saved is true, save preferences == */
	if(prefs.save) {
		arguments.callee.prefs = jQuery.extend(
			{
				/* Absolute Offset X ...... */ absoluteOffsetX: null, aoX: null,
				/* Absolute Offset Y ...... */ absoluteOffsetY: null, aoY: null,
				/* Relative Offset X ...... */ relativeOffsetX: null, roX: null,
				/* Relative Offset Y ...... */ relativeOffsetY: null, roY: null,
				/* Font Path .............. */ path: null,
				/* Font File .............. */ font: null,
				/* Font Size .............. */ fontSize: null,
				/* Text Color ............. */ color: null,
				/* Text Underline ......... */ underline: null,
				/* Text Transform ......... */ textTransform: null,
				/* Text Link Color ........ */ link: null,
				/* Text Hover Color ....... */ hover: null,
				/* Background Color ....... */ backgroundColor: null,
				/* Text Align on X ........ */ textAlign: null,
				/* Content ................ */ content: null,
				/* Width .................. */ width: null,
				/* Height ................. */ height: null
			},
			arguments.callee.prefs,
			prefs,
			{ save: false }
		);
	}

	/* == jQuery Sifr Function == */
	return this.each(function() {

		/* == Set the current element as 'o' == */
		var o = jQuery(this);
	
		/* == If necessary or required, unSIFR text == */
		if(o.is('.sifr') || (prefs.unsifr && o.is('.sifr'))) {

			/* == Restore element with unSIFRed text == */
			o.html(jQuery(this.firstChild).html());
			o.removeClass('sifr');

		}

		/* == SIFR Element == */
		if(!prefs.unsifr) {

			/* == Collect Settings == */
			var s = jQuery.extend({}, arguments.callee.prefs, prefs);

			/* == Converts color to HEX == */
			var hex = function(N) {
				if(N==null) return "00";
				N = parseInt(N);
				if(N==0 || isNaN(N)) return "00";
				N = Math.max(0, N);
				N = Math.min(N, 255);
				N = Math.round(N);
				return "0123456789ABCDEF".charAt((N - N%16) / 16) + "0123456789ABCDEF".charAt(N%16);
			};

			/* == Converts colors to HEX == */
			var hexed = function(color) {
				if(!color) { return false; }
				if(color.search('rgb') > -1) {
					color = color.substr(4,color.length-5).split(', ');
					color = hex(color[0]) + hex(color[1]) + hex(color[2]);
				}
				color = color.replace('#','');
				if(color.length < 6) {
					color = color.substr(0, 1) + color.substr(0, 1) + color.substr(1, 1) + color.substr(1, 1) + color.substr(2, 1) + color.substr(2, 1);
				}
				return '#' + color;
			};

			/* == Evaluates Sifr Settings == */
			/* Add Sifr Class ......... */ o.addClass('sifr');
			/* Font File .............. */ s.font = s.font || (/([^\'\",]+)[,]?/.exec(o.css('fontFamily')) || [,] )[1];
			/* Font Color ............. */ s.color = hexed(s.color || o.css('color'));
			/* Link Color ............. */ s.link = hexed(s.link || o.children('a').css('color')) || s.color;
			/* Link Hover Color ....... */ s.hover = hexed(s.hover) || s.link;
			/* Link Underline ......... */ s.underline = (s.underline || (o.css('textDecoration')=='underline')) ? true : null;
			/* Background Color ....... */ o.css('backgroundColor', hexed(s.backgroundColor));
			/* Text Align on X ........ */ s.textAlign = s.textAlign || o.css('textAlign') || 'left';
			/* Text Part 1 ............ */ o.html('<span style="display:inline;margin:0;padding:0;float:none;width:auto;height:auto;font-weight:inherit;">' + o.html() + '</span>');
			/* Text Part 2 ............ */ var oc = jQuery(this.firstChild);
			/* Text Align on Y ........ */ s.ieM = (o.height() - oc.height())/2;
				s.ieM = (jQuery.browser.msie) ? 'height:' + (o.height()-s.ieM)+'px;margin:' + s.ieM + 'px 0 0;vertical-align:middle;' : 'vertical-align:middle;';
			/* Text Size .............. */
				if (s.fontSize) oc.css('fontSize', s.fontSize);
			/* Text Transform ......... */ s.textTransform = s.textTransform || o.css('textTransform');
				if (s.textTransform=='uppercase') s.content = oc.html().toUpperCase();
				if (s.textTransform=='lowercase') s.content = oc.html().toLowerCase();
				if (s.textTransform=='capitalize') {
					var c = oc.html().replace(/^\s+|\s+$/g, '').replace(/\>/g, '> ').split(' ');
					for (var i = 0; i < c.length; i++) {
						c[i] = c[i].charAt(0).toUpperCase() + c[i].substring(1);
					}
					s.content = c.join(' ').replace(/\> /g, '>');
				}
			/* Content ................ */ s.content = s.content || oc.html();
			/* Width .................. */ s.width = s.width || oc.width();
			/* Height ................. */ s.height = s.height || oc.height();
			/* Relative Offset X ...... */ s.aoX = (s.aoX || 0) + ((s.width / 100) * (s.roX || 0));
			/* Relative Offset Y ...... */ s.aoY = (s.aoY || 0) + ((s.height / 100) * (s.roY || 0));

			/* == Hide == */
			oc.hide();

			o.flash(
				/* == Flash Configuration Part 1: Flash Settings & Style == */
				{
					/* == Assign Sifr Font File == */
					src: s.path + s.font + '.swf',
					
					/* == Assign Sifr Style == */
					flashvars: {
						txt: s.content.replace(/^\s+|\s+$/g, ''),
						w: s.width,
						h: s.height,
						offsetLeft: s.aoX,
						offsetTop: s.aoY,
						textalign: s.textAlign,
						textcolor: s.color,
						linkColor: s.link,
						hoverColor: s.hover,
						underline: s.underline
					}

				},

				/* == Flash Configuration Part 2: Flash Requirements == */
				{
					version: 7,
					update: false
				},

				/* == Flash Configuration Part 3: Flash Settings & Execution == */
				function(htmlOptions) {

					htmlOptions.style = s.ieM;
					htmlOptions.wmode = 'transparent';
					htmlOptions.width = s.width;
					htmlOptions.height = s.height;
					o.append(jQuery.fn.flash.transform(htmlOptions));

				}

			);

		}

	});

};

/* == jQuery Sifr Plugin (without selectors) == */
jQuery.sifr = jQuery(document).sifr;