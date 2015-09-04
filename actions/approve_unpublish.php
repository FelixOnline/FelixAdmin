<?php

namespace FelixOnline\Admin\Actions;

class approve_unpublish extends BaseAction {
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
			$record->setPublished(NULL);
			$record->setApprovedby(NULL);
			$record->save();
		}

		return 'Articles unpublished.';
	}
}