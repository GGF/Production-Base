<?
	
	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	/**
	 *	Функция возвращает HTML код выпадающего списка с родителями    
	 *	
	 *	@param string $src Путь к флэшке
	 *	@param int $w Ширина
	 *	@param int $h Высота
	 *	@param array $params Массив с FlashVars
	 *	@param bool $return Возвращать ли результат (по-умолчанию будет print)
	 *	@param string $html Альтернативная замена для флешки
	 *	@return string $html
	 */
	
	function cmsFlash($src, $w, $h, $params = array(), $return = false, $html = "") {
		
		if ($return) ob_start();
		
		$_SERVER[cmsFlash]++;
		
		$id = ($params[containerId]) ? $params[containerId] : md5($_SERVER[cmsFlash] . $src . mt_rand(100000, 999999));
		unset($params[containerId]);
		
		if ($w == strval((int)$w)) $w .= "px";
		if ($h == strval((int)$h)) $h .= "px";
		
		$style = "style='width: {$w}; height: {$h}; overflow: hidden; display: block'";
		
		$params = is_array($params) ? cmsJson_encode($params) : "'{$params}'";
		
		$id = "cmsFlash_{$id}_container";
		
		print "<!-- FLASH -->\n	";
		print "<span {$style}>\n	";
		print "	<span id='{$id}' {$style}>\n	";
		if (!$html) cmsWarning("<a href='http://www.adobe.com/go/getflashplayer' target='_blank'>" . cmsLang_var("no-flash") . "</a>", "{$w}x{$h}"); else print $html;
		print "	</span>\n	";//
		print "	<script type='text/javascript'> swfobject.embedSWF('{$src}', '{$id}', '{$w}', '{$h}', '9.0.0', '/core/contrib/swfobject/expressInstall.swf', {$params}, {'id':'{$id}','name':'{$id}','allowscriptaccess':'always','wmode':'transparent','quality':'best'}); </script>\n	";
		print "</span>\n	";
		print "<!-- FLASH -->\n";
		
		if ($return) {
			
			$return = ob_get_clean();
			return $return;
			
		}
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//

?>