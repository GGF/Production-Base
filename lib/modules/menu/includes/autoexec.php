<?
defined("CMS") or die("Restricted usage: " . basename(__FILE__));

class Menu {
	var $items;
	
	function Menu() { // конструктор
		$this->items = array();
	}
	
	function start() {
		echo "<div class=\"menu\"><table width=\"100%\"><tr><td align=\"center\"><table><tr>";
	}
	
	function end() {
		echo "</table></table></div>";
	}
	
	function add($type,$text,$checkright=true,$link='') {
		array_push($this->items, array( 
								"type"	=>	$type,
								"text"	=>	$text,
								"link"	=>	$link,
								"picture"	=>	'',
								"right"		=>	$checkright,
								)
					);
	}
	
	function adds($arr)
	{
		foreach($arr as $item)
			array_push($this->items,$item);
	}
	
	function add_newline() {
		array_push($this->items, array( "type"	=>	"newline",));
	}
	
	function show() {
		$this->start();
		$fkey=0;
		foreach($this->items as $item)
		{
			$text=$type=$link=$picture=$right='';
			extract($item);
			//echo $type."_".$right;
			if ($right and !$_SESSION[rights][$type][view]) continue;
			if ($type=="newline") {
				echo "</tr><tr>";
			} else {
				echo "<td><div class='menuitemcp' id='$type'><a onclick=\"selectmenu('$type','".(empty($link)?"":$link)."')\"><div ".(empty($picture)?"":"style='background-image: URL(\"/picture/".$picture."\");'").">".(is_callable("addhypher")?addhypher($text):$text)."</div></a></div>";
				if ($fkey++<10)
					echo "<script>$.keyboard('ctrl+f".($fkey)."',function(){if(\$('#dialog').is(':hidden')) {selectmenu('$type','".(empty($link)?"":$link)."');}});</script>";
			}
		}
		$this->end();
	}
	
	
}

?>