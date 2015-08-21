<?php

namespace FelixOnline\Admin\Widgets;

class CheckboxWidget implements Widget {
	private $fieldName;
	private $label;
	private $currentValue;
	private $readOnly;
	private $required;
	private $help;

	public function __construct($fieldName, $label, $currentValue, $readOnly, $required, $help, $otherProperties = array()) {
		$this->fieldName = $fieldName;
		$this->label = $label;
		$this->currentValue = $currentValue;
		$this->readOnly = $readOnly;
		$this->required = $required;
		$this->help = $help;
	}

	public function render() {
		if($this->readOnly) {
			$readOnly = 'disabled';
		} else {
			$readOnly = '';
		}

		if($this->required) {
			$required = ' <span class="text-danger" title="Required">*</span>';
		} else {
			$required = '';
		}

		if($this->currentValue == 1) {
			$checked = 'checked';
		} else {
			$checked = '';
		}

		echo '<div class="form-group" id="grp-'.$this->fieldName.'">
	<label for="'.$this->fieldName.'" class="col-sm-2 control-label">'.$this->label.$required.'</label>
	<div class="col-sm-10">
		<p class="form-control-static"><input type="checkbox" name="'.$this->fieldName.'" id="'.$this->fieldName.'" value="true" '.$readOnly.' '.$checked.'  aria-describedby="'.$this->fieldName.'-help"></p>
		<span id="'.$this->fieldName.'-help" class="help-block">'.$this->help.'</span>
	</div>
</div>';
	}
}