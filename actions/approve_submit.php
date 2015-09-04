<?php

namespace FelixOnline\Admin\Actions;

class approve_submit extends BaseAction {
	public function __construct($permissions) {
		parent::__construct($permissions);
	}

	public function run($records) {
		if(count($records) == 0) {
			throw new \Exception('No articles selected');
		}

		$this->validateAccess();

		$this->validateClass($records, 'FelixOnline\Core\Article');

		foreach($records as $record) {
			$record = new \FelixOnline\Core\Article($record);
			$record->setHidden(0);
			$record->save();
		}

		return 'Articles submitted for publication.';
	}
}