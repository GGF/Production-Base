<?
defined("CMS") or die("Restricted usage: " . basename(__FILE__));

/*
* Перекодирует файловые ссылки в нужные 
* $filelink имеет вид z:\dir\file.ext
*/

function sharefilelink($filelink) {
	return "file://".NETBIOS_SERVERNAME."/".str_replace(":","",str_replace("\\","/",$filelink))."";
}

function serverfilelink($filelink) {
	return cmsUTF_encode(SHARE_ROOT_DIR.str_replace(":","",str_replace("\\","/",$filelink)));
}

function removeOSsimbols($filename) {
	// для удаления из имен заказов спецсимволов ОС
	return 	str_replace("'","-",str_replace("`","-",str_replace("?","-",str_replace(":","-",str_replace("\'","-",str_replace("\"","-",str_replace("*","-",str_replace("/","-",str_replace("\\","-",$filename)))))))));

}

function createdironserver($filelink) {
	list($disk,$path) = explode(":",$filelink);
	$serpath = SHARE_ROOT_DIR.strtolower($disk)."/";
	$path=str_replace("\\\\","\\",$path);
	$dirs = explode("\\",$path);
	$filename = $dirs[count($dirs)-1];
	unset ($dirs[count($dirs)-1]);
	$dir = $serpath;
	$cats='';
	foreach($dirs as $cat) {
		if (!empty($cat)) {
			$cats .= $cat."/";
			$dir = cmsUTF_encode($serpath.$cats);
			if (!is_dir($dir)) {
				mkdir ($dir);
				chmod ($dir,0777);
			} 
		}
	}
return $dir.cmsUTF_encode($filename);

}

?>