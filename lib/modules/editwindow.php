<?
class Field {
	var $type;
	var $name;
	var $value;
	var $label;
	var $size;
	var $html;
	
	function Field($label,$name,$type,$value,$size=10) {
		$this->label=$label;
		$this->name=$name;
		$this->value=$value;
		$this->type=$type;
		$this->size=$size;
		$this->html="<label>$label</label><input type=$type name=$name id=$name size=$size value='$value'>";
		if ($type!='hidden') $this->html.='<br>';
	}
	
	function show() {
		echo $this->html;
	}
}

class Edit {
	var $type;
	var $fields;
	
	
	function Edit($type) {
		
		$this->type=$type;
	}
	
	function init() {
		global $tid,$edit;
		$this->addfield('','edit','hidden',(isset($edit)?$edit:"0"));
		$this->addfield('','tid','hidden',$tid);
		$this->addfield('','accept','hidden','yes');
	}
	
	function addfield($label,$name,$type='text',$value='',$size=10)
	{
		$this->fields[$name]=new Field($label,$name,$type,$value,$size);
		return $this;
	}
	
	function show() {
		$this->showhead();
		foreach($this->fields as $field) {
			$field->show();
		}
		$this->showfooter();
	}
	
	function showhead() {
		
		echo "<form method=post id=editform>";
	}
	
	function showfooter() {
		echo "<input type=button value='Сохранить' onclick=\"editrecord('".$this->type."',$('#editform').serialize())\"><input type=button value='Отмена' onclick='closeedit()'><input type=button onclick=\"alert($('#editform').serialize())\">";

	}
}
?>