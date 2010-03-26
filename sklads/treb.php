<?
$handle = fopen ("treb.tpl", "r");
while (!feof ($handle)) {
	$buffer = fgets($handle, 4096);
	$buffer=str_replace("_nomer_",$_POST[nomer],$buffer);
	$buffer=str_replace("_date_",$_POST[date],$buffer);
	$buffer=str_replace("_cherezkogo_",$_POST[cherezkogo],$buffer);
	$buffer=str_replace("_zatreb_",$_POST[zatreb],$buffer);
	$buffer=str_replace("_razresh_",$_POST[razresh],$buffer);
	echo $buffer;
}
fclose ($handle);
$handle = fopen ("row.tpl", "r");
while (!feof ($handle)) {
	$buffer = fgets($handle, 4096);
	$buffer=str_replace("_nazv_",$_POST[nazv],$buffer);
	$buffer=str_replace("_edizm_",$_POST[edizm],$buffer);
	$buffer=str_replace("_otp_",$_POST[otp],$buffer);
	echo $buffer;
}
fclose($handle);
$handle = fopen ("bottom.tpl", "r");
while (!feof ($handle)) {
	$buffer = fgets($handle, 4096);	
	$buffer=str_replace("_cherezkogo_",$_POST[cherezkogo],$buffer);
	echo $buffer;
}
fclose($handle);
?>
