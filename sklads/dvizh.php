<?
$db = '`zaomppsklads`.';
require $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php";
authorize();
$sklad = $_COOKIE["sklad"];
$processing_type=basename (__FILE__,".php");;
// serialize form
if (isset(${'form_'.$processing_type})) extract(${'form_'.$processing_type});

ob_start();

if(isset($id)) $spr_id=$id;

if (isset($delete)) 
{
	$sql = "SELECT * FROM ".$db."sk_".$sklad."_dvizh WHERE id='$delete'";
	$rs=sql::fetchOne($sql);
	$sql = "UPDATE ".$db."sk_".$sklad."_ost SET ost=ost".($rs["type"]?"-":"+").abs($rs["quant"])." WHERE spr_id='".$rs["spr_id"]."'";
	sql::query($sql);
	sql::error(true);
	$sql = "DELETE FROM ".$db."sk_".$sklad."_dvizh WHERE id='$delete'";
	sql::query($sql);
	sql::error(true);
	echo "ok";
} 
elseif (isset($edit)) 
{
	if (isset($accept)) 
	{
		// отредактировано
		// найдем поставщика
		if (!empty($supply_id)) 
		{
			$post_id = $supply_id;
		} 
		else 
		{
			$sql="SELECT id FROM ".$db."sk_".$sklad."_postav WHERE supply='$supply'";
			if ($rs=sql::fetchOne($sql)){
				$post_id = $rs;
			} 
			else 
			{
				$sql="INSERT INTO ".$db."sk_".$sklad."_postav (supply) VALUES ('$supply')";
				sql::query($sql);
				if (sql::error(true)!=null) exit;
				$post_id = sql::lastId();
			}
		}			
		// Определим идентификатор коментария
		$sql="SELECT id FROM ".$db."coments WHERE comment='$comment'";
		if ($rs=sql::fetchOne($sql)){
			$comment_id = $rs["id"];
		} else {
			$sql="INSERT INTO ".$db."coments (comment) VALUES ('$comment')";
			sql::query($sql);
			sql::error();
			$comment_id = sql::lastId();
		}
		list($numdf,$numyr)=explode("/",$numd);
		if (empty($edit)) {
			//добавление нового
			$ddate = date("Y-m-d",mktime(0,0,0,substr($ddate,3,2),substr($ddate,0,2),substr($ddate,6,4)));//$dyear."-".$dmonth."-".$dday;
			$sql="INSERT INTO ".$db."sk_".$sklad."_dvizh (type,numd,numdf,docyr,spr_id,quant,ddate,post_id,comment_id,price) VALUES ('$type','$numd','$numdf','$numyr','$spr_id','$quant','$ddate','$post_id','$comment_id','$price')" ;
			sql::query($sql);
			sql::error(true);
			$sql = "UPDATE ".$db."sk_".$sklad."_ost SET ost=ost".($type?"+":"-").abs($quant)." WHERE spr_id='$spr_id'";
			sql::query($sql);
			sql::error(true);
		} 
		else 
		{
			// удалить  старое движенеи
			$sql = "SELECT * FROM ".$db."sk_".$sklad."_dvizh WHERE id='$edit'";
			$rs=sql::fetchOne($sql);
			$sql = "UPDATE ".$db."sk_".$sklad."_ost SET ost=ost".($rs["type"]?"-":"+").abs($rs["quant"])." WHERE spr_id='".$rs["spr_id"]."'";
			sql::query($sql);
			sql::error(true);
			$ddate = date("Y-m-d",mktime(0,0,0,substr($ddate,3,2),substr($ddate,0,2),substr($ddate,6,4)));//$dyear."-".$dmonth."-".$dday;
			$sql="UPDATE ".$db."sk_".$sklad."_dvizh SET type='$type',numd='$numd',numdf='$numdf',docyr='$numyr',spr_id='".$rs["spr_id"]."',quant='$quant',ddate='$ddate',post_id='$post_id',comment_id='$comment_id',price='$price' WHERE id='$edit'" ;
			sql::query($sql);
			sql::error(true);
			$sql = "UPDATE ".$db."sk_".$sklad."_ost SET ost=ost".($type?"+":"-").abs($quant)." WHERE spr_id='".$rs["spr_id"]."'";
			sql::query($sql);
			sql::error(true);
		}
		echo "ok";
	} 
	else 
	{
		// поставщики список для выбора
		$supply["0"] = "Новый";
		$sql="SELECT * FROM ".$db."sk_".$sklad."_postav";
		$res = sql::fetchAll($sql);
		foreach($res as $rs) {
			$supply[$rs["id"]] = $rs["supply"];
		}

		$sql="SELECT *,sk_".$sklad."_dvizh.id,sk_".$sklad."_postav.id as supply_id FROM ".$db."sk_".$sklad."_dvizh JOIN (".$db."sk_".$sklad."_postav,".$db."coments,".$db."sk_".$sklad."_spr) ON (sk_".$sklad."_postav.id=sk_".$sklad."_dvizh.post_id AND coments.id=sk_".$sklad."_dvizh.comment_id AND ".$db."sk_".$sklad."_spr.id=".$db."sk_".$sklad."_dvizh.spr_id) WHERE sk_".$sklad."_dvizh.id='$edit'";
		//echo $sql;
		$rs=sql::fetchOne($sql);
		//print_r($rs);
		$date=(!empty($edit)?date("d.m.Y",mktime(0,0,0,ceil(substr($rs["ddate"],5,2)),ceil(substr($rs["ddate"],8,2)),ceil(substr($rs["ddate"],1,4)))):date("d.m.Y"));
		
		$form = new Edit('dvizh');
		$form->init();
		$form->addFields(array(
			array(
				"type"		=> CMSFORM_TYPE_HIDDEN,
				"name"		=> "spr_id",
				"value"		=> empty($spr_id)?$rs["spr_id"]:$spr_id,
			),
			array(
				"type"		=> CMSFORM_TYPE_TEXT,
				"name"		=> "ddate",
				"label"			=>'Дата:',
				"value"		=> $date,
				"options"		=> array( "html" => ' datepicker=1 '),
			),
			array(
				"type"	=> CMSFORM_TYPE_SELECT,
				"name"	=> "type",
				"label"	=>	"Тип документа:",
				"values"	=>	array(
									"1"	=>	"Приход",
									"0"	=>	"Расход",
								),
				"value"		=> $rs["type"],
			),
			array(
				"type"		=> CMSFORM_TYPE_TEXT,
				"name"		=> "numd",
				"label"			=>'Номер документа:',
				"value"		=> $rs["numd"],
			),
			array(
				"type"		=> CMSFORM_TYPE_TEXT,
				"name"		=> "quant",
				"label"			=>'Количество:',
				"value"		=> $rs["quant"],
			),
			array(
				"type"	=> CMSFORM_TYPE_SELECT,
				"name"	=> "supply_id",
				"label"	=>	"Поставщик:",
				"values"	=>	$supply,
				"value"		=> $rs["supply_id"],
			),
			array(
				"type"		=> CMSFORM_TYPE_TEXT,
				"name"		=> "supply",
				"label"			=>'Новый:',
				"value"		=> "",
			),
			array(
				"type"		=> CMSFORM_TYPE_TEXT,
				"name"		=> "price",
				"label"			=>'Стоимость:',
				"value"		=> $rs["price"],
			),
			array(
				"type"		=>	CMSFORM_TYPE_TEXTAREA,
				"name"		=>	"comment",
				"label"		=>	'Комментарий:',
				"value"		=>	$rs["comment"],
				//"options"	=>	array( "html" => "size=70", ),
			),
			));

		$form->show();

		if (!empty($edit) & $rs["type"]==0) {
			include "trebform.php";
		}
	}
} 
else 
{

	if (isset($all)) {
		$sql="(SELECT *,if(type='1','Приход','Расход') AS prras, sk_".$sklad."_dvizh.id FROM ".$db."sk_".$sklad."_dvizh JOIN (".$db."sk_".$sklad."_postav,".$db."coments) ON (sk_".$sklad."_postav.id=sk_".$sklad."_dvizh.post_id AND coments.id=sk_".$sklad."_dvizh.comment_id) WHERE spr_id='$spr_id' ".(isset($find)?"AND comment LIKE '%$find%' OR supply LIKE '%$find%' OR numd LIKE '%$find%' ":"").") UNION (SELECT *,if(type='1','Приход','Расход') AS prras, sk_".$sklad."_dvizh_arc.id FROM ".$db."sk_".$sklad."_dvizh_arc JOIN (".$db."sk_".$sklad."_postav,".$db."coments) ON (sk_".$sklad."_postav.id=sk_".$sklad."_dvizh_arc.post_id AND coments.id=sk_".$sklad."_dvizh_arc.comment_id) WHERE spr_id='$spr_id' ".(isset($find)?"AND comment LIKE '%$find%' OR supply LIKE '%$find%' OR numd LIKE '%$find%' ":"").") ".(!empty($order)?"ORDER BY ".$order." ":"ORDER BY ddate ");
	} else {
		$sql="SELECT *,if(type='1','Приход','Расход') AS prras, sk_".$sklad."_dvizh.id FROM ".$db."sk_".$sklad."_dvizh JOIN (".$db."sk_".$sklad."_postav,".$db."coments) ON (sk_".$sklad."_postav.id=sk_".$sklad."_dvizh.post_id AND coments.id=sk_".$sklad."_dvizh.comment_id) WHERE spr_id='$spr_id' ".(isset($find)?"AND comment LIKE '%$find%' OR supply LIKE '%$find%' OR numd LIKE '%$find%' ":"").(!empty($order)?"ORDER BY ".$order." ":"ORDER BY ddate ");
	}
	//echo $sql;

	$cols[ddate]="Дата";
	$cols[prras]="+/-";
	$cols[numd]="№ док.";
	$cols[supply]="Поставщик";
	$cols[quant]="Кол-во";
	$cols[comment]="Примечание";
	$cols[price]="Цена";

	
	$table = new Table($processing_type,$processing_type,$sql,$cols,false);
	if (isset($spr_id)) $table->idstr = "&spr_id=$spr_id";
	$table->addbutton=true;
	$table->show();
	
	
}
printpage();
?>
