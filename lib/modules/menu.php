<?

class Menu {
	var $html=''; 
	
	function Menu() { // конструктор
		
	}
	
	function start() {
		echo "<div class=\"menu\"><table width=\"100%\"><tr><td align=\"center\"><table><tr>";
	}
	
	function end() {
		echo "</table></table></div>";
	}
	
	function add($type,$text,$checkright=true) {
		global $user;
		if ($checkright) {
			$r = getright($user);
			if (!($r[$type]["edit"] || $r[$type]["del"] || $r[$type]["view"])) return; 
		}
		$this->$html.="<td><div class='menuitemcp' id='$type'><a onclick=\"selectmenu('$type','')\"><div>$text</div></a></div>";
	}
	
	function show() {
		$this->start();
		echo $this->$html;
		$this->end();
	}
}

?>