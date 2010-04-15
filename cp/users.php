<?
// управление ползователями

require $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php";
authorize(); // вызов авторизации
$processing_type=basename (__FILE__,".php");
// serialize form
if (isset(${'form_'.$processing_type})) extract(${'form_'.$processing_type});

ob_start();

if (isset($edit)) 
{
	if (!isset($accept)) {
		$sql = "SELECT * FROM users WHERE id='".$edit."'";
		$rs=sql::fetchOne($sql);
		
		$form = new Edit($processing_type);
		$form->init();
		$form->addFields(array(
			array(
				"type"		=> CMSFORM_TYPE_TEXT,
				"name"		=> "nik",
				"label"		=>	"Ник:",
				"value"		=> $rs["nik"],
			),
			array(
				"type"		=> CMSFORM_TYPE_TEXT,
				"name"		=> "fullname",
				"label"		=>	"Полное имя:",
				"value"		=> $rs["fullname"],
			),
			array(
				"type"		=> CMSFORM_TYPE_TEXT,
				"name"		=> "position",
				"label"		=>	"Должность:",
				"value"		=> $rs["position"],
			),
			array(
				"type"		=> CMSFORM_TYPE_TEXT,
				"name"		=> "password1",
				"label"		=>	"Пароль:",
				"value"		=> $rs["password"],
			),
			array(
				"type"		=> CMSFORM_TYPE_TEXT,
				"name"		=> "password2",
				"label"		=>	"Повтор пароля",
				"value"		=> $rs["password"],
			),
		));
		$form->show();
	} 
	else 
	{
		// сохрнение
		if ($password1!=$password2)
		{
			echo ("Пароли не совпадают!"); exit;
		}
		if (!empty($edit)) 
		{
			// редактирование
			$sql = "UPDATE users SET nik='$nik', fullname='$fullname', position='$position', password='$password1' WHERE id='$edit'";
		}
		else 
		{
			// добавление
			$sql = "INSERT INTO users (nik,fullname,position,password) VALUES ('$nik','$fullname','$position','$password1')";
		}
		sql::query($sql);
		echo "ok";
	}

} elseif (isset($delete)) {
	// удаление
	$sql = "DELETE FROM users WHERE id='$delete'";
	sql::query($sql);
	sql::error(true);
	echo "ok";
}
else
{
// вывести таблицу
	// sql	
	$sql="SELECT * FROM users ".(isset($find)?"WHERE (nik LIKE '%$find%' OR fullname LIKE '%$find%' OR position LIKE '%$find%') ":"").(!empty($order)?"ORDER BY ".$order." ":"ORDER BY nik ").(isset($all)?"":"LIMIT 20");
	//print $sql;

	$cols[id]="ID";
	$cols[nik]="Nik";
	$cols[fullname]="Fullname";
	$cols[position]="Position";
	
	$table = new Table($processing_type,$_SERVER[tableaction][$processing_type][next],$sql,$cols);
	$table->addbutton=true;
	$table->show();
}

printpage();
?>