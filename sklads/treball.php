<?
$db = '`zaomppsklads`.';
require $_SERVER["DOCUMENT_ROOT"]."/lib/sql.php"; // это нужно так как не вызывается заголовк html
$sklad = $_COOKIE["sklad"];
$processing_type=basename (__FILE__,".php");;

// обработка get тут поставил перекодировку потому что в другом месте не работает с перекодировкой
foreach ($_GET as $key => $val) {
	${$key}=$val;
	if (!is_array($val)) {
		if (mb_detect_encoding($val)=="UTF-8") 
			${$key}=mb_convert_encoding($val,"cp1251","UTF-8");
	}
}



$top = file_get_contents ("treb.tpl");
$middle = file_get_contents("row.tpl");
$bot = file_get_contents ("bottom.tpl");

	$buffer = $top;
	$buffer=str_replace("_nomer_",'',$buffer);
	$buffer=str_replace("_date_",date("d.m.Y",mktime(0,0,0,ceil(substr($ddate,5,2)),ceil(substr($ddate,8,2)),ceil(substr($ddate,0,4)))),$buffer);
	$buffer=str_replace("_cherezkogo_",$cherezkogo,$buffer);
	$buffer=str_replace("_zatreb_",$zatreb,$buffer);
	$buffer=str_replace("_razresh_",$razresh,$buffer);
	echo $buffer;
	$sql = "SELECT *,sk_".$sklad."_spr.id FROM ".$db."sk_".$sklad."_dvizh JOIN ".$db."sk_".$sklad."_spr ON sk_".$sklad."_dvizh.spr_id=sk_".$sklad."_spr.id WHERE ddate='$ddate'";
	$res = sql::fetchAll($sql);
	foreach ($res as $rs) {
		if(array_key_exists($rs["id"], $id)) {
			$buffer = $middle;
			$buffer=str_replace("_nazv_",$rs["nazv"],$buffer);
			$buffer=str_replace("_edizm_",$rs["edizm"],$buffer);
			$buffer=str_replace("_otp_",$rs["quant"],$buffer);
			echo $buffer;
		}
	}
	$buffer = $bot;
	$buffer=str_replace("_cherezkogo_",$cherezkogo,$buffer);
	echo $buffer;
?>
