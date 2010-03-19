/*! jquery.sifr.license.txt *//*

jQuery sIFR Plugin v3.0.4 <http://jquery.thewikies.com/sifr/>
Copyright (c) 2009 Jonathan Neal
This software is released under the MIT License <http://www.opensource.org/licenses/mit-license.php>
This software is released under the GPL License <http://www.opensource.org/licenses/gpl-2.0.php>

jQuery SWFObject Plugin v1.0.4 <http://jquery.thewikies.com/swfobject/>
Copyright (c) 2009 Jonathan Neal
This software is released under the MIT License <http://www.opensource.org/licenses/mit-license.php>
This software is released under the GPL License <http://www.opensource.org/licenses/gpl-2.0.php>

Scalable Inman Flash Replacement (sIFR) v3.0 <http://novemberborn.net/sifr3>
Copyright (c) 2009 Mike Davidson, Shaun Inman, Tomas Jogin and Mark Wubben
This software is released under the LGPL License <http://www.opensource.org/licenses/lgpl-2.1.php>

SWFObject v2.1 <http://code.google.com/p/swfobject/>
Copyright (c) 2007-2009 Geoff Stearns, Michael Williams, and Bobby van der Sluis
This software is released under the MIT License <http://www.opensource.org/licenses/mit-license.php>

jQuery v1.2.6 <http://jquery.com/>
Copyright (c) 2009 John Resig
This software is released under the MIT License <http://www.opensource.org/licenses/mit-license.php>
This software is released under the GPL License <http://www.opensource.org/licenses/gpl-2.0.php>

*//*jslint
	passfail: false,
	white: true,
	browser: true,
	widget: false,
	sidebar: false,
	rhino: false,
	safe: false,
	adsafe: false,
	debug: false,
	evil: false,
	cap: false,
	on: false,
	fragment: false,
	laxbreak: false,
	forin: true,
	sub: false,
	css: false,
	undef: true,
	nomen: false,
	eqeqeq: true,
	plusplus: false,
	bitwise: true,
	regexp: false,
	onevar: true,
	strict: false
*//*global
	jQuery
*/

