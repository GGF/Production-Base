<?
require $_SERVER[DOCUMENT_ROOT]."/lib/engine.php"; // это нужно так как не вызывается заголовк html


if(!headers_sent()) {
	header('Content-type: text/html; charset=windows-1251');
}
if (isset($plus)) {
	$sql="UPDATE bash SET rate=rate+1 WHERE id='$plus'";
	mysql_query($sql);
} else if (isset($minus)) {
	$sql="UPDATE bash SET rate=rate-1 WHERE id='$minus'";
	mysql_query($sql);
} 
//if ($_COOKIE["bash"]=="yes") $showbash = true;
//if (isset($hidebash)) {setcookie("bash","no");unset($showbash);}
//if (isset($showbash)) {setcookie("bash","yes");}
if (!isset($nohead))
	echo "<script type=\"text/javascript\">function showbash() {var html = $.ajax({url:'/getbashlocal.php',data:'nohead&showbash',async: false}).responseText;$('#quote').html(html);} function hidebash() {var html = $.ajax({url:'/getbashlocal.php',data:'nohead&hidebash',async: false}).responseText;$('#quote').html(html);} function plusbash(id) {var html = $.ajax({url:'/getbashlocal.php',data:'nohead&showbash&plus='+id,async: false}).responseText; $('#quote').html(html);} function minusbash(id) {var html = $.ajax({url:'/getbashlocal.php',data:'nohead&showbash&minus='+id,async: false}).responseText;$('#quote').html(html);}</script>";
if (isset($showbash)) {
	//if (isset($full)) include "header.php";
	$sql="SELECT * FROM bash WHERE rate>=0 ORDER BY RAND() LIMIT 1";
	$res=mysql_query($sql);
	//var_dump($res);
	if ($res) {
		if($rs=mysql_fetch_array($res)) {
			$rnd=$rs["id"];
			if (!isset($nohead)) echo "<div id=quote >";
			echo html_entity_decode($rs["quote"]);
			echo "<br><div><a onclick='hidebash()' style='background-color:white;'>Скрыть</a>&nbsp;&nbsp;&nbsp;<a onclick='showbash()' style='background-color:white;'>ЕЩЁ</a>&nbsp;&nbsp;&nbsp;<a onclick='minusbash($rnd)' style='background-color:white;'>---</a>&nbsp;&nbsp;&nbsp;<a onclick='plusbash($rnd)' style='background-color:white;'>+++</a></div>";
			if (!isset($nohead)) echo "</div>";
		}
	}
} else {
	//if (isset($full)) include "header.php";
	if (!isset($nohead)) echo "<div id=quote >";
	echo "<a onclick='showbash()'>Показать цитаты (может содержать нецензурную лексику)</a>";
	if (!isset($nohead)) echo "</div>";
	
}

/*
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
*/
?>