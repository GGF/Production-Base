<?
// ���������� ���������� �����

include_once $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php";
authorize(); // ����� �����������
$processing_type=basename (__FILE__,".php");

ob_start();

if (isset($delete)) 
{
	// ��������
	$sql = "UPDATE conductors SET ts=NOW(), user_id='".$_SESSION[userid]."', ready='1' WHERE id='$delete'";
	sql::query($sql);
	echo "ok";
}
elseif (isset($edit)) 
{
	$sql="SELECT *, customers.id AS cusid, conductors.board_id AS plid FROM conductors JOIN (customers,boards) ON (conductors.board_id=boards.id AND boards.customer_id=customers.id) WHERE conductors.id='$edit'";
	//echo $sql;
	$cond = sql::fetchOne($sql);
		
	$form = new Edit($processing_type);
	$form->init();
	$customer = array();
	$sql="SELECT id,customer FROM customers ORDER BY customer";
	$res=sql::fetchAll($sql);
	foreach($res as $rs) { $customers[$rs[id]] = $rs[customer]; }
	$plates=array();
	$sql="SELECT id,board_name,customer_id FROM boards WHERE customer_id='$cond[cusid]' ORDER BY board_name";
	$res=sql::fetchAll($sql);
	foreach($res as $rs) { $plates[$rs[id]] = $rs[plate]; }

	$fields=array();
	if (!empty($edit)) 
	{
		array_push($fields,array(
							"type"		=> CMSFORM_TYPE_HIDDEN,
							"name"		=> "customer_id",
							"value"		=> $cond["cusid"],
						));
		array_push($fields,array(
							"type"		=> CMSFORM_TYPE_HIDDEN,
							"name"		=> "plate_id",
							"value"		=> $cond["plid"],
						));
		array_push($fields,array(
							"type"		=>	CMSFORM_TYPE_TEXT,
							"name"		=>	"customer",
							"label"		=>	"��������:",
							"value"		=>	$cond["customer"],
							"options"	=>	array( "html" => " readonly ", ),
						));
		array_push($fields,array(
							"type"		=>	CMSFORM_TYPE_TEXT,
							"name"		=>	"plate",
							"label"		=>	"�����:",
							"value"		=>	$cond["plate"],
							"options"	=>	array( "html" => " readonly ", ),
						));
	} 
	else
	{
		array_push($fields,array(
							"type"		=> CMSFORM_TYPE_SELECT,
							"name"		=> "customer_id",
							"label"		=>	"��������:",
							"values"	=>	$customers,
							"value"		=> $cond["cusid"],
							"options"	=>	array( "html" => " onchange=\"var plat=$.ajax({url:'http://".$_SERVER['HTTP_HOST']."/zapuski/zd.php',data:'cusid='+$(this).val()+'&selectplates',async:false}).responseText; $('select[plates]').html(plat);\" ", ),
						));
		array_push($fields,array(
							"type"		=> CMSFORM_TYPE_SELECT,
							"name"		=> "board_id",
							"label"		=>	"�����:",
							"values"	=>	$plates,
							"value"		=> $cond["plid"],
							"options"	=>	array( "html" => " plates ", ),
						));
	}
	array_push($fields,array(
							"type"		=>	CMSFORM_TYPE_TEXT,
							"name"		=>	"pib",
							"label"		=>	"���� � �����",
							"value"		=>	$cond["pib"],
							//"options"	=>	array( "html" => "size=10", ),
						));
	array_push($fields,array(
							"type"		=> CMSFORM_TYPE_TEXT,
							"name"		=> "lays",
							"label"		=>	"�������",
							"value"		=> $cond["lays"],
							//"options"	=>	array( "html" => "size=10", ),
						));
	array_push($fields,array(
							"type"		=> CMSFORM_TYPE_SELECT,
							"name"		=> "side",
							"label"		=>	"�������:",
							"values"	=>	array(
													"TOP"	=>	"TOP",
													"BOT"	=>	"BOT",
													"TOPBOT"	=>	"TOPBOT",
												),
							"value"		=> $cond["side"],
							//"options"	=>	array( "html" => " side ", ),
						));

	$form->addFields($fields);
	$form->show();
}
elseif (isset($print)) 
{
	
}
else
{
// ������� �������

	// sql
	$sql="SELECT *,conductors.id AS condid,conductors.id FROM conductors JOIN (boards,customers) ON (conductors.board_id=boards.id AND boards.customer_id=customers.id ) WHERE ready='0' ".(isset($find)?"AND (boards.board_name LIKE '%$find%')":"").(!empty($order)?" ORDER BY ".$order." ":" ORDER BY conductors.id DESC ").(isset($all)?"":"LIMIT 20");

	//echo $sql;
	
	$cols[condid]="ID";
	$cols[customer]="��������";
	$cols[board_name]="�����";
	$cols[side]="�������";
	$cols[lays]="�������";
	$cols[pib]="���� � �����";


	$table = new SqlTable($processing_type,$processing_type,$sql,$cols);
	$table->addbutton=true;
	$table->show();

}

printpage();

?>