<?
/*
 *  Класс Таблица для выводна данных из базы
 */
class Table {
	var $type;
	var $opentype;
	var $sql;
	var $cols;
	var $tid;
	var $find;
	var $bgcolor;
	var $order;
	var $idstr;
	var $addbutton;
	var $all;
	
	function Table($type='', $optype='', $sql='',$cols='') { //конструктор
		global $find,$all,$order;
		$this->type=$type;
		$this->opentype=$optype;
		$this->sql = $sql;
		$this->cols=$cols;
		$this->order=$order;
		$this->bgcolor='white';
		$this->find=$find;
		$this->idstr='';
		$this->all=isset($all);
		$this->addbutton=false;
	}
	
	
	
	function show_header($tid,$del,$edit) {
		echo "<table class='listtable' style='background-color:".$this->bgcolor.";' cellspacing=0 cellpadding=0 id='".$tid."'".(!empty($this->find)?" find='$this->find' ":"").(!empty($this->idstr)?" idstr='$this->idstr' ":"").(!$this->all?" tall='$this->all' ":"").(!empty($this->order)?" order='$this->order' ":"").">";
		echo "<thead>";
		if (!empty($this->title)) {echo "<tr><th colspan=100 align=center>$this->title";}
		echo "<tr>";
		reset($this->cols);
		while (list($key, $val) = each($this->cols)) {
			echo "<th><a href=\"javascript:sort('http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."?".$this->type."&".($this->all?"all&":"").(!empty($this->find)?"find=".urlencode($this->find)."&":"")."order=".($this->order==$key?$key."%20DESC":$key).(!empty($this->idstr)?$this->idstr:"")."','$tid')\">$val".($this->order==$key?"&darr;":(($this->order==$key.' DESC')?"&uarr;":""))."</a>";
		}
		if ($edit) {echo "<th>&nbsp;";}
		if ($del)  {echo "<th>&nbsp;";}
		echo "<tbody>";
		echo "<tr><td colspan=100 width=100%><input style='width:".(($this->addbutton && $edit)?"50%":"100%")."' type=button onclick=\"updatetable('$tid','".$this->type."','".($this->all?"":"all").(!empty($this->idstr)?$this->idstr:"").(!empty($this->find)?"&find=".$this->find:"")."')\" value='".($this->all?"Последние 20":"Все")."' id=allbutton>";
		
		if ($this->addbutton && $edit) echo "<input style='width:50%' type=button onclick=\"editrecord('".$this->type."','add&edit=0&tid=".$tid.($this->all?"&all":(!empty($this->find)?"&find=".urlencode($this->find)."":"")).(!empty($this->order)?"&order=".$this->order:"").(!empty($this->idstr)?$this->idstr:"")."')\" value='Добавить' id=addbutton>";
		
		echo "<tr><td colspan=100 width=100%><input type=text class='find' value='".(!empty($this->find)?$this->find:"Искать...")."' orgvalue='".(!empty($this->find)?$this->find:"Искать...")."' name='find' id='findtext$tid' ttype='".$this->type."' tid='".$tid."' tall='".($this->all?"&all":"")."' idstr='".(!empty($this->idstr)?$this->idstr:"")."'>";
	}
	
	function show() {
		global $user;

		$r=getright($user);
		$del = $r[$this->type]['del'];
		$edit = $r[$this->type]['edit'];
		
		$tid = uniqid($this->type);
		
		$this->show_header($tid,$del,$edit);

		$res = mysql_query($this->sql);
		
		if ($res && ($numr=mysql_num_rows($res))>0) 
			{
			$i = 0;
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
					//$netrid = $netrid;
				} else {
					// остальные проходы
					$prtrid = $trid;
					$trid = $netrid;
					$netrid = uniqid('tr');
				}
				//$trid = uniqid('tr');
				if (!($i++%2))
					echo "<tr class='chettr' parent='$this->tid' id='$trid' prev='$prtrid' next='$netrid'>";
				else
					echo "<tr class='nechettr' parent='$this->tid' id='$trid' prev='$prtrid' next='$netrid'>";
				$rs["№"]=$i;
				$link = "<a alt='раскрыть' title='Раскрыть' onclick=\"".(!empty($this->openfunc)?$this->openfunc."('".$rs["id"]."','$trid')":(!empty($this->opentype)?"opentr('".$rs["id"]."','$trid','".$this->opentype."'".(($this->type==$this->opentype)?",'show'":"").")":"openempty()"))."\" id=showlink><div class='fullwidth'>";
				$linkend = "</div></a>";
				$rs["file_link"] = substr($rs["file_link"],strrpos($rs["file_link"],"\\")+1);
				reset($this->cols);
				while (list($key, $val) = each($this->cols)) {
					echo "<td>".$link.$rs["$key"].$linkend;
				}
				if ($edit) {
					echo "<td align=center valign=center><a title='Редактировать' onclick=\"editrecord('".$this->type."','edit=".$rs["id"]."&tid=".$tid."')\" id=editlink><img src=/picture/b_edit.png></a>";
				}
				if ($del) {
					echo "<td align=center valign=center><a title='Удалить' onclick=\"my_delete('http://".$_SERVER['HTTP_HOST'].$GLOBALS["PHP_SELF"]."?".$this->type."&delete=".$rs["id"]."','$trid','".addslashes(htmlspecialchars($rs[0]."-".$rs[1]."-".$rs[2]."-".$rs[3]))."')\" id=dellink><img src=/picture/b_drop.png></a>";
				}
			}
			
			
		}
		
		$this->showfooter($tid,$firsttrid,$trid);

	}
	
	function showfooter($tid,$firsttrid,$lastrid) {
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
		if (!empty($firsttrid)) {
			echo "firsttr = '$firsttrid';
			curtr = firsttr;
			lasttr = '$lasttrid';
			$('#'+curtr).css({'background-color' : 'yellow'});
			";
		}
		echo "</script>";
		echo "</table>"; 
	}
}
?>
