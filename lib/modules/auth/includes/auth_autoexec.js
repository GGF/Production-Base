// AUTH
// Функции для использования класс авторизации

function showuserswin() {
	if ($.cookie('adminhere')==2){
		$('#userswin').toggle();
		if ($('#userswin').is(':visible')) {
			var html=$.ajax({url:'/lib/modules/auth/userswin.php',data:'',async: false}).responseText;
			$('#userswin').html(html).fadeTo(0,0.8);
		}
	}
}

$(document).ready(function(){
	var html="<div class=sun id=sun><img onclick=showuserswin() title='Admin здесь' src=/picture/sun.gif><div id=userswin class=sun style='display:none'>&nbsp;</div></div>";
	$('body').prepend(html);
	if ($.cookie('adminhere')>0) {
		$("#sun").show();
		$("div:visible").fadeTo(0,0.95);
	}
});
