<?
defined("CMS") or die("Restricted usage: " . basename(__FILE__));

class Menu {
	var $html=''; 
	
	function Menu() { // �����������
		
	}
	
	function start() {
		echo "<div class=\"menu\"><table width=\"100%\"><tr><td align=\"center\"><table><tr>";
	}
	
	function end() {
		echo "</table></table></div>";
	}
	
	function add($type,$text,$checkright=true,$link='') {
		if ($checkright) {
			$r = isset($_SEVRER[rights])?$_SEVRER[rights]:getright();
			if (!($r[$type]["edit"] || $r[$type]["del"] || $r[$type]["view"])) return; 
		}
		$this->html.="<td><div class='menuitemcp' id='$type'><a onclick=\"selectmenu('$type','".(empty($link)?"":$link)."')\"><div>".(is_callable("addhypher")?addhypher($text):$text)."</div></a></div>";
	}
	
	function add_newline() {
		$this->html.='</tr><tr>';
	}
	
	function show() {
		$this->start();
		echo $this->html;
		$this->end();
	}
}

?>