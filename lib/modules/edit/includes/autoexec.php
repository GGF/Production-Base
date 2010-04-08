<?
/*
 * ����� Edit (c) GGF
 * ��� ���� 
 * ���������� ������� ����� �� ����� � ��������� ������ ���� ��� ������. �� � ������
 */
defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
class Field {
	var $name;
	var $label;
	
	function Field($label,$name) {
		$this->label=$label;
		$this->name=$name;
	}
}

class Edit {
	var $type;
	var $fields;
	var $form;
	
	
	function Edit($type) {
		
		$this->type=$type;
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
	
	function addfield($label,$name)
	{
		$this->fields[$name]=new Field($label,$name);
		return $this;
	}
	
	function addFields($fields) {
		foreach($fields as $field) {
			$this->addfield($field["label"],$field["name"]);
		}
		reset($fields);
		$this->form->addFields($fields);
	}
	
	function show() {
		/* ��������������, ������ ��� ��� �������� JS
		 $this->form->addFields(array(
			array(
				"type"		=> CMSFORM_TYPE_BUTTON,
				"name"		=> "save",
				"value"		=> "���������",
				"options"		=> array ( "html" => " onclick=\"editrecord('".$this->type."',$('form[name=".$this->form->name."]').serialize())\" "),
			),
			array(
				"type"		=> CMSFORM_TYPE_BUTTON,
				"name"		=> "close",
				"value"		=> "������",
				"options"		=> array ( "html" => " onclick='closeedit()' "),
			),
			array(
				"type"		=> CMSFORM_TYPE_BUTTON,
				"name"		=> "serialize",
				"value"		=> "",
				"options"		=> array ( "html" => " onclick=\"alert($('form[name=".$this->form->name."]').serialize())\" "),
			),
			));
		*/
		$this->form->init();
		$this->form->form();
		echo $this->form->add("tid");
		echo $this->form->add("edit");
		echo $this->form->add("accept");
		echo "<table>";
		foreach($this->fields as $field) {
			echo "<tr><td><label>$field->label</label>";
			echo "<td>".$this->form->add($field->name)."";
		}
		/* ��������������, ������ ��� ��� �������� JS
		echo "<tr><td colspan=3>";
		echo $this->form->add("save");
		echo $this->form->add("close");
		echo $this->form->add("serialize");
		echo "</table>";
		*/
		$this->form->end();
		echo "<script>$('select').combobox();</script>";
	}
}


//������� ��� �������������� �� ����� � ����������
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


// 2 ������� �������������� ���� ��� ������ � ����
function date2datepicker($date) {
	return !empty($date)?date("d.m.Y",mktime(0,0,0,ceil(substr($date,5,2)),ceil(substr($date,8,2)),ceil(substr($date,1,4)))):date("d.m.Y");
}

function datepicker2date($date) {
	return substr($date,6,4)."-".substr($date,3,2)."-".substr($date,0,2);
}


?>