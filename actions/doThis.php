<?php

namespace FelixOnline\Admin\Actions;

class doThis {
	public function __construct() {

	}

	public function run($records) {
		if(count($records) == 0) {
			throw new \Exception('Please select some items');
		}

		return 'All done';
	}
}