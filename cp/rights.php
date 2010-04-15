<?
// управление правами доступа

require $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php";
authorize(); // вызов авторизации
$processing_type=basename (__FILE__,".php");
// serialize form
if (isset(${'form_'.$processing_type})) extract(${'form_'.$processing_type});
ob_start();

if (isset($edit)) 
{
	// todo: d массив должно преобразовать чеквоксы
	if (!isset($accept)) {
		$sql = "SELECT * FROM rights WHERE id='".$edit."'";
		$rs=sql::fetchOne($sql);
		$uid = !empty($uid)?$uid:$rs["u_id"];
		
		$form = new Edit($processing_type);
		$form->init();

		$sql="SELECT * FROM rtypes";
		$res=sql::fetchAll($sql);
		$sql = "SELECT * FROM rrtypes";
		$res1=sql::fetchAll($sql);
		foreach($res as $rs) 
		{
			$label = sprintf("<span id='rrr' rtype='".$rs["type"]."'>[%-10s]</span>:",$rs["type"]);
			$name = "r|".$rs["id"]."";//sprintf("[%-10s]:",$rs["type"]);
			//echo $name."<br>";
			foreach($res1 as $rs1) 
			{
				$sql="SELECT * FROM rights WHERE type_id='".$rs["id"]."' AND u_id='$uid' AND rtype_id='".$rs1["id"]."'";
				$rs2=sql::fetchOne($sql);
				//echo $rs1["rtype"]."-<input type=checkbox name=r[".$rs["id"]."][".$rs1["id"]."] ".($rs2["right"]=='1'?"checked":"").">";
				$value[$rs1["id"]]=($rs2["right"]==1?1:0);
				$values[$rs1["id"]]='-';
			}
			//print_r($value);
				$form->addFields(array(
					array(
						"type"		=>	CMSFORM_TYPE_CHECKBOXES,
						"name"		=>	$name,
						"label"		=>	$label,
						"value"		=>	$value,
						"values"	=>	$values,
						"options"	=>	array( "nobr"=>true, "html" => " rtype=".$rs["type"]." " ),
					),
				));
				unset($values);unset($value);
		}
		$form->addFields(array(
			array(
				"type"		=>	CMSFORM_TYPE_HIDDEN,
				"name"		=>	"userid",
				"value"		=>	$uid,
			),
		));
		$form->show();
		echo "<script>\$('#rrr').live('click',function(){\$(':checkbox[rtype='+\$(this).attr('rtype')+']').attr('checked',true);});</script>";
		echo "<script>\$('#rrr').live('dblclick',function(){\$(':checkbox[rtype='+\$(this).attr('rtype')+']').attr('checked',false);});</script>";
	}
	else 
	{
		// сохрнение
		$sql="DELETE FROM rights WHERE u_id='$userid'";
		sql::query($sql);
		// сложный случай чекбоксов
		array_walk(${'form_'.$processing_type},'checkbox2array');
		if (!empty($r)) {
			foreach ($r as $key=>$val) {
				foreach($val as $k=>$V) {
					$sql="INSERT INTO rights (u_id,type_id,rtype_id,rights.right) VALUES ('$userid','$key','$k','1')";
					sql::query($sql);
				}
			}
		}
		echo "ok";
	}

} elseif (isset($delete)) {
	// удаление
	$sql = "DELETE FROM rights WHERE id='$delete'";
	sql::query($sql) or die(sql::error(true));
	echo "ok";
}
else
{
// вывести таблицу
	if (isset($id)) $uid=$id;
	// sql
	$sql="SELECT *,rights.id AS rid,rights.right AS enable,rights.id FROM rights JOIN (users,rtypes,rrtypes) ON (users.id=rights.u_id AND rtypes.id=rights.type_id AND rrtypes.id=rights.rtype_id) ".(isset($find)?"WHERE (type LIKE '%$find%' OR rtype LIKE '%$find%') ":"").(isset($uid)?(isset($find)?"AND u_id='$uid' ":"WHERE u_id='$uid' "):"").(isset($order)?"ORDER BY ".$order." ":"ORDER BY type ").(isset($all)?"":"LIMIT 20");
	//echo $sql;

	$cols[rid]="ID";
	$cols[type]="tables";
	$cols[rtype]="right";
	$cols[enable]="on/off";

	$table = new Table($processing_type,$_SERVER[tableaction][$processing_type][next],$sql,$cols);
	if (isset($uid)) $table->idstr = "&uid=$uid";
	$table->addbutton=true;
	$table->show();
}

printpage();
?>