/*
 * EDITWINDOW
 * функции для окна редактирования
 * Закоментирую, потому как переписал на jquery.dialog
 * 
 */

/*
function closeedit() {
	$('#editdivin').html('');
	$('#editdiv').hide();
}


$(document).ready(function(){
	var html;
	html="<div class='editdiv' style='display:none' id=editdiv><img src=/picture/s_error2.png class='rigthtop' onclick='closeedit()'><div class='editdivin' id='editdivin'></div></div>";
	$("body").append(html);
	$('#editdiv').bind('EditShow',function() {
		$(this).show();
		var mywidth = ($('body').width() - $(this).width())/2;
		var myheight = ($('body').height() - $(this).height())/2-70;
		$(this).css({'top': myheight}).css({'left': mywidth});
	});
});

*/