<?
include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php";
authorize();
// тут первая часть заголовка дальше нужно напистаь название раздела
showheader("Запуски");

if (!isset($nz) & !isset($tz) & !isset($mp) & !isset($zd) & !isset($pt)) $main=true;
?>
<div class="menu">
<table width="100%"><tr><td align="center">
<table><tr>
<!--td><div class='menuitem'><a href='/zapuski/cp/'><img src="/picture/cp.gif"><br>Редактирование</div></td-->
<td><div class=<?print $main?"'menuitemsel'>":"'menuitem'><a href='/zapuski/'>"?><img src="/picture/zapusk.gif"><br>Запуски<?print $main?"":"</a>";?></div>
<td><div class=<?print isset($nz)?"'menuitemsel'>":"'menuitem'><a href='/zapuski/?nz'>"?><img src="/picture/nzapusk.gif"><br>Незапущенные<?print isset($nz)?"":"</a>";?></div>
<td><div class=<?print isset($zd)?"'menuitemsel'>":"'menuitem'><a href='/zapuski/?zd'>"?><img src="/picture/zd.png"><br>Задел<?print isset($zd)?"":"</a>";?></div>
<td><div class=<?print isset($tz)?"'menuitemsel'>":"'menuitem'><a href='/zapuski/?tz'>"?><img src="/picture/tz.gif"><br>ТехЗадания<?print isset($tz)?"":"</a>";?></div>
<td><div class=<?print isset($mp)?"'menuitemsel'>":"'menuitem'><a href='/zapuski/?mp'>"?><img src="/picture/mp.png"><br>Мастерплаты<?print isset($mp)?"":"</a>";?></div>
<td><div class=<?print isset($pt)?"'menuitemsel'>":"'menuitem'><a href='/zapuski/?pt'>"?><img src="/picture/photot.gif"><br>Фотошаблоны<?print isset($pt)?"":"</a>";?></div>
</tr></table>
</td></tr></table>
</div>
<?
if (isset($nz)) {
	include "nzap.php";
} elseif (isset($tz)) {
	include "tz.php";
} elseif (isset($mp)) {
	include "mp.php";
} elseif (isset($zd)) {
	include "zd.php";
} elseif (isset($pt)) {
	include "pt.php";
} else {
	include "zap.php";
}

showfooter();
?>