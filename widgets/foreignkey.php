<?php

namespace FelixOnline\Admin\Widgets;

class ForeignKeyWidget implements Widget {
	private $fieldName;
	private $label;
	private $currentValue;
	private $readOnly;
	private $required;
	private $help;

	private $fkField;
	private $fkPk;
	private $page;
	private $class;

	private $currentValuePk;
	private $currentValueDescr;

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

		if($this->currentValue == '') {
			$this->currentValuePk = '';
			$this->currentValueDescr = '';
		} else {
			$this->currentValuePk = $this->currentValue->fields[$this->fkPk]->getValue();
			$this->currentValueDescr = $this->currentValue->fields[$this->fkField]->getValue();
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

		echo '<select class="form-control select2"';

		if($this->readOnly):
			echo ' readonly disabled';
		endif;

		echo ' name="'.$this->fieldName.'" id="'.$this->fieldName.'" aria-describedby="'.$this->fieldName.'-help" style="width: 100%">';
			if(!($this->currentValuePk == '')) {
				echo '<option selected="selected" value="'.$this->currentValuePk.'">'.\htmlentities($this->currentValueDescr).' ('.$this->currentValuePk.')</option>';
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

	if($this->class == 'FelixOnline\Core\Image') {
		$endpoint = 'imageLookup';
	} else {
		$endpoint = 'lookup';
	}

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
			      	q: "'.$endpoint.'",
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
			  },';
	if($this->class == 'FelixOnline\Core\Image') {
		echo 'templateResult: formatImagePicker,';
	}
	
	echo 'minimumInputLength: 0
			});
		});

		function formatImagePicker(img) {
			if (img.loading) return img.text;
			var image = $(\'<span><img src="'.\FelixOnline\Core\Settings::get('image_url').'../\' + (img.text.split(" "))[0] + \'" class="lookup-img" /> \' + img.text + \'</span>\');
			return image;
		};
		</script>';
	}
}