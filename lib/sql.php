<?

require  $_SERVER[DOCUMENT_ROOT]."/lib/config.php";
require  $_SERVER[DOCUMENT_ROOT]."/lib/core.php";

// ������� ��� ������ � ������
function showheader($subtitle='') 
{
	ob_start(); //�������� ����������� ������ - ����� � ������ �������
	echo '
<!--   Copyright 2010 Igor Fedoroff   |  g_g_f@mail.ru  -->
<html>
<head>
	<meta name="Author" content="����� �������">
	<meta name="Description" content="��� ���">
	<LINK REL="STYLESHEET" TYPE="text/css" HREF="/style/style.css">
	<link type="text/css" href="/style/themes/base/ui.all.css" rel="stylesheet" />
	<link type="text/css" href="/style/jquery.wysiwyg.css" rel="stylesheet" />
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
	<meta http-equiv="Content-Script-Type" content="text/javascript; charset=windows-1251">
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
	<script type="text/javascript" src="/lib/core/contrib/jquery/jquery.nyroModal.js"></script> 
	<script type="text/javascript" src="/lib/core/contrib/jquery/jquery.png.js"></script> 
	<script type="text/javascript" src="/lib/core/contrib/jquery/jquery.maskedinput.js"></script> 
	<script type="text/javascript" src="/lib/core/js/autoexec.js"></script> 
	<script type="text/javascript" src="/lib/core/classes/form/form.js"></script> 
	<script type="text/javascript" src="/lib/core/classes/form_ajax/form_ajax.js"></script> 
	<script type="text/javascript" src="/lib/core/js/alert.js"></script> 
	<script type="text/javascript" src="/lib/core/js/png.js"></script> 
	<script type="text/javascript" src="/lib/core/js/pos.js"></script> 
	<script type="text/javascript" src="/lib/core/js/print.js"></script> 
	<script type="text/javascript" src="/lib/core/js/calendar.js"></script> 
	<script type="text/javascript" src="/lib/core/js/console.js"></script> 
	<script type="text/javascript" src="/lib/core/js/tabs.js"></script> 

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
���� ������ ��� ��� - $subtitle 
</title>
</head>
<body >";
echo "<div class=sun id=sun><img onclick=showuserswin() title='Admin �����' src=/picture/sun.gif></div>";
echo '<div class="glavmenu" onclick="window.location=\'http://'.$_SERVER['HTTP_HOST'].'/\';">������� ����</div>';

//��� ��������

{
$mes = "<div class='soob'>";
$sql = "SELECT fio,dr, (YEAR(NOW())-YEAR(dr)) as let FROM workers WHERE DAYOFYEAR(dr)>= DAYOFYEAR(CURRENT_DATE()) AND DAYOFYEAR(dr)<= (DAYOFYEAR(CURRENT_DATE())+4) ORDER BY DAYOFYEAR(dr)";
$res = sql::fetchAll($sql);
foreach($res as $rs) {
	$dr = true;
	$mes .= "<div>���� �������� - ".$rs["fio"]." - ".$rs["dr"]." - ".$rs["let"]." ���</div>";
}
$mes .= "</div>";
if (isset($dr)) echo $mes;
}

// ������ ����
echo file_get_contents("http://computers.mpp/getbashlocal.php?".$_COOKIE["bash"]);
}


function showfooter($buffer='') 
	{
	if  ($_SERVER[user]=="igor") {
		echo "<div id=userswin class=sun style='display:none'>&nbsp;</div>";
	}
	echo "<div class='maindiv' id=maindiv>";
	if (empty($buffer)) 
		echo "������ �������!!!";
	else 
		echo $buffer;
	echo "</div>";
	echo "<div class='loading' id='loading'>��������...</div>";
	echo "<div class='editdiv' id=editdiv><img src=/picture/s_error2.png class='rigthtop' onclick='closeedit()'>";
	echo "<div class='editdivin' id='editdivin'></div>";
	echo "</div>";//����� ��� �������������� �����
	echo "<script>newinterface=true;</script>";
	echo "</body></html>";
	printpage();
}

