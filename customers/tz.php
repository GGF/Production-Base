<?
// создание и редактирование Тех заданий

require $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php";
authorize(); // вызов авторизации
$processing_type=basename (__FILE__,".php");
// serialize form
if (isset(${'form_'.$processing_type})) extract(${'form_'.$processing_type});

ob_start();

if (isset($edit))
{
	if (!empty($id))
	{
		$sql="SELECT *,orders.id AS order_id, tz.id AS tz_id FROM tz JOIN (customers,orders) ON (customers.id=customer_id AND tz.order_id=orders.id) WHERE tz.id='$id'";
		$rs=sql::fetchOne($sql);
		$_SESSION[tz_id]=$rs[tz_id];
//		$_SESSION[order]=$rs[number];
		$_SESSION[order_id]=$rs[order_id];
		$_SESSION[order]=$rs[number];
		$_SESSION[orderdate]=$rs[orderdate];
		$_SESSION[customer_id]=$rs[customer_id];
		$_SESSION[customer]=$rs[customer];
		echo "ok<script>selectmenu('posintz','');</script>";
	}
	else
	{
		if (empty($edit)){
			if(empty($_SESSION[order_id])) {
				echo "Не известно куда добавлять. Выбери заказ!";
			}
			else
			{
				if (isset($typetz)) 
				{
					// np не надо редактировать - только добавлять с текущей датой и пользователем
					// определим позицию в письме
					$sql="SELECT COUNT(*)+1 AS next FROM tz WHERE order_id='$orderid'";
					$rs=sql::fetchOne($sql);
					$pos_in_order = $rs[next];

					// добавление
					// создать файл с табличкой
					// определим заказчика
					$sql="SELECT number,orderdate,customer, fullname FROM orders JOIN customers ON customers.id=customer_id WHERE orders.id='$orderid'";
					//echo $sql;
					$rs=sql::fetchOne($sql);
					$order = $rs["number"];
					$customer = $rs["customer"];
					$fullname = $rs["fullname"];
					$odate = $rs["orderdate"];
					$cdate = date("m-d-Y");
					

					
					$sql = "INSERT INTO tz (order_id,tz_date,user_id) VALUES ('$orderid',NOW(),'{$_SERVER[userid]}')";
					sql::query($sql);

					$tzid =  sql::lastId();

					do 
					{
						$filetype = $typetz=="mpp"?"МПП":($typetz=="dpp"?"ДПП":"ДПП-Блок");
						$orderstring = removeOSsimbols($rs["number"]);
						$file_link = "t:\\\\Расчет стоимости плат\\\\ТехЗад\\\\{$customer}\\\\{$tzid}-{$filetype}-{$pos_in_order}-{$orderstring} от {$rs["orderdate"]}.xls";
						$filename = createdironserver($file_link);
						$fe = file_exists($filename);
						if ($fe) $pos_in_order++;
					} while ($fe);
					// Определим идентификатор файловой ссылки
					$sql="SELECT id FROM filelinks WHERE file_link='$file_link'";
					$rs=sql::fetchOne($sql);
					if (!empty($rs[id]))
					{
						$file_id = $rs["id"];
					} 
					else 
					{
						$sql="INSERT INTO filelinks (file_link) VALUES ('$file_link')";
						sql::query($sql);
						sql::error(true);
						$file_id = sql::lastId();
					}
					// добавить поля в 
					$sql="UPDATE tz SET file_link_id='{$file_id}', pos_in_order='{$pos_in_order}' WHERE id='{$tzid}'";
					sql::query($sql);

					$excel=file_get_contents($typetz=="mpp"?"tzmpp.xls":($typetz=="dpp"?"tzdpp.xls":"tzdppm.xls"));
					if ($file = @fopen($filename,"w")) 
					{
						fwrite($file,$excel);
						fclose($file);
						chmod($filename,0777);
						if ($file = @fopen($filename.".txt","w")) 
						{
							fwrite($file,$cdate."\n");
							fwrite($file,$fullname."\n");
							fwrite($file,$order."\n");
							fwrite($file,$odate."\n");
							fwrite($file,sprintf("%08d\n",$tzid));
							fclose($file);
							echo "<script>updatetable('$tid','tz','');</script>";
							echo "<div style='margin:10px'>";
							echo "<a class=filelink href='".sharefilelink($file_link)."'>TZ-$tzid</a>";
							echo "</div>";
						} 
						else 
						{
							echo "Не удалось создать файл txt";
						}
					}
					else
					{
						echo "Не удалось создать файл xls";
					}
				}
				else
				{
					//не известен тип задания - спросим
					$orderid = $_SESSION[order_id];
					
					echo "<div style='margin:10px'>";
					echo "<input type=button onclick=\"editrecord('tz','typetz=mpp&orderid=$orderid&tid=$tid&add&edit=0')\" value='МПП'>";
					echo "<input type=button onclick=\"editrecord('tz','typetz=dpp&orderid=$orderid&tid=$tid&add&edit=0')\" value='ДПП'>";
					echo "<input type=button onclick=\"editrecord('tz','typetz=dppblock&orderid=$orderid&tid=$tid&add&edit=0')\" value='ДПП(блок)'>";
					echo "</div>";
				}
			}
		}
		else
		{
			// пока ничего
			$sql = "SELECT file_link FROM tz JOIN filelinks ON filelinks.id=tz.file_link_id WHERE tz.id='$edit'";
			//echo $sql;
			$rs=sql::fetchOne($sql);
			echo "<div style='margin:10px'>";
			echo "<a class=filelink href='".sharefilelink($rs[file_link])."'>TZ-$edit</a>";
			echo "</div>";
		}
	}
}
elseif (isset($delete)) 
{
	// удаление
	$sql = "DELETE FROM tz WHERE id='$delete'";
	sql::query($sql);
	// удаление связей
	$sql = "SELECT * FROM posintz WHERE tz_id='$delete'";
	$res = sql::fetchAll($sql);
	foreach ($res as $rs) 
	{
		$delete = $rs["id"];
		$sql = "DELETE FROM posintz WHERE id='$delete'";
		sql::query($sql);
	}
	echo "ok";
} 
elseif (isset($print)) 
{

} 
else 
{
	// список
	if(isset($all)) $_SESSION[tz_id]='';
	if (!empty($_SESSION[customer_id])) 
	{
		if(empty($_SESSION[order_id])){
			$sql="SELECT *,IF(instr(file_link,'МПП')>0, 'МПП', IF(instr(file_link,'Блок')>0,'ДПП(Блок)','ДПП')) AS type,tz.id as tzid,tz.id FROM `tz` JOIN (orders, customers, users,filelinks) ON ( tz.order_id = orders.id AND orders.customer_id = customers.id AND tz.user_id = users.id AND filelinks.id=tz.file_link_id) WHERE customer_id='".$_SESSION[customer_id]."'".(isset($find)?"WHERE (number LIKE '%$find%')":"").(isset($order)?" ORDER BY ".$order." ":" ORDER BY tz.id DESC ").(isset($all)?"LIMIT 50":"LIMIT 20");
			$ordername="Заказчик - ".$_SESSION[customer]." - Техзадания";
			$cols[number]="Заказ";
		}
		else
		{
			$orderid=$_SESSION[order_id];
			$ordername = "Заказчик - ".$_SESSION[customer]." - ТЗ - ".$_SESSION[order]." от ".$_SESSION[orderdate];
			$sql="SELECT *,IF(instr(file_link,'МПП')>0, 'МПП', IF(instr(file_link,'Блок')>0,'ДПП(Блок)','ДПП')) AS type,tz.id as tzid,tz.id FROM `tz` JOIN (orders, customers, users,filelinks) ON ( tz.order_id = orders.id AND orders.customer_id = customers.id AND tz.user_id = users.id AND filelinks.id=tz.file_link_id) ".(isset($find)?"WHERE (number LIKE '%$find%')":"").(isset($orderid)?(isset($find)?"AND order_id='$orderid'":"WHERE order_id='$orderid'"):"").(isset($order)?" ORDER BY ".$order." ":" ORDER BY tz.id DESC ").(isset($all)?"":"LIMIT 20");
		}
	} 
	else
	{
		$ordername='Техзадания';
		$sql="SELECT *,IF(instr(file_link,'МПП')>0, 'МПП', IF(instr(file_link,'Блок')>0,'ДПП(Блок)','ДПП')) AS type,tz.id as tzid,tz.id FROM `tz` JOIN (orders, customers, users,filelinks) ON ( tz.order_id = orders.id AND orders.customer_id = customers.id AND tz.user_id = users.id AND filelinks.id=tz.file_link_id) ".(isset($find)?"WHERE (number LIKE '%$find%' OR tz.id LIKE '%$find%')":"").(isset($order)?" ORDER BY ".$order." ":" ORDER BY tz.id DESC ").(isset($all)?"LIMIT 50":"LIMIT 20");
		$cols[customer]="Заказчик";
		$cols[number]="Заказ";
	}
	
	$cols[tzid]="ID";
	$cols[type]="Тип";
	$cols[tz_date]="Дата";
	$cols[nik]="Кто заполнил";

	$table = new SqlTable($processing_type,$processing_type,$sql,$cols);
	$table->title=$ordername;
	$table->addbutton=true;
	$table->show();
}
printpage();
?>