<?php

namespace FelixOnline\Admin\Widgets;

class ForeignKeyTextWidget implements Widget {
	private $fieldName;
	private $label;
	private $currentValue;
	private $readOnly;
	private $required;
	private $help;

	private $page;

	private $currentValuePk;
	private $currentValueText;

	public function __construct($fieldName, $label, $currentValue, $readOnly, $required, $help, $otherProperties = array()) {
		$this->fieldName = $fieldName;
		$this->label = $label;
		$this->currentValue = $currentValue;
		$this->readOnly = $readOnly;
		$this->required = $required;
		$this->help = $help;

		$this->page = $otherProperties['page'];

		if($this->currentValue == '') {
			$this->currentValuePk = '';
			$this->currentValueText = '';
		} else {
			$this->currentValuePk = $this->currentValue->getId();
			$this->currentValueText = $this->currentValue->getContent();
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

		echo '<label for="'.$this->fieldName.'" class="col-sm-2 control-label">'.$this->label.$required.'</label>
		<div class="col-sm-10">
			<textarea rows="25" class="form-control sir-trevor sir-trevor-'.$this->fieldName.'" name="'.$this->fieldName.'" id="'.$this->fieldName.'" '.$readOnly.' aria-describedby="'.$this->fieldName.'-help">'.$this->currentValueText.'</textarea>
			<span id="'.$this->fieldName.'-help" class="help-block">'.$this->help.'</span>
		</div>
		<script>
			$(document).ready( function() {
				trevor = new SirTrevor.Editor({
					el: $(\'.sir-trevor-'.$this->fieldName.'\'),
					defaultType: "Text",
					blockTypes: ["Text", "Heading", "Feliximage", "Quote", "Factoid", "List", "OrderedList", "Video", "Tweet"]
				});
				SirTrevor.setBlockOptions("Tweet", {
					fetchUrl: function(tweetID) {
						return "'.STANDARD_URL.'ajaxTweet.php?tweet_id=" + tweetID;
					}
				});
				id = trevor.ID;
				$(\'.sir-trevor-'.$this->fieldName.'\').data(\'trevor-id\', id);
			});
		</script>
</div>';

	}
}