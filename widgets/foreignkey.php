<?php

namespace FelixOnline\Admin\Widgets;

class ForeignKeyWidget implements Widget {
	private $fieldName;
	private $label;
	private $currentValue;
	private $defaultValue;
	private $readOnly;
	private $required;
	private $help;

	private $fkField;
	private $fkPk;
	private $page;
	private $class;

	private $currentValuePk;
	private $currentValueDescr;
	private $defaultValuePk;
	private $defaultValueDescr;

	public function __construct($fieldName, $label, $currentValue, $readOnly, $required, $help, $otherProperties = array()) {
		$this->fieldName = $fieldName;
		$this->label = $label;
		$this->currentValue = $currentValue;
		$this->readOnly = $readOnly;
		$this->required = $required;
		$this->help = $help;

		$this->fkField = $otherProperties['field'];
		$this->fkPk = $this->currentValue->pk;
		$this->page = $otherProperties['page'];
		$this->class = $otherProperties['class'];
		$this->defaultValue = $otherProperties['defaultValue'];

		if($this->currentValue == '') {
			$this->currentValuePk = '';
			$this->currentValueDescr = '';
		} else {
			$this->currentValuePk = $this->currentValue->fields[$this->fkPk]->getValue();
			$this->currentValueDescr = $this->currentValue->fields[$this->fkField]->getValue();
		}

		if($this->defaultValue == '') {
			$this->defaultValuePk = '';
			$this->defaultValueDescr = '';
		} else {
			$this->defaultValuePk = $this->defaultValue->fields[$this->fkPk]->getValue();
			$this->defaultValueDescr = $this->defaultValue->fields[$this->fkField]->getValue();
		}
	}

	public function render() {
		if($this->required) {
			$required = ' <span class="text-danger" title="Required">*</span>';
		} else {
			$required = '';
		}

		if($this->readOnly):
			echo '<div class="form-group" id="grp-'.$this->fieldName.'">';
		else:
			echo '<div class="form-group has-feedback" id="grp-'.$this->fieldName.'">';
		endif;

		echo '<label for="'.$this->fieldName.'" class="col-sm-2 control-label">'.$this->label.$required.'</label>
	<div class="col-sm-10">';

		if(!$this->readOnly) {
			echo '<div class="input-group select2-bootstrap-append">';
		}

		echo '<select class="form-control select2 select2-default" data-page="'.$this->page.'"';

		if($this->readOnly):
			echo ' readonly disabled';
		endif;

		echo ' name="'.$this->fieldName.'" id="'.$this->fieldName.'" aria-describedby="'.$this->fieldName.'-help" style="width: 100%"';

		if($this->defaultValue) {
			echo ' data-default-pk="'.$this->defaultValuePk.'" data-default-value="'.\htmlentities($this->defaultValueDescr).'">';

			if($this->currentValuePk == '') {
				echo '<option selected="selected" value="'.$this->defaultValuePk.'">'.\htmlentities($this->defaultValueDescr).' ('.$this->defaultValuePk.')</option>';
			}
		} else {
			echo '>';
		}

		if(!($this->currentValuePk == '')) {
			echo '<option selected="selected" value="'.$this->currentValuePk.'">'.\htmlentities($this->currentValueDescr).' ('.$this->currentValuePk.')</option>';
		}

		echo '</select>';

		if(!$this->readOnly) {
			echo '<span class="input-group-btn">
				<button class="btn btn-default" onClick="$(\'#'.$this->fieldName.'\').val(null).trigger(\'change\'); return false;">
					<span class="glyphicon glyphicon-erase"></span>
				</button>
				</span></div>';
		}

		echo '<span id="'.$this->fieldName.'-help" class="help-block">'.$this->help.'</span>';

	echo '</div>
</div>';
	}
}