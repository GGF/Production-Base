<?
// Отображает запущенные платы
// временно пока не пойдут нормальными тз
include "head.php";

if (isset($show) || isset($edit) || isset($add))
{
	$r = getright($user);
	$posid=isset($show)?$id:(isset($edit)?$edit:$add);
	//print_r($HTTP_GET_VARS);
	$sql="SELECT *,tz.id AS tzid, blocks.sizex AS bsizex, blocks.sizey AS bsizey, blocks.id AS bid FROM posintz JOIN (tz,filelinks) ON (tz.id=posintz.tz_id AND tz.file_link_id=filelinks.id) LEFT JOIN (blocks) ON (blocks.id=block_id) WHERE posintz.id='$posid'";
	//echo $sql;
	$rs=mysql_fetch_array(mysql_query($sql));
	if ($r["nzap"]["edit"]) {
		echo "<a href='file://servermpp/".str_replace("\\","/",str_replace(":","",$rs["file_link"]))."'>".$rs["file_link"]."</a><br>";
	} else {
		// только просмотр
		echo "<a href='#' onclick=\"window.open('nzap.php?print=tz&posid=$posid');void(0);\">".$rs["file_link"]."</a><br>";
	}
	echo "&nbsp;".$rs["blockname"]."&nbsp;&nbsp;&nbsp;".$rs["numbers"]."шт.&nbsp;&nbsp;&nbsp;".ceil($rs["bsizex"])."x".ceil($rs["bsizey"])." ".$rs["mask"]." ".$rs["mark"]." ".($rs["template_make"]=='0'?$rs["template_check"]:$rs["template_make"])."шаб. <br>";
	$sql="SELECT *, boards.sizex AS psizex, boards.sizey AS psizey, boards.id AS bid FROM blockpos JOIN (customers,blocks,boards) ON (customers.id=boards.customer_id AND blocks.id=block_id AND boards.id=board_id) WHERE block_id='".$rs["bid"]."'";
	//echo $sql;
	$res=mysql_query($sql);
	$nz = 0; // максимальное количество заготовок по количеству плат в блоке
	$nl = 0; // максимальное количество слоев на плате в блоке, хотя бред
	$cl = 0; // класс платы, наибольший по позициям
	$piz = 0; // число плат на заготовке (сумма по блоку)
	while ($rs1=mysql_fetch_array($res)) {
		echo "&nbsp&nbsp&nbsp;".$rs1["board_name"]."&nbsp;&nbsp;".$rs1["psizex"]."x".$rs1["psizey"]."&nbsp;".$rs1["nib"]."-".$rs1["nx"]."x".$rs1["ny"]."&nbsp;".$rs1["layers"]."сл [".$rs1["mask"]."] [".$rs1["mark"]."] <br>";
		$sql="SELECT numbers FROM posintz WHERE tz_id='".$rs["tzid"]."' AND board_id='".$rs1["bid"]."'";
		$rs2=mysql_fetch_array(mysql_query($sql));
		$nz = max($nz,ceil($rs2["numbers"]/$rs1["nib"]));
		$nl = max($nl,$rs1["layers"]);
		$cl = max($cl,$rs1["class"]);
		$piz += $rs1["nib"];
		$customer=$rs1["customer"];
	}
	//echo $nz." ".$nl."<br>";
	if ($r["nzap"]["edit"]) {
		if ($nl>2) {
			// многослойкау радара партии по одной
			if ($rs["customer_id"] == '8') // радар
				$zip = 1;
			else
				$zip = 5;
			// если первичный запуск - мастерплата
			if ($rs["first"]=='1' || $rs["template_make"]>0) 
				{
				$sql="SELECT * FROM masterplate WHERE tz_id='".$rs["tz_id"]."' AND posintz='".$rs["posintz"]."'";
				if (mysql_num_rows(mysql_query($sql))==0) {
					echo "<input type=button id=maspl value='Мастерплата' onclick=\"$('#maspl').hide();window.open('nzap.php?print=mp&tzid=".$rs["tz_id"]."&posintz=".$rs["posintz"].($i==ceil($nz/$zip)?"&last":"")."&drlname=".$rs["drlname"]."&sizex=".ceil($rs["bsizex"])."&sizey=".ceil($rs["bsizey"])."&customer=".($customer)."&blockname=".urlencode($rs["blockname"])."')\"'><br>";
				}
			}
			$mpp = 1;
		} else {
			// одно-двухстороняя
			$zip =25;
			$dpp = 1;
			// если больше пяти заготовок - мастерплата, хотя обойдутся без соповодительного листа
		}
		for ($i=1;$i<=ceil($nz/$zip);$i++) {
			$sql = "SELECT lanch.id,file_link FROM lanch JOIN filelinks ON (file_link_id=filelinks.id) WHERE tz_id='".$rs["tz_id"]."' AND pos_in_tz='".$rs["posintz"]."' AND part='$i'";
			//echo $sql;
			if ($rs3 = mysql_fetch_array(mysql_query($sql))) {
				echo "<a href='file://servermpp/".str_replace("\\","/",str_replace(":","",$rs3[1]))."'>СЛ-".$rs3[0]."</a><br>";
			} else {
				// не запущено
				//print_r($rs);echo "<br>";echo "<br>";
				//print_r($rs1);echo "<br>";echo "<br>";
				//print_r($rs2);echo "<br>";echo "<br>";
				//echo "<input type=button id=sl$i value='$i партия' onclick=\"$('#sl$i').hide();window.open('nzap.php?print=sl".(isset($dpp)?"&dpp":"&mpp")."&class=".$cl."&party=$i&posid=$posid&tzid=".$rs["tz_id"]."&posintz=".$rs["posintz"].($i==ceil($nz/$zip)?"&last":"")."&piz=$piz&zip=$zip&nz=$nz&drlname=".$rs["drlname"]."&plate_id=".$rs["plate_id"]."&sizex=".ceil($rs["bsizex"])."&sizey=".ceil($rs["bsizey"])."&customer=".($customer)."&blockname=".urlencode($rs["blockname"]).($i==ceil($nz/$zip)?"&last":"")."');".($i==ceil($nz/$zip)?"$('#".$trid."').hide();closeedit()":"")."\"'>";
				echo "<input type=button id=sl$i value='$i партия' onclick=\"var html=$.ajax({url:'nzap.php',data:'print=sl".(isset($dpp)?"&dpp":"&mpp")."&class=".$cl."&party=$i&posid=$posid&tzid=".$rs["tz_id"]."&posintz=".$rs["posintz"].($i==ceil($nz/$zip)?"&last":"")."&numbers=".$rs["numbers"]."&piz=$piz&zip=$zip&nz=$nz&drlname=".$rs["drlname"]."&plate_id=".$rs["plate_id"]."&sizex=".ceil($rs["bsizex"])."&sizey=".ceil($rs["bsizey"])."&customer=".($customer)."&blockname=".urlencode($rs["blockname"]).($i==ceil($nz/$zip)?"&last":"")."',async: false}).responseText;$('#sl$i').replaceWith(html);".($i==ceil($nz/$zip)?"$('#".$trid."').hide();":"")."\">";
			}
		}
	}
	echo "<br><input type=button onclick='closeedit()' value='Закрыть'>";
}
elseif (isset($delete)) {
	// не удаление а пометка что запущены
	// или удалять ???
	// 26-01-2010 11:41
	// Удалять все таки, чтоб не валялись в запущенных, удалим позицию из ТЗ
	$sql="DELETE FROM posintz WHERE id='$delete'";
	mylog1($sql);
	mysql_query($sql);
}
elseif (isset($print))
{
	if ($print=='mp') 
	{
		// userid голобальный, а это не функция
		// date для базы - NOW()
		//print_r($HTTP_GET_VARS);
		
		$sql="SELECT * FROM masterplate WHERE tz_id='$tzid' AND posintz='$posintz'";
		$res = mysql_query($sql);
		if (mysql_num_rows($res) == 0){
			$sql="INSERT INTO masterplate (tz_id,posintz,mpdate,user_id) VALUES ('$tzid','$posintz',Now(),'$userid')";
			debug($sql);
			mysql_query($sql);
			$mp_id = mysql_insert_id();
			if (!$mp_id) my_error("не удалось обновить таблицу мастерплат");
				
		} else {
			$sql="UPDATE masterplate SET mpdate=NOW(), user_id='$userid' WHERE tz_id='$tzid' AND posintz='$posintz'";
			if (!mysql_query($sql)) my_error("не удалось обновить таблицу мастерплат");
			$rs=mysql_fetch_array($res);
			$mp_id = $rs["id"];
		}
		$date = date("d-m-Y");
		if (!is_dir(mb_convert_encoding("/home/common/z/Заказчики/".$customer,"KOI8R","cp1251"))) {
			mkdir(mb_convert_encoding("/home/common/z/Заказчики/".$customer,"KOI8R","cp1251"));
			chmod(mb_convert_encoding("/home/common/z/Заказчики/".$customer,"KOI8R","cp1251"),0777);
		}
		if (!is_dir(mb_convert_encoding("/home/common/z/Заказчики/".$customer."/".$blockname,"KOI8R","cp1251"))) {
			mkdir(mb_convert_encoding("/home/common/z/Заказчики/".$customer."/".$blockname,"KOI8R","cp1251"));
			chmod(mb_convert_encoding("/home/common/z/Заказчики/".$customer."/".$blockname,"KOI8R","cp1251"),0777);
		}
		if (!is_dir(mb_convert_encoding("/home/common/z/Заказчики/".$customer."/".$blockname."/Мастерплаты","KOI8R","cp1251"))) {
			mkdir (mb_convert_encoding("/home/common/z/Заказчики/".$customer."/".$blockname."/Мастерплаты","KOI8R","cp1251"));
			chmod (mb_convert_encoding("/home/common/z/Заказчики/".$customer."/".$blockname."/Мастерплаты","KOI8R","cp1251"),0777);
		}
		$filename = mb_convert_encoding("/home/common/z/Заказчики/".$customer."/".$blockname."/Мастерплаты/МП-".$date."-".$mp_id.".xml","KOI8R","cp1251");
		$excel .= file_get_contents("mp.xml");
		$excel = str_replace("_number_",sprintf("%08d",$mp_id),$excel);
		$customer = mb_convert_encoding($customer,"UTF-8","cp1251");
		$excel = str_replace("_customer_",$customer,$excel);
		$excel = str_replace("_date_",$date,$excel);
		$blockname = mb_convert_encoding($blockname,"UTF-8","cp1251");
		$excel = str_replace("_blockname_",$blockname,$excel);
		$excel = str_replace("_sizex_",$sizex,$excel);
		$excel = str_replace("_sizey_",$sizey,$excel);
		$excel = str_replace("_drlname_",$drlname,$excel);
		// записать файл
		$file = fopen($filename,"w");
		fwrite($file,$excel);
		fclose($file);
		chmod($filename,0777);
		header("Content-type: application/vnd.ms-excel");
		//header("Content-type: application/vnd.ms-xpsdocument");
		header("content-disposition: attachment;filename=mp.xml");
		echo $excel;

	} 
	elseif ($print=='sl') 
	{
		// сделать запуск
		// userid глобальный
		//print_r($_GET); exit;
		if (isset($dozap)) {
			$sql = "SELECT posintz.tz_id,posintz.plate_id,posintz.board_id,posintz.block_id,posintz.posintz,posintz.id FROM posintz JOIN lanch ON posintz.id=lanch.pos_in_tz_id WHERE lanch.id='$posid'";
			//echo $sql;
			$rs=mysql_fetch_array(mysql_query($sql));
			//print_r($rs);
			$tzid=$rs[0];
			$plate_id = $rs[1];
			$boardid = $rs[2];
			$blockid = $rs[3];
			$posintz=$rs[4];
			$posid=$rs[5];
			$sql="SELECT MAX(part)+1 FROM lanch WHERE tz_id='$tzid' AND pos_in_tz='$posintz' ";
			//echo $sql; 
			$rs=mysql_fetch_array(mysql_query($sql));
			//print_r($rs);
			$party = $rs[0];
			$numbp = $dozap;
			$sql = "SELECT nib FROM blockpos WHERE block_id='$blockid' AND board_id='$boardid'";
			//echo $sql; exit;
			$rs=mysql_fetch_array(mysql_query($sql));
			//print_r($rs);
			$piz = $rs[0];
			$numbz = ceil($dozap/$piz);
			$comment = "Дозапуск";
			$sql="SELECT customer,blockname FROM blocks JOIN customers ON customers.id=blocks.customer_id WHERE blocks.id='$blockid'";
			$rs=mysql_fetch_array(mysql_query($sql));
			$customer = $rs[0];
			$blockname = $rs[1];
			$sql="SELECT layers FROM boards WHERE id='$boardid'";
			$rs=mysql_fetch_array(mysql_query($sql));
			if ($rs[0]>2) 
				$mpp=1;
			else 
				$dpp=1;
		} else {
			$numbz = $nz<=$zip?$nz:(isset($last)?($nz-($party-1)*$zip):$zip);
			$numbp = $nz<=$zip?$numbers:(isset($last)?($numbers-($party-1)*$zip*$piz):$zip*$piz);
		}
		$sql="SELECT * FROM lanch WHERE tz_id='$tzid' AND pos_in_tz='$posintz' AND part='$party'";
		$res = mysql_query($sql);
		if (mysql_num_rows($res) == 0)
		{
			$sql="INSERT INTO lanch (ldate,board_id,part,numbz,numbp,user_id,pos_in_tz,tz_id,pos_in_tz_id) VALUES (NOW(),'$plate_id','$party','$numbz','$numbp','$userid','$posintz','$tzid','$posid')";
			mysql_query($sql);
			$lanch_id = mysql_insert_id();
			if (!$lanch_id) my_error();
		} 
		else 
		{
			$rs = mysql_fetch_array($res);
			$lanch_id = $rs["id"];
			$sql="UPDATE lanch SET ldate=NOW(), board_id='$plate_id', numbz='$numbz', numbp='$numbp', user_id='$userid', tz_id='$tzid', pos_in_tz_id='$posid' WHERE id='$lanch_id'";
			mysql_query($sql);
		}
		$date = date("d-m-Y");
		// Определим идентификатор коментария
		{
		$sql="SELECT id FROM coments WHERE comment='$comment'";
		debug($sql);
		$res = mysql_query($sql);
		if ($rs=mysql_fetch_array($res)){
			$comment_id = $rs["id"];
		} else {
			$sql="INSERT INTO coments (comment) VALUES ('$comment')";
			debug($sql);
			mysql_query($sql);
			$comment_id = mysql_insert_id();
			if (!$comment_id) my_error();
		}
		}
		// Определим идентификатор файловой ссылки
		{
		$file_link = 'z:\\\\Заказчики\\\\'.$customer.'\\\\'.$blockname.'\\\\запуски\\\\СЛ-'.$date.'-'.$lanch_id.'.xml';
		$sql="SELECT id FROM filelinks WHERE file_link='$file_link'";
		debug($sql);
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
		}
		$sql="UPDATE lanch SET file_link_id='$file_id', comment_id='$comment_id' WHERE id='$lanch_id'";
		mysql_query($sql);
		// обновим таблицу запусков
		{
			
			$sql="DELETE FROM lanched WHERE board_id='$plate_id'";
			mysql_query($sql);
			/*
			//$sql="INSERT INTO lanched SELECT board_id,MAX(ldate),SUM(numbp) FROM `lanch` WHERE board_id='$plate_id' GROUP BY board_id";
			$sql="INSERT INTO lanched SELECT board_id,MAX(ldate) FROM `lanch` WHERE board_id='$plate_id' GROUP BY board_id";//без суммы быстрее
			mysql_query($sql);
			*/
			$sql="INSERT INTO lanched (board_id,lastdate) VALUES ('$plate_id',NOW())";
			mylog1($sql);
			mysql_query($sql);
		}
		// если все запущены - исключить из запуска
		if (!isset($dozap)){
			$sql="SELECT SUM(numbp) FROM lanch WHERE pos_in_tz_id='$posid' GROUP BY pos_in_tz_id";
			$rs=mysql_fetch_array(mysql_query($sql));
			//echo $numbers."___".$rs[0];
			if ($rs[0]>=$nz) {
				$sql="UPDATE posintz SET ldate=NOW(), luser_id='$userid' WHERE id='$posid'";
				mysql_query($sql);
			}
		}
		// создать каталог и имя файла для запуска
		{
		$dir = mb_convert_encoding("/home/common/z/Заказчики/".$customer,"KOI8R","cp1251");
		if (!is_dir($dir)) {
			mkdir ($dir);
			chmod ($dir,0777);
		}
		$dir = mb_convert_encoding("/home/common/z/Заказчики/".$customer."/".$blockname,"KOI8R","cp1251");
		if (!is_dir($dir)) {
			mkdir ($dir);
			chmod ($dir,0777);
		}
		$dir = mb_convert_encoding("/home/common/z/Заказчики/".$customer."/".$blockname."/запуски","KOI8R","cp1251");
		if (!is_dir($dir)) {
			mkdir ($dir);
			chmod ($dir,0777);
		}
		$filename = mb_convert_encoding("/home/common/z/Заказчики/".$customer."/".$blockname."/запуски/СЛ-".$date."-".$lanch_id.".xml","KOI8R","cp1251");
		}
		if (isset($dpp)) 
		{
			// получить данные в переменные
			$sql = "SELECT 
				orderdate AS ldate, 
				orders.number AS letter,
				fullname AS custom, 
				drlname, 
				scomp,
				ssolder AS ssold,
				blocks.sizex,
				blocks.sizey,
				blockname,
				boards.mask,
				boards.mark,
				boards.rmark,
				texеolite AS mater,
				textolitepsi AS psimat,
				pitz_mater AS pmater,
				pitz_psimat AS ppsimat,
				boards.thickness AS tolsh,
				layers,
				immer,
				priem,
				aurum,
				posintz.numbers AS numbers, 
				blockpos.nib AS piz 
				FROM posintz JOIN (customers,blocks,tz,orders,boards,blockpos) ON (blockpos.block_id=blocks.id AND posintz.board_id=boards.id AND blocks.id=posintz.block_id AND customers.id=orders.customer_id AND posintz.tz_id=tz.id AND tz.order_id=orders.id) 
				WHERE posintz.id='$posid'";
			$res = mysql_query($sql);
			if ($rs=mysql_fetch_array($res)) {
				foreach ($rs as $key => $val) {
					if (mb_detect_encoding($val)!="UTF-8") $$key=mb_convert_encoding($val,"UTF-8","cp1251");
				}
			}
			// сделать тсобственно сопроводительный
			if (isset($dozap)) 
			{
				$nz=ceil($dozap/$piz);
				$zag = $nz;
				$ppart = $dozap;
				$part =$party;
			}
			else
			{
				$nz = ceil($numbers/$piz); // общее количество заготовок
				$zag = ($party*$zip>=$nz)?($nz-($party-1)*$zip):$zip;
				$ppart = (ceil($nz/$zip)>1)?(isset($last)?($numbers-(ceil($nz/$zip)-1)*$piz*$zip)."($numbers)":$zag*$piz."($numbers)"):$numbers;
				$part = (ceil($nz/$zip)>1)?$party."(".ceil($nz/$zip).")":$party;
			}
			$excel .= file_get_contents("sl.xml");
			$excel = str_replace("_type_",($layers=='1'?mb_convert_encoding("ОПП","UTF-8","cp1251"):mb_convert_encoding("ДПП","UTF-8","cp1251")),$excel);
			$excel = str_replace("_letter_",$letter,$excel);
			$excel = str_replace("_ldate_",$ldate,$excel);
			$excel = str_replace("_number_",sprintf("%08d",$lanch_id),$excel);
			$excel = str_replace("_custom_",$custom,$excel);
			$excel = str_replace("_drlname_",$drlname,$excel);
			$excel = str_replace("_blockname_",$blockname,$excel);
			$excel = str_replace("_zag_",$zag,$excel);
			$excel = str_replace("_mark_",$mark,$excel);
			$excel = str_replace("_rmark_",($rmark=='1'?"+":"-"),$excel);
			$excel = str_replace("_sizex_",ceil($sizex),$excel);
			$excel = str_replace("_sizey_",ceil($sizey),$excel);
			$excel = str_replace("_datez_",$date,$excel);
			$excel = str_replace("_ppart_",$ppart,$excel);
			$excel = str_replace("_part_",$part,$excel);
			$mater = ($pmater==''?$mater:$pmater);
			$excel = str_replace("_mater_",$mater."-".$tolsh,$excel);
			$excel = str_replace("_mask_",$mask,$excel);
			$excel = str_replace("_pokr_",($immer=='1'?mb_convert_encoding("Иммерсионное золото","UTF-8","cp1251"):mb_convert_encoding("ПОС61","UTF-8","cp1251")),$excel);
			$excel = str_replace("_priem_",$priem,$excel);
			$scomp = sprintf("%3.2f",$scomp/10000);
			$excel = str_replace("_scomp_",$scomp,$excel);
			$ssold = sprintf("%3.2f",$ssold/10000);
			$excel = str_replace("_ssold_",$ssold,$excel);
			$psimat = ($ppsimat==''?$psimat:$ppsimat);
			$excel = str_replace("_psimat_",$psimat."-".$tolsh,$excel);
			$excel = str_replace("_aurum_",($aurum=='1'?mb_convert_encoding("Золочение контактов","UTF-8","cp1251"):""),$excel);
			$excel = str_replace("_dozap_",(isset($dozap)?mb_convert_encoding("ДОЗАПУСК","UTF-8","cp1251"):""),$excel);
		} 
		elseif (isset($mpp))
		{
			// получить данные в переменные
			$sql = "SELECT 
			orderdate AS ldate, 
			orders.number AS letter, 
			fullname AS custom, 
			drlname, 
			scomp,
			ssolder AS ssold,
			blocks.sizex AS sizex,
			blocks.sizey AS sizey, 
			boards.sizex AS psizex, 
			boards.sizey AS psizey,
			blockname,
			boards.mask,
			boards.mark,
			boards.rmark,
			texеolite AS mater,
			textolitepsi AS psimat,
			pitz_mater AS pmater,
			pitz_psimat AS ppsimat,
			boards.thickness AS tolsh,
			layers,
			immer,
			priem,
			aurum,
			posintz.numbers AS numbers,
			blockpos.nib AS piz,
			eltest.type AS etest,
			numlam AS lamel,
			glasscloth AS stkan,
			class
			FROM posintz 
			JOIN (customers,blocks,tz,orders,boards,blockpos,eltest) 
			ON (blockpos.block_id=blocks.id AND posintz.board_id=boards.id AND blocks.id=posintz.block_id AND customers.id=orders.customer_id AND posintz.tz_id=tz.id AND tz.order_id=orders.id AND eltest.board_id=boards.id) 
			WHERE posintz.id='$posid'";
			$res = mysql_query($sql);
			if ($rs=mysql_fetch_array($res)) {
				//print_r($rs);exit;
				foreach ($rs as $key => $val) {
					if (mb_detect_encoding($val)!="UTF-8") $$key=mb_convert_encoding($val,"UTF-8","cp1251");
				}
			}
			if (isset($dozap)) 
			{
				$nz=ceil($dozap/$piz);
				$zag = $nz;
				$ppart = $dozap;
				$part =$party;
			}
			else
			{
				$nz = ceil($numbers/$piz*1.15); // общее количество заготовок + 15%
				$zag = ($party*$zip>=$nz)?($nz-($party-1)*$zip):$zip;
				$ppart = (ceil($nz/$zip)>1)?(isset($last)?($numbers-(ceil($numbers/$piz/$zip)-1)*$piz*$zip)."($numbers)":$zag*$piz."($numbers)"):$numbers;
			}
			// сделать тсобственно сопроводительный
			$excel .= file_get_contents("slmpp.xml");
			$excel = str_replace("_number_",sprintf("%08d",$lanch_id),$excel);
			$excel = str_replace("_class_",$class,$excel);
			$excel = str_replace("_custom_",$custom,$excel);
			$excel = str_replace("_letter_",$letter,$excel);
			$excel = str_replace("_ldate_",$ldate,$excel);
			$excel = str_replace("_datez_",$date,$excel);
			$excel = str_replace("_blockname_",$blockname,$excel);
			$excel = str_replace("_drlname_",$drlname,$excel);
			$mater = ($pmater==''?$mater:$pmater);
			$excel = str_replace("_mater_",$mater,$excel);
			$excel = str_replace("_stkan_",$stkan,$excel);
			$excel = str_replace("_sizex_",ceil($sizex),$excel);
			$excel = str_replace("_sizey_",ceil($sizey),$excel);
			$excel = str_replace("_zag_",$zag."($nz)",$excel);
			$excel = str_replace("_psizex_",ceil($psizex),$excel);
			$excel = str_replace("_psizey_",ceil($psizey),$excel);
			$excel = str_replace("_ppart_",$ppart,$excel);
			$excel = str_replace("_tolsh_",$tolsh,$excel);
			$excel = str_replace("_liststkan_",$liststkan,$excel);
			$excel = str_replace("_raspstkan_",$raspstkan,$excel);
			$excel = str_replace("_rtolsh_",$rtolsh,$excel);
			$excel = str_replace("_bmat1_",$bmat1,$excel);
			$excel = str_replace("_bmat2_",$bmat2,$excel);
			$excel = str_replace("_bmat3_",$bmat3,$excel);
			$excel = str_replace("_bmat4_",$bmat4,$excel);
			$excel = str_replace("_bmat5_",$bmat5,$excel);
			$excel = str_replace("_bmat6_",$bmat6,$excel);
			$excel = str_replace("_bmat7_",$bmat7,$excel);
			$excel = str_replace("_bmat8_",$bmat8,$excel);
			$excel = str_replace("_bmat9_",$bmat9,$excel);
			$excel = str_replace("_sloi1_",$sloi1,$excel);
			$excel = str_replace("_sloi2_",$sloi2,$excel);
			$excel = str_replace("_sloi3_",$sloi3,$excel);
			$excel = str_replace("_sloi4_",$sloi4,$excel);
			$excel = str_replace("_sloi5_",$sloi5,$excel);
			$excel = str_replace("_sloi6_",$sloi6,$excel);
			$excel = str_replace("_sloi7_",$sloi7,$excel);
			$excel = str_replace("_sloi8_",$sloi8,$excel);
			$excel = str_replace("_sloi9_",$sloi9,$excel);
			$excel = str_replace("_drlname1_",$drlname,$excel);
			$excel = str_replace("_drlname2_",$drlname,$excel);
			$scomp = sprintf("%3.2f",$scomp/10000);
			$excel = str_replace("_scomp_",$scomp,$excel);
			$ssold = sprintf("%3.2f",$ssold/10000);
			$excel = str_replace("_ssold_",$ssold,$excel);
			$excel = str_replace("_etest_",$etest,$excel);
			$excel = str_replace("_priemo_",(strstr($priem,mb_convert_encoding("ОТК","UTF-8","cp1251"))?"+":"-"),$excel);
			$excel = str_replace("_priemp_",(strstr($priem,mb_convert_encoding("ПЗ","UTF-8","cp1251"))?"+":"-"),$excel);
			$excel = str_replace("_impokr_",($immer=='1'?"+":"-"),$excel);
			$excel = str_replace("_lamel_",($aurum=='1'?$lamel:"-"),$excel);
			$excel = str_replace("_mark_",$mark,$excel);
			$excel = str_replace("_rmark_",($rmark=='1'?"+":"-"),$excel);
			$excel = str_replace("_maskz_",(strstr($mask,mb_convert_encoding("Ж","UTF-8","cp1251"))?"+":"-"),$excel);
			$excel = str_replace("_masks_",(strstr($mask,mb_convert_encoding("К","UTF-8","cp1251"))?"+":"-"),$excel);
			$excel = str_replace("_osuk_",$osuk,$excel);
			$excel = str_replace("_dozap_",(isset($dozap)?mb_convert_encoding("ДОЗАПУСК","UTF-8","cp1251"):""),$excel);
		}
		// записать файл
		$file = fopen($filename,"w");
		fwrite($file,$excel);
		fclose($file);
		chmod($filename,0777);
		/*
		header("Content-type: application/vnd.ms-excel");
		header("content-disposition: attachment;filename=mp.xml");
		echo $excel;
		*/
		header('Content-type: text/html; charset=windows-1251'); // потому что в для принта не посылается
		$sql="SELECT file_link FROM lanch JOIN (filelinks) ON (file_link_id=filelinks.id) WHERE lanch.id='$lanch_id'";
		$rs=mysql_fetch_array(mysql_query($sql));
		echo "<a href='file://servermpp/".str_replace("\\","/",str_replace(":","",$rs[0]))."'>СЛ-$lanch_id</a><br>";
	} 
	elseif ($print="tz") 
	{
		$sql="SELECT file_link FROM posintz JOIN (filelinks,tz) ON (posintz.tz_id=tz.id AND tz.file_link_id=filelinks.id) WHERE posintz.id='$posid'";
		//echo $sql;
		$rs=mysql_fetch_array(mysql_query($sql));
		//echo str_replace("\\","/",str_replace(":","",$rs[0]));
		$filelink =  mb_convert_encoding("/home/common/".str_replace("заказчики","Заказчики",str_replace("\\","/",str_replace(":","",$rs[0]))),"KOI8R","cp1251");
		//echo $filelink;
		$file = file_get_contents($filelink);
		//echo $file;
		header("Content-type: application/vnd.ms-excel");
		//header("content-disposition: attachment;filename=mp.xml");
		echo $file;
	}
}
else
{
// вывести таблицу

	// sql
	//$sql="CREATE TEMPORARY TABLE tmp (board_id BIGINT(10), lastdate DATE, number INT(10))";
	//mysql_query($sql);
	//$sql="TRUNCATE TABLE `tmp`";
	//mysql_query($sql);
	//$sql="LOCK TABLES lanch read";
	//mysql_query($sql);
	//$sql="INSERT INTO tmp SELECT board_id,MAX(ldate),SUM(numbp) FROM `lanch` GROUP BY board_id";
	//mysql_query($sql);
	//$sql="SELECT *,posintz.id AS nzid,posintz.id FROM posintz LEFT JOIN (lanch) ON (lanch.tz_id = posintz.tz_id AND lanch.pos_in_tz = posintz.posintz) LEFT JOIN (tmp) ON (posintz.plate_id=tmp.board_id) JOIN (plates,tz,filelinks,customers,orders) ON (tz.order_id=orders.id AND plates.id=posintz.plate_id  AND posintz.tz_id=tz.id AND tz.file_link_id=filelinks.id AND plates.customer_id=customers.id) WHERE posintz.tz_id != '0' AND (lanch.id IS NULL OR (lanch.ldate > '2009-10-30' AND posintz.numbers > tmp.number)) ".(isset($find)?"AND (plates.plate LIKE '%$find%' OR filelinks.file_link LIKE '%$find%' OR orders.number LIKE '%$find%') ":"").($order!=''?"ORDER BY ".$order." ":"ORDER BY customers.customer,tz.id,posintz.id ").(isset($all)?"":"LIMIT 20");
	//$sql="SELECT *,posintz.id AS nzid,posintz.id FROM posintz LEFT JOIN (lanch) ON (lanch.tz_id = posintz.tz_id AND lanch.pos_in_tz = posintz.posintz) LEFT JOIN (lanched) ON (posintz.plate_id=lanched.board_id) JOIN (plates,tz,filelinks,customers,orders) ON (tz.order_id=orders.id AND plates.id=posintz.plate_id  AND posintz.tz_id=tz.id AND tz.file_link_id=filelinks.id AND plates.customer_id=customers.id) WHERE posintz.tz_id != '0' AND (lanch.id IS NULL OR lanched.number IS NULL OR (lanch.ldate > '2009-10-30' AND posintz.numbers > lanched.number)) ".(isset($find)?"AND (plates.plate LIKE '%$find%' OR filelinks.file_link LIKE '%$find%' OR orders.number LIKE '%$find%') ":"").($order!=''?"ORDER BY ".$order." ":"ORDER BY customers.customer,tz.id,posintz.id ").(isset($all)?"":"LIMIT 20");
	$sql="SELECT *,posintz.id AS nzid,posintz.id FROM posintz LEFT JOIN (lanched) ON (posintz.plate_id=lanched.board_id) JOIN (plates,tz,filelinks,customers,orders) ON (tz.order_id=orders.id AND plates.id=posintz.plate_id  AND posintz.tz_id=tz.id AND tz.file_link_id=filelinks.id AND plates.customer_id=customers.id) WHERE posintz.ldate = '0000-00-00' ".(isset($find)?"AND (plates.plate LIKE '%$find%' OR filelinks.file_link LIKE '%$find%' OR orders.number LIKE '%$find%') ":"").($order!=''?"ORDER BY ".$order." ":"ORDER BY customers.customer,tz.id,posintz.id ").(isset($all)?"":"LIMIT 20");
	//echo $sql;

	$type="nzap";
	$cols["№"]="№";
	$cols[nzid]="ID";
	$cols[customer]="Заказчик";
	$cols[number]="Заказ";
	$cols[plate]="Плата";
	$cols[numbers]="Кол-во";
	//$cols[tz_date]="Дата ТЗ";
	//$cols[file_link]="Файл";
	$cols[lastdate]="Посл. зап";
	
	//$print[zapusk]="Запустить";
	
	$addbutton=false;

	$opentype = "nzap";

	include "table.php";

	//$sql="UNLOCK TABLES";
	//mysql_query($sql);
	//$sql="DROP TABLE tmp";
	//mysql_query($sql);
}

?>