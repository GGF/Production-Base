<?
// ���������� ����������� �����������

require $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php"; 
authorize(); // ����� �����������
$processing_type=basename (__FILE__,".php");
// serialize form
if (isset(${'form_'.$processing_type})) extract(${'form_'.$processing_type});

ob_start();

if (isset($edit) || isset($add) ) {
	// ������
	if (!isset($accept) ) {
		$sql="SELECT *,DATE_FORMAT(dr,'%d.%m.%Y') as dr FROM workers WHERE workers.id='$edit'";
		//echo $sql;
		$cond = sql::fetchOne($sql);
			
		$form = new Edit($processing_type);
		$form->init();
		
		$fields = array(
					array(
						"type"		=>	CMSFORM_TYPE_TEXT,
						"name"		=>	"tabn",
						"label"		=>	"��������� �����:",
						"value"		=>	$cond["tabn"],
					),
					array(
						"type"		=>	CMSFORM_TYPE_TEXT,
						"name"		=>	"f",
						"label"		=>	"�������:",
						"value"		=>	$cond["f"],
					),
					array(
						"type"		=>	CMSFORM_TYPE_TEXT,
						"name"		=>	"i",
						"label"		=>	"���:",
						"value"		=>	$cond["i"],
					),
					array(
						"type"		=>	CMSFORM_TYPE_TEXT,
						"name"		=>	"o",
						"label"		=>	"��������:",
						"value"		=>	$cond["o"],
					),
					array(
						"type"		=>	CMSFORM_TYPE_TEXT,
						"name"		=>	"dolz",
						"label"		=>	"���������:",
						"value"		=>	$cond["dolz"],
					),
					array(
						"type"		=>	CMSFORM_TYPE_TEXT,
						"name"		=>	"dr",
						"label"		=>	"���� ��������:",
						"value"		=>	$cond["dr"],
						"options"		=> array( "html" => ' datepicker=1 '),
					),
				);

		$form->addFields($fields);
		$form->show();
	} 
	else 
	{
		// ����������
		$dr=datepicker2date($dr);
		if (!empty($edit)) {
			$sql = "UPDATE workers SET tabn='$tabn',fio='$f $i $o',f='$f',i='$i',o='$o',dolz='$dolz',dr='$dr' WHERE id='$edit'";
			
		} else {
			$sql = "INSERT INTO workers (tabn,fio,f,i,o,dolz,dr) VALUES('$tabn','$f $i $o','$f','$i','$o','$dolz','$dr')";
		}
		sql::query($sql);
		echo "ok";
	}
} 
elseif (isset($delete)) 
{
	// ��������
	$sql = "DELETE FROM workers WHERE id='$delete'";
	sql::query($sql);
	sql::error(true);
	echo "ok";
}
else
{
// ������� �������
	
	// sql
	$sql="SELECT *,DAYOFMONTH(dr) as day,MONTH(dr) as month,DAYOFYEAR(dr) as dy,workers.id FROM workers ".(isset($find)?"WHERE f LIKE '%$find%' OR i LIKE '%$find%'":"").(!empty($order)?"ORDER BY ".$order." ":"ORDER BY f DESC ").(isset($all)?"":"LIMIT 20");
	
	$cols[id]="ID";
	$cols[f]="�������";
	$cols[i]="���";
	$cols[o]="��������";
	$cols[dolz]="���������";
	$cols[dr]="��";
	$cols[dy]="��(���� ����)";
	
	$table = new Table($processing_type,$processing_type,$sql,$cols,false);
	$table->addbutton=true;
	$table->show();
	
}

printpage();
?>