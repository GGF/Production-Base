<?
// Отображает запущенные платы
require $_SERVER["DOCUMENT_ROOT"]."/lib/engine.php";
authorize(); // вызов авторизации
$processing_type=basename (__FILE__,".php");
// serialize form
if (isset(${'form_'.$processing_type})) extract(${'form_'.$processing_type});

if(!isset($print)) ob_start();


if (isset($edit)) 
{
	$r = getright();

	$posid=isset($show)?$id:(isset($edit)?$edit:$add);
	$sql="SELECT *,tz.id AS tzid, blocks.sizex AS bsizex, blocks.sizey AS bsizey, blocks.id AS bid FROM posintz JOIN (tz,filelinks) ON (tz.id=posintz.tz_id AND tz.file_link_id=filelinks.id) LEFT JOIN (blocks) ON (blocks.id=block_id) WHERE posintz.id='$posid'";
	//echo $sql;
	$rs=sql::fetchOne($sql);
	if ($r["nzap"]["edit"]) 
	{
		echo "<a class='filelink' href='".sharefilelink($rs["file_link"])."'>".$rs["file_link"]."</a><br>";
	} 
	else 
	{
		// только просмотр
		echo "<a href='#' class='filelink' onclick=\"window.open('nzap.php?print=tz&posid=$posid');void(0);\">".$rs["file_link"]."</a><br>";
	}
	echo "&nbsp;".$rs["blockname"]."&nbsp;&nbsp;&nbsp;".$rs["numbers"]."шт.&nbsp;&nbsp;&nbsp;".ceil($rs["bsizex"])."x".ceil($rs["bsizey"])." ".$rs["mask"]." ".$rs["mark"]." ".($rs["template_make"]=='0'?$rs["template_check"]:$rs["template_make"])."шаб. <br>";

	$sql="SELECT *, boards.sizex AS psizex, boards.sizey AS psizey, boards.id AS bid FROM blockpos JOIN (customers,blocks,boards) ON (customers.id=boards.customer_id AND blocks.id=block_id AND boards.id=board_id) WHERE block_id='".$rs["bid"]."'";
	//echo $sql;
	$res=sql::fetchAll($sql);
	$nz = 0; // максимальное количество заготовок по количеству плат в блоке
	$nl = 0; // максимальное количество слоев на плате в блоке, хотя бред
	$cl = 0; // класс платы, наибольший по позициям
	$piz = 0; // число плат на заготовке (сумма по блоку)
	foreach ($res as $rs1) {
		echo "&nbsp&nbsp&nbsp;".$rs1["board_name"]."&nbsp;&nbsp;".$rs1["psizex"]."x".$rs1["psizey"]."&nbsp;".$rs1["nib"]."-".$rs1["nx"]."x".$rs1["ny"]."&nbsp;".$rs1["layers"]."сл [".$rs1["mask"]."] [".$rs1["mark"]."] <br>";
		$sql="SELECT numbers FROM posintz WHERE tz_id='".$rs["tzid"]."' AND board_id='".$rs1["bid"]."'";
		$rs2=sql::fetchOne($sql);
		$nz = max($nz,ceil($rs2["numbers"]/$rs1["nib"]));
		$nl = max($nl,$rs1["layers"]);
		$cl = max($cl,$rs1["class"]);
		$piz += $rs1["nib"];
		$customer=$rs1["customer"];
	}
	//echo $nz." ".$nl."<br>";


	if ($r["nzap"]["edit"]) 
	{
		if ($nl>2) 
		{
			// многослойкау радара партии по одной
			if ($rs["customer_id"] == '8') // радар
				$zip = 1;
			else
				$zip = 5;
			// если первичный запуск - мастерплата
			if ($rs["first"]=='1' || $rs["template_make"]>0) 
			{
				$sql="SELECT * FROM masterplate WHERE posid='$posid'";
				$mp=sql::fetchOne($sql);
				if (empty($mp)) {
					echo "<input type=button class='partybutton' id=maspl value='Мастерплата' onclick=\"$('#maspl').remove();window.open('nzap.php?print=mp&posid=$posid')\"><br>";
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
			echo $i%5==0?"<br>":"";
			$rs3=sql::fetchOne($sql);
			if (!empty($rs3)) {
				echo "<a class='filelink' href='".sharefilelink($rs3[file_link])."'>СЛ-".$rs3[id]."</a>&nbsp;";
			} else {
				echo "<input type=button class='partybutton' id=sl$i value='$i партия' onclick=\"var html=$.ajax({url:'nzap.php',data:'print=sl".(isset($dpp)?"&dpp":"&mpp")."&party=$i&posid=$posid".($i==ceil($nz/$zip)?"&last":"")."',async: false}).responseText;$('#sl$i').replaceWith(html);".($i==ceil($nz/$zip)?"$('#".$trid."').hide();":"")."\">";
			}
		}
	}
	//echo "<br><input type=button onclick='closeedit()' value='Закрыть'>";
}
elseif (isset($delete)) 
{
	$sql="DELETE FROM posintz WHERE id='$delete'";
	sql::query($sql);
	echo "ok";
}
elseif (isset($print))
{
	if ($print=='mp') 
	{
		$sql="SELECT tz_id,posintz,block_id FROM posintz WHERE id='$posid'";
		$rs=sql::fetchOne($sql);
		$posintz=$rs[posintz];
		$tzid=$rs[tz_id];
		$block_id=$rs[block_id];
		$sql="SELECT * FROM masterplate WHERE tz_id='$tzid' AND posintz='$posintz'";
		$res=sql::fetchOne($sql);
		if (empty($res)){
			$sql="INSERT INTO masterplate (tz_id,posintz,mpdate,user_id,posid) VALUES ('$tzid','$posintz',Now(),'".$_SESSION[userid]."','$posid')";
			sql::query($sql);
			$mp_id = sql::lastId();
		} else {
			$sql="UPDATE masterplate SET mpdate=NOW(), user_id='".$_SESSION[userid]."' WHERE tz_id='$tzid' AND posintz='$posintz' AND posid='$posid'";
			$rs=sql::fetchOne($res);
			$mp_id = $res["id"];
		}
		$sql="SELECT * FROM blocks JOIN customers ON blocks.customer_id=customers.id WHERE blocks.id='$block_id'";
		$rs=sql::fetchOne($sql);
		$customer=$rs[customer];
		$blockname=$rs[blockname];
		$sizex=$rs[sizex];
		$sizey=$rs[sizey];
		$drlname=$rs[drlname];
		$date = date("d-m-Y");
		$filename = createdironserver("z:\\\\Заказчики\\\\".$customer."\\\\".$blockname."\\\\Мастерплаты\\\\МП-".date("Y-m-d")."-".$mp_id.".xml");
		//echo $filename;
		$excel = file_get_contents("mp.xml");
		$excel = str_replace("_number_",sprintf("%08d",$mp_id),$excel);
		$customer = mb_convert_encoding($customer,"UTF-8","cp1251");
		$excel = str_replace("_customer_",$customer,$excel);
		$excel = str_replace("_date_",$date,$excel);
		$blockname = mb_convert_encoding($blockname,"UTF-8","cp1251");
		$excel = str_replace("_blockname_",$blockname,$excel);
		$excel = str_replace("_sizex_",ceil($sizex),$excel);
		$excel = str_replace("_sizey_",ceil($sizey),$excel);
		$excel = str_replace("_drlname_",$drlname,$excel);
		// записать файл
		if($file = @fopen($filename,"w")) 
		{
			fwrite($file,$excel);
			fclose($file);
			chmod($filename,0777);
			header("Content-type: application/vnd.ms-excel");
			header("content-disposition: attachment;filename=mp.xml");
			echo $excel;
			exit;
		} else {
			echo "Не удалось создать файл";
			exit;
		}
	} 
	elseif ($print=='sl') 
	{
		if (isset($dozap)) 
		{
			$sql = "SELECT posintz.tz_id,posintz.plate_id,posintz.board_id,posintz.block_id,posintz.posintz,posintz.id FROM posintz JOIN lanch ON posintz.id=lanch.pos_in_tz_id WHERE lanch.id='$posid'";
			//echo $sql;
			$rs=sql::fetchOne($sql);
			sql::error(true);
			$tzid=$rs[tz_id];
			$plate_id = $rs[plate_id];
			$boardid = $rs[board_id];
			$blockid = $rs[block_id];
			$posintz=$rs[posintz];
			$posid=$rs[id ];
			$sql="SELECT MAX(part)+1 AS party FROM lanch WHERE tz_id='$tzid' AND pos_in_tz='$posintz' ";
			$rs=sql::fetchOne($sql);
			sql::error(true);
			$party = $rs[party];
			$numbp = $dozap;
			$sql = "SELECT nib FROM blockpos WHERE block_id='$blockid' AND board_id='$boardid'";
			$rs=sql::fetchOne($sql);
			$piz = $rs[nib];
			$numbz = ceil($dozap/$piz);
			$comment = "Дозапуск";
			$sql="SELECT customer,blockname FROM blocks JOIN customers ON customers.id=blocks.customer_id WHERE blocks.id='$blockid'";
			$rs=sql::fetchOne($sql);
			sql::error(true);
			$customer = $rs[customer];
			$blockname = $rs[blockname];
			$sql="SELECT layers FROM boards WHERE id='$boardid'";
			$rs=sql::fetchOne($sql);
			sql::error(true);
			if ($rs[layers]>2) 
				$mpp=1;
			else 
				$dpp=1;
		} 
		else 
		{
			$sql="SELECT * FROM posintz WHERE id='$posid'";
			$rs=sql::fetchOne($sql);
			$tzid=$rs[tz_id];
			$posintz=$rs[posintz];
			$numbers=$rs[numbers];
			$plate_id=$rs[plate_id];
			$block_id=$rs[block_id];
			
			
			$nz = 0; // максимальное количество заготовок по количеству плат в блоке
			$nl = 0; // максимальное количество слоев на плате в блоке, хотя бред
			$cl = 0; // класс платы, наибольший по позициям
			$piz = 0; // число плат на заготовке (сумма по блоку)
			//$sql="SELECT * FROM blockspos JOIN blocks ON blockpos.block_id=blocks.id WHERE blocks.id='$block_id'";
			$sql="SELECT *, boards.sizex AS psizex, boards.sizey AS psizey, boards.id AS bid FROM blockpos JOIN (customers,blocks,boards) ON (customers.id=boards.customer_id AND blocks.id=block_id AND boards.id=board_id) WHERE block_id='$block_id'";
			$res=sql::fetchAll($sql);
			foreach($res as $rs )
			{
				$sql="SELECT numbers FROM posintz WHERE tz_id='".$tzid."' AND board_id='".$rs["board_id"]."'";
				$rs2=sql::fetchOne($sql);
				$nz = max($nz,ceil($rs2["numbers"]/$rs["nib"]));
				$nl = max($nl,$rs["layers"]);
				$cl = max($cl,$rs["class"]);
				$piz += $rs["nib"];
				$customer=$rs["customer"];
				$customer_id =$rs["customer_id"];
				$blockname=$rs["blockname"];
			}
			
			if (isset($mpp)) 
			{
				if ($customer_id == '8') // радар
					$zip = 1;
				else
					$zip = 5;
			}
			else
			{
				$zip=25;
			}
			
			$numbz = $nz<=$zip?$nz:(isset($last)?($nz-($party-1)*$zip):$zip);
			$numbp = $nz<=$zip?$numbers:(isset($last)?($numbers-($party-1)*$zip*$piz):$zip*$piz);
			
		}
		$sql="SELECT * FROM lanch WHERE pos_in_tz_id='$posid' AND part='$party'";
		$rs=sql::fetchOne($sql);
		if (empty($rs))
		{
			$sql="INSERT INTO lanch (ldate,board_id,part,numbz,numbp,user_id,pos_in_tz,tz_id,pos_in_tz_id) VALUES (NOW(),'$plate_id','$party','$numbz','$numbp','".$_SERVER[userid]."','$posintz','$tzid','$posid')";
			sql::query($sql);
			$lanch_id = sql::lastId();
		} 
		else 
		{
			$rs = sql::fetchOne($res);
			$lanch_id = $rs["id"];
			$sql="UPDATE lanch SET ldate=NOW(), board_id='$plate_id', numbz='$numbz', numbp='$numbp', user_id='".$_SERVER[userid]."', tz_id='$tzid', pos_in_tz_id='$posid' WHERE id='$lanch_id'";
			sql::query($sql);
		}
		$date = date("d-m-Y");
		// Определим идентификатор коментария
		$comment_id =1;//пустой

		// Определим идентификатор файловой ссылки
		{
		$file_link = 'z:\\\\Заказчики\\\\'.$customer.'\\\\'.$blockname.'\\\\запуски\\\\СЛ-'.date("Y-m-d").'-'.$lanch_id.'.xml';
		$sql="SELECT id FROM filelinks WHERE file_link='$file_link'";
		if ($rs=sql::fetchOne($sql)){
			$file_id = $rs["id"];
		} else {
			$sql="INSERT INTO filelinks (file_link) VALUES ('$file_link')";
			sql::query($sql);
			sql::error(true);
			$file_id = sql::lastId();
		}
		}
		$sql="UPDATE lanch SET file_link_id='$file_id', comment_id='$comment_id' WHERE id='$lanch_id'";
		sql::query($sql);

		// обновим таблицу запусков
		{
			
			$sql="DELETE FROM lanched WHERE board_id='$plate_id'";
			sql::query($sql);
			$sql="INSERT INTO lanched (board_id,lastdate) VALUES ('$plate_id',NOW())";
			sql::query($sql);
		}
		// если все запущены - исключить из запуска
		if (!isset($dozap)){
			$sql="SELECT SUM(numbp) AS snumbp FROM lanch WHERE pos_in_tz_id='$posid' GROUP BY pos_in_tz_id";
			$rs=sql::fetchOne($sql);
			if ($rs[snumbp]>=$nz) {
				$sql="UPDATE posintz SET ldate=NOW(), luser_id='".$_SERVER[userid]."' WHERE id='$posid'";
				sql::query($sql);
			}
		}
		// создать каталог и имя файла для запуска
		$filename=createdironserver($file_link);

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
			$rs = sql::fetchOne($sql);
			foreach ($rs as $key => $val) {
				${$key}=mb_convert_encoding($val,"UTF-8","cp1251");
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
			$excel = '';
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
			$liststkan=$raspstkan=$rtolsh=$bmat1=$bmat2=$bmat3=$bmat4=$bmat5=$bmat6=$bmat7=$bmat8=$bmat9=$sloi1=$sloi2=$sloi3=$sloi4=$sloi5=$sloi6=$sloi7=$sloi8=$sloi9=$osuk="";
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
			$rs = sql::fetchOne($sql);
			foreach ($rs as $key => $val) {
				${$key}=mb_convert_encoding($val,"UTF-8","cp1251");
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
			// сделать собственно сопроводительный
			$excel = "";
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
			$excel = str_replace("_osuk_",$osuk,$excel);
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
			$excel = str_replace("_dozap_",(isset($dozap)?mb_convert_encoding("ДОЗАПУСК","UTF-8","cp1251"):""),$excel);
		}
		// записать файл
		//echo $filename."<br>";
		if ($file = @fopen($filename,"w")) {
			fwrite($file,$excel);
			fclose($file);
			chmod($filename,0777);
			header('Content-type: text/html; charset=windows-1251'); // потому что в для принта не посылается
			$sql="SELECT file_link FROM lanch JOIN (filelinks) ON (file_link_id=filelinks.id) WHERE lanch.id='$lanch_id'";
			$rs=sql::fetchOne($sql);
			//echo $zip."-".$numbz."-".$numbp;
			echo "<a class=filelink href='".sharefilelink($rs[file_link])."'>СЛ-$lanch_id</a><br>";
		} else {
			//echo mb_convert_encoding($filename,"cp1251","UTF-8");
			echo "Не удалось создать файл";
		}

	} 
	elseif ($print="tz") 
	{
		$sql="SELECT file_link FROM posintz JOIN (filelinks,tz) ON (posintz.tz_id=tz.id AND tz.file_link_id=filelinks.id) WHERE posintz.id='$posid'";
		$rs=sql::fetchOne($sql);
		$filelink =  serverfilelink($rs[0]);
		$file = file_get_contents($filelink);
		header("Content-type: application/vnd.ms-excel");
		echo $file;
	}
}
else
{
// вывести таблицу

	// sql

	$sql="SELECT *,posintz.id AS nzid,posintz.id FROM posintz LEFT JOIN (lanched) ON (posintz.plate_id=lanched.board_id) JOIN (plates,tz,filelinks,customers,orders) ON (tz.order_id=orders.id AND plates.id=posintz.plate_id  AND posintz.tz_id=tz.id AND tz.file_link_id=filelinks.id AND plates.customer_id=customers.id) WHERE posintz.ldate = '0000-00-00' ".(isset($find)?"AND (plates.plate LIKE '%$find%' OR filelinks.file_link LIKE '%$find%' OR orders.number LIKE '%$find%') ":"").(!empty($order)?"ORDER BY ".$order." ":"ORDER BY customers.customer,tz.id,posintz.id ").(isset($all)?"":"LIMIT 20");


	$cols["№"]="№";
	$cols[nzid]="ID";
	$cols[customer]="Заказчик";
	$cols[number]="Заказ";
	$cols[plate]="Плата";
	$cols[numbers]="Кол-во";
	$cols[lastdate]="Посл. зап";
	
	
	$table = new SqlTable($processing_type,$processing_type,$sql,$cols);
	$table->show();

}

printpage();
?>