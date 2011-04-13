<?

defined("CMS") or die("Restricted usage: " . basename(__FILE__));

class Menu {

    protected $items;

    public function __construct() {
        $this->items = array();
    }

    private function start() {
        echo "<div class=\"menu\">
            <table width=\"100%\"><tr><td align=\"center\"><table><tr>";
    }

    private function end() {
        echo "</table></table></div>";
    }

    public function add($type, $text, $checkright=true, $link='') {
        array_push($this->items, array(
            "type" => $type,
            "text" => $text,
            "link" => $link,
            "picture" => '',
            "right" => $checkright,
                )
        );
    }

    public function adds($arr) {
        foreach ($arr as $item)
            array_push($this->items, $item);
    }

    public function add_newline() {
        array_push($this->items, array("type" => "newline",));
    }

    public function show() {
        $this->start();
        $fkey = 0;
        foreach ($this->items as $item) {
            $text = $type = $link = $picture = $right = '';
            extract($item);
            //echo $type."_".$right;
            if ($right and !$_SESSION[rights][$type][view])
                continue;
            if ($type == "newline") {
                echo "</tr><tr>";
            } else {
                echo "<td class='menutd'><div class='menuitemcp' id='$type'>
                <a " . ($fkey++<11?"hotkey='Ctrl+f" . $fkey . "'":"") .
                "onclick=\"selectmenu('$type','" . 
                (empty($link) ? "" : $link) . "')\"><div " . 
                    (empty($picture) ? "" : 
                    "style='background-image: URL(\"/picture/{$picture}\");'") .
                    ">" . 
                    (is_callable("addhypher") ? addhypher($text) : $text) . 
                        "</div></a></div>";
                // с jQuery 1.4.3 keyboard не цепляется к document
                // TODO: Сделать чтонить с функциональными
                /* if ($fkey++<10)
                 * echo "<script>$.keyboard('ctrl+f".($fkey)."',
                 * function(){if(\$('#dialog').is(':hidden')) 
                 * {selectmenu('$type','".(empty($link)?"":$link)."');}});
                 * </script>";
                 */
            }
        }
        $this->end();
    }

}

?>