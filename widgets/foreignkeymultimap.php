<?php

namespace FelixOnline\Admin\Widgets;

class ForeignKeyMultiMapWidget implements Widget {
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

		$this->fk = $otherProperties['key'];
		$this->fkField = $otherProperties['field'];
		$this->page = $otherProperties['page'];
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
					echo '<option selected="selected" value="'.$value->fields[$this->fk]->getRawValue().'">'.\htmlentities($value->fields[$this->fk]->getValue()->fields[$this->fkField]->getValue()).' ('.$value->fields[$this->fk]->getRawValue().')</option>';
				}
			}

		echo '</select>';

		if(!$this->readOnly) {
			echo '<span class="input-group-btn">
				<button class="btn btn-default" onClick="$(\'#'.$this->fieldName.'\').val(null).trigger(\'change\'); return false;">
					<span class="glyphicon glyphicon-ban-circle"></span>
				</button>
				</span></div>';
		}

		echo '<span id="'.$this->fieldName.'-help" class="help-block">'.$this->help.'</span>';

	echo '</div>
</div>';

	echo '<script>
		$(document).ready( function() {
			$("#'.$this->fieldName.'").select2({
			  theme: "bootstrap",
			  ajax: {
			    url: getAjaxEndpoint(),
			    dataType: "json",
			    delay: 250,
			    method: "POST",
			    data: function (params) {
			      return {
			      	q: "lookup",
			        query: params.term, // search term
			        page: "'.$this->page.'",
			        widget: "'.$this->fieldName.'"
			      };
			    },
			    processResults: function (data, page) {
			      return {
			        results: data
			      };
			    },
			    cache: false
			  },
			  minimumInputLength: 0
			});
		});
		</script>';
	}
}