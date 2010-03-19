<?
$dbname = 'zaomppsklads';
include_once $_SERVER["DOCUMENT_ROOT"]."/lib/sql.php";
authorize();
$sklad = $_COOKIE["sklad"];
$processing_type=basename (__FILE__,".php");;

if(isset($id)) $spr_id=$id;

if (isset($delete)) 
{
	$sql = "SELECT * FROM sk_".$sklad."_dvizh WHERE id='$delete'";
	mylog1($sql);
	$res = mysql_query($sql);
	if(!$rs=mysql_fetch_array($res))
		my_error();
	$sql = "UPDATE sk_".$sklad."_ost SET ost=ost".($rs["type"]?"-":"+").abs($rs["quant"])." WHERE spr_id='".$rs["spr_id"]."'";
	mylog1($sql);
	if(!mysql_query($sql)) 
		my_error();
	$sql = "DELETE FROM sk_".$sklad."_dvizh WHERE id='$delete'";
	mylog1($sql);
	if(!mysql_query($sql)) 
		my_error();
	echo "ok";
} 
elseif (isset($edit) || isset(${'form_'.$processing_type})) 
{
	// serialize form
	if(!empty(${'form_'.$processing_type})){
		print_R(${'form_'.$type});
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
		// найдем поставщика
		//$_SERVER["debugAPI"] = true;
		if ($supply_id!=0) 
		{
			$post_id = $supply_id;
		} 
		else 
		{
			$sql="SELECT id FROM sk_".$sklad."_postav WHERE supply='$supply'";
			debug($sql);
			$res = mysql_query($sql);
			if ($rs=mysql_fetch_array($res)){
				$post_id = $rs["id"];
			} else 
			{			
				$sql="INSERT INTO sk_".$sklad."_postav (supply) VALUES ('$supply')";
				debug($sql);
				mysql_query($sql);
				$post_id = mysql_insert_id();
				if (!$post_id) 
					my_error();
			}
		}			
		// Определим идентификатор коментария
		$sql="SELECT id FROM coments WHERE comment='$comment'";
		debug($sql);
		$res = mysql_query($sql);
		if ($rs=mysql_fetch_array($res)){
			$comment_id = $rs["id"];
		} else {
			$sql="INSERT INTO coments (comment) VALUES ('$comment')";
			debug($sql);
			mysql_query($sql);
			$comment_id = mysql_insert_id();
			if (!$comment_id) my_error();
		}		
		list($numdf,$docyr)=explode("/",$numd);
		if ($edit==0) {
			//добавление нового
			$ddate = date("Y-m-d",mktime(0,0,0,substr($ddate,3,2),substr($ddate,0,2),substr($ddate,6,4)));//$dyear."-".$dmonth."-".$dday;
			$sql="INSERT INTO sk_".$sklad."_dvizh (type,numd,numdf,docyr,spr_id,quant,ddate,post_id,comment_id,price) VALUES ('$type','$numd','$numdf','$numyr','$spr_id','$quant','$ddate','$post_id','$comment_id','$price')" ;
			debug($sql);
			if(!mysql_query($sql)) 
				my_error();
			$sql = "UPDATE sk_".$sklad."_ost SET ost=ost".($type?"+":"-").abs($quant)." WHERE spr_id='$spr_id'";
			debug($sql);
			if(!mysql_query($sql)) 
				my_error();	
			
		} 
		else 
		{
			// удалить  старое движенеи
			$sql = "SELECT * FROM sk_".$sklad."_dvizh WHERE id='$edit'";
			debug($sql);
			$res = mysql_query($sql);
			if(!$rs=mysql_fetch_array($res))
				my_error();
			$sql = "UPDATE sk_".$sklad."_ost SET ost=ost".($rs["type"]?"-":"+").abs($rs["quant"])." WHERE spr_id='".$rs["spr_id"]."'";
			debug($sql);
			if(!mysql_query($sql)) 
				my_error();
			$ddate = date("Y-m-d",mktime(0,0,0,substr($ddate,3,2),substr($ddate,0,2),substr($ddate,6,4)));//$dyear."-".$dmonth."-".$dday;
			$sql="UPDATE sk_".$sklad."_dvizh SET type='$type',numd='$numd',numdf='$numdf',docyr='$numyr',spr_id='".$rs["spr_id"]."',quant='$quant',ddate='$ddate',post_id='$post_id',comment_id='$comment_id',price='$price' WHERE id='$edit'" ;
			debug($sql);
			if(!mysql_query($sql)) 
				my_error();
			$sql = "UPDATE sk_".$sklad."_ost SET ost=ost".($type?"+":"-").abs($quant)." WHERE spr_id='".$rs["spr_id"]."'";
			debug($sql);
			if(!mysql_query($sql)) 
				my_error();					
		}
		echo "<script>updatetable('$tid','$processing_type','');closeedit();</script>";// успешное завершение обработки
	} 
	else 
	{
		// поставщики список для выбора
		$supply["0"] = "Новый";
		$sql="SELECT * FROM sk_".$sklad."_postav";
		$res = mysql_query($sql);
		while($rs=mysql_fetch_array($res)) {
			$supply[$rs["id"]] = $rs["supply"];
		}

		if($edit!=0) {
			$sql="SELECT *,sk_".$sklad."_dvizh.id,sk_".$sklad."_postav.id as supply_id FROM sk_".$sklad."_dvizh JOIN (sk_".$sklad."_postav,coments) ON (sk_".$sklad."_postav.id=sk_".$sklad."_dvizh.post_id AND coments.id=sk_".$sklad."_dvizh.comment_id) WHERE sk_".$sklad."_dvizh.id='$edit'";
			//echo $sql;
			if (!$rs=mysql_fetch_array(mysql_query($sql))) my_error();
		}
		$date=($edit!=0?date("d.m.Y",mktime(0,0,0,substr($rs["ddate"],5,2),substr($rs["ddate"],8,2),substr($rs["ddate"],1,4))):date("d.m.Y"));
		
		$form = new Edit('dvizh');
		$form->init();
		$form->addFields(array(
			array(
				"type"		=> CMSFORM_TYPE_HIDDEN,
				"name"		=> "spr_id",
				"value"		=> $spr_id,
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

		//if ($edit!=0 & $rs["type"]==0) {
			include "trebform.php";
		//}
	}
} 
else 
{

	if (isset($all)) {
		$sql="(SELECT *,if(type='1','Приход','Расход') AS prras, sk_".$sklad."_dvizh.id FROM sk_".$sklad."_dvizh JOIN (sk_".$sklad."_postav,coments) ON (sk_".$sklad."_postav.id=sk_".$sklad."_dvizh.post_id AND coments.id=sk_".$sklad."_dvizh.comment_id) WHERE spr_id='$spr_id' ".(isset($find)?"AND comment LIKE '%$find%' OR supply LIKE '%$find%' OR numd LIKE '%$find%' ":"").") UNION (SELECT *,if(type='1','Приход','Расход') AS prras, sk_".$sklad."_dvizh_arc.id FROM sk_".$sklad."_dvizh_arc JOIN (sk_".$sklad."_postav,coments) ON (sk_".$sklad."_postav.id=sk_".$sklad."_dvizh_arc.post_id AND coments.id=sk_".$sklad."_dvizh_arc.comment_id) WHERE spr_id='$spr_id' ".(isset($find)?"AND comment LIKE '%$find%' OR supply LIKE '%$find%' OR numd LIKE '%$find%' ":"").") ".($order!=''?"ORDER BY ".$order." ":"ORDER BY ddate ");
	} else {
		$sql="SELECT *,if(type='1','Приход','Расход') AS prras, sk_".$sklad."_dvizh.id FROM sk_".$sklad."_dvizh JOIN (sk_".$sklad."_postav,coments) ON (sk_".$sklad."_postav.id=sk_".$sklad."_dvizh.post_id AND coments.id=sk_".$sklad."_dvizh.comment_id) WHERE spr_id='$spr_id' ".(isset($find)?"AND comment LIKE '%$find%' OR supply LIKE '%$find%' OR numd LIKE '%$find%' ":"").($order!=''?"ORDER BY ".$order." ":"ORDER BY ddate ");
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
?>