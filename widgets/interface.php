<?php

namespace FelixOnline\Admin\Widgets;

interface Widget {
	public function __construct($fieldName, $label, $currentValue, $readOnly, $required, $help, $otherProperties = array());

	public function render();
}