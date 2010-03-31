<?
defined("CMS") or die("Restricted usage: " . basename(__FILE__));

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

?>