	
	var setPosition_array = new Array();
	
	function getPosition(obj) {
		
		var posTop = 0;
		var posLeft = 0;
		while (obj.offsetParent) {
			
			posTop += obj.offsetTop;
			posLeft += obj.offsetLeft;
			obj = obj.offsetParent;
			
		}
		
		return {'x' : posLeft, 'y' : posTop, 'left' : posLeft, 'top' : posTop};
		
	}
	
	function setPosition(id, c, o) {
		
		// ������� �����! ����� ���������� ������ ������, � ������� � ������ ������
		setPosition_array.push({
			"obj": $("#" + id),
			"coord" : {
				"right" :		c.right,
				"left" :		c.left,
				"bottom" :	c.bottom,
				"top" :			c.top
			},
			"offset" : {
				"right" :		o.right,
				"left" :		o.left,
				"bottom" :	o.bottom,
				"top" :			o.top
			}
		});
		
		setPosition_set();
		
	}
	
	function setPosition_set() {
		
		var bw = parseFloat($("body").width());
		var bh = parseFloat($("body").height());
		
		for (var i = 0; i < setPosition_array.length; i++) {
			
			var arr = setPosition_array[i];
			var obj = arr.obj;
			
			obj.css({
				"position":	"absolute",
				"top":			"auto",
				"bottom":		"auto",
				"left":			"auto",
				"right":		"auto"
			});
			for (c in arr.coord) obj.css(c, arr.coord[c]); // ������ ����������� �� ���� � ��-���� ����������������� ������� �� css �� ������
			
			var pos = getPosition(obj.get(0));
			
			var pos = {
				"left" :		pos.x,
				"right" :		bw - pos.x - parseInt(obj.width()),
				"top" :			pos.y,
				"bottom" :	bh - pos.y - parseInt(obj.height())
			};
			
			//alert(obj.attr("class") + " � " + JSON.stringify(pos));
			
			for (o in arr.offset) if (pos[o] < parseInt(arr.offset[o])) {
				
				// ������� �����! ����� ���������� ������ ������, � ������� � ������ ������
				// ����������������� ������� ������������
				if (o == "left")		obj.css("right",	"auto");
				if (o == "right")		obj.css("left",		"auto");
				if (o == "top")			obj.css("bottom",	"auto");
				if (o == "bottom")	obj.css("top",		"auto");
				
				obj.css(o, arr.offset[o]);
				
				//alert(obj.attr("class") + " � " + o + " : " + pos[o] + " < " + parseInt(arr.offset[o]))
				
			}
			
		}
		
	}
	
	$(document).ready(function() { 
		
		$(".absolute").each(function() {
			
			// ������� �����! ����� ���������� ������ ������, � ������� � ������ ������
			setPosition_array.push({
				"obj": $(this),
				"coord" : {
					"right" :		this.style.right,
					"left" :		this.style.left,
					"bottom" :	this.style.bottom,
					"top" :			this.style.top
				},
				"offset" : {
					"right" :		$(this).css("marginRight"),
					"left" :		$(this).css("marginLeft"),
					"bottom" :	$(this).css("marginBottom"),
					"top" :			$(this).css("marginTop")
				}
			});
			
			$(this).css("margin", "0px");
		
		});
		
		$(window).bind("resize", setPosition_set);
		//setInterval(setPosition_set, 100);
		
		setPosition_set();
		
	});
	