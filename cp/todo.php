<?
// ���������� ������ �� ������������������
require $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php";
authorize(); // ����� �����������
$processing_type=basename (__FILE__,".php");

if (isset($edit) || isset(${'form_'.$processing_type})) 
{
	// serialize form
	if(!empty(${'form_'.$processing_type})){
		foreach(${'form_'.$processing_type} as $key => $val) {
			if (mb_detect_encoding($val)=="UTF-8") 
				${$key}=mb_convert_encoding($val,"cp1251","UTF-8");
			else 
				${$key}=$val;
		}
	}
	
	if (!isset($accept)) 
	{
		$sql = "SELECT * FROM todo WHERE id='".$edit."'";
		$rs=sql::fetchOne($sql);
		
		$form = new Edit($processing_type);
		$form->init();
		$form->addFields(array(
			array(
				"type"		=>	CMSFORM_TYPE_TEXTAREA,
				"name"		=> "what",
				"label"		=>	'',
				"value"		=> $rs["what"],
				"options"		=> array( "rows" => "10", "html" => " cols=50 onfocus='$(this).wysiwyg();' ",),
			),
		));
		$form->show();
	} 
	else 
	{
		// ����������

		if ($edit!=0) {
			$sql="UPDATE todo SET what='".addslashes($what)."', cts=NOW(), rts='0', u_id='".$_SERVER[userid]."' WHERE id='$edit'";
		} else {
			$sql="INSERT INTO todo (what,cts,rts,u_id) VALUES ('".addslashes($what)."',NOW(),'0',".$_SERVER[userid].")";
		}
		sql::query($sql);
		sql::error(true);
		echo "ok";
	}
} 
elseif (isset($delete)) 
{
	$sql = "SELECT what FROM todo WHERE id='".$delete."'";
	$rs=sql::fetchOne($sql);
	$sql = "UPDATE todo SET rts=NOW(), what='<del>".$rs["what"]."</del>' WHERE id='$delete'";
	sql::query($sql);
	sql::error(true);
	echo "ok";
} 
else 
{
	$sql="SELECT *, todo.id FROM todo JOIN users ON users.id=u_id ".(isset($find)?"WHERE (what LIKE '%$find%' ) ":"").((isset($all))?"":(isset($find)?" AND rtsrts='000000000000' ":" WHERE rts='000000000000' ")).(!empty($order)?"ORDER BY ".$order." ":"ORDER BY cts ").((isset($all))?"":"LIMIT 20");
	// echo $sql;

	$cols[id]="ID";
	$cols[nik]="���";
	$cols[cts]="�����";
	$cols[rts]="��������";
	$cols[what]="��� �������";
	

	$table = new Table("todo","todo",$sql,$cols);
	$table->addbutton=true;
	$table->show();
}
?>