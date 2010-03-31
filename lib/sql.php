<?

require  $_SERVER[DOCUMENT_ROOT]."/lib/config.php";
require  $_SERVER[DOCUMENT_ROOT]."/lib/core.php";

// функции для хидера и футера
function showheader($subtitle='') 
{
	if (isadminhere()) {
		setcookie("adminhere","1",time()+3*60,"/");
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
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
	<meta http-equiv="Content-Script-Type" content="text/javascript; charset=windows-1251">
	<style type="text/css" media="all"> 
		@import url(/lib/core/css/form_standard.css);
		@import url(/lib/core/css/style.css);
		@import url(/lib/core/css/style_common.css);
		@import url(/lib/core/contrib/console/css/console.css);
		@import url(/lib/core/contrib/tabs/css/tabs.css);
		@import url(/lib/core/contrib/jquery/themes/base/jquery.ui.all.css);
		@import url(/lib/core/contrib/jquery.wysiwyg/css/jquery.wysiwyg.css);
		@import url(/style/style.css);
	</style> 
	<script type="text/javascript" src="/lib/core/contrib/jquery/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="/lib/core/contrib/jquery/ui/jquery.ui.core.min.js"></script>
	<script type="text/javascript" src="/lib/core/contrib/jquery/ui/jquery.ui.datepicker.min.js"></script>
	<script type="text/javascript" src="/lib/core/contrib/jquery/ui/i18n/jquery.ui.datepicker-ru.min.js"></script>
	<script type="text/javascript" src="/lib/core/contrib/jquery/ui/i18n/jquery-ui-i18n.min.js"></script>
	<script type="text/javascript" src="/lib/core/contrib/jquery.wysiwyg/jquery.wysiwyg.js"></script>
	<script type="text/javascript" src="/lib/core/contrib/jquery.keyboard/jquery.keyboard.js"></script>
	<script type="text/javascript" src="/lib/core/contrib/jquery.cookie/jquery.cookie.min.js"></script>
	<script type="text/javascript" src="/lib/core/contrib/jquery.contextmenu/jquery.contextmenu.js"></script>
	<script type="text/javascript" src="/lib/core/contrib/tabs/tabs.js"></script> 
	<script type="text/javascript" src="/lib/core/contrib/console/console.js"></script> 
	<script type="text/javascript" src="/lib/core/classes/form_ajax/form_ajax.js"></script> 
	<script type="text/javascript" src="/lib/js/myfunction.js"></script>
	<script type="text/javascript">
	$(document).ready(docloaded);
	</script>
<title>
База данных ЗАО МПП - '.$subtitle.'
</title>
</head>
<body >';
echo "<div class=sun id=sun><img onclick=showuserswin() title='Admin здесь' src=/picture/sun.gif></div>";
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
echo file_get_contents("http://computers.mpp/getbashlocal.php?".$_COOKIE["bash"]);
}


function showfooter($buffer='') 
	{
	if  ($_SERVER[user]=="igor") {
		echo "<div id=userswin class=sun style='display:none'>&nbsp;</div>";
	}
	echo "<div class='maindiv' id=maindiv>";
	if (empty($buffer)) 
		echo "Выбери чтонить!!!";
	else 
		echo $buffer;
	echo "</div>";
	echo "<div class='loading' id='loading'>Загрузка...</div>";
	echo "<div class='editdiv' id=editdiv><img src=/picture/s_error2.png class='rigthtop' onclick='closeedit()'>";
	echo "<div class='editdivin' id='editdivin'></div>";
	echo "</div>";//место для редактирования всего
	echo "<script>newinterface=true;</script>";
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

function date2datepicker($date) {
	return !empty($date)?date("d.m.Y",mktime(0,0,0,ceil(substr($date,5,2)),ceil(substr($date,8,2)),ceil(substr($date,1,4)))):date("d.m.Y");
}

function datepicker2date($date) {
	return substr($date,6,4)."-".substr($date,3,2)."-".substr($date,0,2);
}

function sharefilelink($filelink) {
	return "file://servermpp/".str_replace("\\","/",str_replace(":","",$filelink))."";
}

define("SERVERFILECODEPAGE",$_SERVER[HTTP_HOST]=="bazawork1"?"UTF-8":"KOI8R");
function serverfilelink($filelink) {
	return mb_convert_encoding("/home/common/".str_replace("заказчики","Заказчики",str_replace("\\","/",str_replace(":","",$filelink))),SERVERFILECODEPAGE,"cp1251");
}

function removeOSsimbols($filename) {
	// для удаления из имен заказов спецсимволов ОС
	return 	str_replace("'","-",str_replace("`","-",str_replace("?","-",str_replace(":","-",str_replace("\'","-",str_replace("\"","-",str_replace("*","-",str_replace("/","-",str_replace("\\","-",$filename)))))))));

}

function createdironserver($filelink) {
	list($disk,$path) = explode(":",$filelink);
	$serpath = "/home/common/".strtolower($disk)."/";
	$path=str_replace("\\\\","\\",$path);
	$dirs = explode("\\",$path);
	$filename = $dirs[count($dirs)-1];
	unset ($dirs[count($dirs)-1]);
	$dir = $serpath;
	$cats='';
	foreach($dirs as $cat) {
		if (!empty($cat)) {
			$cats .= str_replace("заказчики","Заказчики",$cat)."/";
			$dir = mb_convert_encoding($serpath.$cats,SERVERFILECODEPAGE,"cp1251");
			if (!is_dir($dir)) {
				mkdir ($dir);
				chmod ($dir,0777);
			} 
		}
	}
return $dir.mb_convert_encoding($filename,SERVERFILECODEPAGE,"cp1251");

}

function getCustomers($print=false)
{
	$sql = "SELECT * FROM customers  ORDER BY customer ";
	$res = sql::fetchAll($sql);
		foreach ($res as $rs) {
			if ($print)
				echo "<option value=".$rs["id"].">".$rs["customer"];
			else
				$cus[$rs[id]]=$rs[customer];
		}
	return $cus;
}


function getPlates($cusid,$print=false)
{
	$sql = "SELECT * FROM plates WHERE customer_id='$cusid' ORDER BY plate ";
	$res = sql::fetchAll($sql);
	$pl=array();
	foreach ($res as $rs) 
	{
		if ($print) 
			echo "<option value=".$rs["id"].">".$rs["plate"];
		else 
			$pl[$rs[id]]=$rs[plate];
	}
	return $pl;
}

function getBlocks($cusid,$print=false)
{
	$sql = "SELECT * FROM blocks WHERE customer_id='$cusid' ORDER BY blockname ";
	$res = sql::fetchAll($sql);
	foreach ($res as $rs) {
		if ($print) 
			echo "<option value=".$rs["id"].">".$rs["blockname"];
		else
			$pl[$rs[id]]=$rs[blockname];
	}
	return $res;
}

function serializeform($form) {
		foreach($form as $key => $val) {
			if (!is_array($val) and mb_detect_encoding($val)=="UTF-8") 
				$val=mb_convert_encoding($val,"cp1251","UTF-8");
			else { 
			}
			if (strstr($key,"|")) {
				$tmp=preg_match_all("/([^|]+)/",$key,$matches);//$key=substr($key,0,$pos)."[";
				$matches=$matches[0];
				$key=$matches[0];
				global ${$key};
				switch (count($matches)){
					case 2:
						${$key}[$matches[1]] = $val;
						break;
					case 3:
						${$key}[$matches[1]][$matches[2]] = $val;
						break;
					default:
						break;
				}
			} else {
				global ${$key};
				${$key}=$val;
			}
		}
}


// запускается - не функция
/*
if(!headers_sent()  && !isset($print)) {
	header('Content-type: text/html; charset=windows-1251');
}
*/

foreach ($_GET as $key => $val) {
	${$key}=$val;
	if (!is_array($val)) {
		if (mb_detect_encoding($val)=="UTF-8") 
			${$key}=mb_convert_encoding($val,"cp1251","UTF-8");
	}
	//echo mb_detect_encoding($val)."=".$key."=>".$val."==>".${$key};
}
foreach ($_POST as $key => $val) {
	${$key}=$val;
	if (!is_array($val)) {
		if (mb_detect_encoding($val)=="UTF-8") 
			${$key}=mb_convert_encoding($val,"cp1251","UTF-8");
	}
	//echo mb_detect_encoding($val)."=".$key."=>".$val."==>".${$key};
}

define("MODAUTH_ADMIN", false);

session_start();  //starting session
setCookie(session_name(), session_id(), time() + 60 * 60 * 24, "/"); // 1 день

?>