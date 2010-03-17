<?
// Отображает отчет
$dbname = 'zaomppsklads';
include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php";
authorize();
$sklad = $_COOKIE["sklad"];

$cols[nazv]="Наименование";
$cols[prihod]="Приход";
$cols[rashod]="Расход";
$cols[ost]="Остаток на сегодня";
$cols[edizm]="Ед.Изм.";


$table = new Table("movecheck","",$sql,$cols,false);
$table->del= false;
$table->edit= false;

if (empty($month)) $month=(date("m")-1)*10000+date("Y");


$sql="(SELECT MONTH(ddate) as dmonth, YEAR(ddate) as dyear FROM (sk_".$sklad."_dvizh) GROUP BY MONTH(ddate)) UNION (SELECT MONTH(ddate) as dmonth, YEAR(ddate) as dyear FROM (sk_".$sklad."_dvizh_arc) GROUP BY YEAR(ddate),MONTH(ddate)) ORDER BY dyear DESC, dmonth DESC";
//echo $sql."<br>";

$title="Отчет за месяц:";
$title.="<select onchange=\"updatetable('".$table->tid."','movecheck','?o1&month='+$('#month').val())\" id=month name=month>";
$res = mysql_query($sql);
while($rs=mysql_fetch_array($res)) {
	$title.="<option value=".($rs["dmonth"]*10000+$rs["dyear"])." ".((floor($month/10000)==$rs["dmonth"] && ($month%10000)==$rs["dyear"])?"SELECTED":"").">".sprintf("%02d",$rs["dmonth"])."-".$rs["dyear"]."</option>";
}


$title.="</select>";
$table->title=$title;

$sql="(SELECT  nazv,FORMAT(ost,3) as ost,edizm,FORMAT(SUM(IF(type=1,quant,0)),3) as prihod, FORMAT(SUM(IF(type=0,quant,0)),3) as rashod,sk_".$sklad."_spr.id FROM sk_".$sklad."_spr JOIN sk_".$sklad."_ost ON sk_".$sklad."_ost.spr_id=sk_".$sklad."_spr.id RIGHT JOIN sk_".$sklad."_dvizh ON sk_".$sklad."_dvizh.spr_id=sk_".$sklad."_spr.id WHERE nazv<>'' AND MONTH(ddate)=(FLOOR($month/10000)) AND YEAR(ddate)=($month%10000) ".(isset($find)?"AND nazv LIKE '%$find%' ":"")." GROUP BY nazv) UNION (SELECT  nazv,FORMAT(ost,3) as ost,edizm,FORMAT(SUM(IF(type=1,quant,0)),3) as prihod, FORMAT(SUM(IF(type=0,quant,0)),3) as rashod,sk_".$sklad."_spr.id FROM sk_".$sklad."_spr JOIN sk_".$sklad."_ost ON sk_".$sklad."_ost.spr_id=sk_".$sklad."_spr.id RIGHT JOIN sk_".$sklad."_dvizh_arc ON sk_".$sklad."_dvizh_arc.spr_id=sk_".$sklad."_spr.id WHERE nazv<>'' AND MONTH(ddate)=(FLOOR($month/10000)) AND YEAR(ddate)=($month%10000) ".(isset($find)?"AND nazv LIKE '%$find%' ":"")." GROUP BY nazv) ".(!empty($order)?"ORDER BY ".$order." ":"ORDER BY nazv ");

//echo $sql;

$table->sql=$sql;
$table->idstr='&month='.$month;
$table->show();



?>