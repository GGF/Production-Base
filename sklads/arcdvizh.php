<?
/*
 * ���������� �������� ������ ������
 * 
 * ��������� $_GET ���������� ����������� ������ AJAX
 * $id - ������ ������ �������� ������� ����� ��������
 * $delete - ������ ��������� ������ 
 * $edit - ������ ������������� ������, ���� 0 �� �����������
 * $all - �������� ��� ������
 * $find - ������ ������
 * $order - ��� ���� ����������, �������� � ����������� DESC - ��������� �������
 * 
 * ����������� �������������� � cp1251 � engine (�������� ����� ��� $find)
 * � �������������, �� ���� ����������� � ���������� �� �����
 * ������� �������� �������� �� �������������� �� �������, ������������ ����� �� �����
 * � ��� isset ��������� ������ $_GET[���] 
 */

include_once $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php";
authorize();

$db = '`zaomppsklads`.';
$sklad = $_COOKIE["sklad"];

ob_start();

if(!empty($id)) $spr_id=$id;

if (isset($delete)) {
	// �� ������ �� �������
} 
elseif (isset($_GET[edit])) {
	// �� �����������
}
else
{
	// ������� �������

	$sql="SELECT *,sk_arc_".$sklad."_dvizh.id FROM ".$db."sk_arc_".$sklad."_dvizh JOIN (".$db."sk_".$sklad."_postav,".$db."coments) ON (sk_".$sklad."_postav.id=sk_arc_".$sklad."_dvizh.post_id AND coments.id=sk_arc_".$sklad."_dvizh.comment_id) WHERE spr_id='$spr_id'".(!empty($find)?"AND comment LIKE '%".$find."%' OR supply LIKE '%".$find."%' OR numd LIKE '%".$find."%'":"").(!empty($order)?"ORDER BY ".$order." ":"ORDER BY ddate DESC ").((isset($_GET[all]))?"":"LIMIT 20");
	//echo $sql;

	$cols[ddate]="����";
	$cols[prras]="+/-";
	$cols[numd]="� ���.";
	$cols[supply]="���������";
	$cols[quant]="���-��";
	$cols[comment]="����������";
	$cols[price]="����";

	
	$table = new SqlTable("arcdvizh","",$sql,$cols,false);
	$table->del= false;
	$table->edit= false;
	if (isset($spr_id)) $table->idstr = "&spr_id=$spr_id";
	$table->show();
	
}
printpage();
?>
