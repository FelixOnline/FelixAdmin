<?php

namespace FelixOnline\Admin\Actions;

class approve_section extends BaseAction {
	public function __construct($permissions) {
		parent::__construct($permissions);
	}

	public function run($records, $pullThrough = false) {
		if(count($records) == 0) {
			throw new \Exception('No articles selected');
		}

		$this->validateAccess();

		$records = $this->getRecords($records);

		foreach($records as $record) {
			$record->setHidden(0);
			$record->save();
		}

		return 'Articles submitted for review.';
	}
}