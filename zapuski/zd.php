<?
// управление заделом
require $_SERVER["DOCUMENT_ROOT"]."/lib/sql.php";
authorize(); // вызов авторизации
$processing_type=basename (__FILE__,".php");

if (isset($edit) || isset(${'form_'.$processing_type})) 
{
	// serialize form
	if(!empty(${'form_'.$processing_type})){
		serializeform(${'form_'.$processing_type});
	}
	
	if (!isset($accept) ) 
	{
		$sql="SELECT *, customers.id AS cusid, plates.id AS plid FROM zadel JOIN (customers,plates) ON (zadel.board_id=plates.id AND plates.customer_id=customers.id) WHERE zadel.id='$edit'";
		//echo $sql;
		$zd = sql::fetchOne($sql);
			
		$form = new Edit($processing_type);
		$form->init();
		$customer = array();
		$sql="SELECT id,customer FROM customers ORDER BY customer";
		$res=sql::fetchAll($sql);
		foreach($res as $rs) { $customers[$rs[id]] = $rs[customer]; }
		$plates=array();
		$sql="SELECT id,plate,customer_id FROM plates WHERE customer_id='$zd[cusid]' ORDER BY plate";
		$res=sql::fetchAll($sql);
		foreach($res as $rs) { $plates[$rs[id]] = $rs[plate]; }

		$form->addFields(array(
			array(
				"type"		=> CMSFORM_TYPE_SELECT,
				"name"		=> "customer_id",
				"label"		=>	"Заказчик:",
				"values"	=>	$customers,
				"value"		=> $zd["cusid"],
				"options"	=>	array( "html" => " onchange=\"var plat=$.ajax({url:'http://".$_SERVER['HTTP_HOST']."/zapuski/zd.php',data:'cusid='+$(this).val()+'&selectplates',async:false}).responseText; $('select[plates]').html(plat);\" ", ),
			),
			array(
				"type"		=> CMSFORM_TYPE_SELECT,
				"name"		=> "plate_id",
				"label"		=>	"Плата:",
				"values"	=>	$plates,
				"value"		=> $zd["plid"],
				"options"	=>	array( "html" => " plates ", ),
			),
			array(
				"type"		=>	CMSFORM_TYPE_TEXT,
				"name"		=>	"number",
				"label"		=>	'Количество:',
				"value"		=>	$zd["number"],
				//"options"	=>	array( "html" => "size=10", ),
			),
			array(
				"type"		=> CMSFORM_TYPE_TEXT,
				"name"		=> "niz",
				"label"			=>'№ извещения:',
				"value"		=> $zd["niz"],
				//"options"	=>	array( "html" => "size=10", ),
			),
			array(
				"type"		=> CMSFORM_TYPE_TEXT,
				"name"		=> "ldate",
				"label"			=>'Дата:',
				"value"		=> date2datepicker($zd[ldate]),
				"options"		=> array( "html" => ' datepicker=1 '),
			),
		));
		$form->show();
	} 
	else 
	{
		// сохранение
		$ldate=datepicker2date($ldate);
		if (!empty($edit)) {
			$sql = "UPDATE zadel SET number = '$number', ldate='$ldate', board_id='$plate_id', niz='$niz' WHERE id='$edit'";
			
		} else {
			$sql = "INSERT INTO zadel (board_id,ldate,number,niz) VALUES('$plate_id','$ldate','$number','$niz')";
		}
		sql::query($sql);
		sql::error(true);
		echo "ok";
	}
}
elseif (isset($selectplates)) 
{
	$sql = "SELECT * FROM plates WHERE customer_id='$cusid' ORDER BY plate ";
	//echo $sql;
	foreach (sql::fetchAll($sql) as $rs) {
		echo "<option value=".$rs["id"].">".$rs["plate"];
	}
} 
elseif (isset($delete)) 
{
	$sql = "DELETE FROM zadel WHERE id='".$delete."'";
	sql::query($sql);
	sql::error(true);
	echo "ok";
} 
else 
{
	$sql="SELECT *,zadel.id AS zid,zadel.id FROM zadel JOIN (plates,customers) ON (zadel.board_id=plates.id AND plates.customer_id=customers.id) ".(isset($find)?"AND (plates.plate LIKE '%$find%' OR customers.customer LIKE '%$find%')":"").(!empty($order)?" ORDER BY ".$order." ":" ORDER BY zadel.id DESC ").(isset($all)?"":"LIMIT 20");
	//print $sql;
	
	$cols = array (
		"№"		=>		"№",
		"zid"	=>		"ID",
		"customer"	=>	"Заказчик",
		"plate"		=>	"Плата",
		"niz"		=>	"№ изв.",
		"ldate"		=>	"Дата запуска",
		"number"	=>	"Кол-во",
	);

	$table = new Table("zd","zd",$sql,$cols);
	$table->addbutton=true;
	$table->show();
}
?>