<?
include_once $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php"; // ��� ����� ��� ��� ��� notop �� ���������� �������� html
authorize(); // ����� �����������


showheader("������");

$menu = new Menu();

$menu->add("back","�����",false,'/');

$menu->show();

ob_start();
?>
��� ���� ����� ��������� ����� ������ �� ������� ����� ���������� ��������� <a href=nncron193b3.exe> nncron.</a>
������� � ��� ������ c ������ OpenInExcel
� �������
<pre>
WatchClipboard: "*"
: wget1-mask S" /file:\/\/.+\.(xls)|(xml)/i" ;
Rule: WIN-ACTIVE: "���� ������ ��� ���*" RE-MATCH: %CLIPBOARD% %wget1-mask% AND
Action:
QUERY: "������� � Excel?"
IF
ShowNormal   NormalPriority
START-APP: explorer %CLIPBOARD%
THEN
</pre>
����� ����� ����� ���������� ������ � ������ � ��� ���������!!!
<?
showfooter(ob_get_clean());

?>