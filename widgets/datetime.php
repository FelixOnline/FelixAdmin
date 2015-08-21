<?php

namespace FelixOnline\Admin\Widgets;

class DateTimeWidget implements Widget {
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
		if($this->required) {
			$required = ' <span class="text-danger" title="Required">*</span>';
		} else {
			$required = '';
		}

		if($this->currentValue != '') {
			$valDate = date("Y-m-d H:i:s", $this->currentValue);
		} else {
			$valDate = '';
		}

		if($this->readOnly):
			echo '<div class="form-group" id="grp-'.$this->fieldName.'">';
		else:
			echo '<div class="form-group has-feedback" id="grp-'.$this->fieldName.'">';
		endif;

		echo '<label for="'.$this->fieldName.'" class="col-sm-2 control-label">'.$this->label.$required.'</label>
	<div class="col-sm-10">

		<input';

		if($this->readOnly):
			echo ' readonly';
		endif;

		echo ' type="text" class="form-control';

		if(!$this->readOnly):
			echo ' whited datetimefield';
		endif;

		echo '" name="'.$this->fieldName.'" id="'.$this->fieldName.'" value="'.$valDate.'" aria-describedby="'.$this->fieldName.'-help">
		<span id="'.$this->fieldName.'-help" class="help-block">'.$this->help.'</span>';

		if(!$this->readOnly):
			echo '<span class="glyphicon glyphicon-calendar form-control-feedback" aria-label="Click to select a value"></span>';
		endif;
	echo '</div>
</div>';
	}
}