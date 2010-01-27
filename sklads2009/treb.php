<?
$handle = fopen ("treb.tpl", "r");
while (!feof ($handle)) {
	$buffer = fgets($handle, 4096);
	$buffer=str_replace("_nomer_",$nomer,$buffer);
	$buffer=str_replace("_date_",$date,$buffer);
	$buffer=str_replace("_cherezkogo_",$cherezkogo,$buffer);
	$buffer=str_replace("_zatreb_",$zatreb,$buffer);
	$buffer=str_replace("_razresh_",$razresh,$buffer);
	echo $buffer;
}
fclose ($handle);
$handle = fopen ("row.tpl", "r");
while (!feof ($handle)) {
	$buffer = fgets($handle, 4096);
	$buffer=str_replace("_nazv_",$nazv,$buffer);
	$buffer=str_replace("_edizm_",$edizm,$buffer);
	$buffer=str_replace("_otp_",$otp,$buffer);
	echo $buffer;
}
fclose($handle);
$handle = fopen ("bottom.tpl", "r");
while (!feof ($handle)) {
	$buffer = fgets($handle, 4096);	
	$buffer=str_replace("_cherezkogo_",$cherezkogo,$buffer);
	echo $buffer;
}
fclose($handle);

//$sql="INSERT INTO treb (id) VALUES ('$nomer')";
mysql_query($sql);
?>
