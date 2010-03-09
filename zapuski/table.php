<?
	include_once $GLOBALS["DOCUMENT_ROOT"]."/lib/sql.php"; 
	
	// определим права доступа

	
	$r=getright($user);
	$del = $r[$type]['del'];
	$edit = $r[$type]['edit'];
	
	$i = 0;
	$tid = uniqid($type);
	$res = mysql_query($sql);

	echo "<table class='listtable' style='background-color:".(isset($bgcolor)?$bgcolor:"white").";' cellspacing=0 cellpadding=0 id='".$tid."'".(isset($find)?" find='$find' ":"").(isset($idstr)?" idstr='$idstr' ":"").(isset($all)?" tall='$all' ":"").(isset($order)?" order='$order' ":"").">";
	echo "<thead>";
	if (isset($title)) {
		echo "<tr><th colspan=100 align=center>$title";
	}
	echo "<tr>";
	while (list($key, $val) = each($cols)) {
		echo "<th><a href=\"javascript:sort('http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."?".$type."&".(isset($all)?"all&":"").(isset($find)?"find=".urlencode($find)."&":"")."order=".($order==$key?$key."%20DESC":$key).(isset($idstr)?$idstr:"")."','$tid')\">$val".($order==$key?"&darr;":(($order==$key.' DESC')?"&uarr;":""))."</a>";
	}
	if (isset($print)) {
		echo "<th>&nbsp;";
	}
	if ($edit) {
		echo "<th>&nbsp;";
	}
	if ($del) {
		echo "<th>&nbsp;";
	}
	echo "<tbody>";
	echo "<tr><td colspan=100 width=100%><input style='width:".(((!isset($addbutton) || $addbutton) && $edit)?"50%":"100%")."' type=button onclick=\"updatetable('$tid','".$type."','".(isset($all)?"":"all").(isset($idstr)?$idstr:"").(isset($find)?"&find=".$find:"")."')\" value='".(isset($all)?"Последние 20":"Все")."' id=allbutton>";
	if ((!isset($addbutton) || $addbutton) && $edit) echo "<input style='width:50%' type=button onclick=\"editrecord('".$type."','add&edit=0&tid=".$tid.(isset($all)?"&all":(isset($find)?"&find=".urlencode($find)."":"")).(isset($order)?"&order=".$order:"").(isset($idstr)?$idstr:"")."')\" value='Добавить' id=addbutton>";
	echo "<tr><td colspan=100 width=100%><input type=text class='find' value='".(isset($find)?$find:"Искать...")."' orgvalue='".(isset($find)?$find:"Искать...")."' name='find' id='findtext$tid' ttype='".$type."' tid='".$tid."' tall='".(isset($all)?"&all":"")."' idstr='".(isset($idstr)?$idstr:"")."'>";


	if ($res && ($numr=mysql_num_rows($res))>0) {
		$firsttrid = uniqid('tr');
		$trid = $firsttrid;
		$prtrid = $firsttrid;
		$netrid = $firsttrid;
		$curr = 0;
		while ($rs=mysql_fetch_array($res)) {
			$curr++;
			if ($curr==$numr) {
				// последний
				$prtrid = $trid;
				$trid = $netrid;
				$netrid = $netrid;
			} else {
				// остальные проходы
				$prtrid = $trid;
				$trid = $netrid;
				$netrid = uniqid('tr');
			}
			//$trid = uniqid('tr');
			if (!($i++%2))
				echo "<tr class='chettr' parent='$tid' id='$trid' prev='$prtrid' next='$netrid'>";
			else
				echo "<tr class='nechettr' parent='$tid' id='$trid' prev='$prtrid' next='$netrid'>";
			$rs["№"]=$i;
			$link = "<a alt='раскрыть' title='Раскрыть' onclick=\"".(isset($openfunc)?$openfunc."('".$rs["id"]."','$trid')":(isset($opentype)?"opentr('".$rs["id"]."','$trid','".$opentype."'".(($type==$opentype)?",'show'":"").")":"openempty()"))."\" id=showlink><div class='fullwidth'>";
			$linkend = "</div></a>";
			$rs["file_link"] = substr($rs["file_link"],strrpos($rs["file_link"],"\\")+1);
			reset($cols);
			while (list($key, $val) = each($cols)) {
				echo "<td>".$link.$rs["$key"].$linkend;
			}
			if (isset($print)) {
				echo "<td align=center valign=center>";
				while(list($key,$val)=each($print)){
					echo "<a href=\"$type.php?print=".$key."&id=".$rs["id"]."\" title='".$val."'><img src=/picture/b_print.png></a>";
				}
				reset($print);
			}
			if ($edit) {
				echo "<td align=center valign=center><a title='Редактировать' onclick=\"editrecord('".$type."','edit=".$rs["id"]."&tid=".$tid."')\" id=editlink><img src=/picture/b_edit.png></a>";
			}
			if ($del) {
				echo "<td align=center valign=center><a title='Удалить' onclick=\"my_delete('http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."?".$type."&delete=".$rs["id"]."','$trid','".addslashes(htmlspecialchars($rs[0]."-".$rs[1]."-".$rs[2]."-".$rs[3]))."')\" id=dellink><img src=/picture/b_drop.png></a>";
			}
		}
		echo "<script>
		yellowtr();
		
		$('#findtext$tid').focus(function() { $(this).val(''); $(this).addClass('hasFocus');});
		$('#findtext$tid').blur(function() { 
			$(this).removeClass('hasFocus');
			//if ($(this).val()=='') {
				$(this).val($(this).attr('orgvalue'));
			//}
		});
		";
		if ($firsttrid!='') {
			echo "firsttr = '$firsttrid';
			curtr = firsttr;
			lasttr = '$trid';
			$('#'+curtr).css({'background-color' : 'yellow'});
			";
		}
		echo "</script>";
		echo "</table>"; // тогда скрипт будет в таблице и должен обновляться по updatetable() // не получилось

	}
?>