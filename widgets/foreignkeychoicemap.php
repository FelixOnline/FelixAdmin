<?php

namespace FelixOnline\Admin\Widgets;

class ForeignKeyChoiceMapWidget implements Widget {
	private $fieldName;
	private $label;
	private $currentValue;
	private $readOnly;
	private $required;
	private $help;

	private $fk;
	private $fkField;
	private $page;

	public function __construct($fieldName, $label, $currentValue, $readOnly, $required, $help, $otherProperties = array()) {
		$this->fieldName = $fieldName;
		$this->label = $label;
		$this->currentValue = $currentValue;
		$this->readOnly = $readOnly;
		$this->required = $required;
		$this->help = $help;

		$this->fkField = $otherProperties['field'];
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

		echo '<select multiple="multiple" class="form-control select2"';

		if($this->readOnly):
			echo ' readonly disabled';
		endif;

		echo ' name="'.$this->fieldName.'[]" id="'.$this->fieldName.'" aria-describedby="'.$this->fieldName.'-help" style="width: 100%">';
			if(!($this->currentValue == '')) {
				foreach($this->currentValue as $value) {
					if($value->fields[$this->fkField]->getValue() == null) {
						continue;
					}

					echo '<option selected="selected" value="'.$value->fields[$this->fkField]->getValue().'">'.\htmlentities($value->fields[$this->fkField]->getValue()).'</option>';
				}
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

	echo '<script>
		$(document).ready( function() {
			$("#'.$this->fieldName.'.select2").select2({
			  theme: "bootstrap",
			  tags: true,
			  minimumInputLength: 0
			});
		});
		</script>';
	}
}