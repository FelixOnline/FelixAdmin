<?php

namespace FelixOnline\Admin\Widgets;

class ForeignKeyImageWidget implements Widget {
	private $fieldName;
	private $label;
	private $currentValue;
	private $readOnly;
	private $required;
	private $help;

	private $editor;
	private $page;
	private $canUpload;
	private $canPick;
	private $defaultImage;

	private $currentValuePk;

	public function __construct($fieldName, $label, $currentValue, $readOnly, $required, $help, $otherProperties = array()) {
		$this->fieldName = $fieldName;
		$this->label = $label;
		$this->currentValue = $currentValue;
		$this->readOnly = $readOnly;
		$this->required = $required;
		$this->help = $help;

		$this->page = $otherProperties['page'];
		$this->editor = $otherProperties['hasEditor'];
		$this->canUpload = $otherProperties['canUpload'];
		$this->canPick = $otherProperties['canPick'];
		$this->defaultImage = $otherProperties['defaultImage'];

		if($this->currentValue == '') {
			$this->currentValuePk = '';
		} else {
			$this->currentValuePk = $this->currentValue->getId();
		}
	}

	public function render() {
		if($this->required) {
			$required = ' <span class="text-danger" title="Required">*</span>';
		} else {
			$required = '';
		}

		if($this->readOnly):
			echo '<div class="image-group form-group" id="grp-'.$this->fieldName.'">';
		else:
			echo '<div class="image-group form-group has-feedback" id="grp-'.$this->fieldName.'">';
		endif;

		echo '<label class="col-sm-2 control-label">'.$this->label.$required.'</label>
	<div class="col-sm-10">';

		echo '<input type="hidden" class="image-key" name="'.$this->fieldName.'" id="'.$this->fieldName.'" value="'.$this->currentValuePk.'">';

		if($this->readOnly) {
			echo '<div class="row"><div class="col-md-3">';

			if($this->currentValue == '') {
				echo '<i>No image selected.</i>';
			} else {
				echo '<p><img src="'.$this->currentValue->getUrl().'" alt="Selected image" style="width: 100%"></p>';
			}

			echo '</div>';
		} else {
			echo '<ul id="tabs-'.$this->fieldName.'" class="nav nav-tabs" role="tablist">
				<li role="presentation" class="active"><a href="#'.$this->fieldName.'-current" id="'.$this->fieldName.'-current-tab" role="tab" data-toggle="tab" aria-controls="'.$this->fieldName.'-current" aria-expanded="true">Current Image</a></li>';

			if($this->canUpload):
				echo '<li role="presentation"><a href="#'.$this->fieldName.'-new" id="'.$this->fieldName.'-new-tab" role="tab" data-toggle="tab" aria-controls="'.$this->fieldName.'-new" aria-expanded="true">Upload</a></li>';
			endif;

			if($this->canPick):
				echo '<li role="presentation"><a href="#'.$this->fieldName.'-pick" id="'.$this->fieldName.'-pick-tab" role="tab" data-toggle="tab" aria-controls="'.$this->fieldName.'-pick" aria-expanded="true">Pick Previously Uploaded Picture</a></li>';
			endif;

			if($this->defaultImage):
				echo '<li role="presentation"><a href="#'.$this->fieldName.'-default" id="'.$this->fieldName.'-default-tab" role="tab" data-toggle="tab" aria-controls="'.$this->fieldName.'-default" aria-expanded="true">Select Default Image</a></li>';
			endif;

			echo '</ul>';

		    echo '<br><div id="tabs-'.$this->fieldName.'-content" class="tab-content">
					<div role="tabpanel" class="tab-pane fade in active" id="'.$this->fieldName.'-current" aria-labelledby="'.$this->fieldName.'-current-tab">';

			if($this->currentValue == '') {
				echo '<i>No image selected.</i>';
			} else {
				$editor = (int) $this->editor;

				if($this->currentValue->getId() == $this->defaultImage) {
					$editor = 0;
				}

				echo '<script>$(document).ready( function() {
						imageForm("'.$this->fieldName.'", "'.$this->currentValuePk.'", "'.$editor.'");
					});</script>';
			}

			echo '</div>';

			if($this->canUpload):
				echo '<div role="tabpanel" class="tab-pane fade in" id="'.$this->fieldName.'-new" aria-labelledby="'.$this->fieldName.'-new-tab">
						<div id="dropzone-'.$this->fieldName.'" class="dropzone"></div>
						<script>
							$(document).ready( function() {
								Dropzone.autoDiscover = false;
								
								$("#dropzone-'.$this->fieldName.'").dropzone({
									url: getImageUploadEndpoint(),
									uploadMultiple: false,
									maxFiles: 1,
									addRemoveLinks: true,
									init: function() {
										this.on("maxfilesexceeded", function(file) { this.removeFile(file); });
										this.on("success", function(file, responseText) {
											imageForm("'.$this->fieldName.'", responseText, "'.(int) $this->editor.'");
											this.removeFile(file);
										});
									}
								});
							});
						</script>
					</div>';
			endif;

			if($this->canPick):
				echo '<div role="tabpanel" class="tab-pane fade in" id="'.$this->fieldName.'-pick" aria-labelledby="'.$this->fieldName.'-pick-tab">
						<label for="'.$this->fieldName.'-picker">Search for a picture by filename or description</label>
						<div class="input-group select2-bootstrap-append">
							<select class="form-control select2" id="'.$this->fieldName.'-picker" name="'.$this->fieldName.'-picker" style="width: 100%"></select>
							<span class="input-group-btn">
								<button class="btn btn-primary" onClick="pickLookupPic(\''.$this->fieldName.'\', false); return false;">
									<span class="glyphicon glyphicon-ok"></span> Select this image
								</button>
							</span>
						</div>
					</div>

					<script>
						$(document).ready( function() {
							$("#'.$this->fieldName.'-picker").select2({
							  theme: "bootstrap",
							  ajax: {
							    url: getAjaxEndpoint(),
							    dataType: "json",
							    delay: 250,
							    method: "POST",
							    data: function (params) {
							      return {
							      	q: "imageLookup",
							        query: params.term // search term
							      };
							    },
								error: function(data) {
									message = data.responseJSON.message;

									alert(message);
								},
							    processResults: function (data, page) {
							      return {
							        results: data
							      };
							    },
							    cache: false
							  },
							  templateResult: formatImagePicker,
							  minimumInputLength: 0
							});
						});
					</script>';
			endif;

			if($this->defaultImage):
				echo '<div role="tabpanel" class="tab-pane fade in" id="'.$this->fieldName.'-default" aria-labelledby="'.$this->fieldName.'-default-tab">';
				try {
					$defaultImage = new \FelixOnline\Core\Image($this->defaultImage);

					echo '<div class="row">
							<div class="col-md-3">
								<p><img src="'.$defaultImage->getUrl().'" alt="Default image" style="width: 100%"></p>
							</div>
							<div class="col-md-9">
								<button onClick="imageForm(\''.$this->fieldName.'\', \''.$defaultImage->getId().'\', 0); return false;" class="btn btn-primary">
									<span class="glyphicon glyphicon-ok"></span> Select this image
								</button>
							</div>
						</div>';
				} catch(Exception $e) {
					echo '<p class="text-danger">Error occured when loading the default image.</p>';
				}

				echo '</div>';
			endif;
		}

		echo '</div>';

		echo '<span id="'.$this->fieldName.'-help" class="help-block">'.$this->help.'</span>';

	echo '</div>
		</div>';

	}
}