<?
if(!headers_sent()) {
	header('Content-type: text/html; charset=windows-1251');
}
if (isset($_GET[plus])) {
	$text=file_get_contents('http://computers.mpp/getbashlocal.php?showbash&nohead&plus=$plus');
} else if (isset($_GET[minus])) {
	$text=file_get_contents('http://computers.mpp/getbashlocal.php?showbash&nohead&minus=$minus');
} else if (isset($_GET[showbash])) {
	setcookie("bash","showbash");
	$text=file_get_contents('http://computers.mpp/getbashlocal.php?showbash&nohead');
} else {
	setcookie("bash","hidebash");
	$text=file_get_contents('http://computers.mpp/getbashlocal.php?hidebash&nohead');
}
echo $text;
?>