(function ($) {
	var t = true,
	f = false,
	x = '',
	height = 'height',
	width = 'width',
	offsetHeight = 'offsetHeight',
	offsetWidth = 'offsetWidth',
	color = 'color',
	cursor = 'cursor',
	font = 'font',
	fontSize = 'fontSize',
	fontWeight = 'fontWeight',
	lineHeight = 'lineHeight',
	textAlign = 'textAlign',
	textTransform = 'textTransform',
	childNodes = 'childNodes',
	parentNode = 'parentNode',
	children = 'children',
	content = 'content',
	sIFRreplaced = 'sIFR-replaced',
	asNumber = function (x) {
		return parseInt(x, 10);
	},
	mapOfHex = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F'],
	mapOfColors = {
		aqua: '0FF',
		azure: 'F0FFFF',
		beige: 'F5F5DC',
		black: '000',
		blue: '00F',
		brown: 'A52A2A',
		cyan: '0FF',
		darkblue: '00008B',
		darkcyan: '008B8B',
		darkgrey: 'A9A9A9',
		darkgreen: '006400',
		darkkhaki: 'BDB76B',
		darkmagenta: '8B008B',
		darkolivegreen: '556B2F',
		darkorange: 'FF8C00',
		darkorchid: '9932CC',
		darkred: '8B0000',
		darksalmon: 'E9967A',
		darkviolet: '9400D3',
		fuchsia: 'F0F',
		gold: 'FFD700',
		green: '008000',
		indigo: '4B0082',
		khaki: 'F0E68C',
		lightblue: 'ADD8E6',
		lightcyan: 'E0FFFF',
		lightgreen: '90EE90',
		lightgrey: 'D3D3D3',
		lightpink: 'FFB6C1',
		lightyellow: 'FFFFE0',
		lime: '0F0',
		magenta: 'F0F',
		maroon: '800000',
		navy: '000080',
		olive: '808000',
		orange: 'FFA500',
		pink: 'FFC0CB',
		purple: '800080',
		violet: '800080',
		red: 'F00',
		silver: 'C0C0C0',
		white: 'FFF',
		yellow: 'FF0',
		transparent: 'FFF'
	},
	asHex = function (x) {
		return isNaN(x) ? '00': mapOfHex[(x - x % 16) / 16] + mapOfHex[x % 16];
	},
	toHex = function (x) {
		var rgb;

		return '#' + ((x) ? (rgb = mapOfColors[x.toLowerCase()]) ? rgb: (rgb = x.match(/rgb\((\d+),\s(\d+),\s(\d+)\)/)) ? asHex(rgb[1]) + asHex(rgb[2]) + asHex(rgb[3]) : x: '000').replace(/^#{0,}(\w)(\w)(\w)$|^#/, '$1$1$2$2$3$3').toUpperCase();
	};

	/* $.sifrNodeList */
	$.sifrNodeList = $(document).not(document);

	/* $.sifr */
	$.sifr = function (options) {
		var a,
		arrA,
		arrB,
		call = arguments.callee,
		b,
		flashvars;

		options = $.extend({}, call.options, options);

		if (options.save) {
			delete options.save;
			call.options = $.extend({}, options);
		}

		/* Build font path and name */
		options[font] = (options.path || x).replace(/([^\/])$/, '$1/') + (options[font] || x).replace(/\.swf$|$/, '.swf'); // TextTransform options
		switch (options[textTransform]) {
		case 'lowercase':
			options[content] = options[content].toLowerCase();
			break;
		case 'uppercase':
			options[content] = options[content].toUpperCase();
			break;
		case 'capitalize':
			a = options[content].split(/(\s|\>)/);
			options[content] = x;
			for (b in a) {
				options[content] += a[b].charAt(0).toUpperCase() + a[b].substr(1);
			}
		}

		/* Build flashvars */
		if (asNumber(options.version) === 3) {
			flashvars = {
				content: options[content],
				cursor: options[cursor],
				css: $.extend({
					'.sIFR-root': $.extend({
						color: toHex(options[color]),
						fontWeight: options[fontWeight] || 'normal',
						lineHeight: options[lineHeight] || 12,
						textAlign: options[textAlign] || 'left'
					},
					options.style),
					a: {},
					'a:hover': {}
				},
				options.css),
				delayrun: options.delayRun || f,
				events: options.events || f,
				fitexactly: options.fitExactly || f,
				fixhover: options.fixHover || t,
				forcesingleline: options.forceSingleLine || f,
				gridfittype: options.gridFitType || 'pixel',
				height: (options[height] * options.overY) || 14,
				offsetleft: options.offsetLeft || 0,
				offsettop: options.offsetTop || 0,
				opacity: options.opacity || 100,
				preventwrap: options.preventWrap || f,
				size: options[fontSize] || 12,
				tuneheight: options.tuneHeight || 0,
				tunewidth: options.tuneWidth || 0,
				version: options.build || 436,
				width: (options[width] * options.overX) || 320
			};
			flashvars.css.a[color] = flashvars.css.a[color] || toHex(options.linkColor || options[color]);
			flashvars.css['a:hover'][color] = flashvars.css['a:hover'][color] || toHex(options.hoverColor || flashvars.css.a[color] || options[color]);
			flashvars.selectable = options.selectable || ((/arrow|pointer/.test(flashvars[cursor])) ? f: t);

			/* Build filters options */
			if (typeof options.filter === 'object') {
				arrA = [];
				for (a in options.filter) {
					if (typeof options.filter[a] === 'object') {
						arrB = [];
						for (b in options.filter[a]) {
							arrB.push(b.replace(/([A-Z])/, '-$1').toLowerCase() + ':' + ((/color/.test(b)) ? '"0x' + toHex(options.filter[a][b]).substr(1) + '"': options.filter[a][b]));
						}
						options.filter[a] = arrB.join(',');
					}
					arrA.push(a + 'Filter,' + options.filter[a]);
				}
				flashvars.flashfilters = arrA.join(',');
			}
		} else {
			flashvars = {
				h: (options[height] * options.zoom) || 14,
				leading: Math.max(options[lineHeight] - options[fontSize], 0),
				offsetTop: Math.max((options[lineHeight] - options[fontSize]) / 2, 0),
				textAlign: options[textAlign] || 'left',
				textColor: toHex(options[color]),
				txt: options[content],
				w: (options[width] * options.zoom) || 320
			};
			flashvars.linkColor = toHex(options.linkColor || options[color]);
			flashvars.hoverColor = toHex(options.hoverColor || flashvars.linkColor || options[color]);
			if (options.underline === t) {
				flashvars.underline = t;
			}
		}
		if (options.link) {
			flashvars.link = options.link;
		}
		return $.flash({
			flashvars: flashvars,
			height: (options[height] * options.overY) || 14,
			params: {
				wmode: 'transparent'
			},
			swf: options[font],
			width: (options[width] * options.overX) || 320
		});
	};

	/* $.fn.sifr */
	$.fn.sifr = function (options) {
		/* Check if Flash is installed, return false if isn't */
		if (!$.hasFlashPlayer) {
			return f;
		}

		var $alt,
		$each,
		$swf,
		$this = this,
		each = 0,
		eachOptions,
		sendOptions;

		options = $.extend({}, options);

		/* Each */
		while (($each = $this.eq(each++))[0]) {
			sendOptions = $.extend({}, (eachOptions = $.extend({}, $each.data('options'), options)));
			if ($each.hasClass(sIFRreplaced)) {
				$each.unsifr();
			} // HTML options
			$each.addClass(sIFRreplaced)[0].innerHTML = ['<span style="display:inline-block;position:relative;"><span class="sIFR-alternate" ', ((sendOptions.debug) ? '' : 'style="' + (($.browser.msie) ? 'zoom:1;filter:alpha(opacity=0)': 'opacity:0') + ';"'), '>', $each[0].innerHTML, '</span><span class="sIFR-flash" style="position:absolute;top:0;left:0;right:0;bottom:0;"></span></span>'].join(x);

			/* Reference options */
			$alt = $each[children]()[children]().eq(0);
			$swf = $each[children]()[children]().eq(1);

			/* Content options */
			sendOptions[content] = sendOptions[content] || $.trim($alt[0].innerHTML);

			/* TextTransform options */
			sendOptions[textTransform] = sendOptions[textTransform] || $alt.css(textTransform).toLowerCase();

			/* Configure dimensions */
			sendOptions.zoom = sendOptions.zoom || 1;
			sendOptions.overX = (sendOptions.overX || 1) * (sendOptions.over || 1);
			sendOptions.overY = (sendOptions.overY || 1) * (sendOptions.over || 1);
			sendOptions[height] = sendOptions[height] || Math.max($alt[0][offsetHeight] || $alt[0][parentNode][offsetHeight], asNumber($alt.css(lineHeight).replace(/normal/, asNumber($alt.css(fontSize)) * 1.25)));
			sendOptions[width] = sendOptions[width] || $alt[0][offsetWidth] || $alt[0][parentNode][offsetWidth];

			/* Build style attributes */
			sendOptions[font] = sendOptions[font] || $each.css('fontFamily').replace(/^\s+|\s+$|,[\S|\s]+|'|"|(,)\s+/g, '$1');
			sendOptions[color] = sendOptions[color] || $alt.css(color);
			sendOptions[cursor] = sendOptions[cursor] || $alt.css(cursor);
			sendOptions[fontWeight] = (sendOptions[fontWeight] || $alt.css(fontWeight).toString()).replace('400', 'normal').replace('700', 'bold');
			sendOptions[fontSize] = (sendOptions[fontSize] || asNumber($alt.css(fontSize))) * sendOptions.zoom;
			sendOptions[lineHeight] = asNumber(sendOptions[lineHeight] || $alt.css(lineHeight).replace(/normal/, sendOptions[fontSize] * 1.25)) || sendOptions[height];
			sendOptions[textAlign] = sendOptions[textAlign] || $alt.css(textAlign).toString();
			if (sendOptions[textAlign] === 'center') {
				$swf.css('marginLeft', (sendOptions[width] - (sendOptions[width] * sendOptions.overX)) / 2);
			}
			if (sendOptions.resizable) {
				$.sifrNodeList = $.sifrNodeList.add($each.data('options', $.extend({
					offsetHeight: $alt[0][offsetHeight],
					offsetWidth: $alt[0][offsetWidth]
				},
				eachOptions)));
			}
			$swf.html($.sifr(sendOptions));
		}
		return $this;
	};

	/* $.fn.unsifr */
	$.fn.unsifr = function () {
		var $this = this,
		$each,
		each = 0;

		/* Each */
		while ((($each = $this.eq(each++))[0]) && $each.hasClass(sIFRreplaced)) {
			$each.removeClass(sIFRreplaced)[0].innerHTML = $each[0][childNodes][0][childNodes][0].innerHTML;
			$.sifrNodeList = $.sifrNodeList.not($each);
		}
		return $this;
	};

	/* Resizable */
	$(window).resize(function () {
		/* Filter by size change */
		$.sifrNodeList.filter(function (index) {
			var $each,
			alt = this[childNodes][0][childNodes][0],
			options = ($each = $(this)).data('options');

			/* If size has changed */
			if (alt[offsetHeight] !== options[offsetHeight] || alt[offsetWidth] !== options[offsetWidth]) {
				options[offsetHeight] = alt[offsetHeight];
				options[offsetWidth] = alt[offsetWidth];
				$each.data('options', options);
				return t;
			}
			return f;
		}).sifr();
	});
}(jQuery));