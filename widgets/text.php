<?php

namespace FelixOnline\Admin\Widgets;

class TextWidget implements Widget {
	private $fieldName;
	private $label;
	private $currentValue;
	private $readOnly;
	private $required;
	private $help;
	private $otherProperties;

	public function __construct($fieldName, $label, $currentValue, $readOnly, $required, $help, $otherProperties = array()) {
		$this->fieldName = $fieldName;
		$this->label = $label;
		$this->currentValue = $currentValue;
		$this->readOnly = $readOnly;
		$this->required = $required;
		$this->help = $help;
		$this->otherProperties = $otherProperties;
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
	<div class="col-sm-10">';
		if(isset($this->otherProperties['sirTrevor']) && $this->otherProperties['sirTrevor']) {
			if($this->readOnly):
				echo '<div class="read-only-sir-t">';
				$converter = new \Sioen\Converter();

				$text = $converter->toHTML(json_encode(array('data' => array(json_decode($this->currentValue)))));
				$text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x80-\x9F]/u', '', $text); // Some <p>^B</p> tags can get through some times. Should not happen with the current migration script

				// More text tidying
				$text = strip_tags($text, '<p><a><div><b><i><br><blockquote><object><param><embed><li><ul><ol><strong><img><h1><h2><h3><h4><h5><h6><em><iframe><strike>'); // Gets rid of html tags except <p><a><div>
				$text = preg_replace('/(<br(| |\/|( \/))>)/i', '', $text); // strip br tag
				$text = preg_replace('#<div[^>]*(?:/>|>(?:\s|&nbsp;)*</div>)#im', '', $text); // Removes empty html div tags
				$text = preg_replace('#<span*(?:/>|>(?:\s|&nbsp;)[^>]*</span>)#im', '', $text); // Removes empty html span tags
				$text = preg_replace('#<p[^>]*(?:/>|>(?:\s|&nbsp;)*</p>)#im', '', $text); // Removes empty html p tags
				$text = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $text); // Remove style attributes

				echo $text;
				echo '</div>';
			else:
				$current = '';
				if($this->currentValue) {
					$current = json_encode(array('data' => array(json_decode($this->currentValue))));
				}

				echo '<textarea rows="25" class="form-control sir-trevor sir-trevor-'.$this->fieldName.'" name="'.$this->fieldName.'" id="'.$this->fieldName.'" '.$readOnly.' aria-describedby="'.$this->fieldName.'-help" data-trevor-default="'.$this->otherProperties['sirTrevorDefaultField'].'" data-trevor-maxwidgets="'.$this->otherProperties['sirTrevorMaxFields'].'" data-trevor-widgets=\''.$this->otherProperties['sirTrevorFields'].'\'>'.$current.'</textarea>';
			endif;
		}
		else {
			echo '<textarea rows="5" class="form-control" name="'.$this->fieldName.'" id="'.$this->fieldName.'" '.$readOnly.' aria-describedby="'.$this->fieldName.'-help">'.htmlentities($this->currentValue).'</textarea>';
		}

		echo '<span id="'.$this->fieldName.'-help" class="help-block">'.$this->help.'</span>
	</div>
</div>';
	}
}