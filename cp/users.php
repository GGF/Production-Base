<?
// ���������� �������������

require $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php";
authorize(); // ����� �����������
$processing_type=basename (__FILE__,".php");
// serialize form
if (isset(${'form_'.$processing_type})) extract(${'form_'.$processing_type});

ob_start();

if (isset($edit)) 
{
	$sql = "SELECT * FROM users WHERE id='".$edit."'";
	$rs=sql::fetchOne($sql);
	
	$form = new Edit($processing_type);
	$form->init();
	$form->addFields(array(
		array(
			"type"		=> CMSFORM_TYPE_TEXT,
			"name"		=> "nik",
			"label"		=>	"���:",
			"value"		=> $rs["nik"],
		),
		array(
			"type"		=> CMSFORM_TYPE_TEXT,
			"name"		=> "fullname",
			"label"		=>	"������ ���:",
			"value"		=> $rs["fullname"],
		),
		array(
			"type"		=> CMSFORM_TYPE_TEXT,
			"name"		=> "position",
			"label"		=>	"���������:",
			"value"		=> $rs["position"],
		),
		array(
			"type"		=> CMSFORM_TYPE_TEXT,
			"name"		=> "password1",
			"label"		=>	"������:",
			"value"		=> $rs["password"],
		),
		array(
			"type"		=> CMSFORM_TYPE_TEXT,
			"name"		=> "password2",
			"label"		=>	"������ ������",
			"value"		=> $rs["password"],
		),
	));
	$form->show();

} elseif (isset($delete)) {
	// ��������
	$sql = "DELETE FROM users WHERE id='$delete'";
	sql::query($sql);
	sql::error(true);
	echo "ok";
}
else
{
// ������� �������
	// sql	
	$sql="SELECT * FROM users ".(isset($find)?"WHERE (nik LIKE '%$find%' OR fullname LIKE '%$find%' OR position LIKE '%$find%') ":"").(!empty($order)?"ORDER BY ".$order." ":"ORDER BY nik ").(isset($all)?"":"LIMIT 20");
	//print $sql;

	$cols[id]="ID";
	$cols[nik]="Nik";
	$cols[fullname]="Fullname";
	$cols[position]="Position";
	
	$table = new SqlTable($processing_type,$_SERVER[tableaction][$processing_type][next],$sql,$cols);
	$table->addbutton=true;
	$table->openfunc = 'openuser';
	$table->show();
}

printpage();
?>