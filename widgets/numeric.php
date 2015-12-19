<?php

namespace FelixOnline\Admin\Widgets;

class NumericWidget implements Widget {
	private $fieldName;
	private $label;
	private $currentValue;
	private $readOnly;
	private $required;
	private $help;
	private $defaultValue;

	public function __construct($fieldName, $label, $currentValue, $readOnly, $required, $help, $otherProperties = array()) {
		$this->fieldName = $fieldName;
		$this->label = $label;
		$this->currentValue = $currentValue;
		$this->readOnly = $readOnly;
		$this->required = $required;
		$this->help = $help;
		$this->defaultValue = $otherProperties['defaultValue'];
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

		$current = '';

		if($this->defaultValue) {
			$default = 'data-default="'.\htmlentities($this->defaultValue).'"';
			if(!$this->currentValue) {
				$current = \htmlentities($this->defaultValue);
			}
		} else {
			$default = '';
		}

		if($this->currentValue) {
			$current = \htmlentities($this->currentValue);
		}

		echo '<div class="form-group" id="grp-'.$this->fieldName.'">
	<label for="'.$this->fieldName.'" class="col-sm-2 control-label">'.$this->label.$required.'</label>
	<div class="col-sm-10">
		<input type="number" class="form-control" name="'.$this->fieldName.'" id="'.$this->fieldName.'" value="'.$current.'" '.$readOnly.' '.$default.' aria-describedby="'.$this->fieldName.'-help">
		<span id="'.$this->fieldName.'-help" class="help-block">'.$this->help.'</span>
	</div>
</div>';
	}
}