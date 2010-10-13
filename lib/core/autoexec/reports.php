<?
/*
 * Фунции вывода ошибок и репортов CMS (c) Osmio
 */
	
	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function cmsError($text, $format = true) {
		
		if ($format) {
			
			list($w, $h) = explode("x", $format); $style = ($w && $h) ? " style='width: {$w}px; height: {$h}px'" : "";
			
			print "\n	<div class='cmsError'{$style}>{$text}</div>\n";
			
		} else { print "/!\ Ошибка\n\n{$text}"; }
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function cmsWarning($text, $format = true) {
		
		if ($format) {
			
			list($w, $h) = explode("x", $format); $style = ($w && $h) ? " style='width: {$w}px; height: {$h}px'" : "";
			
			print "\n	<div class='cmsWarning'{$style}>{$text}</div>\n";
			
		} else { print "/!\ Предупреждение\n\n{$text}"; }
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function cmsNotice($text, $format = true) {
		
		if ($format) {
			
			list($w, $h) = explode("x", $format); $style = ($w && $h) ? " style='width: {$w}px; height: {$h}px'" : "";
			
			print "\n	<div class='cmsNotice'{$style}>{$text}</div>\n";
			
		} else { print "/!\ Сообщение системы\n\n{$text}"; }
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function cmsAlert_html($content, $title = "", $return = false) {
		
		if (!$title)		$title		= "Сообщение сайта";
		if (!$content)	$content	= "Сообщение сайта";
		
		$id = md5(cmsTime());
		
		ob_start();
		
		?>
			
			<?/*<div class='cmsAlertBG'></div>*/?>
			<div class='cmsAlert' id='cmsAlert_<?=$id?>'><table class='frame fullHeight'><tr><td align='center' class='middle'>
				
				<table class='frame cmsAlert_table'>
					<tr>
						<td class='cmsAlert_title' nowrap><?=$title?></td>
						<td class='cmsAlert_titleRight' nowrap></td>
					</tr>
					<tr>
						<td class='cmsAlert_content'><div>
							
							<form action='<?=$_SERVER[REQUEST_URI]?>' method='get'>
								
								<p id='cmsAlert_<?=$id?>_text'><?=nl2br($content)?></p>
								<center><input type='submit' class='submit' value='ОК' onclick='$("#cmsAlert_<?=$id?>").hide(); return false'></center>
								
							</form>
							
						</div></td>
						<td class='cmsAlert_contentRight'  nowrap></td>
					</tr>
					<tr>
						<td class='cmsAlert_status' nowrap></td>
						<td class='cmsAlert_statusRight' nowrap></td>
					</tr>
				</table>
				
			</td></tr></table></div>	
			
		<?
		
		$html = ob_get_clean();
		if ($return) return $html; else print $html;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	/**
	 * Выводится страница со стандартной критической ошибкой.
	 * 
	 * @param string $title Заголовок страницы
	 * @param string $error Текст ошибки
	 */
	
	function cmsDie($pageName = false, $pageContents = false) {
		
		REQUIRE $_SERVER[CORE] . "/pages/error.php";
		exit();
		
	}
	
	function cmsExit($error) {
		
		exit("КРИТИЧЕСКАЯ ОШИБКА: {$error}");
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
?>