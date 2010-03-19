<?
//
// Auth
//

// Функция получения случайного парол

$_SERVER["astore"] = array("Q","W","E","R","T","Y","U","I","O","P","A","S","D","F","G","H","J","K","L","Z","X","C","V","B","N","M","1","2","3","4","5","6","7","8","9","0","q","w","e","r","t","y","u","i","o","p","a","s","d","f","g","h","j","k","l","z","x","c","v","b","n","m");
mt_srand((double)microtime()*1000000);
function passwdGen($len)
{
	global $astore;
	$ret = "";
	for ($i=0;$i <$len;$i++ )
	{
		$ret .= $astore[mt_rand(0,sizeof($astore)-1)];
	}
	return $ret;
}
// функция авторизации
function authorize()
{
	$sessionid = session_id();
	sql::query("DELETE FROM session WHERE UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(ts) > 3600*8");
	$mes = "";
	if($sessionid)
	{
		$rs = sql::fetchOne("SELECT * from session WHERE session='".$sessionid."'");
		if($rs)
		{
			$urs = sql::fetchOne("SELECT * FROM users WHERE id='".$rs['u_id']."'");
			if($urs)
			{
				$_SERVER[user] = $urs["nik"];
				$_SERVER[userid] = $rs["u_id"];
				mysql_query("UPDATE session SET ts=NOW() WHERE session='$sessionid'");
			}else{
				$mes = "Не могу найти пользователя по сессии. Обратитесь к разработчику!";
			}
		}else{
			//$mes = "Сессия не верна или устарела!";
		}
	} 
	if($_POST["password"] && !$_SERVER[user])
	{
		$res = sql::fetchOne("SELECT * FROM users WHERE password='".$_POST["password"]."'");
		if($res){
			sql::query("INSERT INTO session (session,u_id) VALUES ('".$sessionid."','".$res["id"]."')");
			$_SERVER[userid] = $res["id"];
			$_SERVER[user] = $res["nik"];
			header('Location: http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'');
		}else{
			$mes = "Логин или пароль указаны не верно. Авторизация не удалась. Попробуйте ещё раз.";
		}
	}
	if(!$_SERVER[user])
	{
		echo "<html><head>	<title>База данных ЗАО МПП. Вход.</title>";
		echo "<META HTTP-EQUIV=Content-Type CONTENT=text/html; charset=windows-1251>";
		echo "<style>";
		echo ".zag {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12pt; font-weight: bold; color: #000000} \n";
		echo ".tekst {  font-family: Arial, Helvetica, sans-serif; font-size: 10pt; color: #000000}";
		echo ".podtekst {  font-family: Arial, Helvetica, sans-serif; font-size: 6pt; color: red; text-align:left}";
		echo "</style></head>";
		echo "<body bgcolor=#FFFFFF><div align=center> <p>&nbsp;</p>";
		echo " <form action='' method='POST'>";
		echo "<table width=309 border=0 cellspacing=0 cellpadding=0 bgcolor='#FFFFFF'>";
		echo "<tr>  <td rowspan=6 width=3>&nbsp;</td>";
		echo "<td colspan=2 class=zag align=center>&nbsp;</td><td>&nbsp;</td>";
		echo "</tr> <tr><td colspan=2 class=zag align=center>Необходимо авторизоваться для работы с базой</td><td>&nbsp;</td> </tr>";
		echo "<tr><td colspan=2 class=zag align=center>$mes &nbsp;</td> <td>&nbsp;</td> </tr>";
		echo "<tr><td class=tekst align=right>Пароль <span class=podtekst>(именно пароль и только пароль)</td>";
		echo "<td align=center><input type=password name='password'></td>";
		echo "<td width=40><input type=image src='/picture/sl_enter.gif' width=26 height=25/></td>";
		echo "</tr><tr><td width='10'>&nbsp;</td><td class=tekst>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
		echo "<tr valign=top align=left><td colspan=4><img src='/picture/sl_plb.gif' width=309 height=10></td></tr></table>";
		echo "</form>";
		echo "<p>&nbsp;</p></div></body></html>";
		exit;
	} 
	
}


function isadminhere() {
	$sql="SELECT (UNIX_TIMESTAMP()-UNIX_TIMESTAMP(ts))<180 FROM session WHERE u_id='1' ORDER BY ts DESC LIMIT 1";
	$res=sql::fetchOne($sql);
	//profiler::add($sql);
	//profiler::add(print_r($res,false));
	return empty($res)?false:$res;
}
//
// end Auth
//

function getright() {
	$sql="SELECT rights.right,type,rtype FROM rights JOIN (users,rtypes,rrtypes) ON (users.id=u_id AND rtypes.id=type_id AND rrtypes.id=rtype_id) WHERE nik='".$_SERVER[user]."'";
	$res=sql::fetchAll($sql);
	foreach($res as $rs) {
		if ($rs["right"]=='1') {
			$r[$rs["type"]][$rs["rtype"]] = true;
		} 
	}
	return $r;
}

function debug($text,$uslov='') {
	if ($_SERVER["debugAPI"]) {
		if (empty($uslov) || strstr($text,$uslov)) {
			echo $text."<br>\n";
		}
	}
}

function my_error($text='')
{
	if ($text!='') {
		echo "ERROR:".$text."<br>";
	} else {
		echo "99999999";
	}
	exit;
}

function mylog($table,$id='0',$action='DELETE') {

	global $userid;
	
	if ($id=='0') {
		$sql = "INSERT INTO logs (logdate,user_id,sqltext,action) VALUES (NOW(),'$userid','".addslashes($table)."','OTHER')";
		mysql_query($sql);
	} else {
		$sql = "SELECT * FROM $table WHERE id='$id'";
		$res = mysql_query($sql);
		while ($rs=mysql_fetch_array($res,MYSQL_ASSOC)) {
			while (list($key,$val)=each($rs)) {
				$keys .= ($keys==''?"":",")."$key";
				$sets .= ($sets==''?"":",")."$key='$val'";
				$vals .= ($vals==''?"":",")."'$val'";
			}
			if ($action=='DELETE') {
				$text = "INSERT INTO $table ($keys) VALUES($vals)";
			} elseif ($action=='UPDATE') {
				$text = "UPDATE $table SET $sets WHERE id='$id'";
			}
			$sql = "INSERT INTO logs (logdate,user_id,sqltext,action) VALUES (NOW(),'$userid','".addslashes($text)."','$action')";
			mysql_query($sql);
		}
	}
}

function mylog1($sql) {
	
	global $dbname,$userid ;
	
	$wordarr = explode(" ",$sql);
	// определим действие
	$action = strtoupper($wordarr[0]);
	if ($action=="INSERT") {
		// определим таблицу
		$table = strtolower($wordarr[2]); // INSERT INTO table
	}
	elseif ($action=="DELETE") {
		// определим таблицу
		$table = strtolower($wordarr[2]); // DELETE FROM table
	}
	elseif ($action=="UPDATE") {
		// определим таблицу
		$table = strtolower($wordarr[1]); // UPDATE table
	} 
	else {
		// если селект возвращаемся ничего не делая
		// DROP или TRUNCATE надеюсь не бедет
		return 0;
	}
	// определим идентификатор
	if (eregi("where (.*)",$sql,$wordarr)) {
		$id = $wordarr[1];
	} else {
		$id=0;
	}
	
	if ($id!='0') {
		$sql1 = "SELECT * FROM $table WHERE $id";
		//echo $sql1."<br>";
		if ($res = mysql_query($sql1)){
			while ($rs=mysql_fetch_array($res,MYSQL_ASSOC)) {
				while (list($key,$val)=each($rs)) {
					$keys .= ($keys==''?"":",")."$key";
					$sets .= ($sets==''?"":",")."$key='$val'";
					$vals .= ($vals==''?"":",")."'$val'";
				}
				if ($action=='DELETE') {
					$text = "INSERT INTO $table ($keys) VALUES($vals)";
				} elseif ($action=='UPDATE') {
					$text = "UPDATE $table SET $sets WHERE id='$id'";
				}
			}
		}
	}
	
	//echo "$dbname ___ $userid ___ $id <br>";
	if (isset($dbname) && $dbname!="zaompp" && !mysql_select_db("zaompp") ) my_error("Не удалось выбрать таблицу zaompp");
	$sql = "INSERT INTO logs (logdate,user_id,sqltext,action) VALUES (NOW(),'$userid','".addslashes($sql)."','$action')";
	//echo $sql."<br>";
	if (!mysql_query($sql)) echo mysql_error();
	$id = mysql_insert_id();
	if ($text!='') {
		$sql = "INSERT INTO logs (logdate,user_id,sqltext,action) VALUES (NOW(),'$userid','".addslashes($text)."','$id')";
		//echo $sql."<br>";
		if (!mysql_query($sql)) echo mysql_error();
	}
	if (isset($dbname) && $dbname!="zaompp" && !mysql_select_db($dbname) ) my_error("Не удалось выбрать таблицу $dbname");
	return 1;
}

// функции для хидера и футера
function showheader($subtitle='') {
	ob_start(); //включаем буферизацию вывода - потом в футуре соберем
	echo '
<!--   Copyright 2010 Igor Fedoroff   |  g_g_f@mail.ru  -->
<html>
<head>
	<LINK REL="STYLESHEET" TYPE="text/css" HREF="/style/style.css">
	<link type="text/css" href="/style/themes/base/ui.all.css" rel="stylesheet" />
	<link type="text/css" href="/style/jquery.wysiwyg.css" rel="stylesheet" />
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
	<meta http-equiv="Content-Script-Type" content="text/javascript; charset=windows-1251">
	<meta name="Author" content="Игорь Федоров">
	<meta name="Description" content="ЗАО МПП">
	<style type="text/css" media="all"> 
		@import url(/lib/core/contrib/jquery/jquery.nyroModal.css);
		@import url(/lib/core/css/style.css);
		@import url(/lib/core/css/alert.css);
		@import url(/lib/core/css/form.css);
		@import url(/lib/core/css/form_standard.css);
		@import url(/lib/core/css/node.css);
		@import url(/lib/core/css/tables.css);
		@import url(/lib/core/css/var.css);
		@import url(/lib/core/css/layout.css);
		@import url(/lib/core/css/calendar.css);
		@import url(/lib/core/css/rounded.css);
		@import url(/lib/core/css/mce.css);
		@import url(/lib/core/css/console.css);
		@import url(/lib/core/css/mysql.css);
		@import url(/lib/core/css/node_map.css);
		@import url(/lib/core/css/tabs.css);
	</style> 
	<script type="text/javascript" src="/lib/js/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="/lib/js/jquery.wysiwyg.js"></script>
	<script type="text/javascript" src="/lib/js/jquery.keyboard.js"></script>
	<script type="text/javascript" src="/lib/js/myfunction.js"></script>
	<script type="text/javascript" src="/lib/js/ui/ui.core.js"></script>
	<script type="text/javascript" src="/lib/js/ui/ui.datepicker.js"></script>
	<script type="text/javascript" src="/lib/js/ui/i18n/ui.datepicker-ru.js"></script>
	<script type="text/javascript" src="/lib/js/ui/ui.draggable.js"></script>
	<script type="text/javascript" src="/lib/js/ui/ui.droppable.js"></script>
	<script type="text/javascript" src="/lib/core/js/browserdetect.js"></script> 
	<script type="text/javascript" src="/lib/core/contrib/json/json.js"></script> 
	<script type="text/javascript" src="/lib/core/contrib/md5/md5.js"></script> 
	<!--script type="text/javascript" src="/lib/core/contrib/swfobject/swfobject.js"></script--> 
	<script type="text/javascript" src="/lib/core/contrib/jquery/jquery.nyroModal.js"></script> 
	<script type="text/javascript" src="/lib/core/contrib/jquery/jquery.png.js"></script> 
	<script type="text/javascript" src="/lib/core/contrib/jquery/jquery.maskedinput.js"></script> 
	<script type="text/javascript" src="/lib/core/js/autoexec.js"></script> 
	<script type="text/javascript" src="/lib/core/classes/form/form.js"></script> 
	<script type="text/javascript" src="/lib/core/classes/form_ajax/form_ajax.js"></script> 
	<!--script type="text/javascript" src="/lib/core/js/ajax.js"></script--> 
	<script type="text/javascript" src="/lib/core/js/alert.js"></script> 
	<script type="text/javascript" src="/lib/core/js/png.js"></script> 
	<script type="text/javascript" src="/lib/core/js/pos.js"></script> 
	<script type="text/javascript" src="/lib/core/js/print.js"></script> 
	<script type="text/javascript" src="/lib/core/js/calendar.js"></script> 
	<script type="text/javascript" src="/lib/core/js/console.js"></script> 
	<script type="text/javascript" src="/lib/core/js/tabs.js"></script> 
	<!-- script type="text/javascript" src="/lib/modules/media/includes/autoexec.js"></script --> 

	<script type="text/javascript">
	$(document).ready(function(){
		yellowtr();
		setkeyboard();
		
		$("#loading").bind("ajaxSend", function(){
		  $(this).show();
		}).bind("ajaxComplete", function(){
		  $(this).hide();
		});
		$("#loading").hide();
 		
		$("#editdiv").hide();
		//$("#editdiv").draggable();
		';
		if (isadminhere()) {
			echo "$('#sun').show();";
			echo "$('div:visible').fadeTo(0,0.95);";
		}
		echo "});

	$(function() {
		$.datepicker.setDefaults($.extend({showMonthAfterYear: false}, $.datepicker.regional['']));
		$('#datepicker').live('focus',function(){\$(this).datepicker($.datepicker.regional['ru']);});
		$('input[datepicker]').live('focus',function(){\$(this).datepicker($.datepicker.regional['ru']);});
	});
	</script>
<title>
База данных ЗАО МПП - $subtitle 
</title>
</head>
<body >";
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



function mysql_query1($sql) {
	// логирование вставить
	mylog1($sql);
	return mysql_query($sql);
}

// импортировать дополнительные модули
function importmodules()
{
	$dir = dirname(__FILE__)."/modules/";
	$ls = opendir($dir);
	if ($ls) {
		while(false !== ($file=readdir($ls)))
		{
			$file = trim($file);
			if($file == basename(__FILE__) ||  $file == "sql.php") continue; // самого себя и главный файл библиотеки не брать, хотя библиотека в другом каталоге
			$a = explode(".",$file); // $a[0] = имяфайла без расширения
			if(!empty($a[0])) // потом добавить словие по которому не импортировать, пока все
			{
				include_once($dir."/".$file);
			}
		}
	}
	
}

function logout() {
	$sql="DELETE FROM session WHERE session='".session_id()."'";
	sql::query($sql);
	echo "<script>window.location='http://".$_SERVER['HTTP_HOST']."'</script>";
}

// запускается - не функция
if(!headers_sent()  && !isset($print)) {
	header('Content-type: text/html; charset=windows-1251');
}

foreach ($_GET as $key => $val) {
	if (mb_detect_encoding($val)=="UTF-8") 
		${$key}=mb_convert_encoding($val,"cp1251","UTF-8");
	else 
		${$key}=$val;
}
foreach ($_POST as $key => $val) {
	if (mb_detect_encoding($val)=="UTF-8") 
		${$key}=mb_convert_encoding($val,"cp1251","UTF-8");
	else 
		${$key}=$val;
}

//importmodules();
/*
if (!isset($dbname)) $dbname='zaompp';
if (!mySQLconnect()) {
	my_error('Not connect to base!');
}
*/

define("MODAUTH_ADMIN", false);

session_start();  //starting session
setCookie(session_name(), session_id(), time() + 60 * 60 * 24, "/"); // 1 день

require  $_SERVER[DOCUMENT_ROOT]."/lib/config.php";
require  $_SERVER[DOCUMENT_ROOT]."/lib/core.php";

function showfooter($buffer='') 
	{
	global $user;
	if  ($user=="igor") {
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
	
		$pageContents = ob_get_clean(); // закрываем буферизацию
		$console = "";
		
		if ($_SERVER[debug][report] || $_SERVER[local]) {
			
			foreach (sql::$lang->logOut(		CMSSQL_REPORT_ARRAY) as $line) $console .= cmsConsole_out($line[0], "mysql", $line[1]);
			$console .= cmsConsole_out("", "mysql");
			foreach (sql::$shared->logOut(	CMSSQL_REPORT_ARRAY) as $line) $console .= cmsConsole_out($line[0], "mysql", $line[1]);
			
			profiler::add("Завершение", "Вывод логов SQL");
			
			$console .= profiler::export();
			
		}
		
		if ($_SERVER[cmsGZIP][enabled]) 
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
			
			print $pageContentsGZIP; // окончательно выплевываем содержимое в браузер
			
		} else {
			
			print $pageContents; // окончательно выплевываем содержимое в браузер
			
			if ($_SERVER[debug][report] || $_SERVER[local]) {
				
				print $console;
				print cmsConsole_out("Сжатие <b>отключено</b>.", "", "notice");
				print cmsConsole_out("<b>Полное время выполнения: <u>" . cmsTime_format(profiler::$full) . "</u>.</b>", "", "notice");
				print cmsConsole_out("");
				
			}
			
		}

}

?>