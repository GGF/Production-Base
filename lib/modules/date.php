<?
	
	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	define("CMSDATE_MOD_DEFAULT",	"default");
	define("CMSDATE_MOD_CUT",			"cut");
	define("CMSDATE_MOD_WORD",		"word");
	define("CMSDATE_ADDTIME",			true);
	
	$_SERVER[months] = array(
		"ru" => array(
			CMSDATE_MOD_DEFAULT	=> array("", "Января", "Февраля", "Марта", "Апреля", "Мая", "Июня", "Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабря"),
			CMSDATE_MOD_WORD		=> array("", "Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"),
			CMSDATE_MOD_CUT			=> array("", "Янв", "Фев", "Мар", "Апр", "Мая", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек"),
		),
		"en" => array(
			CMSDATE_MOD_DEFAULT	=> array("", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"),
			CMSDATE_MOD_WORD		=> array("", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"),
			CMSDATE_MOD_CUT			=> array("", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"),
		)
	);
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function cmsDate($time, $pattern = "d.m.y", $mod = CMSDATE_MOD_DEFAULT, $addTime = false) { // no matter
		
		if (is_array($mod)) {	
			
			$mod			= ($mod[mod]) ? $mod[mod] : CMSDATE_MOD_DEFAULT;
			$addTime	= ($mod[addTime]) ? $mod[addTime] : false;
		
		}
		
		$time = $time + 0;
		
		if (!$mod) $mod = 'default';
		
		if (@is_array($_SERVER[months][$pattern][$mod])) {
			
			$m = $_SERVER[months][$pattern][$mod][date("n", $time)];
			$date = date("d", $time) . " {$m} " . date("Y", $time);
			
		} else $date = date($pattern, $time);
		
		if ($addTime) $date .= date(" H:i", $time);
		
		return $date;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
?>