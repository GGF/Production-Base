<?
/*
 * ���������� ����� ������
 * 
 * ��������� $_GET ���������� ����������� ������ AJAX
 * $delete - ������ ��������� ������ 
 * $edit - ������ ������������� ������, ���� 0 �� �����������
 * $all - �������� ��� ������
 * $find - ������ ������
 * $order - ��� ���� ����������, �������� � ����������� DESC - ��������� �������
 * 
 * ����������� �������������� � cp1251 � engine (�������� ����� ��� $find)
 *  � �������������, �� ���� ����������� � ���������� �� �����
 * ������� �������� �������� �� �������������� �� ������� 
 */

$db = '`zaomppsklads`.';
require $_SERVER[DOCUMENT_ROOT]."/lib/engine.php";
authorize();
$sklad = $_COOKIE["sklad"];
$find=(string)$find; //�������, �� ���������� �������������� � ���������������� ����������

if (empty($_GET[delete])) {
	// �� ��������
} 
elseif (isset($_GET[edit]))
{
	include 'arcdvizh.php';
} 
else
{
// ������� �������
	

	$sql="SELECT *,if((krost>ost),'<span style=\'color:red\'><b>����</b></span>','') as malo,sk_arc_".$sklad."_spr.id FROM ".$db."`sk_arc_".$sklad."_spr` JOIN ".$db."sk_arc_".$sklad."_ost ON sk_arc_".$sklad."_ost.spr_id=sk_arc_".$sklad."_spr.id WHERE nazv!='' ".(!empty($find)?"AND nazv LIKE '%".$find."%' ":"").(!empty($_GET[order])?"ORDER BY ".$_GET[order]." ":"ORDER BY nazv ").(isset($_GET[all])?"":"LIMIT 20");
	//echo $sql;
	
	$cols[nazv]="��������";
	$cols[edizm]="��.���.";
	$cols[ost]="������� �� ������";
	$cols[krost]="����. ���-��";
	$cols[malo]="��������";

	
	$table = new Table("arc","arcdvizh",$sql,$cols,false);
	$table->del= false;
	$table->edit= false;
	$table->show();
}

?>