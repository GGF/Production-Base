<?
$dbname = 'zaomppsklads2009';
include_once "/home/common/p/admin/web/baza/WWW/lib/sql.php";
authorize();
include "../../style/header.php";
// тут первая часть заголовка дальше нужно напистаь название раздела
?>
Склад - 
<?
print $skladname;
include "../../style/header1.php";
// тут ссылк ана главное
// дальше собственно текст страницы
if (!isset($o1) & !isset($o2) & !isset($o3) & !isset($arc) & !isset($year)) $main = true;

echo "<div class='menu'>";
echo "<table width='100%'><tr><td align='center'><table><tr>";
echo "<td>".($main?"<div class='menuitemsel'>":"<div class='menuitem'><a href='http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."'>")."<img src="/picture/sclads.gif"><br>Склад".($main?"</div>":"</a></div>");
?>
<td><?print isset($arc)?"<div class='menuitemsel'>":"<div class='menuitem'><a href='?arc'>"?><img src="/picture/slads_arc.gif"><br>Архив<?print isset($arc)?"</div>":"</a></div>";?></td>
<td><?print isset($o1)?"<div class='menuitemsel'>":"<div class='menuitem'><a href='?o1'>"?><img src="/picture/otch.gif"><br>Движение<br>отчет<?print isset($o1)?"</div>":"</a></div>";?></td>
<td><div class='menuitem'><a href='http://<? $path=explode("/",$_SERVER['SCRIPT_NAME']);echo $_SERVER['HTTP_HOST'].'/'.$path[count($path)-3].'/';?>'><img src="/picture/backsclads.gif"><br>Назад к скалдам</a></div></td>
<!--td><?print isset($o2)?"<div class='menuitemsel'>":"<div class='menuitem'><a href='?o2'>"?><img src="/picture/otch.gif"><br>Отчет<?print isset($o2)?"</div>":"</a></div>";?></td-->
<!--td><?print isset($o3)?"<div class='menuitemsel'>":"<div class='menuitem'><a href='?o3'>"?><img src="/picture/otch.gif"><br>Отчет<?print isset($o3)?"</div>":"</a></div>";?></td-->
<?
 $sql = "SELECT YEAR(NOW())>(YEAR(sk_".$sklad."_dvizh_arc.ddate)+1) FROM sk_".$sklad."_dvizh_arc ORDER BY ddate DESC LIMIT 1";
 $rs = mysql_fetch_array(mysql_query($sql));
 if ($rs[0]=='1' || isset($year)) {
 	print "<td>".(isset($year)?"<div class='menuitemsel'>":"<div class='menuitem'>")."<a href='?year'><img src='/picture/slads_arc.gif'><br>Годовая архивация".(isset($o3)?"</div>":"</a></div>")."</td>";
} 
?>
</tr></table>
</td></tr></table>
</div>
<?
if (isset($o1)) {
	include "../o1.php";
} elseif (isset($o2)) {
	include "../o2.php";
} elseif (isset($o3)) {
	include "../o3.php";
} elseif (isset($arc)) {
	include "../arc.php";
} elseif (isset($year)) {
	include "../year.php";
} else {
	include "../ost.php";
}
include "../../style/footer.php";
?>