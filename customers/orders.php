<?
// управление заказчиками

require $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php";
authorize(); // вызов авторизации
$processing_type=basename (__FILE__,".php");
// serialize form
if (isset(${'form_'.$processing_type})) {extract(${'form_'.$processing_type});
print_r($_FILES);}
ob_start();

// берем из гет используемые переменные? они передаются из скрипта ajax
$edit = $_GET["edit"];
$id = $_GET["id"];
$delete = $_GET["delete"];

if (isset($edit)) 
{
	if (!empty($id))
	{
		$sql="SELECT *,orders.id as order_id FROM orders JOIN customers ON customers.id=customer_id WHERE orders.id='$id'";
		$rs=sql::fetchOne($sql);
		$_SESSION[order_id]=$rs[order_id];
		$_SESSION[order]=$rs[number];
		$_SESSION[orderdate]=$rs[orderdate];
		$_SESSION[customer_id]=$rs[customer_id];
		$_SESSION[customer]=$rs[customer];
		echo "ok<script>selectmenu('tz','');</script>";
	} 
	else
	{
		//if (!isset($accept)) { // эта проверка уже не нужна, так как тут только формирование формы, а обработка в другом месте
			$sql = "SELECT * FROM orders WHERE id='$edit'";
			$ord=sql::fetchOne($sql);
			
			$form = new Edit($processing_type);
			$form->init();
			if(empty($edit) && empty($_SESSION[customer_id]))
			{
				$customers = array();
				$sql="SELECT id,customer FROM customers ORDER BY customer";
				$res=sql::fetchAll($sql);
				foreach($res as $rs) { $customers[$rs[id]] = $rs[customer]; }
				$form->addFields(array(
					array(
						"type"		=> CMSFORM_TYPE_SELECT,
						"name"		=> "customerid",
						"label"		=>	"Заказчик:",
						"values"	=>	$customers,
					),
				));
			}
			else
			{
				$form->addFields(array(
					array(
						"type"		=>	CMSFORM_TYPE_HIDDEN,
						"name"		=>	"customerid",
						"value"		=>	!empty($_SESSION[customer_id])?$_SESSION[customer_id]:$ord["customer_id"],
						"options"	=>	array( "html" => "size=30", ),
					),
				));
			}
			$form->addFields(array(
				array(
					"type"		=> CMSFORM_TYPE_TEXT,
					"name"		=> "orderdate",
					"label"			=>'Дата:',
					"value"		=> date2datepicker($ord[orderdate]),
					"options"		=> array( "html" => ' datepicker=1 '),
					"check"	=>	array("type" => CMSFORM_CHECK_NUMERIC),
					"format" => array("type" =>CMSFORM_FORMAT_CUSTOM, "pregPattern" => "/[0-9][0-9]\.[0-9][0-9]\.[0-9][0-9][0-9][0-9]/"),
					"obligatory" =>	true,
				),
				array(
					"type"		=>	CMSFORM_TYPE_TEXT,
					"name"		=>	"number",
					"label"		=>	"Номер письма:",
					"value"		=>	$ord["number"],
					"options"	=>	array( "html" => "size=30", ),
					"obligatory" =>	true,
				),
				array(
					"type"		=>	CMSFORM_TYPE_FILE,
					"name"		=>	"order_file",
					"label"		=>	"Файл письма:",
					//"value"		=>	"",
					//"options"	=>	array( "html" => "size=30", ),
				),

			));
			$form->show();
		//} else 
		// обработка формы в скрипте ./action/... 
	}
} 
elseif (isset($delete)) 
{
	// удаление
	$sql = "DELETE FROM orders WHERE id='$delete'";
	sql::query($sql);
	// удаление связей
	$sql = "SELECT * FROM tz WHERE order_id='$delete'";
	$res = sql::fetchAll($sql);
	foreach ($res as $rs) 
	{
		// удаление
		$delete = $rs["id"];
		$sql = "DELETE FROM tz WHERE id='$delete'";
		sql::query($sql);
		// удаление связей
		$sql = "SELECT * FROM posintz WHERE tz_id='$delete'";
		$res1 = sql::fetchAll($sql);
		foreach ($res1 as $rs1) 
		{
			$delete = $rs1["id"];
			$sql = "DELETE FROM posintz WHERE id='$delete'";
			sql::query($sql);
		}
	}
	echo "ok";
}
else
{
// вывести таблицу
	if(isset($_GET["all"])) $_SESSION[order_id]='';
	if (empty($_SESSION[customer_id])) 
	{
		$customer = "Выберите заказчика!!!";
		$sql="SELECT *,orders.id FROM orders JOIN customers ON customers.id=customer_id ".(isset($_GET["find"])?"WHERE (number LIKE '%".$_GET["find"]."%' OR orderdate LIKE '%".$_GET["find"]."%' ) ":"").(isset($_GET["order"])?"ORDER BY ".$_GET["order"]." ":"ORDER BY orders.orderdate DESC ").((isset($_GET["all"]))?"LIMIT 50":"LIMIT 20");
		$cols[customer]="Заказчик";
	} 
	else 
	{
		$cusid = $_SESSION[customer_id];
		$customer = $_SESSION[customer];
			
		// sql
		$sql="SELECT * FROM orders ".(isset($_GET["find"])?"WHERE (number LIKE '%".$_GET["find"]."%' OR orderdate LIKE '%".$_GET["find"]."%' ) ":"").(isset($cusid)?(isset($_GET["find"])?"AND customer_id='$cusid'":"WHERE customer_id='$cusid'"):"").(isset($_GET["order"])?"ORDER BY ".$_GET["order"]." ":"ORDER BY orders.orderdate DESC ").((isset($_GET["all"]))?"LIMIT 50":"LIMIT 20");
	}

		$cols[id]="ID";
		$cols[number]="Номер заказа";
		$cols[orderdate]="Дата заказа";

		$table = new SqlTable($processing_type,$processing_type,$sql,$cols);
		$table->title="Заказчик - $customer ";;
		$table->addbutton=true;
		$table->show();

}

printpage();
?>