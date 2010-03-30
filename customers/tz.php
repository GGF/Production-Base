<?
// �������� � �������������� ��� �������

require $_SERVER["DOCUMENT_ROOT"]."/lib/sql.php";
authorize(); // ����� �����������
$processing_type=basename (__FILE__,".php");
ob_start();

if (isset($edit))
{
	if (empty($edit)){
		if (isset($typetz)) 
		{
			// np �� ���� ������������� - ������ ��������� � ������� ����� � �������������
			// ��������� ������� � ������
			$sql="SELECT COUNT(*)+1 AS next FROM tz WHERE order_id='$orderid'";
			$rs=sql::fetchOne($sql);
			$pos_in_order = $rs[next];

			// ����������
			// ������� ���� � ���������
			// ��������� ���������
			$sql="SELECT number,orderdate,customer, fullname FROM orders JOIN customers ON customers.id=customer_id WHERE orders.id='$orderid'";
			//echo $sql;
			$rs=sql::fetchOne($sql);
			$order = $rs["number"];
			$customer = $rs["customer"];
			$fullname = $rs["fullname"];
			$odate = $rs["orderdate"];
			$cdate = date("m-d-Y");
			
			do 
			{
				$file_link = "t:\\\\������ ��������� ����\\\\������\\\\".$customer."\\\\".removeOSsimbols($rs["number"])." �� ".$rs["orderdate"]." ".$pos_in_order." ".($typetz=="mpp"?"���":"���").".xls";
				$filename = createdironserver($file_link);
				$fe = file_exists($filename);
				if ($fe) $pos_in_order++;
			} while ($fe);
			// ��������� ������������� �������� ������
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
			
			$sql = "INSERT INTO tz (order_id,tz_date,user_id,pos_in_order,file_link_id) VALUES ('$orderid',NOW(),'".$_SERVER[userid]."','$pos_in_order','$file_id')";
			sql::query($sql);

			$tzid =  sql::lastId();

			$excel=file_get_contents($typetz=="mpp"?"tzmpp.xls":"tzdpp.xls");
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
					echo "<a href='".sharefilelink($file_link)."'>TZ-$tzid</a>";
					echo "</div>";
				} 
				else 
				{
					echo "�� ������� ������� ���� txt";
				}
			}
			else
			{
				echo "�� ������� ������� ���� xls";
			}
		}
		else
		{
			//�� �������� ��� ������� - �������
			//print_r ($_GET);
			echo "<div style='margin:10px'>";
			echo "<input type=button onclick=\"editrecord('tz','typetz=mpp&orderid=$orderid&tid=$tid&add&edit=0')\" value='���'>";
			echo "<input type=button onclick=\"editrecord('tz','typetz=dpp&orderid=$orderid&tid=$tid&add&edit=0')\" value='���'>";
			echo "</div>";
		}
	}
	else
	{
		// ���� ������
		$sql = "SELECT file_link FROM tz JOIN filelinks ON filelinks.id=tz.file_link_id WHERE tz.id='$edit'";
		//echo $sql;
		$rs=sql::fetchOne($sql);
		echo "<div style='margin:10px'>";
		echo "<a href='".sharefilelink($rs[file_link])."'>TZ-$edit</a>";
		echo "</div>";
	}
}
elseif (isset($delete)) 
{
	// ��������
	$sql = "DELETE FROM tz WHERE id='$delete'";
	sql::query($sql);
	// �������� ������
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
	// ������
	if(isset($all)) $_SESSION[tz_id]='';
	if (!empty($_SESSION[customer_id])) 
	{
		if(empty($_SESSION[order_id])){
			$sql="SELECT *,tz.id as tzid,tz.id FROM `tz` JOIN (orders, customers, users,filelinks) ON ( tz.order_id = orders.id AND orders.customer_id = customers.id AND tz.user_id = users.id AND filelinks.id=tz.file_link_id) WHERE customer_id='".$_SESSION[customer_id]."'".(isset($find)?"WHERE (number LIKE '%$find%')":"").(isset($order)?" ORDER BY ".$order." ":" ORDER BY tz.id DESC ").(isset($all)?"LIMIT 50":"LIMIT 20");
			$ordername="�������� - ".$_SESSION[customer]." - ����������";
		}
		else
		{
			$orderid=$_SESSION[order_id];
			$ordername = "�������� - ".$_SESSION[customer]." - �� - ".$_SESSION[order];
			$sql="SELECT *,tz.id as tzid,tz.id FROM `tz` JOIN (orders, customers, users,filelinks) ON ( tz.order_id = orders.id AND orders.customer_id = customers.id AND tz.user_id = users.id AND filelinks.id=tz.file_link_id) ".(isset($find)?"WHERE (number LIKE '%$find%')":"").(isset($orderid)?(isset($find)?"AND order_id='$orderid'":"WHERE order_id='$orderid'"):"").(isset($order)?" ORDER BY ".$order." ":" ORDER BY tz.id DESC ").(isset($all)?"":"LIMIT 20");
		}
	} 
	else
	{
		$ordername='����������';
		$sql="SELECT *,tz.id as tzid,tz.id FROM `tz` JOIN (orders, customers, users,filelinks) ON ( tz.order_id = orders.id AND orders.customer_id = customers.id AND tz.user_id = users.id AND filelinks.id=tz.file_link_id) ".(isset($find)?"WHERE (number LIKE '%$find%' OR tz.id LIKE '%$find%')":"").(isset($order)?" ORDER BY ".$order." ":" ORDER BY tz.id DESC ").(isset($all)?"LIMIT 50":"LIMIT 20");
	}
	
	$cols[tzid]="ID";
	$cols[tz_date]="����";
	$cols[nik]="��� ��������";

	$table = new Table($processing_type,$processing_type,$sql,$cols);
	$table->title=$ordername;
	$table->addbutton=true;
	$table->show();
}
printpage();
?>