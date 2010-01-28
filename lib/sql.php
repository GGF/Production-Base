<?
//
// Auth
//

// Функция получения случайного парол

$GLOBALS["astore"] = array("Q","W","E","R","T","Y","U","I","O","P","A","S","D","F","G","H","J","K","L","Z","X","C","V","B","N","M","1","2","3","4","5","6","7","8","9","0","q","w","e","r","t","y","u","i","o","p","a","s","d","f","g","h","j","k","l","z","x","c","v","b","n","m");
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
	global $sessionid,$user,$userid,$HTTP_POST_VARS,$_COOKIE,$dbname;
	$sessionid = $_COOKIE["sessionid"];
	if (isset($dbname) && $dbname!="zaompp" && !mysql_select_db("zaompp") ) my_error("Не удалось выбрать таблицу zaompp");
	mysql_query("set names cp1251");
	mysql_query("DELETE FROM session WHERE UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(ts) > 3600*8");
	
	$mes = "";
	if($sessionid)
	{
		$result = mysql_query("SELECT * from session WHERE session='".$sessionid."'");
		if($rs = mysql_fetch_array($result))
		{
			$result = mysql_query("SELECT * FROM users WHERE id='".$rs['u_id']."'");
			if($urs = mysql_fetch_array($result))
			{
				$user = $urs["nik"];
				$userid = $rs["u_id"];
				setcookie("sessionid",$sessionid,time() + 60*60*8,'/');//*24,"/");//,"baza");
				mysql_query("UPDATE session SET ts=NOW() WHERE session='$sessionid'");
			}else{
				setcookie("user","",time() - 3600,'/');unset($user);
				setcookie("userid","",time() - 3600,'/');unset($userid);
				setcookie("sessionid","",time() - 3600,'/');unset($sessionid);
				$mes = "Не могу найти пользователя по сессии. Обратитесь к разработчику!";
			}
		}else{
			setcookie("user","",time() - 3600,'/');unset($user);
			setcookie("userid","",time() - 3600,'/');unset($userid);
			setcookie("sessionid","",time() - 3600,'/');unset($sessionid);
			$mes = "Сессия не верна или устарела!";
		}
	} 
	if($HTTP_POST_VARS["password"] && !$user)
	{
		$result = mysql_query("SELECT * FROM users WHERE password='".$HTTP_POST_VARS["password"]."'");
		if($user = mysql_fetch_array($result)){
			$sessionid = passwdGen(12);
			$result = mysql_query("SELECT * FROM session WHERE session='".$sessionid."'");
			while(mysql_num_rows($result) >0){
				$sessionid = passwdGen(12);
				$result = mysql_query("SELECT * FROM session WHERE session='".$sessionid."'");
			}
			mysql_query("INSERT INTO session (session,u_id) VALUES ('".$sessionid."','".$user["id"]."')");
			setcookie("sessionid",$sessionid,time() + 60*60*8,'/');//*24,"/");//,"baza");
			$userid = $user["id"];
			$user = $user["nik"];
			header('Location: http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'');
		}else{
			$mes = "Логин или пароль указаны не верно. Авторизация не удалась. Попробуйте ещё раз.";
		}
	}
	if(!$user)
	{
		echo "<html><head>	<title>База данных ЗАО МПП. Вход.</title>";
		echo "<META HTTP-EQUIV=Content-Type CONTENT=text/html; charset=windows-1251>";
		echo "<style>";
		echo ".zag {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12pt; font-weight: bold; color: #000000} \n";
		echo ".tekst {  font-family: Arial, Helvetica, sans-serif; font-size: 10pt; color: #000000}";
		echo ".podtekst {  font-family: Arial, Helvetica, sans-serif; font-size: 6pt; color: red; align:left}";
		echo "</style></head>";
		echo "<body bgcolor=#FFFFFF><div align=center> <p>&nbsp;</p>";
		echo " <form action='' method='POST'>";
		echo "<table width=309 border=0 cellspacing=0 cellpadding=0 bgcolor='#FFFFFF'>";
		echo "<tr>  <td rowspan=6 width=3>&nbsp;</td>";
		echo "<td colspan=2 class=zag align=center>&nbsp;</td><td>&nbsp;</td>";
		echo "</tr> <tr><td colspan=2 class=zag align=center>Необходимо авторизоваться для работы с базой</td><td>&nbsp;</td> </tr>";
		echo "<tr><td colspan=2 class=zag align=center>$mes &nbsp;</td> <td>&nbsp;</td> </tr>";
		//echo "<tr><td class=tekst align=right>Логин</td>";
		//echo "<td align=center><input type=text name='login'></td><td>&nbsp;</td></tr>";
		//echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
		echo "<tr><td class=tekst align=right>Пароль <span class=podtekst>(именно пароль и только пароль)</td>";
		echo "<td align=center><input type=password name='password'></td>";
		echo "<td width=40><input type=image src='/picture/sl_enter.gif' width=26 height=25/></td>";
		echo "</tr><tr><td width='10'>&nbsp;</td><td class=tekst>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
		echo "<tr valign=top align=left><td colspan=4><img src='/picture/sl_plb.gif' width=309 height=10></td></tr></table>";
		echo "</form>";
		//print_r($_COOKIE);echo $user." - ".$userid." - ".$sessionid;
		echo "<p>&nbsp;</p></div></body></html>";
		exit;
	} 
	
	if (isset($dbname) && $dbname!="zaompp" && !mysql_select_db($dbname) ) my_error("Не удалось выбрать таблицу $dbname");
}