function printpage() {
	
		$pageContents = ob_get_clean(); // ��������� �����������
		$console = "";
		
		if ($_SERVER[debug][report] || $_SERVER[local]) {
			
			foreach (sql::$lang->logOut(		CMSSQL_REPORT_ARRAY) as $line) $console .= cmsConsole_out($line[0], "mysql", $line[1]);
			$console .= cmsConsole_out("", "mysql");
			foreach (sql::$shared->logOut(	CMSSQL_REPORT_ARRAY) as $line) $console .= cmsConsole_out($line[0], "mysql", $line[1]);
			
			profiler::add("����������", "����� ����� SQL");
			
			$console .= profiler::export();
			
		}
		
		if ($_SERVER[cmsGZIP][enabled]) 
			{
			
			//$pageContents = "<!-- {$_SERVER[cmsGZIP][algorythm]} -->\n{$pageContents}";
			
			if ($_SERVER[cmsGZIP][algorythm] == 'deflate') {
				header("Content-Encoding: deflate");
				$pageContentsGZIP = gzdeflate($pageContents, 9); //�������������� ���� ��� ������
			} else {	
				header("Content-Encoding: gzip");
				$pageContentsGZIP = gzencode($pageContents, 9); //�������������� ���� ��� ������
			}
			
			if ($_SERVER[debug][report] || $_SERVER[local]) { // ���� ���� �������� �����
				
				$unCompressed    = getKBSize($pageContents);
				$gzCompressed    = getKBSize($pageContentsGZIP);
				$compRatio       = 100 - floor(($gzCompressed/$unCompressed)*1000)/10;
				
				$reportGZIP      = $console;
				$reportGZIP      .= cmsConsole_out("������ <b>" . mb_strtoupper($_SERVER[cmsGZIP][algorythm]) . "</b>: <b>{$unCompressed}</b> &rarr; <b>{$gzCompressed}</b> ({$compRatio}%).", "", "notice");
				$reportGZIP      .= cmsConsole_out("<b>������ ����� ����������: <u>" . cmsTime_format(profiler::$full) . "</u>.</b>", "", "notice");
				$reportGZIP      .= cmsConsole_out("");
				
				if ($_SERVER[cmsGZIP][algorythm] == 'deflate') {
					$pageContentsGZIP = gzdeflate($pageContents . $reportGZIP, 9); //�������������� ���� ��� ������
				} else {	
					$pageContentsGZIP = gzencode($pageContents . $reportGZIP, 9); //�������������� ���� ��� ������
				}
				
			}
			
			echo $pageContentsGZIP; // ������������ ����������� ���������� � �������
			
		} else {
			
			echo $pageContents; // ������������ ����������� ���������� � �������
			
			if ($_SERVER[debug][report] || $_SERVER[local]) {
				
				echo $console;
				echo cmsConsole_out("������ <b>���������</b>.", "", "notice");
				echo cmsConsole_out("<b>������ ����� ����������: <u>" . cmsTime_format(profiler::$full) . "</u>.</b>", "", "notice");
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
	return mb_convert_encoding("/home/common/".str_replace("���������","���������",str_replace("\\","/",str_replace(":","",$filelink))),SERVERFILECODEPAGE,"cp1251");
}

function removeOSsimbols($filename) {
	// ��� �������� �� ���� ������� ������������ ��
	return 	str_replace("'","-",str_replace("`","-",str_replace("?","-",str_replace(":","-",str_replace("\'","-",str_replace("\"","-",str_replace("*","-",str_replace("/","-",str_replace("\\","-",$filename)))))))));

}

function createdironserver($filelink) {
	list($disk,$path) = explode(":",$filelink);
	$serpath = "/home/common/".strtolower($disk)."/";
	$path=str_replace("\\\\","\\",$path);
	$dirs = explode("\\",$path);
	$filename = $dirs[count($dirs)-1];
	unset ($dirs[count($dirs)-1]);
/*
	echo "<br>";
	print_r($dirs);
	echo "<br>".$filename;
	echo "<br>";
*/
	$dir = $serpath;
	$cats='';
	foreach($dirs as $cat) {
		if (!empty($cat)) {
			$cats .= str_replace("���������","���������",$cat)."/";
			$dir = mb_convert_encoding($serpath.$cats,SERVERFILECODEPAGE,"cp1251");
			if (!is_dir($dir)) {
				mkdir ($dir);
				chmod ($dir,0777);
			} 
//			echo $dir."<br>";
		}
	}
//	echo $dir.mb_convert_encoding($filename,SERVERFILECODEPAGE,"cp1251")."XXX";
	return $dir.mb_convert_encoding($filename,SERVERFILECODEPAGE,"cp1251");

}

// ����������� - �� �������
if(!headers_sent()  && !isset($print)) {
	header('Content-type: text/html; charset=windows-1251');
}

foreach ($_GET as $key => $val) {
	${$key}=$val;
	/*if (!is_array($val)) {
		if (mb_detect_encoding($val)=="UTF-8") 
			${$key}=mb_convert_encoding($val,"cp1251","UTF-8");
	}
	*/
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
setCookie(session_name(), session_id(), time() + 60 * 60 * 24, "/"); // 1 ����

?>