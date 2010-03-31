<?
defined("CMS") or die("Restricted usage: " . basename(__FILE__));

// ������� ��� ������ � ������
function showheader($subtitle='') 
{
	if (isadminhere()) {
		setcookie("adminhere","1",time()+3*60,"/");
	} else {
		setcookie("adminhere","",time()-3*60,"/");
	}
	ob_start(); //�������� ����������� ������ - ����� � ������ �������
	echo '
<!--   Copyright 2010 Igor Fedoroff   |  g_g_f@mail.ru  -->
<html>
<head>
	<meta name="Author" content="����� �������">
	<meta name="Description" content="��� ���">
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
���� ������ ��� ��� - '.$subtitle.'
</title>
</head>
<body >';
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
		$console = "";//<script>cmsConsole_clear()</script>";
		
		if ($_SERVER[debug][report] || $_SERVER[local]) {
			
			foreach (sql::$lang->logOut(		CMSSQL_REPORT_ARRAY) as $line) $console .= cmsConsole_out($line[0], "mysql", $line[1]);
			$console .= cmsConsole_out("", "mysql");
			foreach (sql::$shared->logOut(	CMSSQL_REPORT_ARRAY) as $line) $console .= cmsConsole_out($line[0], "mysql", $line[1]);
			
			profiler::add("����������", "����� ����� SQL");
			
			$console .= profiler::export();
			
		}
		
		if ($_SERVER[cmsGZIP][enabled] && strlen($pageContents)>30) 
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


?>