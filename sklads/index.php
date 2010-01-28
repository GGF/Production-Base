<?
include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php";
authorize();
showheader( "Склады");
// тут ссылк ана главное
// дальше собственно текст страницы

?>
<div class="menu">
<table width="100%"><tr><td align="center">
<table><tr>
<td><div class='menuitem'><a href='himiya/'><img src="/picture/him.gif"><br>Материалы</a></div></td>
<td><div class='menuitem'><a href='materials/'><img src="/picture/mater.gif"><br>Текстолит</a></div></td>
<td><div class='menuitem'><a href='himiya2/'><img src="/picture/him2.gif"><br>Лаборатория</a></div></td>
<td><div class='menuitem'><a href='sverla/'><img src="/picture/sver.gif"><br>Сверла 3.0</a></div></td>
<td><div class='menuitem'><a href='halaty/'><img src="/picture/halat.gif"><br>Спецодежда</a></div></td>
</tr><tr>
<td><div class='menuitem'><a href='instr/'><img src="/picture/instr.gif"><br>Осн.Средства</a></div></td>
<td><div class='menuitem'><a href='nepon/'><img src="/picture/sver.gif"><br>Сверла 3.175</a></div></td>
<td><div class='menuitem'><a href='maloc/'><img src="/picture/none.gif"><br>Малоценка</a></div></td>
<td><div class='menuitem'><a href='stroy/'><img src="/picture/stroy.gif"><br>Стройматериалы</a></div></td>
<td><div class='menuitem'><a href='zap/'><img src="/picture/none.gif"><br>Запчасти<br>инструменты</a></div></td>
</tr></table>
</td></tr></table>
</div>
<?
showfooter();
?>