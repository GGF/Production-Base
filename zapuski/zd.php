<?
// управление заделом
require $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php";
authorize(); // вызов авторизации
$processing_type=basename (__FILE__,".php");

if (isset($edit)) 
{
	$sql="SELECT *, customers.id AS cusid, boards.id AS plid FROM zadel JOIN (customers,boards) ON (zadel.board_id=boards.id AND boards.customer_id=customers.id) WHERE zadel.id='$edit'";
	//echo $sql;
	$zd = sql::fetchOne($sql);
		
	$form = new Edit($processing_type);
	$form->init();
	$customers = getCustomers();
	$plates=getPlates($zd[cusid]);
        $fields = array();
        if (!empty($edit)) {
            array_push($fields, array(
                "type" => CMSFORM_TYPE_HIDDEN,
                "name" => "customer_id",
                "value" => $zd["cusid"],
            ));
            array_push($fields, array(
                "type" => CMSFORM_TYPE_HIDDEN,
                "name" => "board_id",
                "value" => $zd["board_id"],
            ));
            array_push($fields, array(
                "type" => CMSFORM_TYPE_TEXT,
                "name" => "customer",
                "label" => "Заказчик:",
                "value" => $zd["customer"],
                "options" => array("html" => " readonly ",),
            ));
            array_push($fields, array(
                "type" => CMSFORM_TYPE_TEXT,
                "name" => "plate",
                "label" => "Плата:",
                "value" => $zd["board_name"],
                "options" => array("html" => " readonly ",),
            ));
        } 
		else 
			{
            array_push($fields,array(
                "type" => CMSFORM_TYPE_SELECT,
                "name" => "customer_id",
                "label" => "Заказчик:",
                "values" => $customers,
                "value" => '',
                "options"	=>	array( "html" => " onchange=\"var plat=$.ajax({url:'http://{$_SERVER['HTTP_HOST']}/zapuski/zd.php',data:'cusid='+$(this).val()+'&selectplates',async:false}).responseText; $('select[plates]').html(plat); \" customer ", ),
            ));
            array_push($fields,array(
                "type" => CMSFORM_TYPE_SELECT,
                "name" => "board_id",
                "label" => "Плата:",
                "values" => array(),
                "value" => '',
                "options" => array("html" => " plates ",),
            ));
        }
            array_push($fields,array(
                "type" => CMSFORM_TYPE_TEXT,
                "name" => "number",
                "label" => 'Количество:',
                "value" => $zd["number"],
            //"options"	=>	array( "html" => "size=10", ),
            ));
            array_push($fields,array(
                "type" => CMSFORM_TYPE_TEXT,
                "name" => "niz",
                "label" => '№ извещения:',
                "value" => $zd["niz"],
            //"options"	=>	array( "html" => "size=10", ),
            ));
            array_push($fields,array(
                "type" => CMSFORM_TYPE_TEXT,
                "name" => "ldate",
                "label" => 'Дата:',
                "value" => date2datepicker($zd[ldate]),
                "options" => array("html" => ' datepicker=1 '),
            ));

        $form->addFields($fields);
	$form->show();
}
elseif (isset($selectplates)) 
{
	getBoards($cusid,true);
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
	$sql="SELECT *,zadel.id AS zid,zadel.id FROM zadel JOIN (boards,customers) ON (zadel.board_id=boards.id AND boards.customer_id=customers.id) ".(isset($find)?" WHERE (board_name LIKE '%$find%' OR customers.customer LIKE '%$find%')":"").(!empty($order)?" ORDER BY ".$order." ":" ORDER BY zadel.id DESC ").(isset($all)?"":"LIMIT 20");
	//print $sql;
	
	$cols = array (
		"№"		=>		"№",
		"zid"	=>		"ID",
		"customer"	=>	"Заказчик",
		"board_name"		=>	"Плата",
		"niz"		=>	"№ изв.",
		"ldate"		=>	"Дата запуска",
		"number"	=>	"Кол-во",
	);

	$table = new SqlTable("zd","zd",$sql,$cols);
	$table->addbutton=true;
	$table->show();
}
?>