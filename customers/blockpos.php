<?
/*
* ������� � ����� 
*/

require $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php";
authorize(); // ����� �����������
$processing_type=basename (__FILE__,".php");
// serialize form
if (isset(${'form_'.$processing_type})) extract(${'form_'.$processing_type});

ob_start();
if (isset($edit)) 
{
}
elseif (!empty($delete))
{
}
else
{
	$blockid = $id;
	$sql="SELECT *,blockpos.id FROM blockpos JOIN boards ON boards.id=board_id WHERE blockpos.block_id='$blockid' ".(!empty($find)?"AND (boards.board_name LIKE '%$find%') ":"").(isset($order)?"ORDER BY ".$order." ":"ORDER BY boards.board_name DESC ").((isset($all))?"LIMIT 50":"LIMIT 20");
	
	$cols[board_name]="����������� �����";
	$cols[nib]="� �����";
	$cols[nx]="�� �����";
	$cols[ny]="�� ������";


	//echo $sql;
	$table = new Table($processing_type,$processing_type,$sql,$cols,false);
	$table->addbutton=true;
	$table->show();
}
printpage();
?>