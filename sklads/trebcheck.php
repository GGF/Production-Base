<?
include_once $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php";
authorize();


$db = '`zaomppsklads`.';
$sklad = $_COOKIE["sklad"];
$processing_type=basename (__FILE__,".php");;
$order=(string)$order;
$find=(string)$find;


$cols[check]="<input type=checkbox id='ucuc' onclick=\"if ($('#ucuc').attr('checked')) $('.check-me').attr({checked:true}); else $('.check-me').attr({checked:false});\">";
$cols[nazv]="Наименование";
$cols[rashod]="Расход";
$cols[ost]="Остаток на сегодня";
$cols[edizm]="Ед.Изм.";


$table = new Table($processing_type,"","",$cols,false);

$table->del= false;
$table->edit= false;

if (empty($_GET[ddate])) 
	$ddate='';
else 
	$ddate=$_GET[ddate];

$sql="SELECT ddate FROM (".$db."sk_".$sklad."_dvizh) WHERE type='0' AND numd<>'9999' GROUP BY ddate ORDER BY ddate DESC";

$title="Требования за:";
$title.="<select onchange=\"updatetable('trebform','$processing_type','ddate='+$('#ddate').val())\" id=ddate name=ddate>";
$res = sql::fetchAll($sql);
foreach($res as $rs) {
	$title.="<option value=".($rs["ddate"])." ";
	if(empty($ddate)) {
		$title.=" SELECTED ";
		$ddate=$rs["ddate"];
	} else {
		$title .= $ddate==$rs["ddate"]?"SELECTED":"";
	}
	$title .=">".sprintf("%s",$rs["ddate"])."</option>";
}


$title.="</select>";

$title.= "
<table width=100%><tr><td style='border:0'>Через кого:<td style='border:0'>
<select name=cherezkogo>
<option value=''></option>
<optgroup label='Красная группа'>
<option value='Балуков А.Н.' style='color:red;'>Балуков А.Н.</option>
<option value='Куренков Л.Е.' style='color:red;'>Куренков Л.Е.</option>
<option value='Тимофеев В.В.' style='color:red;'>Тимофеев В.В.</option>
</optgroup>
<optgroup label='Синяя группа'>
<option value='Грималовская Г.А.' style='color:blue;'>Грималовская Г.А.</option>
<option value='Егорычева Т.В.' style='color:blue;'>Егорычева Т.В.</option>
<option value='Курочкина М.А.' style='color:blue;'>Курочкина М.А.</option>
<option value='Левитская Н.П.' style='color:blue;'>Левитская Н.П.</option>
<option value='Разина Е.П.' style='color:blue;'>Разина Е.П.</option>
<option value='Угдыжекова И.В.' style='color:blue;'>Угдыжекова И.В.</option>
<option value='Ходина Е.А.' style='color:blue;'>Ходина Е.А.</option>
<option value='Чистякова И.Н.' style='color:blue;'>Чистякова И.Н.</option>
<option value='Шамарина В.В.' style='color:blue;'>Шамарина В.В.</option>
</optgroup>
<optgroup label='Зеленая группа'>
<option value='Власова Т.В.' style='color:green;'>Власова Т.В.</option>
<option value='Полушкин В.Ю.' style='color:green;'>Полушкин В.Ю.</option>
<option value='Фёдоров И.Ю.' style='color:green;'>Фёдоров И.Ю.</option>
</optgroup>
<optgroup label='Черная группа'>
<option value='Большакова А.В.' style='color:black;'>Большакова А.В.</option>
<option value='Васильев С.Б.' style='color:black;'>Васильев С.Б.</option>
<option value='Владимирова Н.В.' style='color:black;'>Владимирова Н.В.</option>
<option value='Власова И.Ф.' style='color:black;'>Власова И.Ф.</option>
<option value='Евдокимов Д.А.' style='color:black;'>Евдокимов Д.А.</option>
<option value='Игнатьев С.Н.' style='color:black;'>Игнатьев С.Н.</option>
<option value='Китуничев Д.С.' style='color:black;'>Китуничев Д.С.</option>
<option value='Легоньков В.А.' style='color:black;'>Легоньков В.А.</option>
<option value='Орлова Н.Н.' style='color:black;'>Орлова Н.Н.</option>
<option value='Потапова Л.В.' style='color:black;'>Потапова Л.В.</option>
<option value='Салангина И.Г.' style='color:black;'>Салангина И.Г.</option>
<option value='Соковнин С.А.' style='color:black;'>Соковнин С.А.</option>
</optgroup>
<optgroup label='Светлозеленая группа'>
<option value='Жинкин А.И.' style='color:lightgreen;'>Жинкин А.И.</option>
</optgroup>
</select>
<td style='border:0'>Разрешил:<td style='border:0'>
<select name=razresh>
<option value='Китуничев Д.С.' style='color:black;'>Китуничев Д.С.</option>
<option value='Николайчук И.И.' style='color:black;'>Николайчук И.И.</option>
<option value='' style='color:black;'></option>
</select>
<td style='border:0'>Затребовал:<td style='border:0'>
<select name=zatreb>
<option value=''></option>
<optgroup label='Красная группа'>
<option value='Мещанинов В.Ф.' style='color:red;'>Мещанинов В.Ф.</option>
<option value='Тимофеев В.В.' style='color:red;'>Тимофеев В.В.</option>
</optgroup>
<optgroup label='Синяя группа'>
<option value='Соколова В.М.' style='color:blue;'>Соколова В.М.</option>
<option value='Угдыжекова И.В.' style='color:blue;'>Угдыжекова И.В.</option>
</optgroup>
<optgroup label='Зеленая группа'>
<option value='Смирнов В.А.' style='color:green;'>Смирнов В.А.</option>
<option value='Фёдоров И.Ю.' style='color:green;'>Фёдоров И.Ю.</option>
</optgroup>
<optgroup label='Черная группа'>
<option value='Михайлов В.Н.' style='color:black;'>Михайлов В.Н.</option>
<option value='Макарова Т.Л.' style='color:black;'>Макарова Т.Л.</option>
</optgroup>
</select>
</table>
";

$title.="<input type=button value='Печать' onclick=\"window.open('treball.php?'+$('#trebform').serialize(),'_blanck')\">";
$table->title=$title;

$sql="SELECT CONCAT('<input type=checkbox value=',sk_".$sklad."_spr.id,' name=id[',sk_".$sklad."_spr.id,'] class=check-me >') AS `check`,nazv,FORMAT(SUM(quant),3) as rashod,ost,ddate,edizm,".$db."sk_".$sklad."_spr.id FROM ".$db."sk_".$sklad."_spr JOIN (".$db."sk_".$sklad."_dvizh,".$db."sk_".$sklad."_ost) ON (sk_".$sklad."_ost.spr_id=sk_".$sklad."_spr.id AND sk_".$sklad."_dvizh.spr_id=sk_".$sklad."_spr.id) WHERE type='0' AND ddate='".$ddate."' AND numd<>'9999' ".(isset($find)?"AND nazv LIKE '%$find%' ":"")." GROUP BY nazv ".(!empty($order)?"ORDER BY ".$order." ":"ORDER BY nazv ");

$table->sql=$sql;
$table->idstr='&ddate='.$ddate;

echo "<form method=post onsubmit=\"return false;\" name=trebform id=trebform>";

$table->show();

echo "</form>";

?>