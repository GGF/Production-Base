<?
include_once $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php";
authorize();


$db = '`zaomppsklads`.';
$sklad = $_COOKIE["sklad"];
$processing_type=basename (__FILE__,".php");;
$order=(string)$order;
$find=(string)$find;


$cols[check]="<input type=checkbox id='ucuc' onclick=\"if ($('#ucuc').attr('checked')) $('.check-me').attr({checked:true}); else $('.check-me').attr({checked:false});\">";
$cols[nazv]="������������";
$cols[rashod]="������";
$cols[ost]="������� �� �������";
$cols[edizm]="��.���.";


$table = new Table($processing_type,"","",$cols,false);

$table->del= false;
$table->edit= false;

if (empty($_GET[ddate])) 
	$ddate='';
else 
	$ddate=$_GET[ddate];

$sql="SELECT ddate FROM (".$db."sk_".$sklad."_dvizh) WHERE type='0' AND numd<>'9999' GROUP BY ddate ORDER BY ddate DESC";

$title="���������� ��:";
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
<table width=100%><tr><td style='border:0'>����� ����:<td style='border:0'>
<select name=cherezkogo>
<option value=''></option>
<optgroup label='������� ������'>
<option value='������� �.�.' style='color:red;'>������� �.�.</option>
<option value='�������� �.�.' style='color:red;'>�������� �.�.</option>
<option value='�������� �.�.' style='color:red;'>�������� �.�.</option>
</optgroup>
<optgroup label='����� ������'>
<option value='������������ �.�.' style='color:blue;'>������������ �.�.</option>
<option value='��������� �.�.' style='color:blue;'>��������� �.�.</option>
<option value='��������� �.�.' style='color:blue;'>��������� �.�.</option>
<option value='��������� �.�.' style='color:blue;'>��������� �.�.</option>
<option value='������ �.�.' style='color:blue;'>������ �.�.</option>
<option value='���������� �.�.' style='color:blue;'>���������� �.�.</option>
<option value='������ �.�.' style='color:blue;'>������ �.�.</option>
<option value='��������� �.�.' style='color:blue;'>��������� �.�.</option>
<option value='�������� �.�.' style='color:blue;'>�������� �.�.</option>
</optgroup>
<optgroup label='������� ������'>
<option value='������� �.�.' style='color:green;'>������� �.�.</option>
<option value='�������� �.�.' style='color:green;'>�������� �.�.</option>
<option value='Ը����� �.�.' style='color:green;'>Ը����� �.�.</option>
</optgroup>
<optgroup label='������ ������'>
<option value='���������� �.�.' style='color:black;'>���������� �.�.</option>
<option value='�������� �.�.' style='color:black;'>�������� �.�.</option>
<option value='����������� �.�.' style='color:black;'>����������� �.�.</option>
<option value='������� �.�.' style='color:black;'>������� �.�.</option>
<option value='��������� �.�.' style='color:black;'>��������� �.�.</option>
<option value='�������� �.�.' style='color:black;'>�������� �.�.</option>
<option value='��������� �.�.' style='color:black;'>��������� �.�.</option>
<option value='��������� �.�.' style='color:black;'>��������� �.�.</option>
<option value='������ �.�.' style='color:black;'>������ �.�.</option>
<option value='�������� �.�.' style='color:black;'>�������� �.�.</option>
<option value='��������� �.�.' style='color:black;'>��������� �.�.</option>
<option value='�������� �.�.' style='color:black;'>�������� �.�.</option>
</optgroup>
<optgroup label='������������� ������'>
<option value='������ �.�.' style='color:lightgreen;'>������ �.�.</option>
</optgroup>
</select>
<td style='border:0'>��������:<td style='border:0'>
<select name=razresh>
<option value='��������� �.�.' style='color:black;'>��������� �.�.</option>
<option value='���������� �.�.' style='color:black;'>���������� �.�.</option>
<option value='' style='color:black;'></option>
</select>
<td style='border:0'>����������:<td style='border:0'>
<select name=zatreb>
<option value=''></option>
<optgroup label='������� ������'>
<option value='��������� �.�.' style='color:red;'>��������� �.�.</option>
<option value='�������� �.�.' style='color:red;'>�������� �.�.</option>
</optgroup>
<optgroup label='����� ������'>
<option value='�������� �.�.' style='color:blue;'>�������� �.�.</option>
<option value='���������� �.�.' style='color:blue;'>���������� �.�.</option>
</optgroup>
<optgroup label='������� ������'>
<option value='������� �.�.' style='color:green;'>������� �.�.</option>
<option value='Ը����� �.�.' style='color:green;'>Ը����� �.�.</option>
</optgroup>
<optgroup label='������ ������'>
<option value='�������� �.�.' style='color:black;'>�������� �.�.</option>
<option value='�������� �.�.' style='color:black;'>�������� �.�.</option>
</optgroup>
</select>
</table>
";

$title.="<input type=button value='������' onclick=\"window.open('treball.php?'+$('#trebform').serialize(),'_blanck')\">";
$table->title=$title;

$sql="SELECT CONCAT('<input type=checkbox value=',sk_".$sklad."_spr.id,' name=id[',sk_".$sklad."_spr.id,'] class=check-me >') AS `check`,nazv,FORMAT(SUM(quant),3) as rashod,ost,ddate,edizm,".$db."sk_".$sklad."_spr.id FROM ".$db."sk_".$sklad."_spr JOIN (".$db."sk_".$sklad."_dvizh,".$db."sk_".$sklad."_ost) ON (sk_".$sklad."_ost.spr_id=sk_".$sklad."_spr.id AND sk_".$sklad."_dvizh.spr_id=sk_".$sklad."_spr.id) WHERE type='0' AND ddate='".$ddate."' AND numd<>'9999' ".(isset($find)?"AND nazv LIKE '%$find%' ":"")." GROUP BY nazv ".(!empty($order)?"ORDER BY ".$order." ":"ORDER BY nazv ");

$table->sql=$sql;
$table->idstr='&ddate='.$ddate;

echo "<form method=post onsubmit=\"return false;\" name=trebform id=trebform>";

$table->show();

echo "</form>";

?>