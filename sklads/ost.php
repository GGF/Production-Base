<?
$db = '`zaomppsklads`.';
include_once $_SERVER["DOCUMENT_ROOT"]."/lib/sql.php";
authorize();
$sklad = $_COOKIE["sklad"];
$processing_type=basename (__FILE__,".php");;


if (isset($delete))
{
	$sql = "INSERT INTO ".$db."sk_arc_".$sklad."_spr (nazv,edizm,krost) SELECT sk_".$sklad."_spr.nazv,sk_".$sklad."_spr.edizm,sk_".$sklad."_spr.krost FROM ".$db."sk_".$sklad."_spr WHERE sk_".$sklad."_spr.id='$delete'";
	sql::query($sql);
	sql::error(true); 
	$id = sql::lastId();
	$sql = "DELETE FROM ".$db."sk_".$sklad."_spr WHERE id='$delete'";
	sql::query($sql);
	sql::error(true); 
	$sql = "INSERT INTO ".$db."sk_arc_".$sklad."_ost (spr_id,ost) SELECT $id,sk_".$sklad."_ost.ost FROM sk_".$sklad."_ost WHERE sk_".$sklad."_ost.spr_id='$delete'";
	sql::query($sql);
	sql::error(true); 
	$sql = "DELETE FROM ".$db."sk_".$sklad."_ost WHERE spr_id='$delete'";
	sql::query($sql);
	sql::error(true); 
	$sql = "INSERT INTO ".$db."sk_arc_".$sklad."_dvizh (type,numd,numdf,docyr,spr_id,quant,ddate,post_id,comment_id,price) SELECT sk_".$sklad."_dvizh.type,sk_".$sklad."_dvizh.numd,sk_".$sklad."_dvizh.numdf,sk_".$sklad."_dvizh.docyr,$id,sk_".$sklad."_dvizh.quant,sk_".$sklad."_dvizh.ddate,sk_".$sklad."_dvizh.post_id,sk_".$sklad."_dvizh.comment_id,sk_".$sklad."_dvizh.price FROM sk_".$sklad."_dvizh WHERE sk_".$sklad."_dvizh.spr_id='$delete'";
	sql::query($sql);
	sql::error(true); 
	$sql = "DELETE FROM ".$db."sk_".$sklad."_dvizh WHERE spr_id='$delete'";
	sql::query($sql);
	sql::error(true); 
	$sql = "INSERT INTO ".$db."sk_arc_".$sklad."_dvizh (type,numd,numdf,docyr,spr_id,quant,ddate,post_id,comment_id,price) SELECT sk_".$sklad."_dvizh_arc.type,sk_".$sklad."_dvizh_arc.numd,sk_".$sklad."_dvizh_arc.numdf,sk_".$sklad."_dvizh_arc.docyr,$id,sk_".$sklad."_dvizh_arc.quant,sk_".$sklad."_dvizh_arc.ddate,sk_".$sklad."_dvizh_arc.post_id,sk_".$sklad."_dvizh_arc.comment_id,sk_".$sklad."_dvizh_arc.price FROM sk_".$sklad."_dvizh_arc WHERE sk_".$sklad."_dvizh_arc.spr_id='$delete'";
	sql::query($sql);
	sql::error(true); 
	$sql = "DELETE FROM ".$db."sk_".$sklad."_dvizh_arc WHERE spr_id='$delete'";
	sql::query($sql);
	sql::error(true); 
	echo "ok";
} 
 elseif (isset($edit) || isset(${'form_'.$processing_type})) 
{
	// serialize form
	if(!empty(${'form_'.$processing_type})){
		//print_r(${'form_'.$processing_type});
		foreach(${'form_'.$processing_type} as $key => $val) {
			if (mb_detect_encoding($val)=="UTF-8") 
				${$key}=mb_convert_encoding($val,"cp1251","UTF-8");
			else 
				${$key}=$val;
		}
	}
	
	if (isset($accept)) 
	{
		// отредактировано
		$fields = array("nazv"=>$nazv, "edizm"=>$edizm, "krost"=>$krost);
		
		if (empty($edit)) {
			//добавление нового
			if (sql::insert('zaomppsklads`.`sk_'.$sklad.'_spr',$fields)>0) {
				$spr_id = sql::lastId();
				sql::insert("zaomppsklads`.`sk_".$sklad."_ost",array("spr_id"=>$spr_id,"ost"=>"0"));
			}
		} else {
			sql::update('zaomppsklads`.`sk_'.$sklad.'_spr',"id='$edit'",$fields);
		}
		sql::error(true);
		echo "ok";
	} 
	else 
	{
		$sql="SELECT * FROM ".$db."sk_".$sklad."_spr WHERE id='".$edit."'";
		$rs = sql::fetchOne($sql);
		$form = new Edit($processing_type);
		$form->init();
		$form->addFields(array(
			array(
				"type"		=> CMSFORM_TYPE_TEXT,
				"name"		=> "nazv",
				"label"			=>'Наименование:',
				"value"		=> $rs["nazv"],
				"options"	=>	array( "html" => "size=70", ),
			),
			array(
				"type"		=> CMSFORM_TYPE_TEXT,
				"name"		=> "edizm",
				"label"			=>'Единица измерения:',
				"value"		=> $rs["edizm"],
				"options"	=>	array( "html" => "size=10", ),
			),
			array(
				"type"		=> CMSFORM_TYPE_TEXT,
				"name"		=> "krost",
				"label"			=>'Критический остаток:',
				"value"		=> $rs["krost"],
				"options"	=>	array( "html" => "size=10", ),
			),
				));
		$form->show();
	}
} else
{
// вывести таблицу

	$sql="SELECT *,if((krost>ost),'<span style=\'color:red\'><b>мало</b></span>','') as malo,sk_".$sklad."_spr.id FROM ".$db."sk_".$sklad."_spr JOIN ".$db."sk_".$sklad."_ost ON sk_".$sklad."_ost.spr_id=sk_".$sklad."_spr.id WHERE nazv!='' ".(isset($find)?"AND nazv LIKE '%$find%' ":"").(!empty($order)?"ORDER BY ".$order." ":"ORDER BY nazv ").(isset($all)?"":"LIMIT 20");
	//echo $sql;
	
	$cols[nazv]="Название";
	$cols[edizm]="Ед.Изм.";
	$cols[ost]="Остаток на складе";
	$cols[krost]="Крит. кол-во";
	$cols[malo]="Внимание";

	
	$table = new Table($processing_type,"dvizh",$sql,$cols,false);
	$table->addbutton=true;
	$table->show();
}

?>