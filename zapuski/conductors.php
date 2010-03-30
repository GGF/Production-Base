<?
// Отображает запущенные платы

include_once $_SERVER["DOCUMENT_ROOT"]."/lib/sql.php";
authorize(); // вызов авторизации
$processing_type=basename (__FILE__,".php");

ob_start();

if (isset($delete)) 
{
	// удаление
	$sql = "UPDATE conductor-s SET ts=NOW(), user_id='".$_SESSION[userid]."', ready='1' WHERE id='$delete'";
	sql::query($sql);
	echo "ok";
}
elseif (isset($edit)|| isset(${'form_'.$processing_type})) 
{
	// serialize form
	if(!empty(${'form_'.$processing_type})){
		serializeform(${'form_'.$processing_type});
	}
	
	if (!isset($accept) ) {
		$sql="SELECT *, customers.id AS cusid, conductors.board_id AS plid FROM conductors JOIN (customers,plates) ON (conductors.board_id=plates.id AND plates.customer_id=customers.id) WHERE conductors.id='$edit'";
		//echo $sql;
		$cond = sql::fetchOne($sql);
			
		$form = new Edit($processing_type);
		$form->init();
		$customer = array();
		$sql="SELECT id,customer FROM customers ORDER BY customer";
		$res=sql::fetchAll($sql);
		foreach($res as $rs) { $customers[$rs[id]] = $rs[customer]; }
		$plates=array();
		$sql="SELECT id,plate,customer_id FROM plates WHERE customer_id='$cond[cusid]' ORDER BY plate";
		$res=sql::fetchAll($sql);
		foreach($res as $rs) { $plates[$rs[id]] = $rs[plate]; }

		$form->addFields(array(
			(!empty($edit)?
			array(
				"type"		=>	CMSFORM_TYPE_TEXT,
				"name"		=>	"customer",
				"label"		=>	"Заказчик:",
				"value"		=>	$cond["customer"],
				"options"	=>	array( "html" => " readonly ", ),
			)
			:
			array(
				"type"		=> CMSFORM_TYPE_SELECT,
				"name"		=> "customer_id",
				"label"		=>	"Заказчик:",
				"values"	=>	$customers,
				"value"		=> $cond["cusid"],
				"options"	=>	array( "html" => " onchange=\"var plat=$.ajax({url:'http://".$_SERVER['HTTP_HOST']."/zapuski/zd.php',data:'cusid='+$(this).val()+'&selectplates',async:false}).responseText; $('select[plates]').html(plat);\" ", ),
			)
			),
			(!empty($edit)?
			array(
				"type"		=>	CMSFORM_TYPE_TEXT,
				"name"		=>	"plate",
				"label"		=>	"Плата:",
				"value"		=>	$cond["plate"],
				"options"	=>	array( "html" => " readonly ", ),
			)
			:
			array(
				"type"		=> CMSFORM_TYPE_SELECT,
				"name"		=> "plate_id",
				"label"		=>	"Плата:",
				"values"	=>	$plates,
				"value"		=> $cond["plid"],
				"options"	=>	array( "html" => " plates ", ),
			)
			),
			array(
				"type"		=>	CMSFORM_TYPE_TEXT,
				"name"		=>	"pib",
				"label"		=>	"Плат в блоке",
				"value"		=>	$cond["pib"],
				//"options"	=>	array( "html" => "size=10", ),
			),
			array(
				"type"		=> CMSFORM_TYPE_TEXT,
				"name"		=> "lays",
				"label"		=>	"Пластин",
				"value"		=> $cond["lays"],
				//"options"	=>	array( "html" => "size=10", ),
			),
			array(
				"type"		=> CMSFORM_TYPE_SELECT,
				"name"		=> "side",
				"label"		=>	"Сторона:",
				"values"	=>	array(
										"TOP"	=>	"TOP",
										"BOT"	=>	"BOT",
										"TOPBOT"	=>	"TOPBOT",
									),
				"value"		=> $cond["side"],
				//"options"	=>	array( "html" => " side ", ),
			),
			(!empty($edit)?
			array(
				"type"		=> CMSFORM_TYPE_HIDDEN,
				"name"		=> "customer_id",
				"value"		=> $cond["cusid"],
			):null),
			(!empty($edit)?
			array(
				"type"		=> CMSFORM_TYPE_HIDDEN,
				"name"		=> "plate_id",
				"value"		=> $cond["plid"],
			):null),
		));
		$form->show();
	} 
	else 
	{
		// сохранение
		if (!empty($edit)) {
			$sql = "UPDATE conductors SET board_id='$plate_id', pib='$pib', side='$side', lays='$lays', user_id='".$_SERVER[userid]."', ts=NOW() WHERE id='$edit'";
			
		} else {
			$sql = "INSERT INTO conductors (board_id,pib,side,lays,user_id,ts) VALUES('$plate_id','$pib','$side','$lays','".$_SERVER[userid]."',NOW())";
		}
		sql::query($sql);
		echo "ok";
	}

}
elseif (isset($print)) {
	
}
else
{
// вывести таблицу

	// sql
	$sql="SELECT *,conductors.id AS condid FROM conductors JOIN (plates,customers) ON (conductors.board_id=plates.id AND plates.customer_id=customers.id ) WHERE ready='0' ".(isset($find)?"AND (plates.plate LIKE '%$find%')":"").(!empty($order)?" ORDER BY ".$order." ":" ORDER BY conductors.id DESC ").(isset($all)?"":"LIMIT 20");

	//echo $sql;
	
	$cols[condid]="ID";
	$cols[customer]="Заказчик";
	$cols[plate]="Плата";
	$cols[side]="Сторона";
	$cols[lays]="Пластин";
	$cols[pib]="Плат в блоке";


	$table = new Table($processing_type,$processing_type,$sql,$cols);
	$table->addbutton=true;
	$table->show();

}

printpage();

?>