<?
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
		
		$this->form = new cmsForm_ajax($this->type);
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
		$this->form->addFields(array(
			array(
				"type"		=> CMSFORM_TYPE_BUTTON,
				"name"		=> "save",
				"value"		=> "Сохранить",
				"options"		=> array ( "html" => " onclick=\"editrecord('".$this->type."',$('form[name=".$this->form->name."]').serialize())\" "),
			),
			array(
				"type"		=> CMSFORM_TYPE_BUTTON,
				"name"		=> "close",
				"value"		=> "Отмена",
				"options"		=> array ( "html" => " onclick='closeedit()' "),
			),
			array(
				"type"		=> CMSFORM_TYPE_BUTTON,
				"name"		=> "serialize",
				"value"		=> "",
				"options"		=> array ( "html" => " onclick=\"alert($('form[name=".$this->form->name."]').serialize())\" "),
			),
			));
		$this->form->init();
		$this->form->form();
		echo $this->form->add("tid");
		echo $this->form->add("edit");
		echo $this->form->add("accept");
		echo "<table>";
		foreach($this->fields as $field) {
			echo "<tr><td><label>$field->label</label><td>";
			echo $this->form->add($field->name);
		}
		echo "<tr><td colspan=3>";
		echo $this->form->add("save");
		echo $this->form->add("close");
		echo $this->form->add("serialize");
		echo "</table>";
		$this->form->end();
	}
}
?>