function isadminhere() {

	if (isset($dbname) && $dbname!="zaompp" && !mysql_select_db("zaompp") ) my_error("Не удалось выбрать таблицу zaompp");
	$sql="SELECT (UNIX_TIMESTAMP()-UNIX_TIMESTAMP(ts))<180 FROM session WHERE u_id='1' ORDER BY ts DESC LIMIT 1";
	$res=mysql_query($sql);
	if ($res) {
		$rs=mysql_fetch_array($res);
		if (isset($dbname) && $dbname!="zaompp" && !mysql_select_db($dbname) ) my_error("Не удалось выбрать таблицу $dbname");
		if ($rs[0]=='1') {
			return true;//echo "<LINK REL='STYLESHEET' TYPE='text/css' HREF='/style/styleggf.css'>";
		} else {
			return false;//echo "<LINK REL='STYLESHEET' TYPE='text/css' HREF='/style/style.css'>";
		}
	}
	return false;

}
//
// end Auth
//

function getright($nik) {
	global $dbname;
	if (isset($dbname) && $dbname!="zaompp" && !mysql_select_db("zaompp") ) my_error("Не удалось выбрать таблицу zaompp");
	//$sql="SELECT * FROM rtypes LEFT JOIN rrtypes ON 1 LEFT JOIN users ON 1 LEFT JOIN rights ON ( rtypes.id = type_id AND rrtypes.id = rtype_id AND users.id=u_id ) WHERE nik='".$nik."'";
	//echo $sql;
	
	$sql="SELECT * FROM rights JOIN (users,rtypes,rrtypes) ON (users.id=u_id AND rtypes.id=type_id AND rrtypes.id=rtype_id) WHERE nik='".$nik."'";
	$res=mysql_query($sql);
	while ($rs=mysql_fetch_array($res)) {
		if ($rs["right"]=='1') {
			$r[$rs["type"]][$rs["rtype"]] = true;
	//		setcookie("r[".$rs["type"]."][".$rs["rtype"]."]","true",time() + 60*60*24,"/","baza");
		} else {
	//		setcookie("r[".$rs["type"]."][".$rs["rtype"]."]","false",time() + 60*60*24,"/","baza");
		}
	}
	if (isset($dbname) && $dbname!="zaompp" && !mysql_select_db($dbname) ) my_error("Не удалось выбрать таблицу $dbname");
	return $r;
}

