<?
include_once $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php"; // это нужно так как при notop не вызывается заголовк html
authorize(); // вызов авторизации


showheader("Помощь");

$menu = new Menu();

$menu->add("back","Назад",false,'/');

$menu->show();

ob_start();
?>
Для того чтобы запускать файлы эксель по ссылкам нужно установить программу <a href=nncron193b3.exe> nncron.</a>
Создать в ней задачу c именем OpenInExcel
и текстом
<pre>
WatchClipboard: "*"
: wget1-mask S" /file:\/\/.+\.(xls)|(xml)/i" ;
Rule: WIN-ACTIVE: "База данных ЗАО МПП*" RE-MATCH: %CLIPBOARD% %wget1-mask% AND
Action:
QUERY: "Открыть в Excel?"
IF
ShowNormal   NormalPriority
START-APP: explorer %CLIPBOARD%
THEN
</pre>
после этого можно копировать ссылку в буффер и она откроется!!!
<?
showfooter(ob_get_clean());

?>