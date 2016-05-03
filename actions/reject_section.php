<?php

namespace FelixOnline\Admin\Actions;

class reject_section extends BaseAction {
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
			$record->setReviewedby(NULL);
			$record->save();
		}

		return 'Articles rejected to section editor.';
	}
}