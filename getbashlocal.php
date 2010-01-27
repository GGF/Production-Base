<?
if(!headers_sent()) {
	header('Content-type: text/html; charset=windows-1251');
}
if (isset($plus)) {
	$text=file_get_contents('http://computers/getbashlocal.php?showbash&nohead&plus=$plus');
} else if (isset($minus)) {
	$text=file_get_contents('http://computers/getbashlocal.php?showbash&nohead&minus=$minus');
} else if (isset($showbash)) {
	setcookie("bash","showbash");
	$text=file_get_contents('http://computers/getbashlocal.php?showbash&nohead');
} else {
	setcookie("bash","hidebash");
	$text=file_get_contents('http://computers/getbashlocal.php?hidebash&nohead');
}
echo $text;
?>