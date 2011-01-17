<?
if(!headers_sent()) {
	header('Content-type: text/html; charset=windows-1251');
}
/*
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
*/
if (isset($_GET["showbash"])) setcookie("showbash","yes");
echo "<script type=\"text/javascript\">
	function showbash() {
		var html = $.ajax({url:'/getbashlocal.php',data:'nohead&showbash',async: false}).responseText;
		$('#quote').html(html);
		
	}
	function hidebash() {
		var html = $.ajax({url:'/getbashlocal.php',data:'nohead&hidebash',async: false}).responseText;
		$('#quote').html(html);
	}
	function plusbash(id) {
		var html = $.ajax({url:'getbashlocal.php',data:'nohead&showbash&plus='+id,async: false}).responseText;
		$('#quote').html(html);
	}
	function minusbash(id) {
		var html = $.ajax({url:'/getbashlocal.php',data:'nohead&showbash&minus='+id,async: false}).responseText;
		$('#quote').html(html);
	}
	</script>
";
if (isset($_GET["showbash"])) {
	//if (isset($full)) include "header.php";
	//if (!isset($nohead)) echo "<div id=quote >";
	$text=file_get_contents('http://bezdna.su/random.php');
	$num = preg_match_all("|<div class=\"q\">[^<]*<div class=\"vote\">.*[.*].*[.*].*<div>(.*)</div>|Us",$text,$res, PREG_PATTERN_ORDER);
	//echo $text;
	$text = $res[1][0];
	//print_r($res);
	echo $text;
	echo "<br><div></div><a onclick='hidebash()'>Скрыть</a>&nbsp;&nbsp;&nbsp;<a onclick='showbash()'>ЕЩЁ</a>&nbsp;&nbsp;&nbsp;<a onclick='minusbash($rnd)'>---</a>&nbsp;&nbsp;&nbsp;<a onclick='plusbash($rnd)'>+++</a></div>";
	//if (!isset($nohead)) echo "</div>";
} else {
	//if (isset($full)) include "header.php";
	//if (!isset($nohead)) echo "<div id=quote >";
	echo "<a onclick='showbash()'>Показать цитаты (может содержать нецензурную лексику)</a>";
	//if (!isset($nohead)) echo "</div>";
	
}

?>