function debug($text,$uslov='') {
	if ($GLOBALS["debugAPI"]) {
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

function mySQLconnect($mysql_db='zaompp')
{
	$mysql_host= 'localhost';
	$mysql_login='root';
	$mysql_password='';


	if( !mysql_connect($mysql_host,$mysql_login,$mysql_password) ) {
		return 0;
	}
	if( !mysql_select_db($mysql_db) ) return 0;
	mysql_query("set names cp1251");
	return 1;
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
	global $dbname;
	echo '
<!--   Copyright 2000 Igor Fedoroff   |  g_g_f@mail.ru  -->
<html>
<head>
	<LINK REL="STYLESHEET" TYPE="text/css" HREF="/style/style.css">
	<link type="text/css" href="/style/themes/base/ui.all.css" rel="stylesheet" />
	<link type="text/css" href="/style/jquery.wysiwyg.css" rel="stylesheet" />
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
	<meta http-equiv="Content-Script-Type" content="text/javascript; charset=windows-1251">
	<meta name="Author" content="Игорь Федоров">
	<meta name="Description" content="ЗАО МПП">
	<script type="text/javascript" src="/lib/jquery-1.4.min.js"></script>
	<script type="text/javascript" src="/lib/jquery.wysiwyg.js"></script>
	<script type="text/javascript" src="/lib/jquery.keyboard.js"></script>
	<script type="text/javascript" src="/lib/myfunction.js"></script>
	<script type="text/javascript" src="/lib/ui/ui.core.js"></script>
	<script type="text/javascript" src="/lib/ui/ui.datepicker.js"></script>
	<script type="text/javascript" src="/lib/ui/i18n/ui.datepicker-ru.js"></script>
	<script type="text/javascript" src="/lib/ui/ui.draggable.js"></script>
	<script type="text/javascript" src="/lib/ui/ui.droppable.js"></script>
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
		$("#editdiv").draggable();
		';
		if (isadminhere()) {
			echo "$('#sun').show();";
			echo "$('div:visible').fadeTo(0,0.95);";
		}
		echo "});

	$(function() {
		$.datepicker.setDefaults($.extend({showMonthAfterYear: false}, $.datepicker.regional['']));
		$('#datepicker').datepicker($.datepicker.regional['ru']);
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
if (isset($dbname) && $dbname!="zaompp" && !mysql_select_db("zaompp") ) my_error("Не удалось выбрать таблицу zaompp");
$sqlquery = "SELECT *, (YEAR(NOW())-YEAR(dr)) as let FROM workers WHERE DAYOFYEAR(dr)>= DAYOFYEAR(CURRENT_DATE()) AND DAYOFYEAR(dr)<= (DAYOFYEAR(CURRENT_DATE())+4) ORDER BY DAYOFYEAR(dr)";
$res = mysql_query($sqlquery);
while ($rs=mysql_fetch_array($res)) {
	$dr = true;
	$mes .= "<div>День рождения - ".$rs["fio"]." - ".$rs["dr"]." - ".$rs["let"]." лет</div>";
}
if (isset($dbname) && $dbname!="zaompp" && !mysql_select_db($dbname) ) my_error("Не удалось выбрать таблицу $dbname");
$mes .= "</div>";
if (isset($dr)) print $mes;
}

// цитаты баша
echo file_get_contents("http://computers/getbashlocal.php?$bash");
}

function showfooter() {
	echo "</body></html>";
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
	global $dbname;
	if (isset($dbname) && $dbname!="zaompp" && !mysql_select_db("zaompp") ) my_error("Не удалось выбрать таблицу zaompp");
	$sql="DELETE FROM session WHERE session='".$sessionid."'";
	mysql_query($sql);
	if (isset($dbname) && $dbname!="zaompp" && !mysql_select_db($dbname) ) my_error("Не удалось выбрать таблицу $dbname");
	setcookie("sessionid","",time() - 3600,'/');
	//echo $sql;
	//header('Location: http://'.$_SERVER['HTTP_HOST'].'');
	echo "<script>window.location='http://".$_SERVER['HTTP_HOST']."'</script>";
}

// запускается - не функция
if(!headers_sent()  && !isset($print)) {
	header('Content-type: text/html; charset=windows-1251');
}

foreach ($_GET as $key => $val) {
	if (mb_detect_encoding($val)=="UTF-8") $$key=mb_convert_encoding($val,"cp1251");
}
foreach ($_POST as $key => $val) {
	if (mb_detect_encoding($val)=="UTF-8") $$key=mb_convert_encoding($val,"cp1251");
}

importmodules();

if (!isset($dbname)) $dbname='zaompp';
if (!mySQLconnect()) {
	my_error('Not connect to base!');
}

?>