<?
$dbname = 'zaomppsklads';
include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php"; // это нужно так как не вызывается заголовк html
if (isset($dbname) && $dbname!="zaompp" && !mysql_select_db($dbname) ) my_error("Не удалось выбрать таблицу $dbname");
$ddate = date("Y-m-d",mktime(0,0,0,substr($ddate,3,2),substr($ddate,0,2),substr($ddate,6,4)));
$top = file_get_contents ("treb.tpl");
$middle = file_get_contents("row.tpl");
$bot = file_get_contents ("bottom.tpl");

//$sql="SELECT * FROM sk_".$sklad."_dvizh WHERE ddate='$ddate'";// GROUP BY numd";
//$res = mysql_query($sql);
//while ($rs=mysql_fetch_array($res)) {
	$buffer = $top;
	$buffer=str_replace("_nomer_",'',$buffer);//$rs["numd"],$buffer);
	$buffer=str_replace("_date_",date("d.m.Y",mktime(0,0,0,substr($ddate,5,2),substr($ddate,8,2),substr($ddate,0,4))),$buffer);
	$buffer=str_replace("_cherezkogo_",$cherezkogo,$buffer);
	$buffer=str_replace("_zatreb_",$zatreb,$buffer);
	$buffer=str_replace("_razresh_",$razresh,$buffer);
	echo $buffer;
	$sql = "SELECT *,sk_".$sklad."_spr.id FROM sk_".$sklad."_dvizh JOIN sk_".$sklad."_spr ON sk_".$sklad."_dvizh.spr_id=sk_".$sklad."_spr.id WHERE ddate='$ddate'";// AND numd='".$rs["numd"]."'";
	$res1 = mysql_query($sql);
	while ($rs1=mysql_fetch_array($res1)) {
		if(array_key_exists($rs1["id"], $id)) {
			$buffer = $middle;
			$buffer=str_replace("_nazv_",$rs1["nazv"],$buffer);
			$buffer=str_replace("_edizm_",$rs1["edizm"],$buffer);
			$buffer=str_replace("_otp_",$rs1["quant"],$buffer);
			echo $buffer;
		}
	}
	$buffer = $bot;
	$buffer=str_replace("_cherezkogo_",$cherezkogo,$buffer);
	echo $buffer;
	//print "\x0c";
//}
?>
