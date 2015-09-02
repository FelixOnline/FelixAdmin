<?php

namespace FelixOnline\Admin\Widgets;

class TextWidget implements Widget {
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
			$readOnly = 'readonly';
		} else {
			$readOnly = '';
		}

		if($this->required) {
			$required = ' <span class="text-danger" title="Required">*</span>';
		} else {
			$required = '';
		}

		echo '<div class="form-group" id="grp-'.$this->fieldName.'">
	<label for="'.$this->fieldName.'" class="col-sm-2 control-label">'.$this->label.$required.'</label>
	<div class="col-sm-10">
		<textarea rows="5" class="form-control" name="'.$this->fieldName.'" id="'.$this->fieldName.'" '.$readOnly.' aria-describedby="'.$this->fieldName.'-help">'.$this->currentValue.'</textarea>
		<span id="'.$this->fieldName.'-help" class="help-block">'.$this->help.'</span>
	</div>
</div>';
	}
}