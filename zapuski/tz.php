<?
// �������� � �������������� ��� �������

include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php";
authorize(); // ����� �����������

if (isset($add) ) {
	if (isset($typetz)) 
	{
		// np �� ���� ������������� - ������ ��������� � ������� ����� � �������������
		// ��������� ������� � ������
		$sql="SELECT COUNT(*)+1 FROM tz WHERE order_id='$orderid'";
		$rs=mysql_fetch_array(mysql_query($sql));
		$pos_in_order = $rs[0];

		// $userid ����������

		// ����������
		// ������� ���� � ���������
		// ��������� ���������
		$sql="SELECT number,orderdate,customer, fullname FROM orders JOIN customers ON customers.id=customer_id WHERE orders.id='$orderid'";
		//echo $sql;
		$rs=mysql_fetch_array(mysql_query($sql));
		$order = $rs["number"];
		$customer = $rs["customer"];
		$fullname = $rs["fullname"];
		$odate = $rs["orderdate"];
		$cdate = date("m-d-Y");
		
		$dir=mb_convert_encoding("/home/common/t/������ ��������� ����/������/".$customer,"KOI8R","cp1251");
		if (!is_dir($dir)) {
			mkdir($dir);
			chmod($dir,0777);
		}
		
		do {
			$filename = mb_convert_encoding("/home/common/t/������ ��������� ����/������/".$customer."/".str_replace("\'","-",str_replace("\"","-",str_replace("*","-",str_replace("/","-",str_replace("\\","-",$rs["number"])))))." �� ".$rs["orderdate"]." ".$pos_in_order." ".($typetz=="mpp"?"���":"���").".xls","KOI8R","cp1251");
			$fe = file_exists($filename);
			if ($fe) $pos_in_order++;
		} while ($fe);
		
		// ��������� ������������� �������� ������
		$file_link = "t:\\\\������ ��������� ����\\\\������\\\\".$customer."\\\\".str_replace("\'","-",str_replace("\"","-",str_replace("*","-",str_replace("/","-",str_replace("\\","-",$rs["number"])))))." �� ".$rs["orderdate"]." ".$pos_in_order." ".($typetz=="mpp"?"���":"���").".xls";
		$sql="SELECT id FROM filelinks WHERE file_link='$file_link'";
		$res = mysql_query($sql);
		if ($rs=mysql_fetch_array($res)){
			$file_id = $rs["id"];
		} else {
			$sql="INSERT INTO filelinks (file_link) VALUES ('$file_link')";
			debug($sql);
			mysql_query($sql);
			$file_id = mysql_insert_id();
			if (!$file_id) my_error();
		}
		
		$sql = "INSERT INTO tz (order_id,tz_date,user_id,pos_in_order,file_link_id) VALUES ('$orderid',NOW(),'$userid','$pos_in_order','$file_id')";
		mylog($sql);
		if (!mysql_query($sql)) {
			my_error("�� ������� ������ ��������� � ������� tz!!!");
		} else {
			$tzid = mysql_insert_id();
			echo "<script>updatetable('$tid','tz','');</script>";
			echo "<div style='margin:10px'>";
			echo "<a href='file://servermpp/".str_replace("\\","/",str_replace(":","",$file_link))."'>TZ-$tzid</a>";
			echo "</div>";
			$excel=file_get_contents($typetz=="mpp"?"tzmpp.xls":"tzdpp.xls");
			$file = fopen($filename,"w");
			fwrite($file,$excel);
			fclose($file);
			chmod($filename,0777);
			$file = fopen($filename.".txt","w");
			fwrite($file,$cdate."\n");
			fwrite($file,$fullname."\n");
			fwrite($file,$order."\n");
			fwrite($file,$odate."\n");
			fwrite($file,sprintf("%08d\n",$tzid));
			fclose($file);
			
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
elseif (isset($edit))
{
	// ���� ������
	$sql = "SELECT file_link FROM tz JOIN filelinks ON filelinks.id=tz.file_link_id WHERE tz.id='$edit'";
	//echo $sql;
	$rs=mysql_fetch_array(mysql_query($sql));
	echo "<div style='margin:10px'>";
	echo "<a href='file://servermpp/".str_replace("\\","/",str_replace(":","",$rs[0]))."'>TZ-$edit</a>";
	echo "</div>";
}
elseif (isset($delete)) 
{
	// ��������
	$sql = "DELETE FROM tz WHERE id='$delete'";
	mylog('tz',$delete);
	mysql_query($sql);
	// �������� ������
	$sql = "SELECT * FROM posintz WHERE tz_id='$delete'";
	$res = mysql_query($sql);
	while($rs=mysql_fetch_array($res)) {
		$delete = $rs["id"];
		$sql = "DELETE FROM posintz WHERE id='$delete'";
		mylog('posintz',$delete);
		mysql_query($sql);
	}
	echo "ok";
} 
elseif (isset($print)) 
{

} 
else 
{
	// ������
	if (isset($id)) $orderid=$id;

	$sql="SELECT *,tz.id as tzid,tz.id FROM `tz` JOIN (orders, customers, users,filelinks) ON ( tz.order_id = orders.id AND orders.customer_id = customers.id AND tz.user_id = users.id AND filelinks.id=tz.file_link_id) ".(isset($find)?"WHERE (number LIKE '%$find%')":"").(isset($orderid)?(isset($find)?"AND order_id='$orderid'":"WHERE order_id='$orderid'"):"").(isset($order)?" ORDER BY ".$order." ":" ORDER BY tz.id DESC ").(isset($all)?"":"LIMIT 20");
	//echo $sql;
	
	$cols[tzid]="ID";
	$cols[tz_date]="����";
	$cols[nik]="��� ��������";

	$table = new Table("tz","posintz",$sql,$cols);
	$table->title='����������';
	if (isset($orderid)) $table->idstr = "&orderid=$orderid";
	$table->addbutton=true;
	$table->show();
}
?>