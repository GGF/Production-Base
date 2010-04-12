<?
/*
 *  Класс Таблица для выводна данных из базы
 */

	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
class Table {
	var $type;
	var $opentype;
	var $openfunc;
	var $sql;
	var $cols;
	var $tid;
	var $find;
	var $bgcolor;
	var $order;
	var $idstr;
	var $addbutton;
	var $all;
	var $title;
	var $checkrights;
	var $del;
	var $edit;
	
	function Table($type='', $optype='', $sql='',$cols='',$checkrights=true) { //конструктор
		global $find,$all,$order;
		$this->type=$type;
		if (file_exists($optype.".php"))
			$this->opentype=$optype;
		else 
			$this->openfunc=$optype;
		$this->sql = $sql;
		$this->cols=$cols;
		$this->order=$order;
		$this->bgcolor='white';
		$this->find=$find;
		$this->idstr='';
		$this->all=isset($all);
		$this->addbutton=false;
		$this->title='';
		$this->checkrights = $checkrights;
		if ($checkrights) {
			$r=getright();
			$this->del = $r[$this->type]['del'];
			$this->edit = $r[$this->type]['edit'];
		} else {
			$this->del=$this->edit=true;
		}
		
		$this->tid= uniqid($this->type);

	}
	
	
	
	function show_header() {
		echo "<table class='listtable' style='background-color:".$this->bgcolor.";' cellspacing=0 cellpadding=0 id='".$this->tid."'".(!empty($this->find)?" find='$this->find' ":"").(!empty($this->idstr)?" idstr='$this->idstr' ":"").(!$this->all?" tall='$this->all' ":"").(!empty($this->order)?" order='$this->order' ":"").">";
		echo "<thead>";
		if (!empty($this->title)) {echo "<tr><th colspan=100 align=center>".$this->title;}
		echo "<tr>";
		reset($this->cols);
		while (list($key, $val) = each($this->cols)) {
			echo "<th>".(($key=='check' or $key=="№")?"":"<a href=\"javascript:sort('http://".$_SERVER['HTTP_HOST'].$_SERVER["PHP_SELF"]."?".$this->type."&".($this->all?"all&":"").(!empty($this->find)?"find=".urlencode($this->find)."&":"")."order=".($this->order==$key?$key."%20DESC":$key).(!empty($this->idstr)?$this->idstr:"")."','".$this->tid."')\">").$val.(($key=='check' or $key=="№")?"":($this->order==$key?"&darr;":(($this->order==$key.' DESC')?"&uarr;":""))."</a>");
		}
		if ($this->edit) {echo "<th>&nbsp;";}
		if ($this->del)  {echo "<th>&nbsp;";}
		echo "<tbody>";
		echo "<tr><td colspan=100 width=100%><input style='width:".(($this->addbutton && $this->edit)?"50%":"100%")."' type=button onclick=\"updatetable('".$this->tid."','".$this->type."','".($this->all?"":"all").(!empty($this->idstr)?$this->idstr:"").(!empty($this->find)?"&find=".$this->find:"")."')\" value='".($this->all?"Последние 20":"Все")."' id=allbutton>";
		
		if ($this->addbutton && $this->edit) echo "<input style='width:50%' type=button onclick=\"editrecord('".$this->type."','add&edit=0&tid=".$this->tid.($this->all?"&all":(!empty($this->find)?"&find=".urlencode($this->find)."":"")).(!empty($this->order)?"&order=".$this->order:"").(!empty($this->idstr)?$this->idstr:"")."')\" value='Добавить' id=addbutton>";
		
		echo "<tr><td colspan=100 width=100%><input type=text class='find' value='".(!empty($this->find)?$this->find:"Искать...")."' orgvalue='".(!empty($this->find)?$this->find:"Искать...")."' name='find' id='findtext".$this->tid."' ttype='".$this->type."' tid='".$this->tid."' tall='".($this->all?"&all":"")."' idstr='".(!empty($this->idstr)?$this->idstr:"")."'>";
		echo "<script>$('#findtext".$this->tid."').keyboard('enter',function(){updatetable($(this).attr('tid'),$(this).attr('ttype'),'find='+$(this).val()+$(this).attr('tall')+$(this).attr('idstr'));});</script>";
	}
	
	function show() {
		global $user;

		$this->show_header();

		$res = sql::fetchAll($this->sql);
		
		$i = 0;
		$firsttrid = uniqid('tr');
		$trid = $firsttrid;
		$prtrid = $firsttrid;
		$netrid = $firsttrid;
		$curr = 0;
		foreach ($res as $rs) {
			$curr++;
			if ($curr==count($res)) {
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
				echo "<tr class='chettr' parent='".$this->tid."' id='$trid' prev='$prtrid' next='$netrid'>";
			else
				echo "<tr class='nechettr' parent='".$this->tid."' id='$trid' prev='$prtrid' next='$netrid'>";
			$rs["№"]=$i;
			$link = "<a alt='раскрыть' title='Раскрыть' onclick=\"".(!empty($this->openfunc)?$this->openfunc."('".$rs["id"]."','$trid')":(!empty($this->opentype)?"opentr('".$rs["id"]."','$trid','".$this->opentype."'".(($this->type==$this->opentype)?",'show'":"").")":"openempty()"))."\" id=showlink><div class='fullwidth'>";
			$linkend = "</div></a>";
			$rs["file_link"] = substr($rs["file_link"],strrpos($rs["file_link"],"\\")+1);
			$delstr = '';
			reset($this->cols);
			while (list($key, $val) = each($this->cols)) {
				echo "<td>".$link.(empty($rs["$key"])?"&nbsp;":$rs["$key"]).$linkend;
				$delstr .= $rs["$key"].' - ';
			}
			if ($this->edit) {
				echo "<td align=center valign=center><a title='Редактировать' onclick=\"editrecord('".$this->type."','edit=".$rs["id"]."&tid=".$this->tid."')\" id=editlink><img src=/picture/b_edit.png></a>";
			}
			if ($this->del) {
				echo "<td align=center valign=center><a title='Удалить' onclick=\"my_delete('http://".$_SERVER['HTTP_HOST'].$_SERVER["PHP_SELF"]."?".$this->type."&delete=".$rs["id"]."','$trid','".addslashes(htmlspecialchars($delstr))."')\" id=dellink><img src=/picture/b_drop.png></a>";
			}
		}

		$this->showfooter($firsttrid,$trid);

	}
	
	function showfooter($firsttrid,$lasttrid) {
		if (!empty($firsttrid)) {
			echo "<script>
			firsttr = '$firsttrid';
			curtr = firsttr;
			lasttr = '$lasttrid';
			$('#'+curtr).toggleClass('yellow');
			//table_set_keyboard();// привжем клавиши к таблице
			</script>";
		}
		echo "</table>"; 
	}
}
?>
