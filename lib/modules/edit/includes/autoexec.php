<?
/*
 * Класс Edit (c) GGF
 * для форм 
 * Собственно создает форму из осмио и вставляет скрыты поля для работы. ну и кнопки
 */
defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
class Field {
	var $name;
	var $label;
	var $type;
	
	function Field($label,$name,$type) {
		$this->label=$label;
		$this->name=$name;
		$this->type=$type;
	}
}

class Edit {
	var $type;
	var $fields;
	var $form;
	var $unids;
	var $unidfirst;
	
	
	function Edit($type) {
		
		$this->type=$type;
		$this->unids=array();
	}
	
	function init() {
		global $tid,$edit;
		
		$this->form = new cmsForm_ajax($this->type,$this->type.'.php');
		$this->form->addFields(array(
			array(
				"type"		=> CMSFORM_TYPE_HIDDEN,
				"name"		=> "edit",
				"value"		=> $edit,
			),
			array(
				"type"		=> CMSFORM_TYPE_HIDDEN,
				"name"		=> "accept",
				"value"		=> 'yes',
			),
			array(
				"type"		=> CMSFORM_TYPE_HIDDEN,
				"name"		=> "tid",
				"value"		=> $tid,
			),
			));
		
	}
	
	function addfield($label,$name,$type)
	{
		$this->fields[$name]=new Field($label,$name,$type);
		return $this;
	}
	
	function addFields($fields) {
		$lfields = array();
		$this->unidfirst=$first=$unid = uniqid('fld');
		foreach($fields as $field) {
			$this->addfield($field["label"],$field["name"],$field["type"]);
			if ($field["type"] == CMSFORM_TYPE_TEXT or $field["type"] == CMSFORM_TYPE_SELECT) {
				$nunid = uniqid('fld');
				$field["options"]["html"] .= " ".$field["type"]." fieldid='".$unid."' fieldnext='".$nunid."'";
				array_push($this->unids,$unid);
				$unid=$nunid;
			}
			$last=array_push($lfields,$field);
		}
		$this->form->addFields($lfields);
	}
	
	function show() {
		$this->form->init();
		$this->form->form();
		echo $this->form->add("tid");
		echo $this->form->add("edit");
		echo $this->form->add("accept");
		// скрытые в начало
		foreach($this->fields as $field) {
			if ($field->type == CMSFORM_TYPE_HIDDEN) {
				echo "".$this->form->add($field->name)."";
			}
		}
		// остальные в таблице
		echo "<table>";
		foreach($this->fields as $field) {
			if ($field->type != CMSFORM_TYPE_HIDDEN) {
				echo "<tr><td><label>$field->label</label>";
				echo "<td>".$this->form->add($field->name)."";
			}
		}
		echo "</table>";
		$this->form->end();
		echo "<script>\$('select').combobox();</script>";
		foreach($this->unids as $unid) {
			echo "<script>\$('[fieldid=".$unid."]').keyboard('enter',function(){\$('[fieldid='+\$(this).attr('fieldnext')+']').focus();});</script>";
		}
		if (isset($unid)) echo "<script>\$('[fieldid=".$unid."]').keyboard('enter',function(){\$('[fieldid=".$this->unidfirst."]').focus();});</script>";
	}
}


//функция для преобразования из формы в глобальные
/*
function serializeform($form) {
		foreach($form as $key => $val) {
			if (!is_array($val) and mb_detect_encoding($val)=="UTF-8") 
				$val=mb_convert_encoding($val,"cp1251","UTF-8");
			else { 
			}
			if (strstr($key,"|")) {
				$tmp=preg_match_all("/([^|]+)/",$key,$matches);//$key=substr($key,0,$pos)."[";
				$matches=$matches[0];
				$key=$matches[0];
				global ${$key};
				switch (count($matches)){
					case 2:
						${$key}[$matches[1]] = $val;
						break;
					case 3:
						${$key}[$matches[1]][$matches[2]] = $val;
						break;
					default:
						break;
				}
			} else {
				global ${$key};
				${$key}=$val;
			}
		}
}
*/

function checkbox2array($val,$key) {
	if (strstr($key,"|")) {
		$tmp=preg_match_all("/([^|]+)/",$key,$matches);//$key=substr($key,0,$pos)."[";
		$matches=$matches[0];
		$key=$matches[0];
		global ${$key};
		switch (count($matches)){
			case 2:
				${$key}[$matches[1]] = $val;
				break;
			case 3:
				${$key}[$matches[1]][$matches[2]] = $val;
				break;
			default:
				break;
		}
	}
}

// 2 функции преобразования даты для пикера и базы
function date2datepicker($date) {
	return !empty($date)?date("d.m.Y",mktime(0,0,0,ceil(substr($date,5,2)),ceil(substr($date,8,2)),ceil(substr($date,1,4)))):date("d.m.Y");
}

function datepicker2date($date) {
	return substr($date,6,4)."-".substr($date,3,2)."-".substr($date,0,2);
}


?>