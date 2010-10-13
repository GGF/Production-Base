<?
defined("CMS") or die("Restricted usage: " . basename(__FILE__));

// функции для хидера и футера
function showheader($subtitle='') 
{
	if (isadminhere()) {
		setcookie("adminhere",($_SERVER[user]=="igor"?"2":"1"),time()+3*60,"/");
	} else {
		setcookie("adminhere","",time()-3*60,"/");
	}
	ob_start(); //включаем буферизацию вывода - потом в футуре соберем
	echo '
<!--   Copyright 2010 Igor Fedoroff   |  g_g_f@mail.ru  -->
<html>
<head>
	<meta name="Author" content="Игорь Федоров">
	<meta name="Description" content="ЗАО МПП">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="Content-Script-Type" content="text/javascript; charset=UTF-8">
';
echo cmsHeader_get("css");
echo cmsHeader_get("js");
echo '
<title>
База данных ЗАО МПП - '.$subtitle.'
</title>
</head>
<body >';
if (!empty($subtitle)) {
	echo '<div class="glavmenu" onclick="window.location=\'http://'.$_SERVER['HTTP_HOST'].'/\';">Главное меню</div>';

	//дни рождения

	{
	$mes = "<div class='soob'>";
	$sql = "SELECT fio,dr, (YEAR(NOW())-YEAR(dr)) as let FROM workers WHERE DAYOFYEAR(dr)>= DAYOFYEAR(CURRENT_DATE()) AND DAYOFYEAR(dr)<= (DAYOFYEAR(CURRENT_DATE())+4) ORDER BY DAYOFYEAR(dr)";
	$res = sql::fetchAll($sql);
	foreach($res as $rs) {
		$dr = true;
		$mes .= "<div>День рождения - ".$rs["fio"]." - ".$rs["dr"]." - ".$rs["let"]." лет</div>";
	}
	$mes .= "</div>";
	if (isset($dr)) echo $mes;
	}

	// цитаты баша
	echo "<div id=quote >".file_get_contents("http://baza2.mpp/getbashlocal.php?".$_COOKIE["bash"])."</div>";
}
}


function showfooter($buffer='') 
	{
	echo "<div class='maindiv' id=maindiv>";
	if (empty($buffer)) 
		echo "Выбери чтонить!!!";
	else 
		echo $buffer;
	echo "</div>";
	echo "</body></html>";
	printpage();
}

function printpage() {
	
		$pageContents = ob_get_clean(); // закрываем буферизацию
		$console = "";//<script>cmsConsole_clear()</script>";
		
		if ($_SERVER[debug][report] || $_SERVER[local]) {
			
			foreach (sql::$lang->logOut(		CMSSQL_REPORT_ARRAY) as $line) $console .= cmsConsole_out($line[0], "mysql", $line[1]);
			$console .= cmsConsole_out("", "mysql");
			foreach (sql::$shared->logOut(	CMSSQL_REPORT_ARRAY) as $line) $console .= cmsConsole_out($line[0], "mysql", $line[1]);
			
			profiler::add("Завершение", "Вывод логов SQL");
			
			$console .= profiler::export();
			
		}
		
		if ($_SERVER[cmsGZIP][enabled] && strlen($pageContents)>30) 
			{
			
			//$pageContents = "<!-- {$_SERVER[cmsGZIP][algorythm]} -->\n{$pageContents}";
			
			if ($_SERVER[cmsGZIP][algorythm] == 'deflate') {
				header("Content-Encoding: deflate");
				$pageContentsGZIP = gzdeflate($pageContents, 9); //первоначальный ГЗИП для отчета
			} else {	
				header("Content-Encoding: gzip");
				$pageContentsGZIP = gzencode($pageContents, 9); //первоначальный ГЗИП для отчета
			}
			
			if ($_SERVER[debug][report] || $_SERVER[local]) { // Если надо генерить отчет
				
				$unCompressed    = getKBSize($pageContents);
				$gzCompressed    = getKBSize($pageContentsGZIP);
				$compRatio       = 100 - floor(($gzCompressed/$unCompressed)*1000)/10;
				
				$reportGZIP      = $console;
				$reportGZIP      .= cmsConsole_out("Сжатие <b>" . mb_strtoupper($_SERVER[cmsGZIP][algorythm]) . "</b>: <b>{$unCompressed}</b> &rarr; <b>{$gzCompressed}</b> ({$compRatio}%).", "", "notice");
				$reportGZIP      .= cmsConsole_out("<b>Полное время выполнения: <u>" . cmsTime_format(profiler::$full) . "</u>.</b>", "", "notice");
				$reportGZIP      .= cmsConsole_out("");
				
				if ($_SERVER[cmsGZIP][algorythm] == 'deflate') {
					$pageContentsGZIP = gzdeflate($pageContents . $reportGZIP, 9); //первоначальный ГЗИП для отчета
				} else {	
					$pageContentsGZIP = gzencode($pageContents . $reportGZIP, 9); //первоначальный ГЗИП для отчета
				}
				
			}
			
			echo $pageContentsGZIP; // окончательно выплевываем содержимое в браузер
			
		} else {
			
			echo $pageContents; // окончательно выплевываем содержимое в браузер
			
			if ($_SERVER[debug][report] || $_SERVER[local]) {
				
				echo $console;
				echo cmsConsole_out("Сжатие <b>отключено</b>.", "", "notice");
				echo cmsConsole_out("<b>Полное время выполнения: <u>" . cmsTime_format(profiler::$full) . "</u>.</b>", "", "notice");
				echo cmsConsole_out("");
				
			}
			
		}

}


?>