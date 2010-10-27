// MENU
// Функции для использования классом меню

function selectmenu(type,data)
{
	//alert(111);
	if (data=='') {
		$('.menuitemcp').removeClass('menuitemsel');;
		$('#'+type).addClass('menuitemsel');;
		var html=$.ajax({url:type+'.php',data:data,async: false}).responseText;
		//alert(html);
		$('#maindiv').html(html);
		//closeedit();
	} else {
		window.location = data;
	}
}

