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
				"name"		=> "submit",
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
		foreach($this->fields as $field) {
			echo "<label>$field->label</label>";
			echo $this->form->add($field->name);
			echo "<br>";
		}
		echo $this->form->add("submit");
		echo $this->form->add("close");
		echo $this->form->add("serialize");
		$this->form->end();
	}
	
	
	function showfooter() {
		echo "<input type=button value='Сохранить' onclick=\"editrecord('".$this->type."',$('#editform').serialize())\"><input type=button value='Отмена' onclick='closeedit()'><input type=button onclick=\"alert($('#editform').serialize())\">";

	}
}
?>