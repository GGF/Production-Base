// функции 

// используется в positionintz
function find(url,data,div)
{
	var html=$.ajax({url:url,data:data,async: false}).responseText;
	$('#'+div).html(html);
}

// используется в positionintz
function activatetz()
{
	$('#plateedit').hide();
	$('#blockedit').hide();
	$('#position').droppable({
					drop: function(event,ui) {
						//alert(ui.draggable.attr('plateid'));
						var html=$('#tposition').html();
						var ahtml = "<tr id='"+ui.draggable.attr('trid')+"'><td>"+ui.draggable.attr('plate')+"</td><td><input type=text value='0' size=8 name='numbers["+ui.draggable.attr('plateid')+"]'></td><td><a href=javascript:my_delete('','"+ui.draggable.attr('trid')+"','"+ui.draggable.attr('plate')+"')><img src=/picture/b_drop.png></a></td></tr>";
						//alert(ahtml);
						$('#tposition').html(html+ahtml);
						ui.draggable.remove();
					}
					});
}

// используется в positionintz
function editblock(id,cusid)
{
	var html;
	if (id==0) {
		//alert(cusid);
		html=$.ajax({url:'posintz.php',data:'editblock=0&cusid='+cusid,async: false}).responseText;
	} else {
		html=$.ajax({url:'posintz.php',data:'editblock='+id,async: false}).responseText;
	}
	//alert(id+'  '+cusid);
	$('#blockeditin').html(html);
	$('#blockedit').show();
	var mywidth = ($('body').width() - $('#blockedit').width())/2;
	var myheight = ($('body').height() - $('#blockedit').height())/2-70;
	$('#blockedit').css({'top': myheight});
	$('#blockedit').css({'left': mywidth});
	$('#blocks').hide();
}

function closeeditblock() {
	$('#blockeditin').html('');
	$('#blockedit').hide();
	$('#plateeditin').html('');
	$('#plateedit').hide();
	$('#blocks').show();
}

// для открытия заказчика в новой таблице
function opencustr(cusid,trid)
{
	var html = $.ajax({	url: "orders.php?notop&cusid="+cusid, async: false}).responseText;
	$('#maindiv').html(html);
}
