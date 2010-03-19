	
	jQuery.fn.dataTableExt.afCustom = {
		parseFloat: function(str) {
			return parseFloat(str.replace(/<[^>]+>/img, "").replace(/[^\d.-]/ig, ""));
		},
		parseDate: function(str, lang, mod) {
			
			lang	= lang || "ru";
			mod		= mod || "default";
			
			var months = {
				"ru": {
					"default": ["", "Января", "Февраля", "Марта", "Апреля", "Мая", "Июня", "Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабря"],
					"word": ["", "Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
					"cut": ["", "Янв", "Фев", "Мар", "Апр", "Мая", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек"]
				},
				"en": {
					"default": ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
					"word": ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
					"cut": ["", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]
				}
			}
			
			var date = str.split(" ");
			date[3] = (date[3]) ? date[3].split(":") : [0,0,0];
			
			var m = 0;
			for (i in months[lang][mod]) if (months[lang][mod][i] == date[1]) m = i;
			
			var dateParts = [date[3][2], date[3][1], date[3][0], date[0], m, date[2]];
			
			var dateInt = 0;
			for (i in dateParts) dateInt += (parseInt(dateParts[i]) || 0) * Math.pow(10, i * 2);
			
			return dateInt;
			
		}
	};

	
	jQuery.fn.dataTableExt.oSort['float-desc']  = function(x,y) {
		var x = jQuery.fn.dataTableExt.afCustom.parseFloat(x);
		var y = jQuery.fn.dataTableExt.afCustom.parseFloat(y);
		return ((x < y) ?  1 : ((x > y) ? -1 : 0));
	};
	
	jQuery.fn.dataTableExt.oSort['float-asc'] = function(x,y) {
		var x = jQuery.fn.dataTableExt.afCustom.parseFloat(x);
		var y = jQuery.fn.dataTableExt.afCustom.parseFloat(y);
		return ((x < y) ?  -1 : ((x > y) ? 1 : 0));
	};
	
	jQuery.fn.dataTableExt.oSort['date-ru-desc']  = function(x,y) {
		var x = jQuery.fn.dataTableExt.afCustom.parseDate(x);
		var y = jQuery.fn.dataTableExt.afCustom.parseDate(y);
		return ((x < y) ?  1 : ((x > y) ? -1 : 0));
	};
	
	jQuery.fn.dataTableExt.oSort['date-ru-asc'] = function(x,y) {
		var x = jQuery.fn.dataTableExt.afCustom.parseDate(x);
		var y = jQuery.fn.dataTableExt.afCustom.parseDate(y);
		return ((x < y) ?  -1 : ((x > y) ? 1 : 0));
	};
	
	